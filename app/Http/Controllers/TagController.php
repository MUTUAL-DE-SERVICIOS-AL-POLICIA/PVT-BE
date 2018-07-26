<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Helpers\Util;
use Muserpol\Models\Workflow\WorkflowState;
use Muserpol\Models\RetirementFund\RetirementFund;
use Log;
use Muserpol\Models\Tag;
class TagController extends Controller
{
    public function wfState()
    {
        $wf_current_state = WorkflowState::where('role_id', Util::getRol()->id)->first();
        return $wf_current_state->tags;
    }
    public function retFun($ret_fun_id)
    {
        return RetirementFund::find($ret_fun_id)->tags;
    }
    public function updateRetFun(Request $request, $ret_fun_id)
    {

        $retirement_fund = RetirementFund::find($ret_fun_id);
        $tags_wf_state = WorkflowState::where('role_id', Util::getRol()->id)->first()->tags;
        foreach ($tags_wf_state as $tag_wf_state) {
            $found = array_filter($request->ids, function ($id) use ($tag_wf_state) {
                return $id == $tag_wf_state['id'];
            });
            if ($found) {
                if ($retirement_fund->tags->contains($tag_wf_state->id)) {
                    // $retirement_fund->tags()->updateExistingPivot($tag_wf_state->id);
                }else{
                    $retirement_fund->tags()->attach($tag_wf_state);
                }
            }else{
                if ($retirement_fund->tags->contains($tag_wf_state->id)) {
                    $retirement_fund->tags()->detach($tag_wf_state->id);
                }
            }
        }
        return $retirement_fund->tags;
    }
}
