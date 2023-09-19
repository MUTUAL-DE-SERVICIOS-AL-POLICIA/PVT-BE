<?php

namespace Muserpol\Observers;

use Auth;
use Log;
use Carbon\Carbon;
use Muserpol\Models\Contribution\Reimbursement;
use Muserpol\Models\Record;
use Muserpol\Models\RecordType;

class ReimbursementObserver
{
    public function created(Reimbursement $reimbursement) {

        $message = 'El usuario '. Auth::user()->username .' registro reintegro del periódo '.$reimbursement->month_year;

        $record_type = RecordType::where('display_name', '=', 'Contribuciones')->first();
        $record = new Record();
        $record->user_id = Auth::user()->id;
        $record->record_type_id = $record_type->id;
        $record->recordable_id = $reimbursement->id;
        $record->recordable_type = $reimbursement->getMorphClass();
        $record->action = $message;
        $record->save();
    }
}