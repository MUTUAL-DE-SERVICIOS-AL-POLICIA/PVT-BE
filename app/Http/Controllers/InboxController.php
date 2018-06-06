<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Helpers\Util;

class InboxController extends Controller
{
    public function received()
    {
        $module_id = Util::getRol()->module->id;
        switch ($module_id) {
            case 2:
                # eco com
                break;
            case 3:
                # ret fun
                // return "hola";
                break;
            case 4:
                # cm
                break;
            case 5:
                # am
            default:
                # code...
                break;
        }
        return view('inbox.received');
    }
    public function edited()
    {
        $module_id = Util::getRol()->module->id;
        switch ($module_id) {
            case 2:
                # eco com
                break;
            case 3:
                # ret fun
                // return "hola";
                break;
            case 4:
                # cm
                break;
            case 5:
                # am
            default:
                # code...
                break;
        }
        return view('inbox.edited');
    }
}
