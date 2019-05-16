<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Helpers\Util;
use Muserpol\Workflow;
use Muserpol\Models\RetirementFund\RetirementFund;
use Illuminate\Validation\ValidationException;
use Log;
use DB;
use Auth;
use Muserpol\Models\Role;
use Muserpol\Models\Workflow\WorkflowState;
use Exception;
use Muserpol\Models\Workflow\WorkflowRecord;
use Carbon\Carbon;
use Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary;
use Muserpol\Models\Contribution\ContributionProcess;
use Muserpol\Models\RetirementFund\RetFunCorrelative;
use Muserpol\Models\QuotaAidMortuary\QuotaAidCorrelative;
use Muserpol\Models\ProcedureType;
use Muserpol\Models\EconomicComplement\EconomicComplement;

class InboxController extends Controller
{
  public function received()
  {
    return view('inbox.received');
  }
  public function edited()
  {
    return view('inbox.edited');
  }
  public function sendForward(Request $request)
  {
    try {
      $this->validate($request, [
        'docs' => 'required|min:1',
        'wfSequenceNext' => 'required',
      ]);
    } catch (ValidationException $exception) {
      return response()->json([
        'status' => 'error',
        'msg' => 'Error',
        'errors' => $exception->errors(),
      ], 422);
    }
    $doc_ids = array_map(function ($doc) {
      return $doc['id'];
    }, $request->docs);
    $wf_state_next_id = $request->wfSequenceNext;

    $rol_id = Util::getRol()->id;
    $module = Role::find($rol_id)->module;
    switch ($module->id) {
      case 1:
        # code...
        break;
      case 2:
        $eco_coms = EconomicComplement::whereIn('id', $doc_ids)->get();
        foreach ($eco_coms as $eco_com) {
          $eco_com->wf_current_state_id = $wf_state_next_id;
          $eco_com->inbox_state = false;
          $eco_com->save();
        }
        break;
      case 3:
        // DB::table('retirement_funds')
        //     ->whereIn('id', $doc_ids)
        //     ->update([
        //         'wf_state_current_id' => $wf_state_next_id,
        //         'inbox_state' => false
        //     ]);
        $retirement_funds = RetirementFund::whereIn('id', $doc_ids)->get();
        foreach ($retirement_funds as $ret_fun) {
          $ret_fun->wf_state_current_id = $wf_state_next_id;
          $ret_fun->inbox_state = false;
          $ret_fun->save();
        }
        break;
      case 4:
        $quota_aids = QuotaAidMortuary::whereIn('id', $doc_ids)->get();
        foreach ($quota_aids as $ret_fun) {
          $ret_fun->wf_state_current_id = $wf_state_next_id;
          $ret_fun->inbox_state = false;
          $ret_fun->save();
        }
        break;
      case 11:
        $contribution_processes = ContributionProcess::whereIn('id', $doc_ids)->get();
        foreach ($contribution_processes as $ret_fun) {
          $ret_fun->wf_state_current_id = $wf_state_next_id;
          $ret_fun->inbox_state = false;
          $ret_fun->save();
        }
        break;
      default:
        # code...
        break;
    }
    return response()->json([
      'status' => 'success',
      'msg' => 'Okay',
    ], 201);
  }
  public function sendBackward(Request $request)
  {
    try {
      $this->validate($request, [
        'docs' => 'required|min:1',
        'wfSequenceBack' => 'required',
        'message' => 'required',
      ]);
    } catch (ValidationException $exception) {
      return response()->json([
        'status' => 'error',
        'msg' => 'Error',
        'errors' => $exception->errors(),
      ], 422);
    }
    $doc_ids = array_map(function ($doc) {
      return $doc['id'];
    }, $request->docs);
    $wf_state_back_id = $request->wfSequenceBack;

    $rol_id = Util::getRol()->id;
    $module = Role::find($rol_id)->module;
    switch ($module->id) {
      case 1:
        # code...
        break;
      case 2:
        $eco_coms = EconomicComplement::whereIn('id', $doc_ids)->get();
        foreach ($eco_coms as $eco_com) {
          $eco_com->wf_current_state_id = $wf_state_back_id;
          $eco_com->inbox_state = false;
          $eco_com->save();
        }
        break;
      case 3:
        $retirement_funds = RetirementFund::whereIn('id', $doc_ids)->get();
        foreach ($retirement_funds as $ret_fun) {
          $ret_fun->wf_state_current_id = $wf_state_back_id;
          $ret_fun->inbox_state = false;
          $ret_fun->save();
        }
        break;
      case 4:
        $quota_aids = QuotaAidMortuary::whereIn('id', $doc_ids)->get();
        foreach ($quota_aids as $quota_aid) {
          $quota_aid->wf_state_current_id = $wf_state_back_id;
          $quota_aid->inbox_state = false;
          $quota_aid->save();
        }
        break;
      case 11:
        $contribution_processes = ContributionProcess::whereIn('id', $doc_ids)->get();
        foreach ($contribution_processes as $doc) {
          $doc->wf_state_current_id = $wf_state_back_id;
          $doc->inbox_state = false;
          $doc->save();
        }
        break;
      default:
        # code...
        break;
    }
    return response()->json([
      'status' => 'success',
      'msg' => 'Okay',
    ], 201);
  }
  public function validateDoc(Request $request, $doc_id)
  {
    $rol_id = Util::getRol()->id;
    $module = Role::find($rol_id)->module;
    try {
      switch ($module->id) {
        case 1:
          break;
        case 2:
          $eco_com = EconomicComplement::find($doc_id);
          if ($eco_com->inbox_state == true) {
            throw new Exception('Trámite ya validado.');
          }
          $wf_current_state = WorkflowState::where('role_id', $rol_id)->where('module_id', '=', $module->id)->first();
          if ($wf_current_state->id != $eco_com->wf_current_state_id) {
            throw new Exception('Error al validar el Trámite, verifique que el trámite este en unas de las bandejas.');
          }
          $eco_com->inbox_state = true;
          $eco_com->user_id = Auth::user()->id;

          $correlative = $eco_com;
          /* TODO
                    * adicionar fechas de revision calificacion etc.
                    */
          $eco_com->save();

          return response()->json([
            'doc' => $eco_com,
            'correlative' => $correlative,
          ], 200);
          break;
        case 3:
          $ret_fun = RetirementFund::find($doc_id);
          if ($ret_fun->inbox_state == true) {
            throw new Exception('Trámite ya validado.');
          }
          $wf_current_state = WorkflowState::where('role_id', $rol_id)->where('module_id', '=', $module->id)->first();
          if ($wf_current_state->id != $ret_fun->wf_state_current_id) {
            throw new Exception('Error al validar el Trámite, verifique que el trámite este en unas de las bandejas.');
          }
          $ret_fun->inbox_state = true;
          $ret_fun->user_id = Auth::user()->id;

          $correlative = Util::getNextAreaCode($ret_fun->id);
          /* TODO
                    * adicionar fechas de revision calificacion etc.
                    */
          $ret_fun->save();

          return response()->json([
            'doc' => $ret_fun,
            'correlative' => $correlative,
          ], 200);
          break;
        case 4:
          $quota_aid = QuotaAidMortuary::find($doc_id);
          if ($quota_aid->inbox_state == true) {
            throw new Exception('Trámite ya validado.');
          }
          $wf_current_state = WorkflowState::where('role_id', $rol_id)->where('module_id', '=', $module->id)->first();
          if ($wf_current_state->id != $quota_aid->wf_state_current_id) {
            throw new Exception('Error al validar el Trámite, verifique que el trámite este en unas de las bandejas.');
          }
          $quota_aid->inbox_state = true;
          $quota_aid->user_id = Auth::user()->id;

          $correlative = Util::getNextAreaCodeQuotaAid($quota_aid->id);

          /* TODO
                    * adicionar fechas de revision calificacion etc.
                    */
          $quota_aid->save();
          return response()->json([
            'doc' => $quota_aid,
            'correlative' => $correlative,
          ], 200);

          break;
        case 11:
          $doc = ContributionProcess::find($doc_id);
          if ($doc->inbox_state == true) {
            throw new Exception('Trámite ya validado.');
          }
          $wf_current_state = WorkflowState::where('role_id', $rol_id)->where('module_id', '=', $module->id)->first();
          if ($wf_current_state->id != $doc->wf_state_current_id) {
            throw new Exception('Error al validar el Trámite, verifique que el trámite este en unas de las bandejas.');
          }
          $doc->inbox_state = true;
          $doc->user_id = Auth::user()->id;

          // $correlative = Util::getNextAreaCodeQuotaAid($doc->id);
          $correlative = $doc;

          /* TODO
                     * adicionar fechas de revision calificacion etc.
                     */
          $doc->save();
          break;
      }
      return response()->json([
        'doc' => $doc,
        'correlative' => $correlative,
      ], 200);
    } catch (Exception $exception) {
      return response()->json([
        'status' => 'error',
        'errors' => $exception->getMessage(),
      ], 422);
    }
  }
  public function invalidateDoc(Request $request, $doc_id)
  {
    $rol_id = Util::getRol()->id;
    $module = Role::find($rol_id)->module;
    switch ($module->id) {
      case 1:
        break;
      case 2:
        try {
          $eco_com = EconomicComplement::find($doc_id);
          if ($eco_com->inbox_state == false) {
            throw new Exception('Trámite aun no validado.');
          }
          $wf_current_state = WorkflowState::where('role_id', $rol_id)->where('module_id', '=', $module->id)->first();
          if ($wf_current_state->id != $eco_com->wf_current_state_id) {
            throw new Exception('Error al validar el Trámite, verifique que el trámite este en unas de las bandejas.');
          }
          $eco_com->inbox_state = false;

          // $correlative = RetFunCorrelative::where('retirement_fund_id',$eco_com->id)->where('wf_state_id',$wf_current_state->id)->where('code','NOT LIKE','%A')->first();
          // if(!isset($correlative->id)) {
          //     throw new Exception('El trátmite no tiene correlativo.');
          // }
          // $correlative->delete();
          /* TODO
                    * adicionar fechas de revision calificacion etc.
                    */
          $eco_com->save();
        } catch (Exception $exception) {
          return response()->json([
            'status' => 'error',
            'errors' => $exception->getMessage(),
          ], 422);
        }
        return response()->json($eco_com, 200);
        break;
      case 3:
        try {
          $ret_fun = RetirementFund::find($doc_id);
          if ($ret_fun->inbox_state == false) {
            throw new Exception('Trámite aun no validado.');
          }
          $wf_current_state = WorkflowState::where('role_id', $rol_id)->where('module_id', '=', $module->id)->first();
          if ($wf_current_state->id != $ret_fun->wf_state_current_id) {
            throw new Exception('Error al validar el Trámite, verifique que el trámite este en unas de las bandejas.');
          }
          $ret_fun->inbox_state = false;

          $correlative = RetFunCorrelative::where('retirement_fund_id', $ret_fun->id)->where('wf_state_id', $wf_current_state->id)->where('code', 'NOT LIKE', '%A')->first();
          if (!isset($correlative->id)) {
            throw new Exception('El trátmite no tiene correlativo.');
          }
          $correlative->delete();
          /* TODO
                     * adicionar fechas de revision calificacion etc.
                     */
          $ret_fun->save();
        } catch (Exception $exception) {
          return response()->json([
            'status' => 'error',
            'errors' => $exception->getMessage(),
          ], 422);
        }
        return response()->json($ret_fun, 200);
        break;
      case 4:
        try {
          $quota_aid = QuotaAidMortuary::find($doc_id);
          if ($quota_aid->inbox_state == false) {
            throw new Exception('Trámite aun no validado.');
          }
          $wf_current_state = WorkflowState::where('role_id', $rol_id)->where('module_id', '=', $module->id)->first();
          if ($wf_current_state->id != $quota_aid->wf_state_current_id) {
            throw new Exception('Error al validar el Trámite, verifique que el trámite este en unas de las bandejas.');
          }
          $quota_aid->inbox_state = false;
          $correlative = QuotaAidCorrelative::where('quota_aid_mortuary_id', $quota_aid->id)->where('wf_state_id', $wf_current_state->id)->where('code', 'NOT LIKE', '%A')->first();
          if (!isset($correlative->id)) {
            throw new Exception('El trátmite no tiene correlativo.');
          }
          $correlative->delete();
          /* TODO
                     * adicionar fechas de revision calificacion etc.
                     */
          $quota_aid->save();
        } catch (Exception $exception) {
          return response()->json([
            'status' => 'error',
            'errors' => $exception->getMessage(),
          ], 422);
        }
        return response()->json($quota_aid, 200);
        break;
      case 11:
        try {
          $doc = ContributionProcess::find($doc_id);
          if ($doc->inbox_state == false) {
            throw new Exception('Trámite aun no validado.');
          }
          $wf_current_state = WorkflowState::where('role_id', $rol_id)->where('module_id', '=', $module->id)->first();
          if ($wf_current_state->id != $doc->wf_state_current_id) {
            throw new Exception('Error al validar el Trámite, verifique que el trámite este en unas de las bandejas.');
          }
          $doc->inbox_state = false;
          /* TODO
                     * adicionar fechas de revision calificacion etc.
                     */
          $doc->save();
        } catch (Exception $exception) {
          return response()->json([
            'status' => 'error',
            'errors' => $exception->getMessage(),
          ], 422);
        }
        return response()->json($doc, 200);
        break;
    }
  }

