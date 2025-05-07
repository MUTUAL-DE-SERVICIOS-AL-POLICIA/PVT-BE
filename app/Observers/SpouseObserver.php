<?php
namespace Muserpol\Observers;

use Muserpol\Models\Record;
use Muserpol\Models\Spouse;
use Auth;
use Muserpol\Models\RecordType;

class SpouseObserver {
    public function created(Spouse $spouse){

        $message = 'El usuario '.Auth::user()->username.' creó un nuevo cónyuge: ';
        $message .= 'CI: '.$spouse->identity_card.', ';
        $message .= 'Nombre: '.$spouse->first_name.' '.$spouse->second_name.' '.$spouse->last_name.' '.$spouse->mothers_last_name.', ';
        $message .= 'Fecha de Nacimiento: '.$spouse->birth_date.', ';
        $message .= 'Ciudad de Nacimiento: '.($spouse->city_birth->name ?? 'Sin ciudad').', ';
        $message .= 'Estado Civil: '.$spouse->getCivilStatus().', ';
        $message .= 'Fecha de Matrimonio: '.$spouse->marriage_date.', ';
        $message .= 'Fecha de Fallecimiento: '.$spouse->date_death ?? 'No disponible'.', ';
        $message .= 'Motivo de Fallecimiento: '.$spouse->reason_death ?? 'No disponible'.', ';
        $message .= 'Número de Certificado de Fallecimiento: '.$spouse->death_certificate_number ?? 'No disponible'.', ';
        $message .= 'Oficial: '.$spouse->official.', ';
        $message .= 'Libro: '.$spouse->book.', ';
        $message .= 'Partida: '.$spouse->departure;

        $record_type = RecordType::where('display_name', '=', 'Datos Personales')->first();

        $record = new Record();
        $record->user_id = Auth::user()->id;
        $record->record_type_id = $record_type->id;
        $record->recordable_id = $spouse->id;
        $record->recordable_type = $spouse->getMorphClass();
        $record->action = $message;
        $record->save();
    }
    public function updating(Spouse $spouse)
    {
        $old = Spouse::find($spouse->id);
        $message = 'El usuario '.Auth::user()->username.' modificó ';
        if($spouse->identity_card != $old->identity_card) {
            $message .=  'número de CI '.$old->identity_card.' a '.$spouse->identity_card.', ';
        }
        if($spouse->first_name != $old->first_name) {
            $message .= 'primer nombre '.$old->first_name.' a '.$spouse->first_name.', ';
        }
        if($spouse->second_name != $old->second_name) {
            $message .=  'segundo nombre '.$old->second_name.' a '.$spouse->second_name.', ';
        }

        if($spouse->last_name != $old->last_name) {
            $message .=  'primer apellido '.$old->last_name.' a '.$spouse->last_name.', ';
        }

        if($spouse->mothers_last_name != $old->mothers_last_name) {
            $message .= 'segundo apellido '.$old->mothers_last_name.' a '.$spouse->mothers_last_name.', ';
        }

        if($spouse->birth_date != $old->birth_date) {
            $message .= 'fecha de nacimiento '.$old->birth_date.' a '.$spouse->birth_date.', ';
        }

        if($spouse->city_birth_id != $old->city_birth_id) {
            $message .= 'ciudad de nacimiento '.($old->city_birth->name ?? 'Sin ciudad').' a '.($spouse->city_birth->name ?? 'Sin ciudad').', ';
        }

        if($spouse->getCivilStatus() != $old->getCivilStatus()) {
            $message .= 'estado civil '.$old->getCivilStatus().' a '.$spouse->getCivilStatus().', ';
        }
        if($spouse->date_death != $old->date_death) {
            $message .= 'Fecha de fallecimiento '.$old->date_death.' a '.$spouse->date_death.', ';
        }
        if($spouse->reason_death != $old->reason_death) {
            $message .= 'Motivo de fallecimiento '.$old->reason_death.' a '.$spouse->reason_death.', ';
        }
        if($spouse->death_certificate_number != $old->death_certificate_number) {
            $message .= 'Numero de certificado de fallecimiento '.$old->death_certificate_number.' a '.$spouse->death_certificate_number.', ';
        }
        if($spouse->official != $old->official) {
            $message .= 'Oficial '.$old->official.' a '.$spouse->official.', ';
        }
        if($spouse->book != $old->book) {
            $message .= 'Libro '.$old->book.' a '.$spouse->book.', ';
        }
        if($spouse->departure != $old->departure) {
            $message .= 'Partida '.$old->departure.' a '.$spouse->departure.', ';
        }
        if($spouse->marriage_date != $old->marriage_date) {
            $message .= 'Fecha de matrimonio '.$old->marriage_date.' a '.$spouse->marriage_date.', ';
        }
        if('El usuario ' . Auth::user()->username . ' modificó ' != $message) {
            logger(Auth::user());
            $message = $message . ' ';
            $record_type = RecordType::where('display_name', '=', 'Datos Personales')->first();
            $record = new Record();
            $record->user_id = Auth::user()->id;
            $record->record_type_id = $record_type->id;
            $record->recordable_id = $spouse->id;
            $record->recordable_type = $spouse->getMorphClass();
            $record->action = $message;
            $record->save();

        }
    }
}
