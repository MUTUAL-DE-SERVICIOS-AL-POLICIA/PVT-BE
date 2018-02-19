<?php

use Illuminate\Database\Seeder;
use Muserpol\Models\ProcedureModality;
class ShortenedModalitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
           
        $fallecimiento=ProcedureModality::find('1');
        $fallecimiento->shortened='FRPS - FALL';
        $fallecimiento->save();

        $retforzosoinvalides=ProcedureModality::find('2');
        $retforzosoinvalides->shortened='FRPS - RFI';
        $retforzosoinvalides->save();

        $jubilacion=ProcedureModality::find('3');
        $jubilacion->shortened='FRPS - JUB';
        $jubilacion->save();

        $fall=ProcedureModality::find('4');
        $fall->shortened='FRPS - FALL';
        $fall->save();

        $retiroforzoso=ProcedureModality::find('5');
        $retiroforzoso->shortened='FRPS - RF';
        $retiroforzoso->save();

        $retforinvalides=ProcedureModality::find('6');
        $retforinvalides->shortened='FRPS - RFI';
        $retforinvalides->save();

        $retvoluntario=ProcedureModality::find('7');
        $retvoluntario->shortened='FRPS - RV';
        $retvoluntario->save();

        $cmtitularcumpliendofun=ProcedureModality::find('8');
        $cmtitularcumpliendofun->shortened='CM - TFCF';
        $cmtitularcumpliendofun->save();

        $cmtitularriesgo=ProcedureModality::find('9');
        $cmtitularriesgo->shortened='CM - TFRC';
        $cmtitularriesgo->save();

        $amtitular=ProcedureModality::find('10');
        $amtitular->shortened='AM - TF';
        $amtitular->save();

        $amconyuge=ProcedureModality::find('11');
        $amconyuge->shortened='AM - CF';
        $amconyuge->save();

        $amviuda=ProcedureModality::find('12');
        $amviuda->shortened='AM - VF';
        $amviuda->save();

        //revisar
        // $anticipofr=ProcedureModality::find('13');
        // $anticipofr->shortened='FRPS - ANT';
        // $anticipofr->save();

        // $itemcero=ProcedureModality::find('14');
        // $itemcero->shortened='A.V. ITEM "0"';
        // $itemcero->save();

        // $expediente=ProcedureModality::find('15');
        // $expediente->shortened='ET';
        // $expediente->save();











        
        


    

        
    }
}
