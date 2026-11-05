<?php

namespace Muserpol\Http\Controllers\API;

use Illuminate\Http\Request;
use Muserpol\Http\Controllers\Controller;
use Muserpol\Http\Controllers\RetirementFundCertificationController as WebRetirementFundCertification;
use Muserpol\Models\Affiliate;
use Muserpol\Models\RetirementFund\RetirementFund;
use Muserpol\Models\RetirementFund\RetFunCorrelative;
use Muserpol\Helpers\ID;
use Muserpol\Helpers\Util;

class RetirementFundCertificationController extends Controller
{
    // App móvil: el afiliado se obtiene del token (middleware api_auth).
    public function index(Request $request)
    {
        $ret_funs = $request->affiliate->retirement_funds()->get();

        return $this->toListResponse($ret_funs);
    }

    // Kiosko: no hay token de afiliado, así que se resuelve por el ci recibido.
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

        return $this->toListResponse($affiliate->retirement_funds()->get());
    }

    // Arma el listado; 'printable' indica si el trámite ya tiene liquidación generada (solo esos se imprimen).
    private function toListResponse($ret_funs)
    {
        $liquidationFrId = ID::wf_state()->liquidationFR;

        $data = $ret_funs->map(function (RetirementFund $ret_fun) use ($liquidationFrId) {
            $printable = RetFunCorrelative::where('retirement_fund_id', $ret_fun->id)
                ->where('wf_state_id', $liquidationFrId)
                ->exists();

            return [
                'id' => $ret_fun->id,
                'code' => $ret_fun->code,
                'reception_date' => $ret_fun->reception_date
                    ? Util::getStringDate($ret_fun->reception_date)
                    : null,
                'procedure_modality' => $ret_fun->procedure_modality->name ?? null,
                'procedure_type' => $ret_fun->procedure_modality->procedure_type->second_name ?? null,
                'printable' => $printable,
            ];
        });

        return [
            'error' => false,
            'message' => 'Listado de trámites de fondo de retiro',
            'data' => $data,
        ];
    }

    // PDF crudo (binario); endpoint v1 (hoy sin ruta expuesta). Valida que el trámite sea del afiliado del token.
    public function print(Request $request, RetirementFund $retirement_fund)
    {
        if ($retirement_fund->affiliate_id != $request->affiliate->id) {
            return response()->json([
                'error' => true,
                'message' => 'Este trámite no le pertenece',
                'data' => null,
            ], 403);
        }
        return $this->print_pdf($retirement_fund);
    }

    // PDF en JSON base64 para la app móvil; valida que el trámite sea del afiliado del token.
    public function print_v2(Request $request, RetirementFund $retirement_fund)
    {
        if ($retirement_fund->affiliate_id != $request->affiliate->id) {
            return response()->json([
                'error' => true,
                'message' => 'Este trámite no le pertenece',
                'data' => null,
            ], 403);
        }
        return $this->print_pdf_v2($retirement_fund);
    }

    // Kiosko: devuelve el mismo JSON base64 que la app móvil (antes PDF crudo) para unificar la impresión.
    // No valida pertenencia porque el kiosko no maneja token de afiliado; el acceso lo controla el HASH_TOKEN.
    public function printLiquidationKiosk(RetirementFund $retirement_fund)
    {
        return $this->print_pdf_v2($retirement_fund);
    }

    // PDF crudo (binario). Si no se pasa $single, usa el default false = 2 copias.
    private function print_pdf(RetirementFund $retirement_fund, $single = false)
    {
        $result = (new WebRetirementFundCertification)->buildLiquidationPdf($retirement_fund, $single);

        return response()->make($result['pdf'], 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $result['name'] . '"',
        ]);
    }

    // Genera 1 copia (kiosko/app móvil) y la codifica en base64 para viajar como JSON.
    private function print_pdf_v2(RetirementFund $retirement_fund)
    {
        $result = (new WebRetirementFundCertification)->buildLiquidationPdf($retirement_fund, true);

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
