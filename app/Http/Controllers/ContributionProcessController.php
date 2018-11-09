<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Contribution\ContributionProcess;
use Muserpol\Models\City;
use Yajra\Datatables\DataTables;
use DB;
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
}
