<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Muserpol\Models\Note;
use Muserpol\Models\Affiliate;
use Muserpol\Models\RecordType;
use Muserpol\Models\Record;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Auth;
use Muserpol\Helpers\Util;

class AffiliateNoteController extends Controller
{
    //
    public function create(Request $request)
    {
        $affiliate = Affiliate::findOrFail($request->affiliateId);
        $record_type = RecordType::whereName('notas')->first();
        if ($record_type) {
            $affiliate->records()->create([
                'user_id' => Auth::user()->id,
                'record_type_id' => $record_type->id,
                'action' => 'NOTAS:' . $request->action
            ]);
            return $affiliate->records()->latest()->first();
        }
        abort(404);
    }
    public function update(Request $request)
    {
        $affiliate = Affiliate::find($request->affiliateId);
        $record = Record::find($request->id);
        $record_type = Record::find($request->id);

        if ($record) {
            $record->update(['message' => $request->action]);
            $action = "El usuario " . auth()->user()->username  . " modifico la nota ";
            if ($record_type->action != $request->action) {
                $action = $action . ' Mensaje de - ' . $record_type->action . ' - a - ' . $request->action . '.';
            }
                return $affiliate->records()->latest()->first();
            
        } 
        return "updated";
    }
    public function delete(Request $request)
    {

        $affiliate = Affiliate::find($request->affiliateId);
        $records= Record::find($request->id);
        $action = $records->action;
        if ($records) {
            $records->delete();
        } 

    }
}
