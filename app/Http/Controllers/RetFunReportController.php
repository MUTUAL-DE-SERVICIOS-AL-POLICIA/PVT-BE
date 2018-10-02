<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Workflow\WorkflowState;
use Muserpol\Models\City;

class RetFunReportController extends Controller
{
    public function index()
    {
        $wf_states = WorkflowState::where('module_id',3)->get();
        $cities = City::where('id', '<>', 10)->get();
        $data = [
            'wf_states' => $wf_states,
            'cities' => $cities,
        ];
        return view('ret_fun.report.index', $data);
    }
}
