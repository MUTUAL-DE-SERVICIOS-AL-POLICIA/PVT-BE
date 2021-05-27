<?php

namespace Muserpol\Http\Controllers\API;

use Illuminate\Http\Request;
use Muserpol\Http\Controllers\Controller;

class MessageController extends Controller
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $type)
    {
        $device = $request->affiliate->device;
        $enrolled = false;
        if ($device) {
            $enrolled = $device->enrolled;
        }
        switch($type) {
            case 'before_liveness':
                return response()->json([
                    'error' => false,
                    'message' => 'Mensaje de cámara',
                    'data' => [
                        'title' => $enrolled ? 'CONTROL DE VIVENCIA' : 'PROCESO DE ENROLAMIENTO',
                        'content' => 'Siga las instrucciones, para comenzar presione el botón azul de "INICIAR"'
                    ]
                ]);
                break;
            default:
                return response()->json([
                    'error' => true,
                    'message' => 'Error',
                    'data' => [
                        'title' => 'Error',
                        'content' => 'Ocurrió un error inesperado, comuniquese con el personal de MUSERPOL.'
                    ]
                ]);
                break;
        }
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
