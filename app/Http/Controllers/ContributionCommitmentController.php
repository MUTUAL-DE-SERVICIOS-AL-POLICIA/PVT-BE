<?php
namespace Muserpol\Http\Controllers;
use Muserpol\Models\Contribution\ContributionCommitment;
use Muserpol\Models\Affiliate;
use Illuminate\Http\Request;
use Validator;
use Carbon\Carbon;
use Muserpol\Helpers\ID;
class ContributionCommitmentController extends Controller
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
    public function create()
    {
        //
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
     * @param  \Muserpol\ContributionCommitment  $contributtionCommitment
     * @return \Illuminate\Http\Response
     */
    public function show(ContributionCommitment $contributtionCommitment)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Muserpol\ContributionCommitment  $contributtionCommitment
     * @return \Illuminate\Http\Response
     */
    public function edit(ContributionCommitment $contributtionCommitment)
    {
        //
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Muserpol\ContributionCommitment  $contributionCommitment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       //*********START VALIDATOR************//

       $date_commision = $request->commision_date;
       $limit=Carbon::now()->subDays(121);
       $rules = [
          'number' => 'required',
          //'commision_date' => 'required|date|date_format:Y-m-d|after:'.$limit,
          'destination' => 'required',
          'commitment_type' => 'required'
          ];
     //     return $rules;
        $messages = [
          'number.required' => __('validation.memorandum'),
          'commision_date.after' => __('validation.limit_days'),
      ];
      $validator = Validator::make($request->all(),$rules,$messages);
      if($validator->fails()){
          return response()->json($validator->errors(), 406);
      }
       //*********END VALIDATOR************//

        if($id == -1){
            $commitment = ContributionCommitment::find($request->id);
            $this->authorize('update', $commitment);
            $commitment->state = 'BAJA';
            $commitment->save();
            $commitment->delete();
            return $commitment;
        }

        if($request->id==0){
            $commitment = new ContributionCommitment();
            $this->authorize('update', $commitment);
            $commitment->affiliate_id = $request->affiliate_id;
        }
        else
            $commitment = ContributionCommitment::find($request->id);
            $this->authorize('update', $commitment);
        $commitment->commitment_type = $request->commitment_type;
        $commitment->commitment_date = $request->commitment_date;
        $commitment->number = $request->number;
        $commitment->destination = $request->destination;
        $commitment->commision_date = $request->commision_date;
        $commitment->state = "ALTA";

        $commitment->save();
        ///'COMISION', 'BAJA TEMPORAL','AGREGADO POLICIAL'
        $affiliate = Affiliate::find($commitment->affiliate_id);
        if($commitment->commitment_type == 'COMISION')
            $affiliate->affiliate_state_id = ID::affiliateState()->comision;
        if($commitment->commitment_type == 'BAJA TEMPORAL')
            $affiliate->affiliate_state_id = ID::affiliateState()->baja_temporal;
        if($commitment->commitment_type == 'AGREGADO POLICIAL')
            $affiliate->affiliate_state_id = ID::affiliateState()->agregado_policial;
        $affiliate->save();
        return $commitment;
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \Muserpol\ContributionCommitment  $contributtionCommitment
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContributionCommitment $contributtionCommitment)
    {
        //
    }
}
