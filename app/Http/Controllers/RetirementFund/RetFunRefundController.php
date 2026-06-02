<?php

namespace Muserpol\Http\Controllers\RetirementFund;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Muserpol\Http\Controllers\Controller;
use Muserpol\Models\RetirementFund\RetFunRefundAmount;

class RetFunRefundController extends Controller
{
    public function storeAmounts(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'beneficiaries' => 'required|array|min:1',
                'beneficiaries.*.id' => 'required|integer|exists:ret_fun_beneficiaries,id',
                'beneficiaries.*.percentage' => 'required|numeric|min:0',
                'beneficiaries.*.amount' => 'required|numeric|min:0',
            ]);

            DB::beginTransaction();

            foreach ($validated['beneficiaries'] as $beneficiary) {
                RetFunRefundAmount::updateOrCreate(
                    [
                        'ret_fun_beneficiary_id' => $beneficiary['id'],
                        'ret_fun_refund_id' => $id
                    ],
                    [
                        'percentage' => $beneficiary['percentage'],
                        'amount' => $beneficiary['amount']
                    ]
                );
            }

            DB::commit();
            return response()->json(['message' => 'Montos guardados correctamente'], 200);
        } catch (ValidationException $ve) {
            logger()->warning('Validación fallida al guardar montos en RetFunRefund', [
                'refund_id' => $id,
                'input' => $request->all(),
                'errors' => $ve->errors(),
            ]);

            return response()->json([
                'error' => 'Error de validación',
                'details' => $ve->errors()
            ], 422);
        } catch (\Throwable $e) {
            DB::rollBack();
            logger()->error('Error al guardar montos en RetFunRefund', [
                'refund_id' => $id,
                'input' => $request->all(),
                'error_message' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Error al guardar montos', 'details' => $e->getMessage()], 500);
        }
    }
}
