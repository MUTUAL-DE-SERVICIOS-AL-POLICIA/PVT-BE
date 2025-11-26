<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Degree;
use Muserpol\Models\BaseWage;
use Muserpol\Models\Contribution\Contribution;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalaryCalculationController extends Controller
{
    public function getYears()
    {
        $baseWageYears = BaseWage::select(DB::raw('EXTRACT(YEAR FROM month_year) as year'))->where('is_real_value', true)->distinct()->pluck('year');

        $contributionYears = Contribution::select(DB::raw('EXTRACT(YEAR FROM month_year) as year'))
            ->where(function($q){
                $q->where('contributionable_type','ilike','%payroll_commands%')
                ->orWhere('contributionable_type','ilike','%contributions%');
            })
            ->where('type','ilike','%Planilla%')
            ->whereNotIn(DB::raw('EXTRACT(YEAR FROM month_year)'), $baseWageYears)
            ->distinct()
            ->pluck('year');
        return response()->json($contributionYears);
    }

    public function calculateComparativeSalaries(Request $request)
    {
        $year = $request->input('year');
        $month = 8; // agosto es constante
        if (!$year) {
            return response()->json(['error' => 'Año no proporcionado'], 400);
        }

        $contributionSalaries = DB::table('contributions as c')
            ->join('degrees as d', 'c.degree_id', '=', 'd.id')
            ->select('d.id as degree_id', DB::raw('mode() WITHIN GROUP (ORDER BY c.base_wage) as salary'))
            ->whereYear('c.month_year', $year)
            ->whereMonth('c.month_year', $month)
            ->groupBy('d.id')
            ->get()
            ->keyBy('degree_id');

        $allDegrees = Degree::orderBy('id')->get(['id', 'shortened']);
        $results = [];

        foreach ($allDegrees as $degree) {
            $results[] = [
                'degree_name' => $degree->shortened,
                'contribution_salary' => optional($contributionSalaries->get($degree->id))->salary,
            ];
        }
        return response()->json($results);
    }

    public function executeUpdateBaseWage(Request $request)
    {
        $year = $request->input('year');
        $month = 8; // Agosto es constante

        if (!$year) {
            return response()->json(['error' => 'Año no proporcionado'], 400);
        }
        $contributionSalaries = DB::table('contributions as c')
            ->join('degrees as d', 'c.degree_id', '=', 'd.id')
            ->select('d.id as degree_id', DB::raw('mode() WITHIN GROUP (ORDER BY c.base_wage) as salary'))
            ->whereYear('c.month_year', $year)
            ->whereMonth('c.month_year', $month)
            ->groupBy('d.id')
            ->get();

        if ($contributionSalaries->isEmpty()) {
            return response()->json(['message' => "No se encontraron datos de contribuciones para actualizar en el año {$year}."]);
        }

        $recordsUpdated = 0;
        $recordsCreated = 0;
        $userId = Auth::id();

        foreach ($contributionSalaries as $contribution) {
            if ($contribution->salary === null) {
                continue;
            }
            $monthYear = "{$year}-{$month}-01";
            $baseWage = BaseWage::updateOrCreate(
                [
                    'degree_id' => $contribution->degree_id,
                    'month_year' => $monthYear
                ],
                [
                    'user_id' => $userId,
                    'amount' => $contribution->salary,
                    'is_real_value' => true
                ]
            );

            if ($baseWage->wasRecentlyCreated) {
                $recordsCreated++;
            } elseif ($baseWage->wasChanged()) {
                $recordsUpdated++;
            }
        }
        logger('base ', $baseWage->toArray());
        return response()->json([
            'message' => "Actualización completada para el año {$year}. Registros actualizados: {$recordsUpdated}. Registros creados: {$recordsCreated}."
        ]);
    }
}
