<?php

namespace Muserpol\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Genera una exportacion de Excel fuera de la peticion del usuario.
 *
 * Las exportaciones pesadas corrian dentro del request: con varios usuarios
 * exportando a la vez, cada uno ocupaba un worker de php-fpm durante minutos y
 * competia por memoria. Aca el trabajo pasa a la cola y el usuario recibe una
 * pagina que avisa cuando el archivo esta listo.
 */
class ExportExcelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** Tiempo maximo del job (segundos). Debe ser menor que queue.redis.retry_after. */
    public $timeout = 900;

    /** Sin reintentos: una exportacion de minutos no conviene repetirla sola. */
    public $tries = 1;

    public function __construct(
        public string $jobKey,
        public string $exportClass,
        public array $params,
        public string $filename,
        public int $userId
    ) {
    }

    public function handle(): void
    {
        Cache::put($this->cacheKey(), ['status' => 'processing'], 3600);

        try {
            $export = new $this->exportClass(...$this->params);
            $path = 'exports/' . $this->userId . '/' . $this->filename;

            Excel::store($export, $path, 'local');

            Cache::put($this->cacheKey(), [
                'status' => 'done',
                'path' => $path,
                'filename' => $this->filename,
            ], 3600);
        } catch (\Throwable $e) {
            Log::error('ExportExcelJob fallo', [
                'job' => $this->jobKey,
                'export' => $this->exportClass,
                'error' => $e->getMessage(),
            ]);

            Cache::put($this->cacheKey(), [
                'status' => 'failed',
                'error' => 'No se pudo generar el archivo.',
            ], 3600);

            throw $e;
        }
    }

    public function failed(\Throwable $e): void
    {
        Cache::put($this->cacheKey(), [
            'status' => 'failed',
            'error' => 'No se pudo generar el archivo.',
        ], 3600);
    }

    private function cacheKey(): string
    {
        return 'export:' . $this->jobKey;
    }
}
