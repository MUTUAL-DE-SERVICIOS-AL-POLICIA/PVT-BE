<?php

namespace Muserpol\Http\Controllers\Notificatons;

use Illuminate\Http\Request;
use Muserpol\Http\Controllers\Controller;
use Muserpol\Http\Controllers\EcoComObservationController;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Muserpol\Models\Notification\NotificationCarrier;
use Muserpol\Models\Notification\NotificationNumber;
use Muserpol\Models\Notification\NotificationSend;
use Carbon\Carbon;
use Muserpol\Models\AffiliateToken;

class NotificationObservationController extends Controller

{
    // id. user_id, carrier_id, sendable_type, sendable_id, date, delivered, number_id, message, subject
    public function notify(Request $request) {

        $eco_com_id = $request->eco_com_id;
        $affiliate_id = EconomicComplement::find($eco_com_id)->affiliate_id;

        $affiliate_token = AffiliateToken::whereAffiliateId($affiliate_id);

        $user_id = $request->user_id;
        $message = $request->message;
        $subject = $request->subject;

        // return response()->json([
        //     'eco_com_id' => $eco_com_id,
        //     'user_id' => $user_id,
        //     'message' => $message,
        //     'subject' => $subject
        // ]);

        //  FASE DE REGISTRO
        $notificaton_carrier = new NotificationCarrier;

        $new_carrier = $notification_carrier->create([
            'module_id' => 2, 
            'image' => null,
            'name' => 'Notification'
        ]);

        if($new_carrier->id != null) {

            $notification_carrier = NotificationCarrier::find(1); // Obtenemos el transportador 'Notification, SMS, WhatsApp'
            $date = Carbon::now()->toDateTimeString(); // Generamos la hora actual
            $eco_com = 'economic_complements'; // para complemento económico
            $new_send = new NotificationSend; // creamos un registro vacío de notification_sends

            // Rellenamos
            $register_send = $new_send->create([
                'user_id' => $user_id,
                'carrier_id' => $new_carrier->id,
                'sendable_type' => $eco_com,
                'sendable_id' => $eco_com_id,
                'date' => $date,
                'delivered' => false,
                'number_id' => null,
                'message' => $message,
                'subject' => $subject
            ]);

            // Verificamos
            if($register_send->id != null){
                return response()->json([
                    'error' => false,
                    'message' => 'Notificación exitosa!',
                    'data' => [
                        ''
                    ]
                ]);
            }
        }
    }
}
