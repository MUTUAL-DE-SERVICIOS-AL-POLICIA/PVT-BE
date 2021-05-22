<?php

namespace Muserpol\Http\Controllers\API;

use Illuminate\Http\Request;
use Muserpol\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class PolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $path = 'policy';
        $files = collect(File::allFiles(Storage::path($path)))->filter(function ($file) {
            return in_array($file->getExtension(), ['pdf']);
        })->sortBy(function ($file) {
            return $file->getCTime();
        })->map(function ($file) {
            return $file->getBaseName();
        })->values();
        if ($files->count() > 0) {
            return response()->make(file_get_contents(Storage::path($path.'/'.$files->last())), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$files->last().'"'
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error inesperado, comuniquese con el personal de MUSERPOL.',
                'data' => null,
            ], 500);
        }
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
