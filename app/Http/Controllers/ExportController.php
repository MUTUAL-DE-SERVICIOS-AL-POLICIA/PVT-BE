<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Muserpol\Jobs\ExportExcelJob;

/**
 * Descarga de las exportaciones generadas en segundo plano.
 *
 * Flujo: el endpoint de reportes encola ExportExcelJob y devuelve una pagina
 * que consulta el estado; cuando el job termina, el navegador descarga el
 * archivo por aca.
 */
class ExportController extends Controller
{
    /** Encola una exportacion. Devuelve la clave con la que se consulta el estado. */
    public function dispatchJob(Request $request, string $exportClass, array $params, string $filename)
    {
        $jobKey = Str::uuid()->toString();

        Cache::put('export:' . $jobKey, ['status' => 'queued'], 3600);

        ExportExcelJob::dispatch($jobKey, $exportClass, $params, $filename, (int) auth()->id());

        return $jobKey;
    }

    public function status(string $jobKey)
    {
        $estado = Cache::get('export:' . $jobKey);

        if (!$estado) {
            return response()->json(['status' => 'unknown'], 404);
        }

        return response()->json($estado);
    }

    public function download(string $jobKey)
    {
        $estado = Cache::get('export:' . $jobKey);

        if (!$estado || ($estado['status'] ?? null) !== 'done') {
            abort(404, 'La exportacion no esta lista.');
        }

        $path = $estado['path'];

        // Solo el dueno puede descargarla.
        if (!Str::startsWith($path, 'exports/' . auth()->id() . '/')) {
            abort(403);
        }

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'El archivo ya no esta disponible.');
        }

        return Storage::disk('local')->download($path, $estado['filename']);
    }
}
