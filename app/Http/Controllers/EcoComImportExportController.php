<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Muserpol\Imports\EcoComImportSenasir;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Imports\EcoComImportAPS;

class EcoComImportExportController extends Controller
{
    public function importSenasir(Request $request)
    {
        $uploadedFile = $request->file('image');
        $filename = 'senasir.' . $uploadedFile->getClientOriginalExtension();
        Storage::disk('local')->putFileAs(
            'senasir/' . now()->year,
            $uploadedFile,
            $filename
        );
        Excel::import(new EcoComImportSenasir, 'senasir/'.now()->year.'/senasir.xlsx');
        $no_import = EconomicComplement::with('eco_com_beneficiary')->select('economic_complements.*')
            ->leftJoin('affiliates', 'economic_complements.affiliate_id', '=', 'affiliates.id')
            ->where('eco_com_procedure_id',14)
            ->where('rent_type','<>','Automatico')
            ->where('affiliates.pension_entity_id',5)
            ->get();
        return array_merge(session()->get('senasir_data'), ['not_found'=>$no_import]);

        // return session()->get('senasir_data');
    }
    public function importAPS(Request $request)
    {
        logger($request->all());
        $sw_refresh = false;
        $sw_override = false;
        if ($request->refresh == 'true') {
            $sw_refresh = true;
        }
        if ($request->override == 'true') {
            $sw_override = true;
        }
        if (!$sw_override && !$sw_refresh) {
            logger("entre");
            $uploadedFile = $request->file('image');
            $filename = 'aps.' . $uploadedFile->getClientOriginalExtension();
            Storage::disk('local')->putFileAs(
                'aps/' . now()->year,
                $uploadedFile,
                $filename
            );
        };
        Excel::import(new EcoComImportAPS, 'aps/'.now()->year.'/aps.csv');
        $data = session()->get('aps_data');
        $collect = collect([]);
        $process = collect([]);
        foreach ($data as $d1) {
            $temp = $d1;
            // [34] PTC_DERECHOHABIENTE
            if ((is_null($d1[34]) || $d1[34] == 'C') && !$process->contains($d1[0])) {
                foreach ($data as $d2) {
                    if ($d1[3] == $d2[3] && ($d2[34] == 'C' || is_null($d2[34])) && $d1[0] != $d2[0]) {
                        $temp[13] =  $temp[13] + $d2[13]; //TOTAL_CC
                        $temp[19] =  $temp[19] + $d2[19]; //TOTAL_FSA
                        $temp[25] =  $temp[25] + $d2[25]; //TOTAL_FS
                        $process->push($d2[0]);
                    }
                }
                $collect->push($temp);
            }
        }
        // logger($collect->count());
        $eco_coms = EconomicComplement::with('affiliate')->select('economic_complements.*')
        ->leftJoin('affiliates', 'economic_complements.affiliate_id', '=', 'affiliates.id')
        ->where('affiliates.pension_entity_id','<>', 5)
        ->where('eco_com_procedure_id', 14)
        ->get();
        $fails = collect([]);
        foreach ($eco_coms as $e) {
            foreach ($collect as $c) {
                // logger($e->affiliate->identity_card);
                // logger($c[3]);
                $affiliate_ci_eco_com = explode("-", ltrim($e->affiliate->identity_card, "0"))[0];
                $ci_aps = explode("-", ltrim($c[10], "0"))[0];
                if ($ci_aps == $affiliate_ci_eco_com && $c[3] == $e->affiliate->nua) {
                    if ($e->aps_total_cc <> round($c[13],2) || $e->aps_total_fsa <> round($c[19],2) || $e->aps_total_fs <> round($c[25],2) ) {
                        if ($sw_override) {
                            $e->aps_total_cc = round($c[13],2);
                            $e->aps_total_fsa = round($c[19],2);
                            $e->aps_total_fs = round($c[25],2);
                            $e->save();
                        }else{
                            $e->aps_total_cc_aps = round($c[13],2);
                            $e->aps_total_fsa_aps = round($c[19],2);
                            $e->aps_total_fs_aps = round($c[25],2);
                            $fails->push($e);
                        }
                    }
                }
            }
        }
        logger("fails ".$fails->count());
        $data = [
            'fails'=>$fails
        ];
        return $data;
        // $no_import = EconomicComplement::with('eco_com_beneficiary')->select('economic_complements.*')
        //     ->leftJoin('affiliates', 'economic_complements.affiliate_id', '=', 'affiliates.id')
        //     ->where('eco_com_procedure_id',14)
        //     ->where('rent_type','<>','Automatico')
        //     ->where('affiliates.pension_entity_id',5)
        //     ->get();
        // return array_merge(session()->get('senasir_data'), ['not_found'=>$no_import]);
    }
}
