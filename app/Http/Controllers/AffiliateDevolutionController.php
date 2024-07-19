<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Muserpol\Helpers\Util;
use DB;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Models\EconomicComplement\Devolution;
use Muserpol\Models\EconomicComplement\EcoComDirectPayment;
use Muserpol\Models\EconomicComplement\EcoComMovement;
use Muserpol\Models\EconomicComplement\EcoComProcedure;

class AffiliateDevolutionController extends Controller
{
    public function getDevolutions($affiliate_id)
    {
        $devolutions = Devolution::where('affiliate_id',$affiliate_id)->get();
        $list_devolution= collect();
        foreach ($devolutions as $devolution ) {
            $devolution_object = new \stdClass();
            $devolution_object->id = $devolution->id;
            $devolution_object->has_payment_commitmment = $devolution->has_payment_commitmment;
            $devolution_object->percentage = $devolution->percentage;
            $devolution_object->start_eco_com_procedure = $devolution->eco_com_procedure ? $devolution->eco_com_procedure->getTextName() : null;
            $devolution_object->total = EcoComMovement::where("movement_id",$devolution->id)->where("movement_type","devolutions")->first()->amount;
            $dues = $devolution->dues;
            $devolution_object->dues = collect();
            $correlative = 1;
            foreach ($dues as $due) {
                $due_object = new \stdClass();
                $due_object->id = $due->id;
                $due_object->correlative = $correlative++;
                $due_object->amount = $due->amount;
                $due_object->eco_com_procedure_name=$due->eco_com_procedure->getTextName();
                $devolution_object->dues->push($due_object);
            }
            $list_devolution->push($devolution_object);
        }
        $data = [
            'devolutions' => $list_devolution,
        ];
        return $data;
    }
    public function printCertificationDevolutions($affiliate_id)
    {
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DEL COMPLEMENTO ECONÓMICO";
        $title = "CERTIFICACIÓN DE REPOSICIÓN DE FONDOS";
        $user = auth()->user();
        $area = Util::getRol()->wf_states->first()->first_shortened;
        $date = Util::getTextDate();
        $affiliate = Affiliate::find($affiliate_id);
        $movement_list = EcoComMovement::where("affiliate_id", $affiliate_id)->get();
        $balance =EcoComMovement::where("affiliate_id", $affiliate_id)->latest()->orderBy('id', 'desc')->first()->balance;
        $direct_payments = $movement_list->where("movement_type", "eco_com_direct_payments");
        $direct_payment_list = collect([]);
        foreach ($direct_payments as $direct_payment) {
            $payment = new \stdClass();
            $payment->amount = $direct_payment->amount;
            $eco_com_direct_payment = EcoComDirectPayment::find($direct_payment->movement_id)->first();
            $payment->voucher = $eco_com_direct_payment->voucher;
            $payment->payment_date = $eco_com_direct_payment->payment_date;
            $direct_payment_list->push($payment);
        }
        $dues_list = $movement_list->where("movement_type", "devolutions");
        $total_dues = $dues_list->sum('amount');
        $devolutions = $affiliate->devolutions()->with(['observation_type', 'dues'])->get();
        $devolution = $devolutions->where('observation_type_id', 13)->first();
        if (!isset($devolution->id)) {
            return response()->json('El Trámite no tiene deudas', 204);
        }
        $dues = $devolution->dues()->select('dues.*')
        ->leftJoin('eco_com_procedures', 'dues.eco_com_procedure_id', '=', 'eco_com_procedures.id')
        ->orderBy('eco_com_procedures.year')
        ->orderBy('eco_com_procedures.semester')
        ->get();
        $semesters = collect([]);
        foreach ($dues as $d) {
            $semesters->push($d->eco_com_procedure->getTextName());
        }
        $semesters = $semesters->implode(', ');
        $eco_com = $affiliate->economic_complements()->orderBy(DB::raw("regexp_replace(split_part(code, '/',3),'\D','','g')::integer"))->orderBy(DB::raw("split_part(code, '/',2)"))->orderBy(DB::raw("split_part(code, '/',1)::integer"))->get()->last();
        $eco_com_beneficiary = $eco_com->eco_com_beneficiary;
        $eco_coms = EconomicComplement::with('discount_types')->leftJoin('eco_com_procedures', 'economic_complements.eco_com_procedure_id', '=', 'eco_com_procedures.id')
        ->leftJoin('eco_com_applicants', 'economic_complements.id', '=', 'eco_com_applicants.economic_complement_id')
        ->where('affiliate_id', $affiliate->id)
            ->whereIn('economic_complements.eco_com_state_id', [1, 21, 17, 18, 26, 2])
            ->select('economic_complements.*')
            ->orderBY('eco_com_procedures.year')
            ->orderBY('eco_com_procedures.semester')
            ->get();
        $eco_coms = $eco_coms->filter(function ($item) {
            return $item->discount_types->contains(6);
        });
        $bar_code = \DNS2D::getBarcodePNG($affiliate->encode(), "QRCODE");
        $footerHtml = view()->make('eco_com.print.footer', ['bar_code' => $bar_code, 'user' => $user])->render();

        $data = [
            'direction' => $direction,
            'institution' => $institution,
            'unit' => $unit,
            'title' => $title,
            'area' => $area,
            'date' => $date,
            'total_dues' => $total_dues,
            'direct_payment_list' => $direct_payment_list,
            'balance'=>$balance,
            'devolution' => $devolution,
            'eco_com_beneficiary' => $eco_com_beneficiary,
            'affiliate' => $affiliate,
            'eco_coms' => $eco_coms,
            'user' => $user,
            'semesters' => $semesters
        ];
        $pages = [];
        $pages[] = \View::make('eco_com.print.certification_devolutions', $data)->render();

        $pages[] = \View::make('eco_com.print.certification_all_eco_coms_second', array_merge($data, ['title' => 'CONFORMIDAD DE ENTREGA']))->render();
        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($pages);
        return $pdf->setOption('encoding', 'utf-8')
        ->setOption('margin-bottom', '23mm')
        ->setOption('footer-html', $footerHtml)
            ->stream("certificacion.pdf");
        return $data;
    }
    public function store(Request $request)
    {
        $affiliate = Affiliate::find($request->affiliate_id);
        $address = $affiliate->address->first();
        if (!$address) {
            return response()->json([
                'errors' => ['Debe Actualizar la información de domicilio del afiliado.']
            ], 422);
        }
        $devolution = $affiliate->devolutions()->where('observation_type_id', 13)->first();
        if ($devolution) {
            if ($request->discountType == 'percentage') {
                $devolution->percentage = $request->percentage;
            } else {
                $devolution->percentage = null;
            }
            $devolution->has_payment_commitment = true;
            $devolution->start_eco_com_procedure_id = $request->start_eco_com_procedure_id;
            $devolution->save();
            $data = [
                'devolution' => $devolution,
            ];
            return $data;
            // $devolution->eco_com_procedures()->sync($request->eco_com_procedures);
            // return redirect()->route('devolution_print', $devolution->id);
        }
        return response()->json([
            'errors' => ['El afiliado no tiene deudas.']
        ], 422);
        // }
        // Session::flash('message', $message);
        // return redirect('affiliate/' . $affiliate->id);
    }
    public function printDevolutionPaymentCommitment(Request $request, $affiliate_id)
    {
        $duesss = $request->all();

        $duess = $request->dues;
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DEL COMPLEMENTO ECONÓMICO";
        $title = "COMPROMISO DE DEVOLUCIÓN POR PAGOS EN DEMASÍA DEL COMPLEMENTO ECONÓMICO";
        $user = auth()->user();
        $area = Util::getRol()->wf_states->first()->first_shortened;
        $date = Util::getTextDate();
        $affiliate = Affiliate::find($affiliate_id);
        $devolutions = $affiliate->devolutions()->with(['observation_type', 'dues'])
            ->where('observation_type_id', 13)
            ->get();

        $devolution = $devolutions->first();
        $dues = collect([]);

        foreach ($devolutions as $devolution) {
            $aux = $devolution->dues()->get();
            if(count($aux) != 0) {
                $dues = $dues->concat($aux->whereIn('id', $duess));
            }
        }

        if($devolutions->isEmpty()) {
            return response()->json(['error' => 'No se encontraron devoluciones.'], 400);
        }

        $semesters = collect([]);
        foreach ($dues as $d) {
           $semesters->push($d->eco_com_procedure->getTextName());
        }
        $semesters = $semesters->implode(', ');
        if(!($devolution->start_eco_com_procedure_id && $devolution->has_payment_commitment)) {
            return response()->json(['error' => 'El Trámite no tiene compromiso de pago y/o el semestre de inicio de pago.'], 400);
        }
        $start_eco_com_procedure = EcoComProcedure::find($devolution->start_eco_com_procedure_id);
        $eco_com = $affiliate->economic_complements()->orderBy(DB::raw("regexp_replace(split_part(code, '/',3),'\D','','g')::integer"))->orderBy(DB::raw("split_part(code, '/',2)"))->orderBy(DB::raw("split_part(code, '/',1)::integer"))->get()->last();
        $eco_com_beneficiary = $eco_com->eco_com_beneficiary;
        $bar_code = \DNS2D::getBarcodePNG($affiliate->encode(), "QRCODE");
        $footerHtml = view()->make('eco_com.print.footer', ['bar_code' => $bar_code, 'user' => $user])->render();

        $data = [
            'direction' => $direction,
            'institution' => $institution,
            'unit' => $unit,
            'title' => $title,
            'area' => $area,
            'date' => $date,

            'devolution' => $devolution,
            'dues' => $dues,
            'eco_com_beneficiary' => $eco_com_beneficiary,
            'affiliate' => $affiliate,
            'eco_com' => $eco_com,
            'user' => $user,
            'semesters' => $semesters,
            'start_eco_com_procedure' => $start_eco_com_procedure,
            'duess' => $duess
        ];
        $pages = [];
        $pages[] = \View::make('affiliates.print.devolution_payment_commitment', $data)->render();
        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($pages);
        return $pdf->setOption('encoding', 'utf-8')
            ->setOption('margin-bottom', '23mm')
            ->setOption('footer-html', $footerHtml)
            ->stream("certificacion.pdf");
    }

