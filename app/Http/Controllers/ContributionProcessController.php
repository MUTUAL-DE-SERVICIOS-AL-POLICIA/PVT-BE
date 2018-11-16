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
    public function getAllContributionProcess(DataTables $datatables)
    {
        $contribution_processes = ContributionProcess::with([
            'affiliate:id,identity_card,city_identity_card_id,first_name,second_name,last_name,mothers_last_name,surname_husband,gender,degree_id,degree_id',
            'city:id,name,first_shortened',
            'wf_state:id,name,first_shortened',
            'procedure_modality:id,name,shortened,procedure_type_id',
            'workflow:id,name',
        ])->select(
            'id',
            'code',
            'date',
            'affiliate_id',
            'city_id',
            'inbox_state',
            'wf_state_current_id',
            'procedure_modality_id',
            'workflow_id'
        )
            ->where('code', 'not like', '%A')
            ->orderByDesc(DB::raw("split_part(code, '/',1)::integer"));
        return $datatables->eloquent($contribution_processes)
            ->editColumn('inbox_state', function ($contribution_process) {
                return $contribution_process->inbox_state ? 'Validado' : 'Pendiente';
            })
            ->editColumn('affiliate.city_identity_card_id', function ($contribution_process) {
                $city = City::find($contribution_process->affiliate->city_identity_card_id);
                return $city ? $city->first_shortened : null;
            })
            ->addColumn('action', function ($contribution_process) {
                return "<a href='/contribution_process/" . $contribution_process->id . "' class='btn btn-default'><i class='fa fa-eye'></i></a>";
            })
            ->make(true);
    }
    public function index()
    {
        return view('contribution_processes.index');
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
        $this->authorize('create', ContributionProcess::class);
        $user = Auth::User();
        $affiliate = Affiliate::select('affiliates.id', 'identity_card', 'city_identity_card_id', 'registration', 'first_name', 'second_name', 'last_name', 'mothers_last_name', 'surname_husband', 'birth_date', 'gender', 'degrees.name as degree', 'civil_status', 'affiliate_states.name as affiliate_state', 'phone_number', 'cell_phone_number', 'date_derelict', 'date_death', 'reason_death')
            ->leftJoin('degrees', 'affiliates.id', '=', 'degrees.id')
            ->leftJoin('affiliate_states', 'affiliates.affiliate_state_id', '=', 'affiliate_states.id')
            ->find($affiliate->id);

        $procedure_types = ProcedureType::where('module_id', 11)->get();
        $procedure_requirements = ProcedureRequirement::select('procedure_requirements.id', 'procedure_documents.name as document', 'number', 'procedure_modality_id as modality_id')
            ->leftJoin('procedure_documents', 'procedure_requirements.procedure_document_id', '=', 'procedure_documents.id')
            ->orderBy('procedure_requirements.procedure_modality_id', 'ASC')
            ->orderBy('procedure_requirements.number', 'ASC')
            ->get();
        $spouse = Spouse::where('affiliate_id', $affiliate->id)->first();
        if (!isset($spouse->id)) {
            $spouse = new Spouse();
        }
        $modalities = ProcedureModality::where('procedure_type_id', '=', 6)->select('id', 'name', 'procedure_type_id')->get();
        $kinships = Kinship::get();
        $cities = City::get();
        $searcher = new SearcherController();

        $data = [
            'user' => $user,
            'requirements' => $procedure_requirements,
            'procedure_types' => $procedure_types,
            'modalities' => $modalities,
            'affiliate' => $affiliate,
            'kinships' => $kinships,
            'cities' => $cities,
            'spouse' => $spouse,
            'searcher' => $searcher,
        ];

        return view('contribution_processes.create', $data);
    }
    public function store(Request $request)
    {
        $contribution_process = new ContributionProcess();
        $contribution_process->affiliate_id = $request->affiliate_id;
        $contribution_process->user_id = Auth::user()->id;
        $contribution_process->city_id = $request->city_id;
        $contribution_process->wf_state_current_id = 52;
        $contribution_process->workflow_id = 7;
        $contribution_process->procedure_modality_id = $request->procedure_modality_id;
        $contribution_process->date = now();
        $contribution_process->code = Util::getNextCode(Util::getLastCode(ContributionProcess::class), '1');
        $contribution_process->inbox_state = false;
        $contribution_process->type = $request->contribution_process_type;
        $contribution_process->save();
        return redirect()->route('contribution_process.show',$contribution_process->id );
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
