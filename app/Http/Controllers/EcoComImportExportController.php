<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Muserpol\Imports\EcoComImportSenasir;

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
        return session()->get('senasir_data');
    }
}
