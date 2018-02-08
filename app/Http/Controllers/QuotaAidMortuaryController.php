<?php

namespace Muserpol\Http\Controllers;
use Auth;
use Validator;
use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary;
use Muserpol\Models\ProcedureModality;

class QuotaAidMortuaryController extends Controller
{
    
    public function index()
    {
        return View("quota_aid.index");
    }
    public function getAllQuotaAid(Request $request)
    {
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'desc';          
        $last_name = strtoupper($request->last_name) ?? '';
        $first_name = strtoupper($request->first_name) ?? '';
        $code = $request->code ?? '';
        $modality = strtoupper($request->modality) ?? '';
        

        $total = QuotaAidMortuary::select('quota_aid_mortuaries.id')
                                ->leftJoin('affiliates','quota_aid_mortuaries.id','=','affiliates.id')
                                ->leftJoin('procedure_modalities','quota_aid_mortuaries.procedure_modality_id','=','procedure_modalities.id')
                                ->leftJoin('workflows','quota_aid_mortuaries.workflow_id','=','workflows.id')                               
                                ->where('quota_aid_mortuaries.code','LIKE',$code.'%')                                
                                ->where('affiliates.first_name','LIKE',$first_name.'%')
                                ->where('affiliates.last_name','LIKE',$last_name.'%')                             
                                ->count();
        //dd($total);
         $quota_aid_mortuaries = QuotaAidMortuary::select('quota_aid_mortuaries.id','affiliates.first_name as first_name','affiliates.last_name as last_name','procedure_modalities.name as modality','workflows.name as workflow','quota_aid_mortuaries.code','quota_aid_mortuaries.reception_date','quota_aid_mortuaries.total')
                                ->leftJoin('affiliates','quota_aid_mortuaries.id','=','affiliates.id')
                                ->leftJoin('procedure_modalities','quota_aid_mortuaries.procedure_modality_id','=','procedure_modalities.id')
                                ->leftJoin('workflows','quota_aid_mortuaries.workflow_id','=','workflows.id')                               
                                ->where('quota_aid_mortuaries.code','LIKE',$code.'%')                                
                                ->where('affiliates.first_name','LIKE',$first_name.'%')
                                ->where('affiliates.last_name','LIKE',$last_name.'%')                                
                                ->skip($offset)
                                ->take($limit)
                                ->orderBy($sort,$order)
                                ->get();             
       // dd($quota_aid_mortuaries);
        return response()->json(['quota_aid_mortuaries' => $quota_aid_mortuaries->toArray(),'total'=>$total]);
    }

   
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

   
    public function show($id)
    {
        //
    }

  
    public function edit($id)
    {
        //
    }

   
    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
