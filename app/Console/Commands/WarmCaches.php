<?php

namespace Muserpol\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Muserpol\Models\Contribution\Contribution;

/**
 * Precalienta los caches que dependen de tablas enormes.
 *
 * /get_calculation_years recorre ~13 millones de contribuciones para sacar la
 * lista de anios. El resultado se cachea un dia, pero la primera peticion
 * despues del vencimiento paga el costo completo. Correr esto al desplegar
 * evita que el primer usuario lo sufra.
 */
class WarmCaches extends Command
{
    protected $signature = 'cache:warm';
    protected $description = 'Precalienta los caches costosos (anios de calculo de salarios)';

    public function handle()
    {
        $t = microtime(true);

        Cache::forget('salary_calculation_years');

        $years = Cache::remember('salary_calculation_years', 86400, function () {
            return Contribution::select(DB::raw('EXTRACT(YEAR FROM month_year) as year'))
                ->whereIn('contributionable_type', ['payroll_commands', 'contributions'])
                ->where('type', 'Planilla')
                ->distinct()
                ->orderBy('year')
                ->pluck('year');
        });

        $this->info(sprintf(
            'salary_calculation_years: %d anios en %.2fs',
            $years->count(),
            microtime(true) - $t
        ));

        return self::SUCCESS;
    }
}
