<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Contribution\ContributionProcess;
use Muserpol\Models\City;
use Yajra\Datatables\DataTables;
use DB;
use Auth;
use Muserpol\Models\Affiliate;
use Muserpol\Models\ProcedureType;
use Muserpol\Models\ProcedureModality;
use Muserpol\Models\ProcedureRequirement;
use Muserpol\Models\Spouse;
use Muserpol\Models\Kinship;
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
}
