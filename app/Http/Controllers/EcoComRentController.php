<?php

namespace Muserpol\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Muserpol\Helpers\Util;
use Muserpol\Models\Degree;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Models\EconomicComplement\EcoComRent;
use Muserpol\Models\EconomicComplement\EconomicComplement;

class EcoComRentController extends Controller
{
    /**
     * Listar todos los registros.
     */
    public function index(Request $request)
    {
        $year = $request->year;
        $semester = $request->semester;

        if (!$year || !$semester) {
            $procedure = Util::getEcoComCurrentProcedure()->first();
            $year = Carbon::parse($procedure->year)->year;
            $semester = $procedure->semester;
        }

        $query = EcoComRent::with(['degree', 'procedureModality'])
            ->whereYear('year', $year)
            ->where('semester', $semester)
            ->orderBy('degree_id', 'ASC')
            ->orderBy('procedure_modality_id', 'ASC')
            ->get();
        
        return response()->json($query);
    }


    /**
     * Guardar un nuevo registro.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'degree_id' => 'required|exists:degrees,id',
            'year' => 'required|date',
            'semester' => 'required|in:Primer,Segundo',
            'minor' => 'numeric',
            'higher' => 'numeric',
            'average' => 'required|numeric',
            'procedure_modality_id' => 'exists:procedure_modalities,id',
            'referencial_limit' => 'numeric',
        ]);

        $rent = EcoComRent::create($validated);

        return response()->json([
            'message' => 'Registro creado correctamente',
            'data' => $rent
        ], 201);
    }

    /**
     * Mostrar un registro específico.
     */
    public function show($id)
    {
        $rent = EcoComRent::with(['user', 'degree', 'procedureModality'])
            ->findOrFail($id);

        return response()->json($rent);
    }

    /**
     * Actualizar un registro.
     */
    public function update(Request $request, $id)
    {
        $rent = EcoComRent::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'degree_id' => 'required|exists:degrees,id',
            'year' => 'required|date',
            'semester' => 'required|in:Primer,Segundo',
            'minor' => 'nullable|numeric',
            'higher' => 'nullable|numeric',
            'average' => 'required|numeric',
            'procedure_modality_id' => 'exists:procedure_modalities,id',
            'referencial_limit' => 'nullable|numeric',
        ]);

        $rent->update($validated);

        return response()->json([
            'message' => 'Registro actualizado correctamente',
            'data' => $rent
        ]);
    }

    /**
     * Eliminar un registro.
     */
    public function destroy($id)
    {
        $rent = EcoComRent::findOrFail($id);
        $rent->delete();

        return response()->json([
            'message' => 'Registro eliminado correctamente'
        ]);
    }

    public function importAverage(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
            'ecoComProcedureId' => 'required|integer',
            'semester' => 'required|in:Primer,Segundo',
            'year' => 'required'
        ]);
        // Verificar si ya existen calificaciones del procedure
        $existCalifications = EconomicComplement::where('eco_com_procedure_id', $request->ecoComProcedureId)
            ->where('total', '>', 0)
            ->exists();

        if ($existCalifications) {
            return response()->json([
                'status' => 'error',
                'message' => "No se puede importar el archivo porque ya existen calificaciones registradas para el periódo seleccionado."
            ], 422);
        }

        $procedure = EcoComProcedure::findOrFail($request->ecoComProcedureId);
        $year = Carbon::parse($procedure->year)->format('Y');
        $semester = $procedure->semester; // Primer / Segundo
        // Abrir archivo CSV
        $file = fopen($request->file('file')->getRealPath(), 'r');
        fgetcsv($file); //para saltar encabezado
        $imported = 0;
        $line_error = 1;

        while (($data = fgetcsv($file, 1000, ":")) !== false) {
            $line_error++;
            // Campos del CSV
            $degree_id  = trim($data[0]);
            $type_name  = trim($data[2]);
            $average    = trim($data[3]);
            $limit      = trim($data[4]);
            // campos obligatorios
            if (!$degree_id || !$type_name) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Error en la línea $line_error: grado o prestación están vacíos. Datos: ($degree_id, $type_name)"
                ], 422);
            }
            // Mapear prestación segun procedure_modality_id
            $mapModalities = [
                "Vejez"     => 29,
                "Viudedad"  => 30
            ];
            $modality = $mapModalities[$type_name] ?? null;
            if (!$modality) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Error en la línea $line_error: la prestación '$type_name' no es válida."
                ], 422);
            }
            // Validaciones
            if (!is_numeric($average)) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Error en la línea $line_error: el promedio '$average' no es numérico."
                ], 422);
            }
            if ($limit !== "" && !is_numeric($limit)) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Error en la línea $line_error: el límite '$limit' no es numérico."
                ], 422);
            }
            // crear o actualizar
            EcoComRent::updateOrCreate(
                [
                    'degree_id' => $degree_id,
                    'procedure_modality_id' => $modality,
                    'year' => $year . '-01-01',
                    'semester' => $semester
                ],
                [
                    'user_id' => Auth::id(),
                    'average' => $average,
                    'referencial_limit' => $limit ?: null,
                    'minor' => null,
                    'higher' => null
                ]
            );
            $imported++;
        }

        fclose($file);
        return response()->json([
            'status' => 'error',
            'message' => "Importación completada",
            'imported' => $imported
        ]);
    }

    public function exportDegreesWithPrestations()
    {
        $fileName = "Plantilla grados.csv";
        $degrees = Degree::where("is_active", true)->orderBy('correlative')->get();
        $prestaciones = ["Vejez", "Viudedad"];

        $headers = [
            "Content-Type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($degrees, $prestaciones) {
            $file = fopen('php://output', 'w');
            // ENCABEZADOS DEL CSV
            fputcsv($file, [
                'id_grado_(obligatorio)',
                'nombre_grado',
                'prestacion_(obligatorio)',
                'promedio',
                'limite_referencial'
            ], ':');

            // CONTENIDO
            foreach ($degrees as $degree) {
                foreach ($prestaciones as $prestacion) {
                    fputcsv($file, [
                        $degree->id,
                        $degree->name,
                        $prestacion,
                        '', // average
                        ''  // limit
                    ], ':');
                }
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}