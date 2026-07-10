<?php

use Illuminate\Database\Seeder;

class SimplificaciónRequisitos extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => 3, 'shortened' => 'CI_T'],
            ['id' => 7, 'shortened' => 'CH'],
            ['id' => 9, 'shortened' => 'CD_T'],
            ['id' => 11, 'shortened' => 'CI_D'],
            ['id' => 12, 'shortened' => 'CERT_MAT'],
            ['id' => 13, 'shortened' => 'CUL_T'],
            ['id' => 15, 'shortened' => 'CDS'],
            ['id' => 16, 'shortened' => 'DHER'],
            ['id' => 17, 'shortened' => 'TES_OB'],
            ['id' => 19, 'shortened' => 'RES_BDEF'],
            ['id' => 20, 'shortened' => 'MEMO_BDEF'],
            ['id' => 25, 'shortened' => 'AHE_T'],
            ['id' => 26, 'shortened' => 'DICT_CALF'],
            ['id' => 27, 'shortened' => 'CERT_SLD'],
            ['id' => 29, 'shortened' => 'CD_C'],
            ['id' => 30, 'shortened' => 'CI_C'],
            ['id' => 32, 'shortened' => 'CD_V'],
            ['id' => 33, 'shortened' => 'CI_V'],
            ['id' => 44, 'shortened' => 'CERT_EC'],
            ['id' => 45, 'shortened' => 'CERT_BDEF'],
            ['id' => 67, 'shortened' => 'CERT_OBT'],
            ['id' => 71, 'shortened' => 'DJ_ECD'],
            ['id' => 101, 'shortened' => 'RES_NCI'],
            ['id' => 115, 'shortened' => 'CERT_PM'],
            ['id' => 121, 'shortened' => 'DJ_ECT'],
            ['id' => 130, 'shortened' => 'CD_P'],
            ['id' => 131, 'shortened' => 'PN_PT'],
            ['id' => 135, 'shortened' => 'TES_CD'],
            ['id' => 137, 'shortened' => 'TES_RH'],
            ['id' => 166, 'shortened' => 'PN'],
            ['id' => 179, 'shortened' => 'INF_TS'],
            ['id' => 186, 'shortened' => 'CRD'],
            ['id' => 187, 'shortened' => 'CRG'],
            ['id' => 189, 'shortened' => 'CERT_REI'],
            ['id' => 193, 'shortened' => 'CONT_AFP'],
            ['id' => 206, 'shortened' => 'MD_C'],
            ['id' => 208, 'shortened' => 'MD_A'],
            ['id' => 210, 'shortened' => 'APO'],
            ['id' => 218, 'shortened' => 'MEMO_REI'],
            ['id' => 226, 'shortened' => 'FR'],
            ['id' => 227, 'shortened' => 'CI_P'],
            ['id' => 228, 'shortened' => 'CI_HA'],
            ['id' => 229, 'shortened' => 'MAS'],
            ['id' => 231, 'shortened' => 'CAS'],
            ['id' => 237, 'shortened' => 'BOL_JUBV'],
            ['id' => 238, 'shortened' => 'TES_TUT'],
            ['id' => 239, 'shortened' => 'CERT_EST'],
            ['id' => 240, 'shortened' => 'CERT_TBJ'],
            ['id' => 234, 'shortened' => 'RES_SENASIR'],
            ['id' => 248, 'shortened' => 'CER_COM'],
            ['id' => 249, 'shortened' => 'BOL_PI'],
            ['id' => 260, 'shortened' => 'CER_IMAFP'],
            ['id' => 263, 'shortened' => 'DES_RENTA'],
            ['id' => 264, 'shortened' => 'DOC_DISC'],
            ['id' => 268, 'shortened' => 'CER_CIP'],
            ['id' => 269, 'shortened' => 'BOL_JUBC'],
            ['id' => 270, 'shortened' => 'RES_SENASIRV'],
            ['id' => 272, 'shortened' => 'FOR_SIGEP'],
            ['id' => 439, 'shortened' => 'CER_AM'],
            ['id' => 320, 'shortened' => 'FOR_AUT'],
            ['id' => 324, 'shortened' => 'CD'],
            ['id' => 325, 'shortened' => 'CND'],
            ['id' => 334, 'shortened' => 'CDIS_C'],
            ['id' => 335, 'shortened' => 'CDIS_A'],
            ['id' => 337, 'shortened' => 'CND_C'],
            ['id' => 338, 'shortened' => 'CND_A'],
            ['id' => 341, 'shortened' => 'CI_AP'],
            ['id' => 342, 'shortened' => 'CSB'],
            ['id' => 343, 'shortened' => 'CHB'],
            ['id' => 346, 'shortened' => 'CER_D'],
            ['id' => 378, 'shortened' => 'CERT_ECD'],
            ['id' => 379, 'shortened' => 'CERT_MATD'],
            ['id' => 380, 'shortened' => 'CERT_DESD'],
            ['id' => 407, 'shortened' => 'CD_D'],
            ['id' => 408, 'shortened' => 'RES_ASCT'],
            ['id' => 431, 'shortened' => 'CER_DP'],
            ['id' => 432, 'shortened' => 'FF'],
            ['id' => 433, 'shortened' => 'BOL_PAG'],
            ['id' => 434, 'shortened' => 'CSB_F'],
            ['id' => 435, 'shortened' => 'DMH'],
            ['id' => 436, 'shortened' => 'CD_CD'],
            ['id' => 437, 'shortened' => 'CN_H'],
            ['id' => 438, 'shortened' => 'C_RSENASIR'],
        ];

        foreach ($data as $item) {
            DB::table('procedure_documents')
            ->where('id', $item['id'])
            ->update(['shortened' => $item['shortened']]);
        }
    }
}
