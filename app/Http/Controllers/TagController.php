<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Helpers\Util;
use Muserpol\Models\Workflow\WorkflowState;
use Muserpol\Models\RetirementFund\RetirementFund;
use Log;
use Muserpol\Models\Tag;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;
use Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary;
use Muserpol\Models\Module;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Models\Affiliate;

class TagController extends Controller
{
    public function wfState()
    {
        $wf_current_state = WorkflowState::where('role_id', Util::getRol()->id)->first();
        return $wf_current_state->tags;
    }
    public function module()
    {
        $module = Module::find(Util::getRol()->module_id);
        return $module->tags;
    }
    public function retFun($ret_fun_id)
    {
        return RetirementFund::find($ret_fun_id)->tags;
    }
    public function quotaAid($quota_aid_id)
    {
        return QuotaAidMortuary::find($quota_aid_id)->tags;
    }
    public function ecoCom($eco_com_id)
    {
        return EconomicComplement::find($eco_com_id)->tags;
    }
    public function affiliate($affiliate_id)
    {
        return Affiliate::find($affiliate_id)->tags;
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
                    $retirement_fund->tags()->save($tag_wf_state, ['date'=>Carbon::now(), 'user_id'=>Util::getAuthUser()->id]);
                }
            }else{
                if ($retirement_fund->tags->contains($tag_wf_state->id)) {
                    $retirement_fund->tags()->detach($tag_wf_state->id);
                }
            }
        }
        return $retirement_fund->tags;
    }
    public function updateQuotaAid(Request $request, $quota_aid_id)
    {

        $quota_aid = QuotaAidMortuary::find($quota_aid_id);
        $tags_wf_state = WorkflowState::where('role_id', Util::getRol()->id)->first()->tags;
        foreach ($tags_wf_state as $tag_wf_state) {
            $found = array_filter($request->ids, function ($id) use ($tag_wf_state) {
                return $id == $tag_wf_state['id'];
            });
            if ($found) {
                if ($quota_aid->tags->contains($tag_wf_state->id)) {
                    // $quota_aid->tags()->updateExistingPivot($tag_wf_state->id);
                }else{
                    $quota_aid->tags()->save($tag_wf_state, ['date'=>Carbon::now(), 'user_id'=>Util::getAuthUser()->id]);
                }
            }else{
                if ($quota_aid->tags->contains($tag_wf_state->id)) {
                    $quota_aid->tags()->detach($tag_wf_state->id);
                }
            }
        }
        return $quota_aid->tags;
    }
    public function updateEcoCom(Request $request, $eco_com_id)
    {

        $eco_com = EconomicComplement::find($eco_com_id);
        $tags_wf_state = WorkflowState::where('role_id', Util::getRol()->id)->first()->tags;
        foreach ($tags_wf_state as $tag_wf_state) {
            $found = array_filter($request->ids, function ($id) use ($tag_wf_state) {
                return $id == $tag_wf_state['id'];
            });
            if ($found) {
                if ($eco_com->tags->contains($tag_wf_state->id)) {
                    // $eco_com->tags()->updateExistingPivot($tag_wf_state->id);
                }else{
                    $eco_com->tags()->save($tag_wf_state, ['date'=>Carbon::now(), 'user_id'=>Util::getAuthUser()->id]);
                }
            }else{
                if ($eco_com->tags->contains($tag_wf_state->id)) {
                    $eco_com->tags()->detach($tag_wf_state->id);
                }
            }
        }
        return $eco_com->tags;
    }
    public function updateAffiliate(Request $request, $affiliate_id)
    {

        $affiliate = Affiliate::find($affiliate_id);
        $tags_module = Module::find(Util::getRol()->module_id)->tags;
        foreach ($tags_module as $tag_module) {
            $found = array_filter($request->ids, function ($id) use ($tag_module) {
                return $id == $tag_module['id'];
            });
            if ($found) {
                if ($affiliate->tags->contains($tag_module->id)) {
                    // $affiliate->tags()->updateExistingPivot($tag_module->id);
                }else{
                    $affiliate->tags()->save($tag_module, ['date'=>Carbon::now(), 'user_id'=>Util::getAuthUser()->id]);
                }
            }else{
                if ($affiliate->tags->contains($tag_module->id)) {
                    $affiliate->tags()->detach($tag_module->id);
                }
            }
        }
        return $affiliate->tags;
    }
    public function getTags()
    {
        $tags = Tag::all();
        return Datatables::of($tags)
            ->addColumn('action', function ($t) {
                return '<a href="/tag/' . $t->id . '/edit" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i>Edit</a>';
            })->rawColumns(['action'])
            ->make(true);
    }
    public function getTag($id)
    {
        $tag = Tag::find($id);
        if ($tag) {
            return $tag;
        }
        return null;
    }
    public function getTagWfState(Request $request)
    {
        $wf_state = WorkflowState::find($request->id);
        return $wf_state->tags;
    }
    public function tagWfState()
    {
        $modules = Module::all();
        $wf_states = WorkflowState::all();
        // $wf_states = WorkflowState::where('module_id', Util::getRol()->module_id)->get();
        $tags = Tag::all();
        $data =[
            'tags'=> $tags,
            'modules'=> $modules,
            'wf_states'=> $wf_states,
        ];
        return view('tags.wf_state',$data);
    }
    public function updateTagWfState(Request $request)
    {
        $wf_state = WorkflowState::find($request->wf_state_id);
        if ($wf_state) {

            $ids = $request->ids;
            $pivotData = array_fill(0, count($ids), ['date' => Carbon::now(), 'user_id' => Util::getAuthUser()->id]);
            $syncData = array_combine($ids, $pivotData);

            $wf_state->tags()->sync($syncData);
            return response('updated tag wf state',202);
        }
        abort(500,'WorkflowState not found.');
    }
    public function index()
    {
        return view('tags.index');
    }
    public function create()
    {
        // $wf_states = WorkflowState::where('module_id', Util::getRol()->module_id)->get();
        $modules = Module::all();
        $wf_states = WorkflowState::all();
        $data = [
            'modules'=> $modules,
            'wf_states'=> $wf_states,
        ];
        return view('tags.create',$data);
    }
    public function store(Request $request)
    {
        $found = Tag::where('name', $request->name)->where('shortened', $request->shortened)->first();
        if (! $found) {
            $t = new Tag();
            $t->name = $request->name;
            $t->shortened = $request->shortened;
            $t->slug = str_slug($request->name, '-');
            $t->save();
            if ($request->wf_state_id) {
                $wf_state = WorkflowState::find($request->wf_state_id);
                if ($wf_state) {
                    $wf_state->tags()->save($t, ['date' => Carbon::now(), 'user_id' => Util::getAuthUser()->id]);
                }
            }
            return response('Created tag',201);
        }
        abort(500, 'error al guardar.');
    }
    public function edit(Request $request, Tag $tag)
    {
        $tags = Tag::all();
        $data = [
            'tags'=>$tags,
            'tag'=>$tag,
        ];
        return view('tags.edit',$data);
    }
    public function update(Request $request, Tag $tag)
    {
        $found = Tag::where('name', $request->name)->where('shortened', $request->shortened)->first();
        if ($found) {
            abort(500,'tag duplicated');
        }
        $t = Tag::find($request->id);
        if ($t) {
            $t->name = $request->name;
            $t->shortened = $request->shortened;
            $t->slug = str_slug($request->name, '-');
            $t->save();
            return response("updated",202);
        }
        abort(500,'tag not found');
    }
}
