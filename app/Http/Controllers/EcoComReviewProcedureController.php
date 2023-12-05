<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Muserpol\Helpers\ID;
use Muserpol\Models\EconomicComplement\EcoComReviewProcedure;
use Muserpol\Models\EconomicComplement\ReviewProcedure;
use Muserpol\Models\Affiliate;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\User;

class EcoComReviewProcedureController extends Controller
{
    public function store(Request $request)
    {
        $economic_complement = EconomicComplement::find($request->economic_complement_id);
        $user = User::find($request->user_id);

        $review_procedures = $request->review_procedures;

        $exist = EcoComReviewProcedure::where('economic_complement_id', $request->economic_complement_id)->first();

        if ($economic_complement->eco_com_reception_type_id == 2 || $economic_complement->eco_com_reception_type_id == 3) {
            foreach ($review_procedures as $review) {
                if (!$exist) {
                    $submit = EcoComReviewProcedure::create(
                        [
                            'review_procedure_id' => $review['id'],
                            'economic_complement_id' => $economic_complement->id,
                            'user_id' => $user->id,
                            'is_valid' => $review['is_valid'],
                        ]
                    );
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => 'Cuenta con registro'
                    ], 403);
                }
            }
            return response()->json([
                'error' => false,
                'message' => 'Datos registrados'
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'No puede crear la certificación'
            ], 403);
        }
    }

    public function show($id)
    {
        // $this->authorize('read', new EcoComReviewProcedure());
        $economic_complement = EconomicComplement::findOrFail($id);

        $review_procedures = ReviewProcedure::select('review_procedures.*', 'eco_com_review_procedures.is_valid')
            ->join('eco_com_review_procedures', 'eco_com_review_procedures.review_procedure_id', '=', 'review_procedures.id')
            ->join('economic_complements', 'economic_complements.id', '=', 'eco_com_review_procedures.economic_complement_id')
            ->where('eco_com_review_procedures.economic_complement_id', $id)
            ->where('review_procedures.active', true)
            ->orderBy('review_procedures.id', 'asc')
            ->get();

        $has_certification = false;
        if (isset($economic_complement)) {
            $exist = EcoComReviewProcedure::where('economic_complement_id', $economic_complement->id)->first();
            if (isset($exist) && ($economic_complement->eco_com_reception_type_id == 2 || $economic_complement->eco_com_reception_type_id == 3)) {
                $has_certification = true;
            }
        }
        $data = [
            'has_certification' => $has_certification,
            'economic_complement' => $economic_complement,
            'review_procedures' => $review_procedures
        ];
        return response()->json(['data' => $data]);
    }
}
