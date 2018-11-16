<?php

namespace Muserpol\Http\Controllers;
use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Muserpol\Models\Contribution\DirectContribution;
use Auth;
use Muserpol\Models\ProcedureType;
use Muserpol\Models\ProcedureRequirement;
use Muserpol\Models\ProcedureModality;
use Muserpol\Models\Spouse;
use Muserpol\Models\Kinship;
use Muserpol\Models\City;
class DirectContributionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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
        return 12342;
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
}
