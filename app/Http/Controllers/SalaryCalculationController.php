<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Degree;
use Muserpol\Models\BaseWage;
use Muserpol\Models\Contribution\Contribution;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalaryCalculationController extends Controller
{
    public function getYears()
    {
        $baseWageYears = BaseWage::select(DB::raw('EXTRACT(YEAR FROM month_year) as year'))->distinct()->pluck('year');

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

    private function _getComparativeSalariesData($year)
    {
        $month = 8; // agosto es constante

        $contributionSalaries = DB::table('contributions as c')
            ->join('degrees as d', 'c.degree_id', '=', 'd.id')
            ->select('d.id as degree_id', DB::raw('mode() WITHIN GROUP (ORDER BY c.base_wage) as salary'))
            ->whereYear('c.month_year', $year)
            ->whereMonth('c.month_year', $month)
            ->groupBy('d.id')
            ->get()
            ->keyBy('degree_id');

        $allDegrees = Degree::orderBy('id')->get(['id', 'name', 'shortened']);
        $results = [];

        foreach ($allDegrees as $degree) {
            $results[] = [
                'degree_shortened' => $degree->shortened,
                'degree_name' => $degree->name,
                'contribution_salary' => optional($contributionSalaries->get($degree->id))->salary,
            ];
        }
        return collect($results);
    }

    public function calculateComparativeSalaries(Request $request)
    {
        $year = $request->input('year');
        if (!$year) {
            return response()->json(['error' => 'Año no proporcionado'], 400);
        }

        $results = $this->_getComparativeSalariesData($year);
        return response()->json($results);
    }

    public function exportExcel(Request $request)
    {
        $year = $request->input('year');
        if (!$year) {
            return back()->withErrors(['error' => 'Año no proporcionado para la exportación.']);
        }

        $salaries = $this->_getComparativeSalariesData($year);

        $salariesWithIndex = $salaries->map(function ($item, $key) {
            $item['export_index'] = $key + 1;
            return $item;
        });

        $export = new class($salariesWithIndex, $year) implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle
        {
            protected $salaries;
            protected $year;

            public function __construct($salaries, $year)
            {
                $this->salaries = $salaries;
                $this->year = $year;
            }

            public function collection()
            {
                return $this->salaries;
            }

            public function headings(): array
            {
                return [
                    'Nro',
                    'Grado',
                    'Nombre',
                    'Salario',
                ];
            }

            public function map($salary): array
            {
                return [
                    $salary['export_index'],
                    $salary['degree_shortened'],
                    $salary['degree_name'],
                    ($salary['contribution_salary'] !== null && $salary['contribution_salary'] != 0) ? number_format($salary['contribution_salary'], 2, ',', '.') : '-',
                ];
            }

            public function title(): string
            {
                return 'Salarios ' . $this->year;
            }
        };

        return Excel::download($export, "Calculo_Salarial_{$year}.xlsx");
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
