<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Contribution\ContributionProcess;

use Muserpol\Models\Contribution\AidCommitment;
use Muserpol\Models\City;
use Yajra\Datatables\DataTables;
use Validator;
use DB;
use Auth;
use Log;
use Muserpol\Models\Affiliate;
use Muserpol\Models\ProcedureType;
use Muserpol\Models\ProcedureModality;
use Muserpol\Models\ProcedureRequirement;
use Muserpol\Models\Spouse;
use Muserpol\Models\Kinship;
use Muserpol\Helpers\Util;
use Muserpol\Models\Contribution\ContributionCommitment;
use Muserpol\Models\Workflow\WorkflowState;
use Carbon\Carbon;
use Muserpol\Models\Contribution\AidContribution;
class ContributionProcessController extends Controller
{
    public function index()
    {
        //
    }
    public function show(ContributionProcess $contribution_process)
    {
        $affiliate = $contribution_process->affiliate;
        $cities = City::all();
        $data = [
            'affiliate' => $affiliate,
            'cities' => $cities,
            'contribution_process' => $contribution_process,
        ];
        return view('contribution_processes.show', $data);
    }
    public function create(Affiliate $affiliate)
    {
    }
    public function store(Request $request)
    {
        $contribution_process = new ContributionProcess();
        $contribution_process->user_id = Auth::user()->id;
        $wf_state = WorkflowState::where('role_id', Util::getRol()->id)->whereIn('sequence_number', [0, 1])->first();
        if (!$wf_state) {
            Log::info("error al crear el tramite");
            return;
        }
        $contribution_process->wf_state_current_id = $wf_state->id;
        $contribution_process->workflow_id = 7;
        $contribution_process->procedure_state_id = 1;
        $contribution_process->direct_contribution_id = $request->direct_contribution_id;
        $contribution_process->date = Carbon::now()->toDateString();
        $contribution_process->code = Util::getNextCode(Util::getLastCode(ContributionProcess::class), '1');
        $contribution_process->inbox_state = false;
        $contribution_process->save();
        return $contribution_process;
    }
    // public function saveCommitment(Request $request)
    // {
    //     $affiliate = Affiliate::find($request->affiliate_id);
    //     if ($request->procedure_modality_id == 18) {
    //         $aid_commitment = new AidCommitment();
    //         $aid_commitment->affiliate_id = $request->affiliate_id;
    //         $aid_commitment->user_id = Auth::user()->id;
    //         $aid_commitment->date_commitment = Util::verifyBarDate($request->commitment['date_commitment']) ? Util::parseBarDate($request->commitment['date_commitment']) : $request->commitment['date_commitment'];
    //         $aid_commitment->contributor = $request->commitment['contributorType'];
    //         $aid_commitment->pension_declaration = $request->commitment['pension_declaration'];
    //         $aid_commitment->pension_declaration_date = Util::verifyBarDate($request->commitment['pension_declaration_date']) ? Util::parseBarDate($request->commitment['pension_declaration_date']) : $request->commitment['pension_declaration_date'];
    //         $aid_commitment->start_contribution_date = Util::verifyBarDate($request->commitment['start_contribution_date']) ? Util::parseBarDate($request->commitment['start_contribution_date']) : $request->commitment['start_contribution_date'];
    //         $aid_commitment->state = "ALTA";
    //         $aid_commitment->save();
    //         return $aid_commitment;
    //     } elseif ($request->procedure_modality_id == 19) {
    //         $commitment = new ContributionCommitment();
    //         $commitment->affiliate_id = $request->affiliate_id;
    //         $commitment->commitment_type = $request->commitment['commitment_type'];
    //         $commitment->commitment_date = Util::verifyBarDate($request->commitment['commitment_date']) ? Util::parseBarDate($request->commitment['commitment_date']) : $request->commitment['commitment_date'];
    //         $commitment->number = $request->commitment['number'];
    //         $commitment->destination = $request->commitment['destination'];
    //         $commitment->commision_date = Util::verifyBarDate($request->commitment['commision_date']) ? Util::parseBarDate($request->commitment['commision_date']) : $request->commitment['commision_date'];
    //         $commitment->state = "ALTA";
    //         $commitment->save();
    //         switch ($commitment->commitment_type) {
    //             case 'COMISION':
    //                 $affiliate->affiliate_state_id = 2;
    //                 break;
    //             case 'BAJA TEMPORAL':
    //                 $affiliate->affiliate_state_id = 9;
    //                 break;
    //             case 'AGREGADO POLICIAL':
    //                 $affiliate->affiliate_state_id = 10;
    //                 break;
    //             default:
    //                 break;
    //         }
    //         $affiliate->save();
    //         return $commitment;
    //     } else {
    //         return "error";
    //     }
    // }
    public function aidContributionSave(Request $request)
    {
        $contribution_process = $this->store($request);
        $commitment = $contribution_process->direct_contribution;
        $affiliate = $commitment->affiliate;

        //*********START VALIDATOR************//
        $rules = [];
        $biz_rules = [];
        // ->where('state', 'ALTA')->first();

        if (!isset($commitment->id)) {
            $biz_rules = [
                'has_commitment' => 'required',
            ];
            $validator = Validator::make($request->all(), $biz_rules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 406);
            }
        }