  public function printSend(Request $request)
  {
    $rol_id = Util::getRol()->id;
    $module = Role::find($rol_id)->module;
    $procedure_ids = array();
    foreach ($request->procedures as $procedure) {
      array_push($procedure_ids, $procedure['id']);
    }
    $title = '';
    $wf_state_from = WorkflowState::find($request->from_area);
    $wf_state_to = WorkflowState::find($request->to_area);
    $user = Auth::user();
    $procedures = array();
    $procedure_types = ProcedureType::where('module_id', $module->id)->get();
    foreach ($procedure_types as $procedure_type) {
      switch ($module->id) {
        case 3:
          $title = 'FONDO DE RETIRO POLICIAL SOLIDARIO';
          $procedures = RetirementFund::with(['procedure_modality'])->whereIn('id', $procedure_ids)
            ->wherehas('procedure_modality', function ($query) use ($procedure_type) {
              $query->where('procedure_type_id', $procedure_type->id);
            })
            ->get();
          foreach ($procedures as $procedure) {
            $correlative = explode('/', $procedure->getCorrelative(26)->code);
            $procedure->correlative_code = intval($correlative[0]);
            $procedure->correlative_year = intval($correlative[1]);
          }
          $procedures = $procedures->sortBy('correlative_code')->sortBy('correlative_year');
          break;
        case 4:
          $title = 'CUOTA Y AUXILIO MORTUORIO';
          $procedures = QuotaAidMortuary::with(['procedure_modality'])->whereIn('id', $procedure_ids)
            ->wherehas('procedure_modality', function ($query) use ($procedure_type) {
              $query->where('procedure_type_id', $procedure_type->id);
            })
            ->get();
          foreach ($procedures as $procedure) {
            $correlative = explode('/', $procedure->getCorrelative(40)->code);
            $procedure->correlative_code = intval($correlative[0]);
            $procedure->correlative_year = intval($correlative[1]);
          }
          $procedures = $procedures->sortBy('correlative_code')->sortBy('correlative_year');
          break;
        default:
          return 0;
          break;
      }
      $data = [
        'procedures'  =>  $procedures,
        'title'  =>  $title,
        'subtitle'  =>  $wf_state_from->name,
        'from_area'  =>  $wf_state_from,
        'to_area'  =>  $wf_state_to,
        'user'  =>  $user,
        'year'  =>  date('Y')
      ];
      if ($procedures->count() > 0) {
        if ($wf_state_from->id == 26) {
          $pages[] = \View::make('print_global.send_daa', $data)->render();
        }
        if ($wf_state_from->id == 40) {
          $pages[] = \View::make('print_global.send_daa_quota_aid', $data)->render();
        }
        if ($wf_state_from->id != 40 && $wf_state_from->id != 26) {
          $pages[] = \View::make('print_global.send', $data)->render();
        }
      }
    }
    $pdf = \App::make('snappy.pdf.wrapper');
    $pdf->loadHTML($pages);
    return $pdf->setOption('encoding', 'utf-8')
      ->setOption('margin-bottom', '15mm')
      ->setOrientation('landscape')
      ->setOption('footer-center', 'Pagina [page] de [toPage]')
      ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')
      ->stream("documentos enviados.pdf");
  }
}
