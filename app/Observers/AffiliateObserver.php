<?php

namespace Muserpol\Observers;
use Muserpol\Models\Affiliate;
use Muserpol\Models\AffiliateRecord;
use Log;
use Carbon\Carbon;
use Auth;
use Muserpol\Helpers\Util;
class AffiliateObserver
{
    public function created(Affiliate $affiliate){
        Log::info('affiliado creado');
        Log::info($affiliate);
    }
    public function updating(Affiliate $affiliate){
        // Log::info('antes de actualizar');
        $old = Affiliate::find($affiliate->id);

        $message = 'El usuario '.Auth::user()->username.' modifico ';


        if($affiliate->city_identity_card_id != $old->city_identity_card_id)
        {
            $message = $message . ' lugar de expedición '.$old->city_identity_card->name.' a '.$affiliate->city_identity_card->name.', ';
        }
        
        if($affiliate->identity_card != $old->identity_card)
        {   
            $message = $message . ' numero de CI '.$old->identity_card.' a '.$affiliate->identity_card.', ';
            
        }

        if($affiliate->first_name != $old->first_name)
        {   
            $message = $message . ' primer nombre '.$old->first_name.' a '.$affiliate->first_name.', ';
            
        }

        if($affiliate->second_name != $old->second_name)
        {   
            $message = $message . ' segundo nombre '.$old->second_name.' a '.$affiliate->second_name.', ';
            
        }

        if($affiliate->last_name != $old->last_name)
        {   
            $message = $message . ' primer apellido '.$old->last_name.' a '.$affiliate->last_name.', ';
            
        }

        if($affiliate->mothers_last_name != $old->mothers_last_name)
        {   
            $message = $message . ' segundo apellido '.$old->mothers_last_name.' a '.$affiliate->mothers_last_name.', ';
            
        }

        if($affiliate->mothers_last_name != $old->mothers_last_name)
        {   
            $message = $message . ' segundo apellido '.$old->mothers_last_name.' a '.$affiliate->mothers_last_name.', ';
            
        }

        if($affiliate->gender != $old->gender)
        {   
            $message = $message . ' genero '.Util::getGenderLabel($old->gender).' a '.Util::getGenderLabel($affiliate->gender).', ';
            
        }
        
        if($affiliate->civil_status != $old->civil_status)
        {   
            $message = $message . ' estado civil '.Util::getCivilStatus( $old->civil_status,$old->gender).' a '.Util::getCivilStatus( $affiliate->civil_status , $affiliate->gender).', ';
            
        }
        
        if($affiliate->birth_date != $old->birth_date)
        {   
            $message = $message . ' fecha de nacimiento '.$old->birth_date.' a '.$affiliate->birth_date.', ';
            
        }

        if($affiliate->city_birth_id != $old->city_birth_id)
        {   
            $message = $message . ' fecha de nacimiento '.$old->city_birth->name.' a '.$affiliate->city_birth->name.', ';

        }

        if($affiliate->phone_number != $old->phone_number)
        {   
            $message = $message . ' numero de telefono '.$old->phone_number.' a '.$affiliate->phone_number.', ';

        }

        if($affiliate->cell_phone_number != $old->cell_phone_number)
        {   
            $message = $message . ' numero de celular '.$old->cell_phone_number.' a '.$affiliate->cell_phone_number.', ';

        }

        if($affiliate->affiliate_state_id != $old->affiliate_state_id)
        {   
            $message = $message . ' estado '.$old->affiliate_state->name.' a '.$affiliate->affiliate_state->name.', ';

        }

        if($affiliate->type != $old->type)
        {   
            $message = $message . ' tipo '.$old->type.' a '.$affiliate->type.', ';

        }

        if($affiliate->category_id != $old->category_id)
        {   
            $message = $message . ' categoria '.$old->category->name.' a '.$affiliate->category->name.', ';

        }
        
        if($affiliate->date_entry != $old->date_entry)
        {   
            $message = $message . ' fecha de ingreso  '.$old->date_entry.' a '.$affiliate->date_entry.', ';

        }
        
        if($affiliate->degree_id != $old->degree_id)
        {   
            $message = $message . ' grado '.$old->degree->name.' a '.$affiliate->degree->name.', ';

        }

        if($affiliate->pension_entity_id != $old->pension_entity_id)
        {   
            $message = $message . ' ente gestor '.$old->pension_entity->name.' a '.$affiliate->pension_entity->name.', ';

        }

        if($affiliate->item != $old->item)
        {   
            $message = $message . ' numero de item '.$old->item.' a '.$affiliate->item.', ';
        }
        
        $message = $message . ' '.Carbon::now();

        Log::info('updating');
        $affiliate_record = new AffiliateRecord;
        $affiliate_record->user_id = Auth::user()->id;
        $affiliate_record->affiliate_id = $affiliate->id;
        $affiliate_record->message = $message;
        $affiliate_record->save();
    }
   
}