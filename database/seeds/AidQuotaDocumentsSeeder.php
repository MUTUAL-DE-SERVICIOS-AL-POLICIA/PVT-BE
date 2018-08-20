<?php

use Illuminate\Database\Seeder;

class AidQuotaDocumentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('procedure_documents')->insert([
            ['name' => 'Declaratoria de Aceptación de Herencia (original o copia legalizada).'],
            ['name' => 'En el caso de herederos por sucesión testamentaria presentar “Testamento”, dentro del cual señale expresamente la otorgación del beneficio (original o copia legalizada).'],
            ['name' => 'Certificación sobre la verificación de partidas de matrimonio existentes del titular fallecido emitido por el SERECI.'],
            ['name' => 'Certificado de descendencia del titular fallecido.'],
            ['name' => 'Certificado de óbito sobre el registro de defunción en su cementerio del (os) derechohabiente (s) emitido por el Municipio o la Autoridad Originaria Campesina (original y actualizado).'],
            ['name' => 'Certificado de estado civil del (os) derechohabiente (s) antes de su fallecimiento emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado de descendencia del (os) derechohabiente (s) antes de su fallecimiento emitido por el SERECI (original y actualizado).'],
            ['name' => 'Declaración jurada voluntaria, poniendo en conocimiento la existencia o no del (os) derechohabiente (s) del titular fallecido emitida ante Notario de Fe Publica (original y actualizado).'],
            ['name' => 'Declaración jurada voluntaria, poniendo en conocimiento el fallecimiento del (os) derechohabiente (s) del titular fallecido emitida ante Notario de Fe Publica (original y actualizado).'],
            ['name' => 'Sobre lacrado dejado por el titular fallecido, mismo que debe estar aperturado por el (os) derechohabiente (s) ante autoridad competente para tal fin.'],
            ['name' => 'Certificado de la existencia o no de Cedula de Identidad del titular fallecido emitido por el SEGIP (original y actualizado).'],
            ['name' => 'Resolución sobre la adhesión del alfanúmero a la cedula de identidad del titular fallecido emitido por le SEGIP (original o fotocopia legalizada).'],
            ['name' => 'Resolución sobre la adhesión del alfanúmero a la cedula de identidad del (os) derechohabiente (s) emitido por el SEGIP (original o fotocopia legalizada).'],
            ['name' => 'Certificado de defunción de los progenitores del titular fallecido (original y actualizado).'],
            ['name' => 'Poder del extranjero visado por cancillería y transcrito en escritura pública emitido por Notaria de Fe Publica (original y actualizado).'],
            ['name' => 'Certificación sobre la verificación de partidas de matrimonio existentes del titular fallecido emitido por el SERECI.'],
            ['name' => 'Certificado de descendencia del titular fallecido.'],
            ['name' => 'Certificado de óbito sobre el registro de defunción en su cementerio del (os) derechohabiente (s) emitido por el Municipio o la Autoridad Originaria Campesina (original y actualizado).'],
            ['name' => 'Certificado de estado civil del (os) derechohabiente (s) antes de su fallecimiento emitido por el SERECI (original y actualizado).'],
            ['name' => 'Certificado de descendencia del (os) derechohabiente (s) antes de su fallecimiento emitido por el SERECI (original y actualizado).'],
            ['name' => 'Declaración jurada voluntaria, poniendo en conocimiento la existencia o no del (os) derechohabiente (s) del titular fallecido emitida ante Notario de Fe Publica (original y actualizado).'],
            ['name' => 'Declaración jurada voluntaria, poniendo en conocimiento el fallecimiento del (os) derechohabiente (s) del titular fallecido emitida ante Notario de Fe Publica (original y actualizado).'],
            ['name' => 'Sobre lacrado dejado por el titular fallecido, mismo que debe estar aperturado por el (os) derechohabiente (s) ante autoridad competente para tal fin.'],
            ['name' => 'Certificado de la existencia o no de Cedula de Identidad del titular fallecido emitido por el SEGIP (original y actualizado).'],
            ['name' => 'Fotocopia simple de la Cedula de Identidad del titular fallecido.'],
            ['name' => 'Resolución sobre la adhesión del alfanúmero a la cedula de identidad del titular fallecido emitido por le SEGIP (original o fotocopia legalizada).'],
            ['name' => 'Resolución sobre la adhesión del alfanúmero a la cedula de identidad del (os) derechohabiente (s) emitido por el SEGIP (original o fotocopia legalizada).'],
            ['name' => 'Certificado de defunción de los progenitores del titular fallecido (original y actualizado).'],            
         ]);        
    }

}
