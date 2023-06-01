<?php

use Illuminate\Database\Seeder;

class AidQuotaProcedureNewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('quota_aid_procedures')->insert([
            /*cuota en cumplimiento de funciones*/
            ['hierarchy_id' => '1', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '8','amount' => '70000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'12','months_max'=>'1200'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '8','amount' => '70000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'12','months_max'=>'1200'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '8','amount' => '60000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'12','months_max'=>'1200'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '8','amount' => '50000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'12','months_max'=>'1200'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '8','amount' => '40000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'12','months_max'=>'1200'],
            /*cuota riesgo comun*/
            ['hierarchy_id' => '1', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '18000', 'year'=>'2022-10-31','months'=>'40','months_min'=>'12','months_max'=>'40'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '19500', 'year'=>'2022-10-31','months'=>'80','months_min'=>'41','months_max'=>'80'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '21000', 'year'=>'2022-10-31','months'=>'120','months_min'=>'81','months_max'=>'120'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '22500', 'year'=>'2022-10-31','months'=>'160','months_min'=>'121','months_max'=>'160'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '24000', 'year'=>'2022-10-31','months'=>'200','months_min'=>'161','months_max'=>'200'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '25500', 'year'=>'2022-10-31','months'=>'240','months_min'=>'201','months_max'=>'240'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '27000', 'year'=>'2022-10-31','months'=>'280','months_min'=>'241','months_max'=>'280'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '28500', 'year'=>'2022-10-31','months'=>'320','months_min'=>'281','months_max'=>'320'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '30000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'321','months_max'=>'1200'],

            ['hierarchy_id' => '2', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '18000', 'year'=>'2022-10-31','months'=>'40','months_min'=>'12','months_max'=>'40'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '19500', 'year'=>'2022-10-31','months'=>'80','months_min'=>'41','months_max'=>'80'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '21000', 'year'=>'2022-10-31','months'=>'120','months_min'=>'81','months_max'=>'120'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '22500', 'year'=>'2022-10-31','months'=>'160','months_min'=>'121','months_max'=>'160'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '24000', 'year'=>'2022-10-31','months'=>'200','months_min'=>'161','months_max'=>'200'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '25500', 'year'=>'2022-10-31','months'=>'240','months_min'=>'201','months_max'=>'240'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '27000', 'year'=>'2022-10-31','months'=>'280','months_min'=>'241','months_max'=>'280'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '28500', 'year'=>'2022-10-31','months'=>'320','months_min'=>'281','months_max'=>'320'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '30000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'321','months_max'=>'1200'],

            ['hierarchy_id' => '4', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '14000', 'year'=>'2022-10-31','months'=>'40','months_min'=>'12','months_max'=>'40'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '15500', 'year'=>'2022-10-31','months'=>'80','months_min'=>'41','months_max'=>'80'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '17000', 'year'=>'2022-10-31','months'=>'120','months_min'=>'81','months_max'=>'120'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '18500', 'year'=>'2022-10-31','months'=>'160','months_min'=>'121','months_max'=>'160'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '20000', 'year'=>'2022-10-31','months'=>'200','months_min'=>'161','months_max'=>'200'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '21500', 'year'=>'2022-10-31','months'=>'240','months_min'=>'201','months_max'=>'240'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '23000', 'year'=>'2022-10-31','months'=>'280','months_min'=>'241','months_max'=>'280'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '24500', 'year'=>'2022-10-31','months'=>'320','months_min'=>'281','months_max'=>'320'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '26000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'321','months_max'=>'1200'],

            ['hierarchy_id' => '3', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '10000', 'year'=>'2022-10-31','months'=>'40','months_min'=>'12','months_max'=>'40'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '11500', 'year'=>'2022-10-31','months'=>'80','months_min'=>'41','months_max'=>'80'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '13000', 'year'=>'2022-10-31','months'=>'120','months_min'=>'81','months_max'=>'120'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '14500', 'year'=>'2022-10-31','months'=>'160','months_min'=>'121','months_max'=>'160'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '16000', 'year'=>'2022-10-31','months'=>'200','months_min'=>'161','months_max'=>'200'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '17500', 'year'=>'2022-10-31','months'=>'240','months_min'=>'201','months_max'=>'240'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '19000', 'year'=>'2022-10-31','months'=>'280','months_min'=>'241','months_max'=>'280'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '20500', 'year'=>'2022-10-31','months'=>'320','months_min'=>'281','months_max'=>'320'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '22000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'321','months_max'=>'1200'],

            ['hierarchy_id' => '5', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '10000', 'year'=>'2022-10-31','months'=>'40','months_min'=>'12','months_max'=>'40'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '11500', 'year'=>'2022-10-31','months'=>'80','months_min'=>'41','months_max'=>'80'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '13000', 'year'=>'2022-10-31','months'=>'120','months_min'=>'81','months_max'=>'120'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '14500', 'year'=>'2022-10-31','months'=>'160','months_min'=>'121','months_max'=>'160'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '16000', 'year'=>'2022-10-31','months'=>'200','months_min'=>'161','months_max'=>'200'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '17500', 'year'=>'2022-10-31','months'=>'240','months_min'=>'201','months_max'=>'240'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '19000', 'year'=>'2022-10-31','months'=>'280','months_min'=>'241','months_max'=>'280'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '20500', 'year'=>'2022-10-31','months'=>'320','months_min'=>'281','months_max'=>'320'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '9','amount' => '22000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'321','months_max'=>'1200'],
/*auxilio mortuorio */

            ['hierarchy_id' => '1', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '30000', 'year'=>'2022-10-31','months'=>'5','months_min'=>'1','months_max'=>'5'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '30750', 'year'=>'2022-10-31','months'=>'30','months_min'=>'6','months_max'=>'30'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '31500', 'year'=>'2022-10-31','months'=>'60','months_min'=>'31','months_max'=>'60'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '32250', 'year'=>'2022-10-31','months'=>'90','months_min'=>'61','months_max'=>'90'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '33000', 'year'=>'2022-10-31','months'=>'120','months_min'=>'91','months_max'=>'120'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '33750', 'year'=>'2022-10-31','months'=>'150','months_min'=>'121','months_max'=>'150'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '34500', 'year'=>'2022-10-31','months'=>'180','months_min'=>'151','months_max'=>'180'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '35250', 'year'=>'2022-10-31','months'=>'210','months_min'=>'181','months_max'=>'210'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '36000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'211','months_max'=>'1200'],

            ['hierarchy_id' => '2', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '30000', 'year'=>'2022-10-31','months'=>'5','months_min'=>'1','months_max'=>'5'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '30750', 'year'=>'2022-10-31','months'=>'30','months_min'=>'6','months_max'=>'30'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '31500', 'year'=>'2022-10-31','months'=>'60','months_min'=>'31','months_max'=>'60'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '32250', 'year'=>'2022-10-31','months'=>'90','months_min'=>'61','months_max'=>'90'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '33000', 'year'=>'2022-10-31','months'=>'120','months_min'=>'91','months_max'=>'120'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '33750', 'year'=>'2022-10-31','months'=>'150','months_min'=>'121','months_max'=>'150'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '34500', 'year'=>'2022-10-31','months'=>'180','months_min'=>'151','months_max'=>'180'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '35250', 'year'=>'2022-10-31','months'=>'210','months_min'=>'181','months_max'=>'210'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '36000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'211','months_max'=>'1200'],

            ['hierarchy_id' => '4', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '26000', 'year'=>'2022-10-31','months'=>'5','months_min'=>'1','months_max'=>'5'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '26750', 'year'=>'2022-10-31','months'=>'30','months_min'=>'6','months_max'=>'30'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '27500', 'year'=>'2022-10-31','months'=>'60','months_min'=>'31','months_max'=>'60'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '28250', 'year'=>'2022-10-31','months'=>'90','months_min'=>'61','months_max'=>'90'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '29000', 'year'=>'2022-10-31','months'=>'120','months_min'=>'91','months_max'=>'120'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '29750', 'year'=>'2022-10-31','months'=>'150','months_min'=>'121','months_max'=>'150'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '30500', 'year'=>'2022-10-31','months'=>'180','months_min'=>'151','months_max'=>'180'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '31250', 'year'=>'2022-10-31','months'=>'210','months_min'=>'181','months_max'=>'210'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '32000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'211','months_max'=>'1200'],

            ['hierarchy_id' => '3', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '22000', 'year'=>'2022-10-31','months'=>'5','months_min'=>'1','months_max'=>'5'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '22750', 'year'=>'2022-10-31','months'=>'30','months_min'=>'6','months_max'=>'30'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '23500', 'year'=>'2022-10-31','months'=>'60','months_min'=>'31','months_max'=>'60'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '24250', 'year'=>'2022-10-31','months'=>'90','months_min'=>'61','months_max'=>'90'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '25000', 'year'=>'2022-10-31','months'=>'120','months_min'=>'91','months_max'=>'120'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '25750', 'year'=>'2022-10-31','months'=>'150','months_min'=>'121','months_max'=>'150'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '26500', 'year'=>'2022-10-31','months'=>'180','months_min'=>'151','months_max'=>'180'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '27250', 'year'=>'2022-10-31','months'=>'210','months_min'=>'181','months_max'=>'210'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '28000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'211','months_max'=>'1200'],

            ['hierarchy_id' => '5', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '22000', 'year'=>'2022-10-31','months'=>'5','months_min'=>'1','months_max'=>'5'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '22750', 'year'=>'2022-10-31','months'=>'30','months_min'=>'6','months_max'=>'30'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '23500', 'year'=>'2022-10-31','months'=>'60','months_min'=>'31','months_max'=>'60'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '24250', 'year'=>'2022-10-31','months'=>'90','months_min'=>'61','months_max'=>'90'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '25000', 'year'=>'2022-10-31','months'=>'120','months_min'=>'91','months_max'=>'120'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '25750', 'year'=>'2022-10-31','months'=>'150','months_min'=>'121','months_max'=>'150'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '26500', 'year'=>'2022-10-31','months'=>'180','months_min'=>'151','months_max'=>'180'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '27250', 'year'=>'2022-10-31','months'=>'210','months_min'=>'181','months_max'=>'210'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Titular', 'procedure_modality_id' => '13','amount' => '28000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'211','months_max'=>'1200'],
//
            ['hierarchy_id' => '1', 'type_mortuary' => 'Conyuge', 'procedure_modality_id' => '14','amount' => '12000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'12','months_max'=>'1200'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Conyuge', 'procedure_modality_id' => '14','amount' => '12000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'12','months_max'=>'1200'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Conyuge', 'procedure_modality_id' => '14','amount' => '10000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'12','months_max'=>'1200'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Conyuge', 'procedure_modality_id' => '14','amount' => '8000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'12','months_max'=>'1200'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Conyuge', 'procedure_modality_id' => '14','amount' => '8000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'12','months_max'=>'1200'],
//
            ['hierarchy_id' => '1', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '10000', 'year'=>'2022-10-31','months'=>'20','months_min'=>'1','months_max'=>'20'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '10750', 'year'=>'2022-10-31','months'=>'40','months_min'=>'21','months_max'=>'40'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '11500', 'year'=>'2022-10-31','months'=>'60','months_min'=>'41','months_max'=>'60'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '12250', 'year'=>'2022-10-31','months'=>'80','months_min'=>'61','months_max'=>'80'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '13000', 'year'=>'2022-10-31','months'=>'100','months_min'=>'81','months_max'=>'100'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '13750', 'year'=>'2022-10-31','months'=>'120','months_min'=>'101','months_max'=>'120'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '14500', 'year'=>'2022-10-31','months'=>'140','months_min'=>'121','months_max'=>'140'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '15250', 'year'=>'2022-10-31','months'=>'160','months_min'=>'141','months_max'=>'160'],
            ['hierarchy_id' => '1', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '16000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'161','months_max'=>'1200'],

            ['hierarchy_id' => '2', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '10000', 'year'=>'2022-10-31','months'=>'20','months_min'=>'1','months_max'=>'20'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '10750', 'year'=>'2022-10-31','months'=>'40','months_min'=>'21','months_max'=>'40'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '11500', 'year'=>'2022-10-31','months'=>'60','months_min'=>'41','months_max'=>'60'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '12250', 'year'=>'2022-10-31','months'=>'80','months_min'=>'61','months_max'=>'80'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '13000', 'year'=>'2022-10-31','months'=>'100','months_min'=>'81','months_max'=>'100'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '13750', 'year'=>'2022-10-31','months'=>'120','months_min'=>'101','months_max'=>'120'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '14500', 'year'=>'2022-10-31','months'=>'140','months_min'=>'121','months_max'=>'140'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '15250', 'year'=>'2022-10-31','months'=>'160','months_min'=>'141','months_max'=>'160'],
            ['hierarchy_id' => '2', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '16000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'161','months_max'=>'1200'],

            ['hierarchy_id' => '4', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '8000', 'year'=>'2022-10-31' ,'months'=>'20','months_min'=>'1','months_max'=>'20'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '8750', 'year'=>'2022-10-31' ,'months'=>'40','months_min'=>'21','months_max'=>'40'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '9500', 'year'=>'2022-10-31' ,'months'=>'60','months_min'=>'41','months_max'=>'60'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '10250', 'year'=>'2022-10-31','months'=>'80','months_min'=>'61','months_max'=>'80'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '11000', 'year'=>'2022-10-31','months'=>'100','months_min'=>'81','months_max'=>'100'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '11750', 'year'=>'2022-10-31','months'=>'120','months_min'=>'101','months_max'=>'120'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '12500', 'year'=>'2022-10-31','months'=>'140','months_min'=>'121','months_max'=>'140'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '13250', 'year'=>'2022-10-31','months'=>'160','months_min'=>'141','months_max'=>'160'],
            ['hierarchy_id' => '4', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '14000', 'year'=>'2022-10-31','months'=>'1200','months_min'=>'161','months_max'=>'1200'],

            ['hierarchy_id' => '3', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '6000', 'year'=>'2022-10-31','months'=>'20','months_min'=>'1','months_max'=>'20'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '6750', 'year'=>'2022-10-31','months'=>'40','months_min'=>'21','months_max'=>'40'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '7500', 'year'=>'2022-10-31','months'=>'60','months_min'=>'41','months_max'=>'60'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '8250', 'year'=>'2022-10-31','months'=>'80','months_min'=>'61','months_max'=>'80'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '9000', 'year'=>'2022-10-31','months'=>'100','months_min'=>'81','months_max'=>'100'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '9750', 'year'=>'2022-10-31','months'=>'120','months_min'=>'101','months_max'=>'120'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '10500','year'=>'2022-10-31','months'=>'140','months_min'=>'121','months_max'=>'140'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '11250','year'=>'2022-10-31','months'=>'160','months_min'=>'141','months_max'=>'160'],
            ['hierarchy_id' => '3', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '12000','year'=>'2022-10-31','months'=>'1200','months_min'=>'161','months_max'=>'1200'],

            ['hierarchy_id' => '5', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '6000', 'year'=>'2022-10-31','months'=>'20','months_min'=>'1','months_max'=>'20'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '6750', 'year'=>'2022-10-31','months'=>'40','months_min'=>'21','months_max'=>'40'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '7500', 'year'=>'2022-10-31','months'=>'60','months_min'=>'41','months_max'=>'60'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '8250', 'year'=>'2022-10-31','months'=>'80','months_min'=>'61','months_max'=>'80'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '9000', 'year'=>'2022-10-31','months'=>'100','months_min'=>'81','months_max'=>'100'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '9750', 'year'=>'2022-10-31','months'=>'120','months_min'=>'101','months_max'=>'120'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '10500','year'=>'2022-10-31','months'=>'140','months_min'=>'121','months_max'=>'140'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '11250','year'=>'2022-10-31','months'=>'160','months_min'=>'141','months_max'=>'160'],
            ['hierarchy_id' => '5', 'type_mortuary' => 'Viuda', 'procedure_modality_id' => '15','amount' => '12000','year'=>'2022-10-31','months'=>'1200','months_min'=>'161','months_max'=>'1200'],

        ]);
    }
}
