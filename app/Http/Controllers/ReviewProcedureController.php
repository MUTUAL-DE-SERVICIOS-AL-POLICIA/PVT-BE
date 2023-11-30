<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\EconomicComplement\ReviewProcedure;

class ReviewProcedureController extends Controller
{
    /**
     * Display a listing of Review Procedures.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ReviewProcedure::orderBy('id', 'asc')->get();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ReviewProcedure::findOrFail($id);
    }
}
