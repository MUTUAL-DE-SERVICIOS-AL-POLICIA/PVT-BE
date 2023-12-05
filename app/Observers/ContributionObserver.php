<?php

namespace Muserpol\Observers;

use Auth;
use Log;
use Carbon\Carbon;
use Muserpol\Models\Contribution\Contribution;
use Muserpol\Models\Record;
use Muserpol\Models\RecordType;

class ContributionObserver
{
    public function created(Contribution $contribution) {
        logger("entra aca");
        $message = 'El usuario '.Auth::user()->username.' registro aporte en fecha '.Carbon::now();
        $record_type = RecordType::where('display_name', '=', 'Contribuciones')->first();
        $record = new Record();
        $record->user_id = Auth::user()->id;
        $record->record_type_id = $record_type->id;
        $record->recordable_id = $contribution->id;
        $record->recordable_type = $contribution->getMorphClass();
        $record->action = $message;
        $record->save();
    }
    public function updating(Contribution $contribution) {
        $old = Contribution::find($contribution->id);

        $message = 'El usuario '. Auth::user()->username.' modificó';

        if($contribution->base_wage != $old->base_wage) {
            $message = $message . ' sueldo de'. $old->base_wage . ' a '. $contribution->base_wage;
        }
        if($contribution->seniority_bonus != $old->seniority_bonus) {
            $message = $message . ' la antigüedad de ' . $old->seniority_bonus . ' a ' . $contribution->seniority_bonus;
        }
        if($contribution->gain != $old->gain) {
            $message = $message . ' cotizable de' . $old->gain . ' a ' . $contribution->gain;
        }
        if($contribution->total != $old->total) {
            $message = $message . ' total de '. $old->total . ' a ' . $contribution->total;
        }
        if('El usuario ' . Auth::user()->username . ' modificó ' != $message) {
            $message = $message . ' ';
            $record_type = RecordType::where('display_name', '=', 'Contribuciones')->first();
            $record = new Record();
            $record->user_id = Auth::user()->id;
            $record->record_type_id = $record_type->id;
            $record->recordable_id = $contribution->id;
            $record->recordable_type = $contribution->getMorphClass();
            $record->action = $message;
            $record->save();
        }
    }
}