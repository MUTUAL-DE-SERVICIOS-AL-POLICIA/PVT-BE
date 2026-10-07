<?php

namespace Muserpol\Http\Controllers\API;

use Illuminate\Http\Request;
use Muserpol\Http\Controllers\Controller;
use Muserpol\Http\Controllers\QuotaAidCertificationController as WebQuotaAidCertification;
use Muserpol\Models\Affiliate;
use Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary;
use Muserpol\Models\QuotaAidMortuary\QuotaAidCorrelative;
use Muserpol\Helpers\Util;

class QuotaAidCertificationController extends Controller
{
    public function index(Request $request)
    {
        $quota_aids = $request->affiliate->quota_aid_mortuaries()->get();

        return $this->toListResponse($quota_aids);
    }

    public function kioskIndex(Request $request)
    {
        $affiliate = Affiliate::where('identity_card', $request->ci)->first();

        if (!$affiliate) {
            return response()->json([
                'error' => true,
                'message' => 'Afiliado no encontrado',
                'data' => [],
            ], 404);
        }

        return $this->toListResponse($affiliate->quota_aid_mortuaries()->get());
    }

    private function toListResponse($quota_aids)
    {
        $data = $quota_aids->map(function (QuotaAidMortuary $quota_aid) {
            $printable = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)
                ->where('wf_state_id', 61)
                ->exists();

            return [
                'id' => $quota_aid->id,
                'code' => $quota_aid->code,
                'reception_date' => $quota_aid->reception_date
                    ? Util::getStringDate($quota_aid->reception_date)
                    : null,
                'procedure_modality' => $quota_aid->procedure_modality->name ?? null,
                'procedure_type' => $quota_aid->procedure_modality->procedure_type->second_name ?? null,
                'printable' => $printable,
            ];
        });

        return [
            'error' => false,
            'message' => 'Listado de trámites de cuota y auxilio mortuorio',
            'data' => $data,
        ];
    }

    public function print(Request $request, QuotaAidMortuary $quota_aid)
    {
        if ($quota_aid->affiliate_id != $request->affiliate->id) {
            return response()->json([
                'error' => true,
                'message' => 'Este trámite no le pertenece',
                'data' => null,
            ], 403);
        }
        return $this->print_pdf($quota_aid);
    }

    public function print_v2(Request $request, QuotaAidMortuary $quota_aid)
    {
        if ($quota_aid->affiliate_id != $request->affiliate->id) {
            return response()->json([
                'error' => true,
                'message' => 'Este trámite no le pertenece',
                'data' => null,
            ], 403);
        }
        return $this->print_pdf_v2($quota_aid);
    }

    public function printLiquidationKiosk(QuotaAidMortuary $quota_aid)
    {
        return $this->print_pdf($quota_aid, true);
    }

    private function print_pdf(QuotaAidMortuary $quota_aid, $single = false)
    {
        $result = (new WebQuotaAidCertification)->buildLiquidationPdf($quota_aid, $single);

        return response()->make($result['pdf'], 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $result['name'] . '"',
        ]);
    }

    private function print_pdf_v2(QuotaAidMortuary $quota_aid)
    {
        $result = (new WebQuotaAidCertification)->buildLiquidationPdf($quota_aid, true);

        return [
            'error' => false,
            'message' => 'PDF generado correctamente',
            'data' => [
                'filename' => $result['name'],
                'content' => base64_encode($result['pdf']),
            ],
        ];
    }
}