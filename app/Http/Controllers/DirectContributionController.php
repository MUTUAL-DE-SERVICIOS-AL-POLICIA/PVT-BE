<?php

namespace Muserpol\Http\Controllers;
use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Muserpol\Models\Contribution\DirectContribution;
use Yajra\Datatables\DataTables;
use Auth;
use DB;
use Log;
use Muserpol\Models\ProcedureType;
use Muserpol\Models\ProcedureRequirement;
use Muserpol\Models\ProcedureModality;
use Muserpol\Models\Spouse;
use Muserpol\Models\Kinship;
use Muserpol\Models\City;
use Muserpol\Helpers\Util;
use Muserpol\Models\ProcedureState;
use Muserpol\Models\Contribution\DirectContributionSubmittedDocument;
class DirectContributionController extends Controller
{
    public function getAllDirectContribution(DataTables $datatables)
    {
        $direct_contributions = DirectContribution::with([
            'affiliate:id,identity_card,city_identity_card_id,first_name,second_name,last_name,mothers_last_name,surname_husband,gender,degree_id,degree_id',
            'city:id,name,first_shortened',
            'procedure_modality:id,name,shortened,procedure_type_id',
        ])->select(
            'id',
            'code',
            'date',
            'affiliate_id',
            'city_id',
            'procedure_modality_id'
        )
            ->where('code', 'not like', '%A')
            ->orderByDesc(DB::raw("split_part(code, '/',1)::integer"));
        return $datatables->eloquent($direct_contributions)
            ->editColumn('affiliate.city_identity_card_id', function ($direct_contribution) {
                $city = City::find($direct_contribution->affiliate->city_identity_card_id);
                return $city ? $city->first_shortened : null;
            })
            ->addColumn('action', function ($direct_contribution) {
                return "<a href='/direct_contribution/" . $direct_contribution->id . "' class='btn btn-default'><i class='fa fa-eye'></i></a>";
            })
            ->make(true);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('direct_contributions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Affiliate $affiliate)
    {
        $this->authorize('create', DirectContribution::class);
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
        $modalities = ProcedureModality::whereIn('procedure_type_id', $procedure_types->pluck('id'))->select('id', 'name', 'procedure_type_id')->get();
        $kinships = Kinship::whereIn('id', [1,2])->get();
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

        return view('direct_contributions.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $direct_contribution = new DirectContribution();
        $direct_contribution->affiliate_id = $request->affiliate_id;
        $direct_contribution->user_id = Auth::user()->id;
        $direct_contribution->city_id = $request->city_id;
        $direct_contribution->procedure_modality_id = $request->procedure_modality_id;
        $direct_contribution->procedure_state_id = 1;
        $direct_contribution->contributor_type_id = $request->contributor_type_id;
        $direct_contribution->commitment_date = Util::verifyBarDate($request->commitment_date) ? Util::parseBarDate($request->commitment_date) : $request->commitment_date;
        $direct_contribution->document_number = $request->document_number;
        $direct_contribution->document_date = Util::verifyBarDate($request->document_date) ? Util::parseBarDate($request->document_date) : $request->document_date;
        $direct_contribution->start_contribution_date = Util::verifyBarDate($request->start_contribution_date) ? Util::parseBarDate($request->start_contribution_date) : $request->start_contribution_date;
        $direct_contribution->date = now();
        $direct_contribution->code = Util::getNextCode(Util::getLastCode(DirectContribution::class), "1");
        $direct_contribution->save();

        $requirements = ProcedureRequirement::select('id')->get();
        foreach ($requirements as $requirement) {
            if ($request->input('document' . $requirement->id) == 'checked') {
                $submit = new DirectContributionSubmittedDocument();
                $submit->direct_contribution_id = $direct_contribution->id;
                $submit->procedure_requirement_id = $requirement->id;
                $submit->reception_date = now();
                $submit->comment = $request->input('comment' . $requirement->id);
                $submit->save();
            }
        }

        $affiliate = $direct_contribution->affiliate;
        // save contributor
        if ($request->contributor_type_id == 1) { // affiliate
            $affiliate::updatePersonalInfo($affiliate->id, Util::parseRequest($request->all(), 'contributor'));
        } elseif ($request->contributor_type_id == 2) { // spouse
            $affiliate::updateSpouse($affiliate->id, Util::parseRequest($request->all(), 'contributor'));
        }
        return redirect()->route('direct_contribution.show',$direct_contribution->id );
    }

    /**
     * Display the specified resource.
     *
     * @param  \Muserpol\DirectContribution  $directContribution
     * @return \Illuminate\Http\Response
     */
    //public function show(DirectContribution $directContribution)
    public function show(DirectContribution $directContribution)
    {
        $affiliate = Affiliate::find($directContribution->affiliate_id);        
        $cities = City::get();
        $cities_pluck = $cities->pluck('first_shortened', 'id');
        $birth_cities = City::all()->pluck('name', 'id');
        $states = ProcedureState::get();
        $data = [
            'direct_contribution'   =>  $directContribution,
            'affiliate' =>  $affiliate,
            'cities'    =>  $cities,
            'cities_pluck'  =>  $cities_pluck,
            'birth_cities'  =>  $birth_cities,
            'is_editable'   =>  true,
            'states'    =>  $states,
        ];
        return view('direct_contributions.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Muserpol\DirectContribution  $directContribution
     * @return \Illuminate\Http\Response
     */
    public function edit(DirectContribution $directContribution)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Muserpol\DirectContribution  $directContribution
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DirectContribution $directContribution)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Muserpol\DirectContribution  $directContribution
     * @return \Illuminate\Http\Response
     */
    public function destroy(DirectContribution $directContribution)
    {
        //
    }

    /**
     * Edit basic information from direct contribution process
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Muserpol\DirectContribution  $directContribution
     */
    public function updateInformation(Request $request)
    {
        $direct_contribution = DirectContribution::find($request->id);
        // $this->authorize('update', $direct_contribution);
        $direct_contribution->city_id = $request->city_id;
        if($direct_contribution->ret_fun_state_id == ID::state()->eliminado){
            $direct_contribution->code.="A";
        }
        $direct_contribution->commitment_date = $request->commitment_date;
        $direct_contribution->document_number = $request->document_number;
        $direct_contribution->document_date = $request->document_date;
        $direct_contribution->start_contribution_date = $request->start_contribution_date;
        $direct_contribution->date = $request->date;                
        $direct_contribution->save();
        $data = [
            'retirement_fund' => $direct_contribution, 
            'procedure_modality'=>$direct_contribution->procedure_modality,
            'city_start'=>$direct_contribution->city_start,
            'city_end'=>$retirement_fund->city_end
        ];        
        return $data;
    }
}
