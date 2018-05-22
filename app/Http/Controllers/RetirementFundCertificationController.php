<?php

namespace Muserpol\Http\Controllers;

use Muserpol\RetirementFundCertification;
use Illuminate\Http\Request;


use Muserpol\Models\Affiliate;
use Muserpol\Models\ProcedureRequirement;
use Muserpol\Models\ProcedureModality;
use Muserpol\Models\Kinship;
use Muserpol\Models\City;
use Muserpol\Models\RetirementFund\RetirementFund;
use Muserpol\Models\RetirementFund\RetFunSubmittedDocument;
use Muserpol\Models\RetirementFund\RetFunBeneficiary;
use Muserpol\Models\RetirementFund\RetFunAddressBeneficiary;
use Muserpol\Models\RetirementFund\RetFunAdvisor;
use Muserpol\Models\RetirementFund\RetFunIncrement;
use Session;
use Auth;
use DB;
use Validator;
use Muserpol\Models\Address;
use Muserpol\Models\Spouse;
use Muserpol\Models\RetirementFund\RetFunLegalGuardian;
use Muserpol\Models\RetirementFund\RetFunAdvisorBeneficiary;
use Muserpol\Models\RetirementFund\RetFunLegalGuardianBeneficiary;
use Muserpol\Models\AffiliateFolder;
use DateTime;
use Muserpol\User;
use Carbon\Carbon;
use Muserpol\Helpers\Util;
use Muserpol\Models\Voucher;
use Muserpol\Models\VoucherType;
use Muserpol\Models\Contribution\ContributionCommitment;
use Muserpol\Models\Contribution\Contribution;

class RetirementFundCertificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Muserpol\RetirementFundCertification  $retirementFundCertification
     * @return \Illuminate\Http\Response
     */
    public function show(RetirementFundCertification $retirementFundCertification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Muserpol\RetirementFundCertification  $retirementFundCertification
     * @return \Illuminate\Http\Response
     */
    public function edit(RetirementFundCertification $retirementFundCertification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Muserpol\RetirementFundCertification  $retirementFundCertification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RetirementFundCertification $retirementFundCertification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Muserpol\RetirementFundCertification  $retirementFundCertification
     * @return \Illuminate\Http\Response
     */
    public function destroy(RetirementFundCertification $retirementFundCertification)
    {
        //
    }
    public function printReception($id)
    {
        $retirement_fund = RetirementFund::find($id);
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $modality = $retirement_fund->procedure_modality->name;
        $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
        $title = "REQUISITOS DEL BENEFICIO FONDO DE RETIRO – " . strtoupper($modality);
        $number = $retirement_fund->code;
        $user = Auth::user();//agregar cuando haya roles
        $username = Auth::user()->username;//agregar cuando haya roles
        $date = Util::getStringDate($retirement_fund->reception_date);
        $applicant = RetFunBeneficiary::where('type', 'S')->where('retirement_fund_id', $retirement_fund->id)->first();
        $submitted_documents = RetFunSubmittedDocument::leftJoin('procedure_requirements', 'procedure_requirements.id', '=', 'ret_fun_submitted_documents.procedure_requirement_id')->where('retirement_fund_id', $retirement_fund->id)->orderBy('procedure_requirements.number', 'asc')->get();
        $bar_code = \DNS2D::getBarcodePNG(("Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt quaerat enim, ea iure similique mollitia, molestiae voluptas, consequuntur aspernatur deserunt accusamus eius id beatae corporis! Blanditiis dolore aut aperiam error!"), "PDF417", 100, 33, array(1, 1, 1));
        //return view('ret_fun.print.reception', compact('title','usuario','fec_emi','name','ci','expedido'));

       // $pdf = view('print_global.reception', compact('title','usuario','fec_emi','name','ci','expedido'));       
    //    return view('ret_fun.print.reception',compact('user','title','institution', 'direction', 'unit','username','date','modality','applicant','submitted_documents','header','number'));
        $pdftitle = "Recepcion";
        $namepdf = Util::getPDFName($pdftitle, $applicant);
        $footerHtml = view()->make('ret_fun.print.footer', ['bar_code'=>$bar_code])->render();
        return \PDF::loadView('ret_fun.print.reception', compact('bar_code','user','title', 'institution', 'direction', 'unit', 'username', 'date', 'modality', 'applicant', 'submitted_documents', 'header', 'number'))
            ->setPaper('letter')
            // ->setOption('margin-top', '20mm')
            ->setOption('margin-bottom', '15mm')
            // ->setOption('margin-left', '25mm')
            // ->setOption('margin-right', '15mm')
            // ->setOption('encoding', 'utf-8')
            ->setOption('footer-right', 'Pagina [page] de [toPage]')
            // ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')
            ->setOption('footer-html', $footerHtml)
            ->stream("$namepdf");
    }
    public function printFile($id)
    {
        $affiliate = Affiliate::find($id);
        $retirement_fund = RetirementFund::where('affiliate_id', $affiliate->id)->get()->last();
        $number = $retirement_fund->code;
        $date = Util::getStringDate($retirement_fund->reception_date);
        $title = "CERTIFICACIÓN DE ARCHIVO – " . strtoupper($retirement_fund->procedure_modality->name ?? 'ERROR');
        $username = Auth::user()->username;//agregar cuando haya roles        
        $affiliate_folders = AffiliateFolder::where('affiliate_id', $affiliate->id)->get();
        $applicant = RetFunBeneficiary::where('type', 'S')->where('retirement_fund_id', $retirement_fund->id)->first();
        $cite = RetFunIncrement::getIncrement(Session::get('rol_id'), $retirement_fund->id);
        $subtitle = $cite;
        $pdftitle = "Certificación de Archivo";
        $namepdf = Util::getPDFName($pdftitle, $affiliate);
        return \PDF::loadView('ret_fun.print.file_certification', compact('date', 'subtitle', 'username', 'cite', 'title', 'number', 'retirement_fund', 'affiliate', 'affiliate_folders', 'applicant'))->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");

    }
    public function printLegalReview($id)
    {
        $retirement_fund = RetirementFund::find($id);
        $date = Util::getStringDate($retirement_fund->reception_date);
        //$title = "CERTIFICACION DE ARCHIVO – ".strtoupper($retirement_fund->procedure_modality->name);       
        $title = "CERTIFICACI&Oacute;N DE DOCUMENTACI&Oacute;N PRESENTADA Y REVISADA";
        $submitted_documents = RetFunSubmittedDocument::where('retirement_fund_id', $id)->orderBy('procedure_requirement_id', 'ASC')->get();
        $username = Auth::user()->username;//agregar cuando haya roles
        $date = Util::getStringDate($retirement_fund->reception_date);
        $affiliate = $retirement_fund->affiliate;
        $number = $retirement_fund->code;
//        $data = [
//            'retirement_fund'   =>  $retirement_fund,
//            'submitted_documents'   => $submitted_documents,            
//        ];
        $cite = RetFunIncrement::getIncrement(Session::get('rol_id'), $retirement_fund->id);
        $subtitle = $cite;
        $pdftitle = "Revision Legal";
        $namepdf = Util::getPDFName($pdftitle, $affiliate);
        return \PDF::loadView('ret_fun.print.legal_certification', compact('date', 'subtitle', 'username', 'title', 'number', 'retirement_fund', 'affiliate', 'submitted_documents'))->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
    }
    public function printBeneficiariesQualification($id)
    {
        $retirement_fund = RetirementFund::find($id);
        $date = date('d/m/Y');
        $title = $retirement_fund->procedure_modality->procedure_type->module->name;
        $username = Auth::user()->username;//agregar cuando haya roles

        $affiliate = $retirement_fund->affiliate;
        $applicant = $retirement_fund->ret_fun_beneficiaries()->where('type', 'S')->with('kinship')->first();
        $beneficiaries = $retirement_fund->ret_fun_beneficiaries;

        $number = $retirement_fund->code;
        $pdftitle = "Calificacion";
        $namepdf = Util::getPDFName($pdftitle, $affiliate);

        $data = [
            'date' => $date,
            'username' => $username,
            'title' => $title,
            'number' => $number,

            'affiliate' => $affiliate,
            'applicant' => $applicant,
            'beneficiaries' => $beneficiaries,
            'retirement_fund' => $retirement_fund,
        ];
        // return view('ret_fun.print.beneficiaries_qualification', $data);
        return \PDF::loadView('ret_fun.print.beneficiaries_qualification', $data)->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
    }
    public function printDataQualification($id)
    {
        $retirement_fund = RetirementFund::find($id);
        $date = date('d/m/Y');
        $title = $retirement_fund->procedure_modality->procedure_type->module->name;
        $username = Auth::user()->username;//agregar cuando haya roles
        $affiliate = $retirement_fund->affiliate;
        $applicant = $retirement_fund->ret_fun_beneficiaries()->where('type', 'S')->with('kinship')->first();

        $beneficiaries = $retirement_fund->ret_fun_beneficiaries;
        $number = $retirement_fund->code;
        $pdftitle = "Calificacion";
        $namepdf = Util::getPDFName($pdftitle, $affiliate);

        $data = [
            'date' => $date,
            'username' => $username,
            'title' => $title,
            'number' => $number,

            'affiliate' => $affiliate,
            'applicant' => $applicant,
            'beneficiaries' => $beneficiaries,
            'retirement_fund' => $retirement_fund,
        ];
        // return view('ret_fun.print.beneficiaries_qualification', $data);
        return \PDF::loadView('ret_fun.print.qualification_step_data', $data)->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
    }
    public function printDataQualificationAvailability($id)
    {
        $retirement_fund = RetirementFund::find($id);
        $date = date('d/m/Y');
        // $title = $retirement_fund->procedure_modality->procedure_type->module->name;
        $title = "devolución de aportes en disponibilidad ";
        $username = Auth::user()->username;//agregar cuando haya roles
        $affiliate = $retirement_fund->affiliate;
        $applicant = $retirement_fund->ret_fun_beneficiaries()->where('type', 'S')->with('kinship')->first();

        $beneficiaries = $retirement_fund->ret_fun_beneficiaries;
        $number = $retirement_fund->code;
        $pdftitle = "Calificacion";
        $namepdf = Util::getPDFName($pdftitle, $affiliate);

        $data = [
            'date' => $date,
            'username' => $username,
            'title' => $title,
            'number' => $number,

            'affiliate' => $affiliate,
            'applicant' => $applicant,
            'beneficiaries' => $beneficiaries,
            'retirement_fund' => $retirement_fund,
        ];
        // return view('ret_fun.print.beneficiaries_qualification', $data);
        return \PDF::loadView('ret_fun.print.qualification_data_availability', $data)->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
    }
    public function printDataQualificationRetFunAvailability($id)
    {
        $retirement_fund = RetirementFund::find($id);
        $date = date('d/m/Y');
        // $title = $retirement_fund->procedure_modality->procedure_type->module->name;
        $title = "fondo de retiro y disponibilidad ";
        $username = Auth::user()->username;//agregar cuando haya roles
        $affiliate = $retirement_fund->affiliate;
        $applicant = $retirement_fund->ret_fun_beneficiaries()->where('type', 'S')->with('kinship')->first();
        $beneficiaries = $retirement_fund->ret_fun_beneficiaries;
        $number = $retirement_fund->code;
        $pdftitle = "Calificacion";
        $namepdf = Util::getPDFName($pdftitle, $affiliate);

        $data = [
            'date' => $date,
            'username' => $username,
            'title' => $title,
            'number' => $number,

            'affiliate' => $affiliate,
            'applicant' => $applicant,
            'beneficiaries' => $beneficiaries,
            'retirement_fund' => $retirement_fund,
        ];
        // return view('ret_fun.print.beneficiaries_qualification', $data);
        return \PDF::loadView('ret_fun.print.qualification_data_ret_fun_availability', $data)->setPaper('letter')->setOption('encoding', 'utf-8')->setOption('footer-right', 'Pagina [page] de [toPage]')->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')->stream("$namepdf");
    }
    public function printRetFunCommitmentLetter($id)
    {
        $affiliate = Affiliate::find($id);
        $commitment = ContributionCommitment::where('affiliate_id', $affiliate->id)->first();
        $date = Util::getStringDate(date('Y-m-d'));
        $username = Auth::user()->username;//agregar cuando haya roles
        $city = Auth::user()->city->name;
        $glosa = "No corresponde";
        if ($affiliate->affiliate_state->name == "Baja Temporal") {
            $title = "COMPROMISO DE PAGO - APORTE VOLUNTARIO SUSPENDIDOS TEMPORALMENTE DE FUNCIONES POR PROCESOS DISCIPLINARIOS";
            $glosa = 'Suspendido temporalmente de funciones por procesos disciplinarios, figurando en planilla de haberes con ítem "0".';
            $glosa_pago = "de mi última boleta de pago efectivamente percibida";
        } else {
            $title = 'COMPROMISO DE PAGO - APORTE VOLUNTARIO COMISIÓN DE SERVICIO ÍTEM "0" O AGREGADOS POLICIALES EN EL EXTERIOR DEL PAÍS';
            $glosa_pago = "de mi total ganado mensual (sin descuentos)";
            if ($affiliate->affiliate_state->name == "Comisión") {
                $glosa = 'Comisión de Servicio Ítem "0".';
            } else {
                if ($affiliate->affiliate_state->name == "Agregado Policial") {
                    $glosa = "Agregado Policial en el exterior del país.";
                }
            }
        }
        $pdftitle = "Carta de Compromiso de Fondo de Retiro";
        $namepdf = Util::getPDFName($pdftitle, $affiliate);
        // return view('ret_fun.print.beneficiaries_qualification', compact('date','subtitle','username','title','number','retirement_fund','affiliate','submitted_documents'));
        return \PDF::loadView(
            'ret_fun.print.ret_fun_commitment_letter',
            compact(
                'date',
                'username',
                'title',
                'affiliate',
                'glosa',
                'city',
                'glosa_pago',
                'commitment'
            )
        )
            ->setPaper('letter')
            ->setOption('encoding', 'utf-8')
            ->setOption('footer-right', 'Pagina [page] de [toPage]')
            ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')
            ->stream("$namepdf");
    }

    public function printVoucher(Request $request, $affiliate_id, $voucher_id)
    {
        $affiliate = Affiliate::find($affiliate_id);
        $voucher = Voucher::find($voucher_id);
        $contributions = [];
        $total_literal = Util::convertir($voucher->total);
        $payment_date = Util::getStringDate($voucher->payment_date);
        $date = Util::getStringDate(date('Y-m-d'));
        $title = "RECIBO";
        $subtitle = "FONDO DE RETIRO Y CUOTA MORTUORIA";
        $username = Auth::user()->username;//agregar cuando haya roles
        $name_user_complet = Auth::user()->first_name . " " . Auth::user()->last_name;
        $number = $voucher->code;
        $descripcion = VoucherType::where('id', $voucher->voucher_type_id)->first();
        $beneficiary = $affiliate;
        $contributions = json_decode($request->contributions);
        $pdftitle = "Comprobante";
        $namepdf = Util::getPDFName($pdftitle, $beneficiary);
        $util = new Util();
        // return view('ret_fun.print.beneficiaries_qualification', compact('date','subtitle','username','title','number','retirement_fund','affiliate','submitted_documents'));
        return \PDF::loadView(
            'ret_fun.print.voucher_contribution',
            compact(
                'date',
                'username',
                'title',
                'subtitle',
                'affiliate',
                'submitted_documents',
                'beneficiary',
                'contributions',
                'number',
                'voucher',
                'util',
                'descripcion',
                'payment_date',
                'total_literal',
                'name_user_complet'
            )
        )
            ->setPaper('letter')
            ->setOption('encoding', 'utf-8')
            ->setOption('footer-right', 'Pagina [page] de [toPage]')
            ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')
            ->stream("$namepdf");
    }

    public function printDirectContributionQuote(Request $request)
    {
        $contributions = json_decode($request->contributions);
        $total = $request->total;
        $total_literal = Util::convertir($total);
        $affiliate = Affiliate::find($request->affiliate_id);
        $date = Util::getStringDate(date('Y-m-d'));
        $title = "PAGO DE APORTES VOLUNTARIOS APORTE DIRECTO VIUDAS EFECTIVO";
        $username = Auth::user()->username;//agregar cuando haya roles
        $name_user_complet = Auth::user()->first_name . " " . Auth::user()->last_name;
        $detail = "Pago de aporte directo";
        $beneficiary = $affiliate;
        $name_beneficiary_complet = Util::fullName($beneficiary);
        $pdftitle = "Comprobante";
        $namepdf = Util::getPDFName($pdftitle, $beneficiary);
        $util = new Util();
        return \PDF::loadView(
            'ret_fun.print.affiliate_contribution',
            compact(
                'date',
                'username',
                'title',
                'number',
                'beneficiary',
                'contributions',
                'total',
                'total_literal',
                'detail',
                'util',
                'name_user_complet',
                'name_beneficiary_complet'
            )
        )
            ->setPaper('letter')
            ->setOption('encoding', 'utf-8')
            ->setOption('footer-right', 'Pagina [page] de [toPage]')
            ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')
            ->stream("$namepdf");
    }
}
