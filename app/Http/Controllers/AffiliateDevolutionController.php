<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Muserpol\Helpers\Util;
use DB;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Models\EconomicComplement\Devolution;
use Muserpol\Models\EconomicComplement\EcoComProcedure;

class AffiliateDevolutionController extends Controller
{
    public function getDevolutions($affiliate_id)
    {
        $affiliate = Affiliate::find($affiliate_id);
        $devolutions = $affiliate->devolutions()->with(['observation_type', 'dues'])->get();
        $devolution = $devolutions->where('observation_type_id', 13)->first();
        $dues = [];
        if ($devolution) {
            $dues = $devolution->dues()->select('dues.*')
                ->leftJoin('eco_com_procedures', 'dues.eco_com_procedure_id', '=', 'eco_com_procedures.id')
                ->orderByDesc('eco_com_procedures.year')
                ->orderByDesc('eco_com_procedures.semester')
                ->get();
            foreach ($dues as $d) {
                $d->eco_com_procedure_name = $d->eco_com_procedure->getTextName();
            }
        }
        $data = [
            'devolutions' => $devolutions,
            'devolution' => $devolution,
            'dues' => $dues,
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
            // ->where('eco_com_applicants.identity_card', $eco_com_beneficiary->identity_card)
            ->where('affiliate_id', $affiliate->id)
            ->select('economic_complements.*')
            ->orderBY('eco_com_procedures.year')
            ->orderBY('eco_com_procedures.semester')
            ->get();
        $eco_coms = $eco_coms->filter(function ($item) {
            return $item->discount_types->contains(6);
        });
        // dd($eco_coms->first()->discount_types->where('id', 6));
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
        logger($request->all());
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
            $devolution->save();
            return $devolution;
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
    public function printDevolutionPaymentCommitment($affiliate_id)
    {
        $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
        $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
        $unit = "UNIDAD DE OTORGACIÓN DEL COMPLEMENTO ECONÓMICO";
        $title = "COMPROMISO DE DEVOLUCIÓN POR PAGOS EN DEFECTO DEL COMPLEMENTO ECONÓMICO";
        $user = auth()->user();
        $area = Util::getRol()->wf_states->first()->first_shortened;
        $date = Util::getTextDate();
        $affiliate = Affiliate::find($affiliate_id);
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
        $current_semester = EcoComProcedure::find(Util::getEcoComCurrentProcedure()->first());
        $eco_com = $affiliate->economic_complements()->orderBy(DB::raw("regexp_replace(split_part(code, '/',3),'\D','','g')::integer"))->orderBy(DB::raw("split_part(code, '/',2)"))->orderBy(DB::raw("split_part(code, '/',1)::integer"))->get()->last();
        $eco_com_beneficiary = $eco_com->eco_com_beneficiary;
        // $eco_coms = EconomicComplement::with('discount_types')->leftJoin('eco_com_procedures', 'economic_complements.eco_com_procedure_id', '=', 'eco_com_procedures.id')
        //     ->leftJoin('eco_com_applicants', 'economic_complements.id', '=', 'eco_com_applicants.economic_complement_id')
        //     // ->where('eco_com_applicants.identity_card', $eco_com_beneficiary->identity_card)
        //     ->where('affiliate_id', $affiliate->id)
        //     ->select('economic_complements.*')
        //     ->orderBY('eco_com_procedures.year')
        //     ->orderBY('eco_com_procedures.semester')
        //     ->get();
        // $eco_coms = $eco_coms->filter(function ($item) {
        //     return $item->discount_types->contains(6);
        // });
        // dd($eco_coms->first()->discount_types->where('id', 6));
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
            'current_semester' => $current_semester
        ];
        $pages = [];
        $pages[] = \View::make('affiliates.print.devolution_payment_commitment', $data)->render();
        // $pages[] = \View::make('eco_com.print.certification_all_eco_coms_second', array_merge($data, ['title' => 'CONFORMIDAD DE ENTREGA']))->render();
        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($pages);
        return $pdf->setOption('encoding', 'utf-8')
            ->setOption('margin-bottom', '23mm')
            ->setOption('footer-html', $footerHtml)
            ->stream("certificacion.pdf");
    }
}
