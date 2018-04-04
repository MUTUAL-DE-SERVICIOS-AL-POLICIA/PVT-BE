<?php

namespace Muserpol\Http\Controllers;
use Muserpol\QuotaAidCertification;
use Illuminate\Http\Request;

use Muserpol\Models\Affiliate;
use Muserpol\Models\City;
use Session;
use Auth;
use Validator;
use Muserpol\Models\Address;
use DateTime;
use Muserpol\User;
use Carbon\Carbon;
use Muserpol\Helpers\Util;
use Muserpol\Models\Voucher;
use Muserpol\Models\VoucherType;
use Muserpol\Models\Spouse;

class QuotaAidCertificationController extends Controller
{
    //
    public function printQuotaAidCommitmentLetter($id)
    {
        $affiliate = Affiliate::find($id);
        $date = Util::getStringDate(date('Y-m-d'));
        $username = Auth::user()->username;//agregar cuando haya roles
        $city = Auth::user()->city->name;
        // return $city;
        if ($affiliate->affiliate_state->name == "Fallecido") {
            $title = "COMPROMISO DE PAGO - APORTE PARA SOLICITANTES PARA EL PAGO DE APORTE DIRECTO DE LAS (OS) VIUDAS (OS) DEL  SECTOR PASIVO CORRESPONDIENTE AL SISTEMA INTEGRAL DE PENSIONES";
            $spouses = Spouse::where('affiliate_id', $affiliate->id)->first();
            $beneficiary = "en mi calidad de viuda (o)";
            $glosa = "Soy beneficiaria(o) (derechohabiente) con una prestación en curso de pago del Sistema Integral de Pensiones (SIP).";
            $bene = $spouses;
        } else {
            $title = "COMPROMISO DE PAGO - APORTE PARA SOLICITANTES PARA EL PAGO DE APORTE DIRECTO DEL SECTOR PASIVO CORRESPONDIENTE AL SISTEMA INTEGRAL DE PENSIONES";
            $beneficiary = "como miembro del servicio pasivo de la Policía Boliviana";
            $glosa = "Recibo una prestación en curso de pago del Sistema Integral de Pensiones.";
            $bene = $affiliate;
        }
        $pdftitle = "Carta de Compromiso de Auxilio Mortuorio";
        $namepdf = Util::getPDFName($pdftitle, $bene);
        // return view('ret_fun.print.beneficiaries_qualification', compact('date','subtitle','username','title','number','retirement_fund','affiliate','submitted_documents'));
        return \PDF::loadView('quota_aid.print.quota_aid_commitment_letter', compact('date', 'username', 'title', 'glosa', 'bene', 'city', 'beneficiary'))->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
    }
}
