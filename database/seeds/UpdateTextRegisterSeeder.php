<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureDocument;

class UpdateTextRegisterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProcedureDocument::whereIn('id', [18, 225, 224])
            ->update([
                'name'
                    => DB::raw("CASE
                                    WHEN id = 18  THEN 'Certificado de Haberes en original, considerando los últimos sesenta (60) meses antes del fallecimiento del Titular, emitido por el Comando General de la Policía Boliviana.'
                                    WHEN id = 225 THEN 'Poder Notarial conferido por el (la) o (los) derechohabientes del Titular en original, emitido por Autoridad Competente.'
                                    WHEN id = 224 THEN 'Poder Notarial conferido por el (la) o (los) y (las) derechohabientes del Titular en copia legalizada, emitido por Autoridad Competente.'
                                    ELSE name
                                END")
            ]);
    }
}