    public function actualizarTotalDeuda(Request $request){
        $affiliate = Affiliate::find($request->affiliate_id);
        $devolution = $affiliate->devolutions()->with('dues')->where('observation_type_id', 13)->first();

        $dues = $devolution->dues()->get();
        $totalDeuda=0;
        foreach ($dues as $d) {
            $totalDeuda+= $d->amount;
        }

        if ($totalDeuda>0)
        {
            $devolution->total=$totalDeuda;
            $devolution->save();
        }

        $data = [
            'devolution' => $devolution,
        ];
        return $data;
    }

    public function actualizarTotalDeudaPendiente(Request $request){
        $affiliate = Affiliate::find($request->affiliate_id);
        $devolution = $affiliate->devolutions()->where('observation_type_id', 13)->first();

        $user = auth()->user();
        $eco_coms = EconomicComplement::with('discount_types')->leftJoin('eco_com_procedures', 'economic_complements.eco_com_procedure_id', '=', 'eco_com_procedures.id')
            ->leftJoin('eco_com_applicants', 'economic_complements.id', '=', 'eco_com_applicants.economic_complement_id')
            ->where('affiliate_id', $affiliate->id)
            ->whereIn('economic_complements.eco_com_state_id', [1,21,17,18,26,2])
            ->select('economic_complements.*')
            ->orderBY('eco_com_procedures.year')
            ->orderBY('eco_com_procedures.semester')
            ->get();
        $eco_coms = $eco_coms->filter(function ($item) {
            return $item->discount_types->contains(6);
        });

        $totalDeudaPendiente=0;
        foreach ($eco_coms as $eco){
            $totalDeudaPendiente+=$eco->discount_types->where('id',6)->first()->pivot->amount;
        }

        if (!is_null($devolution->payment_amount))
        {
            $totalDeudaPendiente+=$devolution->payment_amount;
        }

        if ($totalDeudaPendiente>=0)
        {
            $devolution->balance=$devolution->total - $totalDeudaPendiente;
            $devolution->save();
        }

        $data = [
            'devolution' => $devolution,
        ];
        return $data;
    }
}
