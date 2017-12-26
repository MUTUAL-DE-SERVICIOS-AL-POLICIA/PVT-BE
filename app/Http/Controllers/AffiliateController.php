<?php

namespace Muserpol\Http\Controllers;

use Muserpol\Models\Affiliate;
use Muserpol\Models\AffiliateState;
use Muserpol\Models\Category;
use Muserpol\Models\City;
use Muserpol\Models\Degree;
use Muserpol\Models\PensionEntity;

use Muserpol\Models\Contribution\Contribution;
use Illuminate\Http\Request;
use Log;
use Yajra\Datatables\Datatables;

class AffiliateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('affiliates.index');
    }
    public function getAllAffiliates(Request $request)
    {
        /*$query = Affiliate::take(100)->get();

        return $datatables->collection($query)
                          // ->addColumn('action', 'eloquent.tables.users-action')
                          ->make(true);*/
        $offset = $request->offset ?? 0;
        $limit = $request->limit ?? 10;
        $sort = $request->sort ?? 'id';
        $order = $request->order ?? 'asc';
        $last_name = $request->last_name ?? '';
        $affiliates = Contribution::skip($offset)->take($limit)->orderBy($sort,$order)->get();
        $total=6669783;
        // $total=Affiliate::all()->count();
        return response()->json(['affiliates' => $affiliates->toArray(),'total'=>$total]);
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
     * @param  \Muserpol\Affiliate  $affiliate
     * @return \Illuminate\Http\Response
     */
    public function show(Affiliate $affiliate)
    {
        $cities = City::all()->pluck('first_shortened', 'id');
        $categories = Category::all()->pluck('name', 'id');
        $degrees = Degree::all()->pluck('name', 'id');
        $pension_entities = PensionEntity::all()->pluck('name', 'id');
        $affiliate_states = AffiliateState::all()->pluck('name', 'id');
        $affiliate->load([
            'city_identity_card:id,first_shortened',
            'city_birth:id,name',
            'affiliate_state',
            'pension_entity',
            'category',
            'degree',
        ]);
        return view('affiliates.show',compact('affiliate','affiliate_states', 'cities', 'categories', 'degrees','degrees_all', 'pension_entities'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Muserpol\Affiliate  $affiliate
     * @return \Illuminate\Http\Response
     */
    public function edit(Affiliate $affiliate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Muserpol\Affiliate  $affiliate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Affiliate $affiliate)
    {
        $affiliate = Affiliate::where('id','=', $affiliate->id)->first();
        $affiliate->identity_card = $request->identity_card;
        $affiliate->first_name = $request->first_name;
        $affiliate->second_name = $request->second_name;
        $affiliate->last_name = $request->last_name;
        $affiliate->mothers_last_name = $request->mothers_last_name;
        $affiliate->gender = $request->gender;
        $affiliate->civil_status = $request->civil_status;
        $affiliate->birth_date = $request->birth_date;
        $affiliate->save();

    }
    public function update_affiliate_police(Request $request, Affiliate $affiliate)
    {
        $affiliate = Affiliate::where('id','=', $affiliate->id)->first();
        $affiliate->affiliate_state_id = $request->affiliate_state;
        $affiliate->type = $request->affiliate_type;
        $affiliate->date_entry = $request->affiliate_date_entry;
        $affiliate->item = $request->affiliate_item;
        $affiliate->category_id = $request->affiliate_category;
        $affiliate->degree_id = $request->affiliate_degree;
        $affiliate->pension_entity_id = $request->affiliate_pension_entity;
        $affiliate->save();



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Muserpol\Affiliate  $affiliate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Affiliate $affiliate)
    {
        //
    }
}