        $key = 0;
        foreach ($request->aportes as $ap) {
            $aporte = (object)$ap;
            $cont = AidContribution::where('affiliate_id', $affiliate->id)->where('month_year', $aporte->year . '-' . $aporte->month . '-01')->first();
            $has_contribution = false;
            if (isset($cont->id)) {
                $has_contribution = true;
            }
            $biz_rules = [
                'has_contribution.' . $key => $has_contribution ? 'required' : '',
            ];
            $rules = array_merge($rules, $biz_rules);
            $array_rules = [
                'aportes.' . $key . '.sueldo' => 'required|numeric|min:0',
                'aportes.' . $key . '.dignity_rent' => 'min:0',
                'aportes.' . $key . '.subtotal' => 'required|numeric|min:0',
                'aportes.' . $key . '.interes' => 'required|numeric',
                'aportes.' . $key . '.year' => 'required|numeric|min:1700',
                'aportes.' . $key . '.month' => 'required|numeric|min:1|max:12',
            ];
            $key++;
            $rules = array_merge($rules, $array_rules);
        }
        $rules = array_merge($rules, $biz_rules);
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 406);
        }
         //*********END VALIDATOR************//
        $result = [];
        $ids = [];
        foreach ($request->aportes as $ap)  // guardar 1 a 3 reg en contribuciones
        {
            $aporte = (object)$ap;

            if ($aporte->sueldo > 0) {
                $aid_contribution = new AidContribution();
                $aid_contribution->user_id = Auth::user()->id;
                $aid_contribution->affiliate_id = $affiliate->id;
                $aid_contribution->month_year = $aporte->year . '-' . $aporte->month . '-01';
                $aid_contribution->type = 'DIRECTO';
                if (is_numeric($aporte->dignity_rent)) {
                    $aid_contribution->dignity_rent = $aporte->dignity_rent;
                    $aid_contribution->quotable = $aporte->sueldo - $aporte->dignity_rent;
                } else {
                    $aid_contribution->dignity_rent = 0;
                    $aid_contribution->quotable = $aporte->sueldo;
                }
                $aid_contribution->rent = $aporte->sueldo;
                $aid_contribution->mortuary_aid = $aporte->mortuary_aid;
                $aid_contribution->total = $aporte->subtotal;
                $aid_contribution->interest = $aporte->interes;
                $aid_contribution->save();
                // array_push($result, [
                //     'total' => $aid_contribution->total,
                //     'month_year' => $aporte->year . '-' . $aporte->month . '-01',
                // ]);
                array_push($ids, $aid_contribution->id);
            }
        }

        // $voucher_code = Voucher::select('id', 'code')->orderby('id', 'desc')->first();
        // if (!isset($voucher_code->id)){
        //     $code = Util::getNextCode("");
        // }else{
        //     $code = Util::getNextCode($voucher_code->code);
        // }
        // $voucher = new Voucher();
        // $voucher->user_id = Auth::user()->id;
        // $voucher->affiliate_id = $request->afid;
        // $voucher->voucher_type_id = 1;//$request->tipo; 1 default as Pago de aporte directo
        // $voucher->total = $request->total;
        // $voucher->payment_date = Carbon::now();
        // $voucher->code = $code;
        // $voucher->save();


        $data = [
            // 'aid_contribution' => $result,
            // 'aid_contributions' => $ids,
            // 'voucher_id' => $voucher->id,s
            // 'affiliate_id' => $affiliate->id,
        ];
        $contribution_process->aid_contributions()->attach($ids);
        $data = [
            'contribution_process' => $contribution_process,
            'aid_contributions' => $contribution_process->aid_contributions,
        ];
        return $data;
    }
    public function contributionSave(Request $request)
    {
        $contribution_process = $this->store($request);
        Log::info($request->all());
    }
}
