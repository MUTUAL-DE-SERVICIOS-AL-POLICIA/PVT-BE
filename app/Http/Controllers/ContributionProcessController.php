<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Contribution\ContributionProcess;

use Muserpol\Models\Contribution\AidCommitment;
use Muserpol\Models\City;
use Yajra\Datatables\DataTables;
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
    }
    public function saveCommitment(Request $request)
    {
        $affiliate = Affiliate::find($request->affiliate_id);
        if ($request->procedure_modality_id == 18) {
            $aid_commitment = new AidCommitment();
            $aid_commitment->affiliate_id = $request->affiliate_id;
            $aid_commitment->user_id = Auth::user()->id;
            $aid_commitment->date_commitment = Util::verifyBarDate($request->commitment['date_commitment']) ? Util::parseBarDate($request->commitment['date_commitment']) : $request->commitment['date_commitment'];
            $aid_commitment->contributor = $request->commitment['contributorType'];
            $aid_commitment->pension_declaration = $request->commitment['pension_declaration'];
            $aid_commitment->pension_declaration_date = Util::verifyBarDate($request->commitment['pension_declaration_date']) ? Util::parseBarDate($request->commitment['pension_declaration_date']) : $request->commitment['pension_declaration_date'];
            $aid_commitment->start_contribution_date = Util::verifyBarDate($request->commitment['start_contribution_date']) ? Util::parseBarDate($request->commitment['start_contribution_date']) : $request->commitment['start_contribution_date'];
            $aid_commitment->state = "ALTA";
            $aid_commitment->save();
            return $aid_commitment;
        } elseif ($request->procedure_modality_id == 19) {
            $commitment = new ContributionCommitment();
            $commitment->affiliate_id = $request->affiliate_id;
            $commitment->commitment_type = $request->commitment['commitment_type'];
            $commitment->commitment_date = Util::verifyBarDate($request->commitment['commitment_date']) ? Util::parseBarDate($request->commitment['commitment_date']) : $request->commitment['commitment_date'];
            $commitment->number = $request->commitment['number'];
            $commitment->destination = $request->commitment['destination'];
            $commitment->commision_date = Util::verifyBarDate($request->commitment['commision_date']) ? Util::parseBarDate($request->commitment['commision_date']) : $request->commitment['commision_date'];
            $commitment->state = "ALTA";
            $commitment->save();
            switch ($commitment->commitment_type) {
                case 'COMISION':
                    $affiliate->affiliate_state_id = 2;
                    break;
                case 'BAJA TEMPORAL':
                    $affiliate->affiliate_state_id = 9;
                    break;
                case 'AGREGADO POLICIAL':
                    $affiliate->affiliate_state_id = 10;
                    break;
                default:
                    break;
            }
            $affiliate->save();
            return $commitment;
        } else {
            return "error";
        }
    }
}
