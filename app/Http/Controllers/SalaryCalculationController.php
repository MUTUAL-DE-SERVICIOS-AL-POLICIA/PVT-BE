<?php

namespace Muserpol\Http\Controllers;

use Carbon\Carbon;
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

        $contributionYears = Contribution::select(DB::raw('EXTRACT(YEAR FROM month_year) as year'))
            ->where(function($q){
                $q->where('contributionable_type','ilike','%payroll_commands%')
                ->orWhere('contributionable_type','ilike','%contributions%');
            })
            ->where('type','ilike','%Planilla%')
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
                'degree_id' => $degree->id,
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

        //filatrar los salarios que tienen contribution_salary > 0
        $filteredSalaries = $salaries->filter(function ($salary) {
            return isset($salary['contribution_salary']) && $salary['contribution_salary'] > 0;
        });

        $export = new class($filteredSalaries, $year) implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithTitle
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
                    'codigo_de_grado',
                    'grado_abreviado',
                    'grado_nombre_completo',
                    'año',
                    'salario',
                ];
            }

            public function map($salary): array
            {
                return [
                    $salary['degree_id'],
                    $salary['degree_shortened'],
                    $salary['degree_name'],
                    $this->year,
                    ($salary['contribution_salary'] == 0) ? null : $salary['contribution_salary'],
                ];
            }

            public function title(): string
            {
                return 'Calculo_Salarial';
            }
        };

        return Excel::download($export, "Calculo_Salarial_{$year}.xlsx");
    }

    public function downloadSalaryTemplate(Request $request)
    {
        $allDegrees = Degree::orderBy('id')->get(['id', 'name', 'shortened']);

        $templateData = $allDegrees->map(function ($degree) {
            return [
                'codigo_de_grado' => $degree->id,
                'grado_abreviado' => $degree->shortened,
                'grado_nombre_completo' => $degree->name,
                'año' => '',
                'salario' => ''
            ];
        });

        $export = new class($templateData) implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
        {
            protected $templateData;

            public function __construct($templateData)
            {
                $this->templateData = $templateData;
            }

            public function collection()
            {
                return $this->templateData;
            }

            public function headings(): array
            {
                return [
                    'codigo_de_grado',
                    'grado_abreviado',
                    'grado_nombre_completo',
                    'año',
                    'salario',
                ];
            }

            public function title(): string
            {
                return 'Plantilla_Sueldos';
            }
        };

        return Excel::download($export, "Plantilla_Sueldos.xlsx");
    }

    public function importSalaries(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        logger('Importando sueldos desde archivo.');
        try {
            $import = new \Muserpol\Imports\SalariesImport();
            $rows = Excel::toCollection($import, $request->file('file'))->first()->map(function ($row) {
                // Normalize the 'ano' key to 'año' if it exists
                if (isset($row['ano']) && !isset($row['año'])) {
                    $row['año'] = $row['ano'];
                }
                return $row;
            });
            logger('Filas leídas del archivo: ' . $rows->count());

            $rowsToImport = collect();
            $invalidRows = collect();

            foreach ($rows as $index => $row) {
                // Ignorar filas sin datos
                if (empty($row['codigo_de_grado']) && empty($row['año']) && empty($row['salario'])) {
                    continue;
                }

                $isSalarioValid = isset($row['salario']) && is_numeric($row['salario']) && $row['salario'] > 0;
                $isAnoValid = isset($row['año']) && is_numeric($row['año']) && $row['año'] > 0;

                if ($isSalarioValid && $isAnoValid) {
                    $rowsToImport->push($row);
                } else {
                    $errorDetails = [];
                    if (!isset($row['año']) || !is_numeric($row['año']) || $row['año'] <= 0) {
                        $errorDetails[] = 'año inválido';
                    }
                    if (!isset($row['salario']) || !is_numeric($row['salario']) || $row['salario'] <= 0) {
                        $errorDetails[] = 'salario inválido';
                    }
                    // Las filas de Excel son 1-based, y hay un encabezado, por lo que el número de fila es índice + 2
                    $invalidRows->push('Fila ' . ($index + 2) . ' (' . implode(' y ', $errorDetails) . ')');
                }
            }

            if ($invalidRows->isNotEmpty()) {
                $errorString = $invalidRows->implode(', ');
                return response()->json(['message' => "La importación ha fallado. Las siguientes filas tienen un año o salario inválido (valor no numérico o cero): " . $errorString], 422);
            }

            logger('Filas a importar: ' . $rowsToImport->count());
            if ($rowsToImport->isEmpty()) {
                return response()->json(['message' => 'El archivo no contiene filas con salarios y años válidos para importar.'], 422);
            }
            
            // Mapear las filas para incluir el objeto Carbon de la fecha month_year actual
            $checksWithDate = $rowsToImport->map(function ($row) {
                $year = (int)$row['año'];
                $month = 8; // Agosto es constante
                $row['month_year_carbon'] = Carbon::createFromDate($year, $month, 1);
                return $row;
            });

            $existingSalaries = BaseWage::where(function ($query) use ($checksWithDate) {
                foreach ($checksWithDate as $check) {
                    $query->orWhere(function ($q) use ($check) {
                        $q->where('degree_id', $check['codigo_de_grado'])
                          ->whereDate('month_year', $check['month_year_carbon']); // Use whereDate for comparison
                    });
                }
            })->get();

            if ($existingSalaries->isNotEmpty()) {
                $errors = $existingSalaries->map(function ($wage) {
                    return "Grado: {$wage->degree->shortened} para el año {$wage->month_year->year}";
                })->implode(', ');
                return response()->json(['message' => "La importación ha fallado. Ya existen registros para: {$errors}."], 422);
            }

            $userId = Auth::id();
            foreach ($checksWithDate as $row) {
                BaseWage::create([
                    'user_id' => $userId,
                    'degree_id' => $row['codigo_de_grado'],
                    'month_year' => $row['month_year_carbon'],
                    'amount' => (float)$row['salario'],
                ]);
            }

            return response()->json(['message' => 'Sueldos importados y creados correctamente.'], 200);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = "Fila " . $failure->row() . ": " . implode(", ", $failure->errors());
            }
            return response()->json(['message' => implode("; ", $errors)], 422);
        } catch (\Exception $e) {
            \Log::error("Error importing salaries: " . $e->getMessage());
            return response()->json(['message' => 'Error al importar los sueldos: ' . $e->getMessage()], 500);
        }
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
