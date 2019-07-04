<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Muserpol\Imports\EcoComImportSenasir;
use Muserpol\Models\EconomicComplement\EconomicComplement;

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
}
