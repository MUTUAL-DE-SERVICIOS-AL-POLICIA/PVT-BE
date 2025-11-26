<?php

namespace Muserpol\Http\Controllers;

use Muserpol\Models\BaseWage;
use Muserpol\Models\Hierarchy;
use Muserpol\Models\Degree;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Muserpol\Helpers\Util;
use Validator;
use Auth;

class BaseWageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('base_wages.index');
    }

    public function getGroupedWages()
    {
        $hierarchies = Hierarchy::with(['degrees' => function ($query) {
            $query->where('is_active', true)->orderBy('id', 'asc');
        }, 'degrees.base_wages' => function ($query) {
            $query->where('is_real_value', true)->orderBy('month_year', 'asc');
        }])
        ->whereHas('degrees.base_wages', function ($query) {
            $query->where('is_real_value', true);
        })
        ->orderBy('id', 'asc')->get();

        $transformedHierarchies = $hierarchies->map(function ($hierarchy) {
            $filteredDegrees = $hierarchy->degrees->filter(function ($degree) {
                return $degree->base_wages->isNotEmpty();
            });

            return [
                'id' => $hierarchy->id,
                'name' => $hierarchy->name,
                'degrees' => $filteredDegrees->map(function ($degree) {
                    return [
                        'id' => $degree->id,
                        'name' => $degree->name,
                        'shortened' => $degree->shortened,
                        'wages' => $degree->base_wages->mapWithKeys(function ($wage) {
                            return [$wage->month_year->year => [
                                'id' => $wage->id,
                                'amount' => Util::formatMoney($wage->amount),
                            ]];
                        })
                    ];
                })->values()
            ];
        });

        return response()->json($transformedHierarchies);
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
     * @param  \Muserpol\BaseWage  $baseWage
     * @return \Illuminate\Http\Response
     */
    public function show(BaseWage $baseWage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Muserpol\BaseWage  $baseWage
     * @return \Illuminate\Http\Response
     */
    public function edit(BaseWage $baseWage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Muserpol\BaseWage  $baseWage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BaseWage $baseWage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Muserpol\BaseWage  $baseWage
     * @return \Illuminate\Http\Response
     */
    public function destroy(BaseWage $baseWage)
    {
        //
    }

    public function base_wage_create(Request $request)
    {
        $rules = [
            'year' => 'required|numeric',
            'level' => 'required',
        ];
        $messages = [
            'year.required' => 'el campo año es requerido',
            'level.required' => 'el campo año es requerido',
        ];
        $level = $request->level;
        $year = $request->year;
        $base_wages = $request->except(['year', 'level', '_token']);
        $validator = Validator::make($request->all(), $rules, $messages);
        $date = Carbon::now();
        $month_year = Carbon::create($year,$date->month, $date->day,0,0,0)->toDateString();
        $message = false;
        $user = $user = Auth::User();
        if (!$validator->fails()) {
            switch($level){
                case 1:
                    $degrees = Degree::where('hierarchy_id',1)->where('is_active', true)->orWhere('hierarchy_id',2)->orderBy('id')->get();
                    $c = 0;
                    if($degrees->count() == count($base_wages)){
                        foreach($base_wages as $base_wage)
                        {
                            $base_wage_object = new BaseWage();
                            $base_wage_object->user_id = $user->id;
                            $base_wage_object->degree_id = $degrees[$c]->id;
                            $base_wage_object->month_year = $month_year;
                            $base_wage_object->amount = $base_wage;
                            $base_wage_object->save();
                            $c++;
                        }
                        $message = true;
                    }
                    break;
                case 2:
                    $degrees = Degree::where('hierarchy_id',3)->where('is_active', true)->orderBy('id')->get();
                    $c = 0;
                    if($degrees->count() == count($base_wages)){
                        foreach($base_wages as $base_wage)
                        {
                            $base_wage_object = new BaseWage();
                            $base_wage_object->user_id = $user->id;
                            $base_wage_object->degree_id = $degrees[$c]->id;
                            $base_wage_object->month_year = $month_year;
                            $base_wage_object->amount = $base_wage;
                            $base_wage_object->save();
                            $c++;
                        }
                        $message = true;
                    }
                    break;
                case 3:
                    $degrees = Degree::where('hierarchy_id',4)->where('is_active', true)->orderBy('id')->get();
                    $c = 0;
                    if($degrees->count() == count($base_wages)){
                        foreach($base_wages as $base_wage)
                        {
                            $base_wage_object = new BaseWage();
                            $base_wage_object->user_id = $user->id;
                            $base_wage_object->degree_id = $degrees[$c]->id;
                            $base_wage_object->month_year = $month_year;
                            $base_wage_object->amount = $base_wage;
                            $base_wage_object->save();
                            $c++;
                        }
                        $message = true;
                    }
                    break;
                case 4:
                    $degrees = Degree::where('hierarchy_id',5)->where('is_active', true)->orderBy('id')->get();
                    $c = 0;
                    if($degrees->count() == count($base_wages)){
                        foreach($base_wages as $base_wage)
                        {
                            $base_wage_object = new BaseWage();
                            $base_wage_object->user_id = $user->id;
                            $base_wage_object->degree_id = $degrees[$c]->id;
                            $base_wage_object->month_year = $month_year;
                            $base_wage_object->amount = $base_wage;
                            $base_wage_object->save();
                            $c++;
                        }
                        $message = true;
                    }
                    break;
                default:
                    $message = false;
                    break;
            }
            if($message)
                return redirect()->route('eco_com_qualification_parameters');
            else
                return response()->json($validator->errors(), 406);
        }
        else{
            return response()->json($validator->errors(), 406);
        }
    }
}