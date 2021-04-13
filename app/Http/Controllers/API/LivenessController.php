<?php

namespace Muserpol\Http\Controllers\API;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Muserpol\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Muserpol\Http\Requests\LivenessForm;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use GuzzleHttp\Client;

class LivenessController extends Controller
{
    public function __construct()
    {
        $this->api_client = new Client([
            'base_uri' => env("LIVENESS_URL"),
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'request.options' => [
                'exceptions' => false,
            ],
        ]);

        $this->eco_com_procedure = EcoComProcedure::orderBy('created_at', 'desc')->limit(1)->first();
    }

    private function random_actions($enroll)
    {
        $actions = [
            [
                'gaze' => 'forward',
                'emotion' => 'neutral',
                'successful' => false,
                'message' => 'Mire al frente con la boca cerrada'
            ], [
                'gaze' => 'left',
                'emotion' => 'any',
                'successful' => false,
                'message' => 'Mire ligéramente hacia su izquierda'
            ], [
                'gaze' => 'right',
                'emotion' => 'any',
                'successful' => false,
                'message' => 'Mire ligéramente hacia su derecha'
            ], [
                'gaze' => 'forward',
                'emotion' => 'happy',
                'successful' => false,
                'message' => 'Mire al frente sonriendo'
            ]
        ];
        shuffle($actions);
        if ($enroll) {
            return $actions;
        } else {
            return array_slice($actions, 0, 3);
        }
    }

