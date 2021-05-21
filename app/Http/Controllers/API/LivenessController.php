<?php

namespace Muserpol\Http\Controllers\API;

use Illuminate\Http\Request;
use Muserpol\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Muserpol\Http\Requests\LivenessForm;
use Muserpol\Models\EconomicComplement\EcoComProcedure;
use GuzzleHttp\Client;

class LivenessController extends Controller
{
    public function __construct()
    {
        $this->api_client = new Client([
            'base_uri' => env('LIVENESS_URL', 'localhost'),
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'request.options' => [
                'exceptions' => false,
            ],
        ]);
        $this->image_count = 6;
        // Constante para comparación de rostros entre [0, 1]
        $this->distance = floatval(env("LIVENESS_DISTANCE", 0.4));
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
                'message' => 'Gire ligeramente su rostro hacia la izquierda'
            ], [
                'gaze' => 'right',
                'emotion' => 'any',
                'successful' => false,
                'message' => 'Gire ligeramente su rostro hacia la derecha'
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
            return array_slice($actions, 0, 1);
        }
    }

    public function index(Request $request)
    {
        $device = $request->affiliate->device;
        $available_procedures = EcoComProcedure::affiliate_available_procedures($request->affiliate->id);

        if ($device->enrolled && Storage::exists('liveness/faces/'.$request->affiliate->id) && ($available_procedures->count() > 0)) {
            if ($device->eco_com_procedure_id != null) {
                if ($device->eco_com_procedure_id == $available_procedures->first()->id) {
                    return response()->json([
                        'error' => false,
                        'message' => 'Proceso terminado',
                        'data' => [
                            'completed' => true,
                            'type' => 'liveness',
                            'verified' => $device->verified
                        ]
                    ], 200);
                }
            }

            $device->liveness_actions = $this->random_actions(false);
            $device->save();

            return response()->json([
                'error' => false,
                'message' => '1/'.count($device->liveness_actions).'. Siga las instrucciones',
                'data' => [
                    'completed' => false,
                    'type' => 'liveness',
                    'dialog' => [
                        'title' => 'Reconocimiento Facial',
                        'content' => 'Para poder generar trámites en línea debe realizar el proceso de control de vivencia, para ello debe tomar fotografías de su rostro de acuerdo a las instrucciones que aparecerán en pantalla. Debe quitarse elementos como anteojos y sombrero para que el proceso resulte efectivo.',
                    ],
                    'action' => $device->liveness_actions[0],
                    'current_action' => 1,
                    'total_actions' => count($device->liveness_actions)
                ]
            ], 200);
        } elseif (!$device->enrolled) {
            if (Storage::exists('liveness/faces/'.$request->affiliate->id)) {
                Storage::deleteDirectory('liveness/faces/'.$request->affiliate->id);
            }
            Storage::makeDirectory('liveness/faces/'.$request->affiliate->id, 0775, true);
            $device->liveness_actions = $this->random_actions(true);
            $device->save();
            return response()->json([
                'error' => false,
                'message' => '1/'.count($device->liveness_actions).'. Siga las instrucciones',
                'data' => [
                    'completed' => false,
                    'type' => 'enroll',
                    'dialog' => [
                        'title' => 'Reconocimiento Facial',
                        'content' => 'Para poder generar trámites en línea debe realizar el proceso de enrolamiento, para ello debe tomar fotografías de su rostro de acuerdo a las instrucciones que aparecerán en pantalla. Debe quitarse elementos como anteojos y sombrero para que el proceso resulte efectivo.',
                    ],
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
            Storage::put($path.$file_name, base64_decode($image), 'public');
            $res = $this->api_client->post(env('LIVENESS_API_ENDPOINT').'/crop', [
                'body' => json_encode([
                    'id' => $request->affiliate->id,
                    'image' => $file_name
                ]),
                'http_errors' => false,
            ]);
            if (env('APP_DEBUG')) logger(json_decode($res->getBody(), true));
            if ($res->getStatusCode() != 200) $continue = false;
            if ($continue) {
                $files = Storage::files($path);
                if (count(array_filter($files, function($item) {
                    return strpos($item, '.npy') !== false;
                })) > 1) {
                    $res = $this->api_client->post(env('LIVENESS_API_ENDPOINT').'/verify', [
                        'body' => json_encode([
                            'id' => $request->affiliate->id,
                            'image' => $file_name
                        ]),
                        'http_errors' => false,
                    ]);
                    $response = json_decode($res->getBody(), true);
                    if (env('APP_DEBUG')) logger($response);
                    if ($res->getStatusCode() == 200) {
                        $continue = $response['data']['verfied'];
                    } else {
                        $continue = false;
                    }
                }
                if ($continue) {
                    $res = $this->api_client->post(env('LIVENESS_API_ENDPOINT').'/analyze', [
                        'body' => json_encode([
                            'is_base64' => false,
                            'id' => $request->affiliate->id,
                            'image' => $file_name,
                            'gaze' => true,
                            'emotion' => $current_action['emotion'] == 'any' ? false : true,
                            'distance' => $this->distance
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
                                                'completed' => false,
                                                'type' => $device->enrolled ? 'liveness' : 'enroll',
                                                'action' => $liveness_actions[$current_action_index],
                                                'current_action' => $current_action_index + 1,
                                                'total_actions' => $total_actions,
                                                'verified' => $device->verified
                                            ]
                                        ], 200);
                                    } else {
                                        $enrolled = $device->enrolled;
                                        if (!$device->enrolled) {
                                            $device->update([
                                                'enrolled' => true,
                                                'liveness_actions' => null
                                            ]);
                                        } else {
                                            $current_procedure = EcoComProcedure::affiliate_available_procedures($request->affiliate->id)->first();
                                            if ($current_procedure) {
                                                $device->update([
                                                    'eco_com_procedure_id' => $current_procedure->id,
                                                    'liveness_actions' => null
                                                ]);
                                                $files = collect(File::allFiles(Storage::path($path)))->filter(function ($file) {
                                                    return in_array($file->getExtension(), ['npy']);
                                                })->sortBy(function ($file) {
                                                    return $file->getCTime();
                                                })->map(function ($file) {
                                                    return $file->getBaseName();
                                                })->values();
                                                if ($files->count() >= $this->image_count) {
                                                    Storage::delete($path.$files->first());
                                                    $image_file = $path.substr($files->first(), 0, strpos($files->first(), '.npy')).'.jpg';
                                                    if (Storage::exists($image_file)) Storage::delete($image_file);
                                                }
                                            } else {
                                                return response()->json([
                                                    'error' => true,
                                                    'message' => 'Ocurrió un error inesperado, comuniquese con el personal de MUSERPOL.',
                                                    'data' => []
                                                ], 500);
                                            }
                                        }
                                        return response()->json([
                                            'error' => false,
                                            'message' => 'Proceso terminado',
                                            'data' => [
                                                'completed' => true,
                                                'type' => $enrolled ? 'liveness' : 'enroll',
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
                    'completed' => false,
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
                    'completed' => true,
                    'type' => $device->enrolled ? 'liveness' : 'enroll',
                    'verified' => $device->verified
                ]
            ], 200);
        }
    }

    public function show(Request $request, $affiliate)
    {
        $last_procedure = $request->affiliate->economic_complements()->has('eco_com_beneficiary')->orderBy('reception_date', 'desc')->whereHas('eco_com_beneficiary', function($query) {
            return $query->where('cell_phone_number', '!=', '')->where('cell_phone_number', '!=', null);
        })->first();
        $current_procedures = EcoComProcedure::affiliate_available_procedures($request->affiliate->id);
        if (($current_procedures->count() > 0) && $request->affiliate->device) {
            if ($last_procedure) {
                $phones = array_unique(explode(',', str_replace('-', '', str_replace(')', '', str_replace('(', '', $last_procedure->eco_com_beneficiary->cell_phone_number)))));
            } else {
                $phones = [];
            }
            if ($request->affiliate->device->eco_com_procedure_id == $current_procedures->first()->id) {
                return response()->json([
                    'error' => false,
                    'message' => 'Puede crear trámites',
                    'data' => [
                        'procedure_id' => $request->affiliate->device->eco_com_procedure_id,
                        'validate' => $request->affiliate->device->verified,
                        'liveness_success' => true,
                        'cell_phone_number' => $phones,
                    ],
                ]);
            } else {
                return response()->json([
                    'error' => false,
                    'message' => 'Puede crear trámites',
                    'data' => [
                        'procedure_id' => $current_procedures->first()->id,
                        'validate' => $request->affiliate->device->verified,
                        'liveness_success' => false,
                        'cell_phone_number' => $phones,
                    ],
                ]);
            }
        }
        return response()->json([
            'error' => true,
            'message' => 'No es posible crear trámites',
            'data' => [],
        ], 400);
    }
}