    public function index(Request $request)
    {
        if (!Storage::exists('liveness/faces/'.$request->affiliate->id)) {
            Storage::makeDirectory('liveness/faces/'.$request->affiliate->id, 0775, true);
        }

        $device = $request->affiliate->device;

        if ($device->enrolled && !$device->verified) {
            return response()->json([
                'error' => true,
                'message' => 'Su identidad está siendo verificada, será notificado cuando se complete el proceso.',
                'data' => [
                    'type' => 'verifying'
                ]
            ], 200);
        } elseif ($device->enrolled && $device->verified) {
            // TODO: verificar si es el rango de fechas correcto
            $eco_com_start_date = Carbon::createFromFormat('d/m/Y', $this->eco_com_procedure->normal_start_date)->startOfDay();
            $eco_com_end_date = Carbon::createFromFormat('d/m/Y', $this->eco_com_procedure->normal_end_date)->endOfDay();
            if (!Carbon::now()->between($eco_com_start_date, $eco_com_end_date)) {
                return response()->json([
                    'error' => true,
                    'message' => 'No puede generar trámites ahora, espere a las fechas que se indican en la página web.',
                    'data' => [
                        'type' => 'wrong'
                    ]
                ], 200);
            } else {
                // TODO: verificar si es funciona en la creación de trámites
                if ($device->eco_com_procedure_id != null) {
                    if ($device->eco_com_procedure_id == $this->eco_com_procedure->id) {
                        return response()->json([
                            'error' => false,
                            'message' => 'Proceso terminado',
                            'data' => [
                                'type' => 'completed',
                                'verified' => $device->verified
                            ]
                        ], 200);
                    }
                }
            }

            $device->liveness_actions = $this->random_actions(false);
            $device->save();

            return response()->json([
                'error' => false,
                'message' => '1/'.count($device->liveness_actions).'. Siga las instrucciones',
                'data' => [
                    'type' => 'liveness',
                    'action' => $device->liveness_actions[0],
                    'current_action' => 1,
                    'total_actions' => count($device->liveness_actions)
                ]
            ], 200);
        } elseif (!$device->enrolled && !$device->verified) {
            $device->liveness_actions = $this->random_actions(true);
            $device->save();

            return response()->json([
                'error' => false,
                'message' => '1/'.count($device->liveness_actions).'. Siga las instrucciones',
                'data' => [
                    'type' => 'enroll',
                    'action' => $device->liveness_actions[0],
                    'current_action' => 1,
                    'total_actions' => count($device->liveness_actions)
                ]
            ], 200);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Ocurrió un error inesperado, comuniquese con el personal de MUSERPOL.',
                'data' => []
            ], 500);
        }
    }

    public function store(LivenessForm $request)
    {
        $device = $request->affiliate->device;
        $remove_file = true;
        $continue = true;
        if (str_contains($request->image, ';base64,')) {
            $image = explode(";base64,", $request->image)[1];
        } else {
            $image = $request->image;
        }
        $path = 'liveness/faces/'.$request->affiliate->id.'/';
        $file_name = str_random(12).'.jpg';

        $liveness_actions = collect($device->liveness_actions);
        $total_actions = $liveness_actions->count();
        $remaining_actions = $liveness_actions->where('successful', false)->count();
        $current_action = $liveness_actions->where('successful', false)->first();
        $current_action_index = $total_actions - $remaining_actions;

        if ($remaining_actions > 0) {
            // TODO: Eliminar foto mas antigua para reemplazar por la última en caso de control de vivencia
            if (!$device->enrolled) {
                Storage::put($path.$file_name, base64_decode($image), 'public');
            }
            $res = $this->api_client->post(env('LIVENESS_API_ENDPOINT').'/crop', [
                'body' => json_encode([
                    'id' => $request->affiliate->id,
                    'image' => $file_name
                ]),
                'http_errors' => false,
            ]);
            if ($res->getStatusCode() != 200) $continue = false;
            if ($continue) {
                $files = count(Storage::files($path));
                if ($files > 1) {
                    $res = $this->api_client->post(env('LIVENESS_API_ENDPOINT').'/verify', [
                        'body' => json_encode([
                            'id' => $request->affiliate->id,
                            'image' => $file_name
                        ]),
                        'http_errors' => false,
                    ]);
                    if (env('APP_DEBUG')) logger(json_decode($res->getBody(), true));
                    if ($res->getStatusCode() != 200) $continue = false;
                }
                if ($continue) {
                    $res = $this->api_client->post(env('LIVENESS_API_ENDPOINT').'/analyze', [
                        'body' => json_encode([
                            'is_base64' => false,
                            'id' => $request->affiliate->id,
                            'image' => $file_name
                        ]),
                        'http_errors' => false,
                    ]);
                    if (env('APP_DEBUG')) logger(json_decode($res->getBody(), true));
                    if ($res->getStatusCode() == 200) {
                        $data = json_decode($res->getBody(), true);
                        if ($data['data']['gaze'] == $current_action['gaze']) {
                            if (($current_action['emotion'] == 'neutral' && ($data['data']['analysis']['dominant_emotion'] != 'happy' && $data['data']['analysis']['dominant_emotion'] != 'surprise')) || $data['data']['analysis']['dominant_emotion'] == $current_action['emotion'] || $current_action['emotion'] == 'any') {
                                $current_action['successful'] = true;
                                $liveness_actions[$current_action_index] = $current_action;
                                $device->update([
                                    'liveness_actions' => $liveness_actions
                                ]);
                                $res = $this->api_client->post(env('LIVENESS_API_ENDPOINT').'/build', [
                                    'body' => json_encode([
                                        'id' => $request->affiliate->id,
                                        'image' => $file_name
                                    ]),
                                    'http_errors' => false,
                                ]);
                                if ($res->getStatusCode() == 200) {
                                    $remove_file = false;
                                    $current_action_index += 1;
                                    if ($current_action_index < $total_actions) {
                                        return response()->json([
                                            'error' => false,
                                            'message' => ($current_action_index + 1).'/'.$total_actions.'. Siga las instrucciones',
                                            'data' => [
                                                'type' => $device->enrolled ? 'liveness' : 'enroll',
                                                'action' => $liveness_actions[$current_action_index],
                                                'current_action' => $current_action_index + 1,
                                                'total_actions' => $total_actions,
                                                'verified' => $device->verified
                                            ]
                                        ], 200);
                                    } else {
                                        if (!$device->enrolled && !$device->verified) {
                                            $device->update([
                                                'enrolled' => true,
                                                'liveness_actions' => null
                                            ]);
                                        } elseif ($device->enrolled && $device->verified) {
                                            $device->update([
                                                'eco_com_procedure_id' => $this->eco_com_procedure->id
                                            ]);
                                        }
                                        return response()->json([
                                            'error' => false,
                                            'message' => 'Proceso terminado',
                                            'data' => [
                                                'type' => 'completed',
                                                'verified' => $device->verified
                                            ]
                                        ], 200);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if ($remove_file) Storage::delete($path.$file_name);
            return response()->json([
                'error' => true,
                'message' => ($current_action_index + 1).'/'.$total_actions.'. Intente nuevamente',
                'data' => [
                    'type' => $device->enrolled ? 'liveness' : 'enroll',
                    'action' => $current_action,
                    'current_action' => $current_action_index + 1,
                    'total_actions' => $total_actions,
                    'verified' => $device->verified
                ]
            ], 200);
        } else {
            return response()->json([
                'error' => false,
                'message' => 'Proceso terminado',
                'data' => [
                    'type' => 'completed',
                    'verified' => $device->verified
                ]
            ], 200);
        }
    }
}
