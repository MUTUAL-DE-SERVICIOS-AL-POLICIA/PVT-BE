<?php

namespace Muserpol\Http\Controllers;

use Illuminate\Http\Request;
use Muserpol\Models\Affiliate;
use Muserpol\Models\ProcedureRequirement;
use Muserpol\Models\ProcedureModality;
use Muserpol\Models\Kinship;
use Muserpol\Models\City;
use Muserpol\Models\Degree;
use Auth;
use Log;
use DB;
use Validator;
use Yajra\Datatables\DataTables;
use Muserpol\Models\Address;
use Muserpol\Models\Spouse;
use Muserpol\Models\QuotaAidMortuary\QuotaAidProcedure;
use Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary;
use Muserpol\Models\QuotaAidMortuary\QuotaAidSubmittedDocument;
use Muserpol\Models\QuotaAidMortuary\QuotaAidAdvisorBeneficiary;
use Muserpol\Models\QuotaAidMortuary\QuotaAidLegalGuardian;
use Muserpol\Models\QuotaAidMortuary\QuotaAidAdvisor;
use Muserpol\Models\QuotaAidMortuary\QuotaAidBeneficiary;
use Muserpol\Models\QuotaAidMortuary\QuotaAidBeneficiaryLegalGuardian;
use Muserpol\Helpers\Util;
use Muserpol\Models\ProcedureType;
use Muserpol\Models\AidCommitment;
use Muserpol\User;
use Muserpol\Models\ObservationType;
use Muserpol\Models\Role;
use Muserpol\Models\Workflow\WorkflowState;
use Muserpol\Models\QuotaAidMortuary\QuotaAidCorrelative;
use Muserpol\Models\RetirementFund\RetFunState;
use Muserpol\Helpers\ID;
use Muserpol\Models\QuotaAidMortuary\QuotaAidRecord;
use Muserpol\Models\Workflow\WorkflowRecord;
use Muserpol\Models\Testimony;
use Muserpol\Models\InfoLoan;
use Muserpol\Models\DiscountType;
use Muserpol\Models\ProcedureState;
use Muserpol\Models\FinancialEntity;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;

class QuotaAidMortuaryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */

  public function index()
  {
    return View("quota_aid.index");
  }
  public function getAllQuotaAid(DataTables $datatables)
  {
    $quota_aids = QuotaAidMortuary::with([
      'affiliate:id,identity_card,city_identity_card_id,first_name,second_name,last_name,mothers_last_name,surname_husband,gender,degree_id,degree_id,date_death,date_entry,date_derelict,date_last_contribution',
      'city_start:id,name,first_shortened',
      'wf_state:id,name,first_shortened',
      'procedure_modality:id,name,shortened,procedure_type_id',
      'workflow:id,name',
      'quota_aid_correlative',
    ])->select(
      'id',
      'code',
      'reception_date',
      'affiliate_id',
      'city_start_id',
      'inbox_state',
      'total',
      'wf_state_current_id',
      'procedure_modality_id',
      'quota_aid_procedure_id',
      'workflow_id',
      'total'
    )
      ->where('code', 'not like', '%A')
      ->orderByDesc(DB::raw("split_part(code, '/',1)::integer"));
    return $datatables->eloquent($quota_aids)
      ->addColumn('type', function ($quota_aid) {
        return ProcedureType::find($quota_aid->procedure_modality->procedure_type_id)->name;
      })
      ->editColumn('inbox_state', function ($quota_aid) {
        return $quota_aid->inbox_state ? 'Validado' : 'Pendiente';
      })
      ->editColumn('affiliate.city_identity_card_id', function ($quota_aid) {
        $city = City::find($quota_aid->affiliate->city_identity_card_id);
        return $city ? $city->first_shortened : null;
      })
      ->addColumn('phone_number', function ($quota_aid) {
        $filter = array_filter($quota_aid->quota_aid_beneficiaries->toArray(), function ($value) {
                return $value['type'] == 'S';
            });
            if (sizeof($filter) > 0) {
                return (reset($filter)['phone_number']);
            }
            return null;
        })
        ->addColumn('cell_phone_number', function ($quota_aid) {
            $filter = array_filter($quota_aid->quota_aid_beneficiaries->toArray(), function ($value) {
                return $value['type'] == 'S';
            });
            if (sizeof($filter) > 0) {
                return (reset($filter)['cell_phone_number']);
            }
            return null;
        })
      ->addColumn('file_code', function ($quota_aid) {
        $filter = array_filter($quota_aid->quota_aid_correlative->toArray(), function ($value) {
          return $value['wf_state_id'] == 34;
        });
        if (sizeof($filter) > 0) {
          return (reset($filter)['code']);
        }
        return null;
      })
      ->addColumn('file_date', function ($quota_aid) {
        $filter = array_filter($quota_aid->quota_aid_correlative->toArray(), function ($value) {
          return $value['wf_state_id'] == 34;
        });
        if (sizeof($filter) > 0) {
          return (reset($filter)['date']);
        }
        return null;
      })
      ->addColumn('review_code', function ($quota_aid) {
        $filter = array_filter($quota_aid->quota_aid_correlative->toArray(), function ($value) {
          return $value['wf_state_id'] == 35;
        });
        if (sizeof($filter) > 0) {

          return (reset($filter)['code']);
        }
        return null;
      })
      ->addColumn('review_date', function ($quota_aid) {
        $filter = array_filter($quota_aid->quota_aid_correlative->toArray(), function ($value) {
          return $value['wf_state_id'] == 35;
        });
        if (sizeof($filter) > 0) {
          return (reset($filter)['date']);
        }
        return null;
      })
      ->addColumn('individuals_account_code', function ($quota_aid) {
        $filter = array_filter($quota_aid->quota_aid_correlative->toArray(), function ($value) {
          return $value['wf_state_id'] == 36;
        });
        if (sizeof($filter) > 0) {

          return (reset($filter)['code']);
        }
        return null;
      })
      ->addColumn('individuals_account_date', function ($quota_aid) {
        $filter = array_filter($quota_aid->quota_aid_correlative->toArray(), function ($value) {
          return $value['wf_state_id'] == 36;
        });
        if (sizeof($filter) > 0) {
          return (reset($filter)['date']);
        }
        return null;
      })
      ->addColumn('qualification_code', function ($quota_aid) {
        $filter = array_filter($quota_aid->quota_aid_correlative->toArray(), function ($value) {
          return $value['wf_state_id'] == 37;
        });
        if (sizeof($filter) > 0) {

          return (reset($filter)['code']);
        }
        return null;
      })
      ->addColumn('qualification_date', function ($quota_aid) {
        $filter = array_filter($quota_aid->quota_aid_correlative->toArray(), function ($value) {
          return $value['wf_state_id'] == 37;
        });
        if (sizeof($filter) > 0) {
          return (reset($filter)['date']);
        }
        return null;
      })
      ->addColumn('dictum_code', function ($quota_aid) {
        $filter = array_filter($quota_aid->quota_aid_correlative->toArray(), function ($value) {
          return $value['wf_state_id'] == 39;
        });
        if (sizeof($filter) > 0) {

          return (reset($filter)['code']);
        }
        return null;
      })
      ->addColumn('dictum_date', function ($quota_aid) {
        $filter = array_filter($quota_aid->quota_aid_correlative->toArray(), function ($value) {
          return $value['wf_state_id'] == 39;
        });
        if (sizeof($filter) > 0) {
          return (reset($filter)['date']);
        }
        return null;
      })
      ->addColumn('headship_code', function ($quota_aid) {
        $filter = array_filter($quota_aid->quota_aid_correlative->toArray(), function ($value) {
          return $value['wf_state_id'] == 38;
        });
        if (sizeof($filter) > 0) {

          return (reset($filter)['code']);
        }
        return null;
      })
      ->addColumn('headship_date', function ($quota_aid) {
        $filter = array_filter($quota_aid->quota_aid_correlative->toArray(), function ($value) {
          return $value['wf_state_id'] == 38;
        });
        if (sizeof($filter) > 0) {
          return (reset($filter)['date']);
        }
        return null;
      })
      ->addColumn('resolution_code', function ($quota_aid) {
        $filter = array_filter($quota_aid->quota_aid_correlative->toArray(), function ($value) {
          return $value['wf_state_id'] == 40;
        });
        if (sizeof($filter) > 0) {

          return (reset($filter)['code']);
        }
        return null;
      })
      ->addColumn('resolution_date', function ($quota_aid) {
        $filter = array_filter($quota_aid->quota_aid_correlative->toArray(), function ($value) {
          return $value['wf_state_id'] == 40;
        });
        if (sizeof($filter) > 0) {
          return (reset($filter)['date']);
        }
        return null;
      })
      ->addColumn('action', function ($quota_aid) {
        return Util::getRol()->id != 70? "<a href='/quota_aid/" . $quota_aid->id . "' class='btn btn-default'><i class='fa fa-eye'></i></a>":"";
      })
      ->make(true);
  }


  public function create()
  {
    //
  }

   //funcion para agregar uuid a los registros que tienen null
   public static function add_uuid(){
    $quota_aid_mortuaries=QuotaAidMortuary::withTrashed()->get();
    foreach ($quota_aid_mortuaries as $quota_aid_mortuary) {
        $quota_aid_mortuary->uuid=Uuid::uuid1()->toString();
        $quota_aid_mortuary->save();
    }
    return $quota_aid_mortuary;
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $first_name = $request->beneficiary_first_name;
    $second_name = $request->beneficiary_second_name;
    $last_name = $request->beneficiary_last_name;
    $mothers_last_name = $request->beneficiary_mothers_last_name;
    $surname_husband = $request->surname_husband;
    $identity_card = $request->beneficiary_identity_card;
    $city_id = $request->beneficiary_city_identity_card;
    $birth_date = $request->beneficiary_birth_date;
    $kinship = $request->beneficiary_kinship;
    $gender = $request->beneficiary_gender;

    $requirements = ProcedureRequirement::select('id')->get();
    $affiliate = Affiliate::find($request->affiliate_id);

    $procedure = QuotaAidProcedure::where('hierarchy_id', $affiliate->degree->hierarchy_id)->where('procedure_modality_id', $request->quota_aid_modality)->where('is_enabled',true)->select('id','type_mortuary')->first();
    switch ($procedure->type_mortuary) {
      case 'Titular':
        $affiliate->affiliate_state_id = ID::affiliateState()->fallecido;
        $affiliate->date_death = Util::verifyBarDate($request->date_death) ? Util::parseBarDate($request->date_death) : $request->date_death;
        break;
      case 'Conyuge':
      case 'Viuda':
        if($request->quota_aid_modality == 14 || $request->quota_aid_modality == 15){
          $affiliate->affiliate_state_id = ID::affiliateState()->jubilado;
        }
        $spouse = Spouse::where('affiliate_id', $affiliate->id)->first();
        if (!isset($spouse->id))
          $spouse = new Spouse();
        $spouse->user_id = Auth::user()->id;
        $spouse->affiliate_id = $request->affiliate_id;
        $spouse->city_identity_card_id = $request->spouse_city_identity_card_id;
        $spouse->identity_card = $request->spouse_identity_card;
        $spouse->registration = ''; //$request->spouse_registration;
        $spouse->last_name = $request->spouse_last_name;
        $spouse->mothers_last_name = $request->spouse_mothers_last_name;
        $spouse->first_name = $request->spouse_first_name;
        $spouse->second_name = $request->spouse_second_name;
        $spouse->surname_husband = $request->spouse_surname_husband;
        $spouse->civil_status = $request->spouse_civil_status;
        $spouse->birth_date = Util::verifyBarDate($request->spouse_birth_date) ?  Util::parseBarDate($request->spouse_birth_date) : $request->spouse_birth_date;
        $spouse->city_birth_id = $request->spouse_city_birth_id;
        $spouse->date_death = Util::verifyBarDate($request->spouse_date_death) ?  Util::parseBarDate($request->spouse_date_death) : $request->spouse_date_death;
        $spouse->reason_death = $request->spouse_reason_death;
        $spouse->save();

        break;
      default:
        return "error modality not found";
        break;
    }
    $affiliate->save();

    // if ($request->quota_aid_modality == 14) { //fallecimiento conyugue
    //   $procedure = QuotaAidProcedure::where('hierarchy_id', $affiliate->degree->hierarchy_id)->where('procedure_modality_id', $request->quota_aid_modality)->where('is_enabled',true)->select('id')->first();
    // }
    $validator = Validator::make($request->all(), [
      //'applicant_first_name' => 'required|max:5',
    ]);
    //custom this validator
    $validator->after(function ($validator) {
      if (false)
        $validator->errors()->add('Modalidad', 'el campo modalidad no puede ser tramitada este mes');
    });
    if ($validator->fails()) {
      return $validator->errors();
    }

    $rules = [];
    $biz_rules = [];

    // $has_quota_aid = false;
    // $quota_aid = QuotaAidMortuary::where('affiliate_id',$affiliate->id)->where('code','NOT LIKE','%A')->first();
    // if(isset($quota_aid->id)) {
    //     $has_quota_aid = true;
    //     return $quota_aid;
    //     return "ya tiene un trámite";
    //     // $biz_rules = [
    //     //     'quota_aid_double'
    //     // ];
    //     // $code = Util::getNextCode ("");
    // }
    // else {
    //     $quota_aid = QuotaAidMortuary::select('id','code')->orderBy('id','desc')->first();
    //     $code = Util::getNextCode ($quota_aid->code);
    // }

    $nextcode = QuotaAidMortuary::where('affiliate_id', $request->affiliate_id)->where('code', 'like', '%A')->first();

    if (isset($nextcode->id)) {
      $code = str_replace("A", "", $nextcode->code);
    } else {
      if ($request->procedure_type_id == 3) {

        //$quota_aid_code = Util::getLastCodeQM(QuotaAidMortuary::class);
        $quota_aid_code = Util::getLastCodeQM();
        $code = Util::getNextCode($quota_aid_code, '179');
      } elseif ($request->procedure_type_id == 4) {

        $quota_aid_code = Util::getLastCodeAM(QuotaAidMortuary::class);
        $code = Util::getNextCode($quota_aid_code, '268');
      }
    }

    $modality = ProcedureModality::find($request->quota_aid_modality);

    $quota_aid = new QuotaAidMortuary();
    //$this->authoriza('create', $quota_aid);
    $quota_aid->user_id = Auth::user()->id;
    $quota_aid->affiliate_id = $request->affiliate_id;
    $quota_aid->procedure_modality_id = $request->quota_aid_modality;
    // if ($request->quota_aid_modality == 14) { //fallecimiento conyugue
    //   $quota_aid->quota_aid_procedure_id = $procedure->id;
    // }
    if($procedure->type_mortuary == 'Conyuge'){
      $quota_aid->quota_aid_procedure_id = $procedure->id;
    }
    $quota_aid->city_start_id = Auth::user()->city_id;
    $quota_aid->city_end_id = Auth::user()->city_id;
    $quota_aid->code = $code;
    $quota_aid->uuid = Uuid::uuid1()->toString();
    $quota_aid->reception_date = date('Y-m-d');
    if($request->procedure_type_id == 3){
       $quota_aid->workflow_id = 5;
    }elseif ($request->procedure_type_id == 4){
      $quota_aid->workflow_id = 6;
    }
    $wf_state = WorkflowState::where('role_id', Util::getRol()->id)->whereIn('sequence_number', [0, 1])->first();
    if (!$wf_state) {
      return;
    }
    $quota_aid->wf_state_current_id = $wf_state->id;
    $quota_aid->subtotal = 0;
    $quota_aid->total = 0;
    $quota_aid->procedure_state_id = 1;
    $quota_aid->save();

    foreach ($requirements  as  $requirement) {
      if ($request->input('document' . $requirement->id) == 'checked') {
        $submit = new QuotaAidSubmittedDocument();
        $submit->quota_aid_mortuary_id = $quota_aid->id;
        $submit->procedure_requirement_id = $requirement->id;
        $submit->reception_date = date('Y-m-d');
        $submit->comment = $request->input('comment' . $requirement->id);
        $submit->save();
      }
    }

    if ($request->aditional_requirements) {
      foreach ($request->aditional_requirements  as  $requirement) {
        $submit = new QuotaAidSubmittedDocument();
        $submit->quota_aid_mortuary_id = $quota_aid->id;
        $submit->procedure_requirement_id = $requirement;
        $submit->reception_date = date('Y-m-d');
        $submit->comment = "";
        $submit->save();
      }
    }

    $account_type = $request->input('accountType');

    $beneficiary = new QuotaAidBeneficiary();
    $beneficiary->quota_aid_mortuary_id = $quota_aid->id;
    $beneficiary->city_identity_card_id = $request->applicant_city_identity_card;
    $beneficiary->kinship_id = $request->applicant_kinship;
    $beneficiary->identity_card = mb_strtoupper($request->applicant_identity_card);
    $beneficiary->last_name = mb_strtoupper($request->applicant_last_name);
    $beneficiary->mothers_last_name = mb_strtoupper($request->applicant_mothers_last_name);
    $beneficiary->first_name = mb_strtoupper($request->applicant_first_name);
    $beneficiary->second_name = mb_strtoupper($request->applicant_second_name);
    $beneficiary->surname_husband = mb_strtoupper($request->applicant_surname_husband);
    $beneficiary->gender = $request->applicant_gender;
    $beneficiary->phone_number = trim(implode(",", $request->applicant_phone_number));
    $beneficiary->cell_phone_number = trim(implode(",", $request->applicant_cell_phone_number));
    $beneficiary->type = "S";
    $beneficiary->save();

    if ($account_type == ID::applicant()->advisor_id) {
      $advisor = new QuotaAidAdvisor();
      //$advisor->retirement_fund_id = $retirement_found->id;
      $advisor->city_identity_card_id = $request->applicant_city_identity_card;
      $advisor->kinship_id = null;
      $advisor->identity_card = $request->applicant_identity_card;
      $advisor->last_name = $request->applicant_last_name;
      $advisor->mothers_last_name = $request->applicant_mothers_last_name;
      $advisor->first_name = $request->applicant_first_name;
      $advisor->second_name = $request->applicant_second_name;
      $advisor->surname_husband = $request->applicant_surname_husband;
      $advisor->gender = "M";
      $advisor->phone_number = trim(implode(",", $request->applicant_phone_number));
      $advisor->cell_phone_number = trim(implode(",", $request->applicant_cell_phone_number));
      $advisor->name_court = $request->advisor_name_court;
      $advisor->resolution_number = $request->advisor_resolution_number;
      $advisor->resolution_date = $request->advisor_resolution_date;
      $advisor->type = "Natural";
      $advisor->save();

      $advisor_beneficiary = new QuotaAidAdvisorBeneficiary();
      $advisor_beneficiary->quota_aid_beneficiary_id = $beneficiary->id;
      $advisor_beneficiary->quota_aid_advisor_id = $advisor->id;
      $advisor_beneficiary->save();
    }

    if ($account_type == ID::applicant()->legal_guardian_id) {

      $legal_guardian = new QuotaAidLegalGuardian();
      $legal_guardian->city_identity_card_id = $request->legal_guardian_identity_card_id;
      $legal_guardian->identity_card = strtoupper(trim($request->legal_guardian_identity_card));
      $legal_guardian->last_name = strtoupper(trim($request->legal_guardian_last_name));
      $legal_guardian->mothers_last_name = strtoupper(trim($request->legal_guardian_mothers_last_name));
      $legal_guardian->first_name = strtoupper(trim($request->legal_guardian_first_name));
      $legal_guardian->second_name = strtoupper(trim($request->legal_guardian_second_name));
      $legal_guardian->surname_husband = strtoupper(trim($request->legal_guardian_surname_husband));
      $legal_guardian->phone_number = trim(implode(",", $request->applicant_phone_number ?? []));
      $legal_guardian->cell_phone_number = trim(implode(",", $request->applicant_cell_phone_number ?? []));
      $legal_guardian->number_authority = $request->legal_guardian_number_authority;
      $legal_guardian->notary_of_public_faith = $request->legal_guardian_notary_of_public_faith;
      $legal_guardian->notary = $request->legal_guardian_notary;
      //$legal_guardian->date_authority = Util::verifyBarDate($request->legal_guardian_date_authority) ? Util::parseBarDate($request->legal_guardian_date_authority) : $request->legal_guardian_date_authority;
      $legal_guardian->gender = $request->legal_guardian_gender;
      $legal_guardian->save();


      $beneficiary_legal_guardian = new QuotaAidBeneficiaryLegalGuardian();
      $beneficiary_legal_guardian->quota_aid_beneficiary_id = $beneficiary->id;
      $beneficiary_legal_guardian->quota_aid_legal_guardian_id = $legal_guardian->id;
      $beneficiary_legal_guardian->save();
      //$beneficiary->type = "N";
    }


    if ($request->beneficiary_city_address_id || $request->beneficiary_zone || $request->beneficiary_street || $request->beneficiary_number_address) {
      $address = new Address();
      $address->city_address_id = $request->beneficiary_city_address_id ?? 1;
      $address->zone = $request->beneficiary_zone;
      $address->street = $request->beneficiary_street;
      $address->number_address = $request->beneficiary_number_address;
      $address->save();

      $beneficiary->address()->save($address);
    }

    // crear relacion
    //borrar esto
    // $address_rel = new RetFunAddressBeneficiary();
    // $address_rel->ret_fun_beneficiary_id = $beneficiary->id;
    // $address_rel->address_id = $address->id;
    // $address_rel->save();



    for ($i = 0; is_array($first_name) &&  $i < sizeof($first_name); $i++) {
      if ($first_name[$i] != "" && $last_name[$i] != "") {
        $beneficiary = new QuotaAidBeneficiary();
        $beneficiary->quota_aid_mortuary_id = $quota_aid->id;
        $beneficiary->city_identity_card_id = $city_id[$i];
        $beneficiary->kinship_id = $kinship[$i];
        $beneficiary->identity_card = strtoupper($identity_card[$i]);
        $beneficiary->last_name = strtoupper($last_name[$i]);
        $beneficiary->mothers_last_name = strtoupper($mothers_last_name[$i]);
        $beneficiary->first_name = strtoupper($first_name[$i]);
        $beneficiary->second_name = strtoupper($second_name[$i]);
        $beneficiary->surname_husband = strtoupper($surname_husband[$i]);
        $beneficiary->birth_date = Util::verifyBarDate($birth_date[$i]) ? Util::parseBarDate($birth_date[$i]) : $birth_date[$i];
        $beneficiary->gender = strtoupper(trim($gender[$i]));
        $beneficiary->type = "N";
        $beneficiary->save();
      }
    }

    $data = [];
    return redirect('quota_aid/' . $quota_aid->id);
  }

  /**
   * Display the specified resource.
   *
   * @param  \Muserpol\QuotaAidMortuary  $quotaAidMortuary
   * @return \Illuminate\Http\Response
   */
  public function storeLegalReview(Request $request, $id)
  {
    $quota_id = QuotaAidMortuary::find($id);
    // $this->authorize('update',new RetFunSubmittedDocument);

    foreach ($request->submit_documents as $document_array) {
      $document = $document_array[0];
      $submit_document = QuotaAidSubmittedDocument::find($document['submit_document_id']);
      $submit_document->is_valid = $document['status'];
      $submit_document->comment = $document['comment'];
      $submit_document->save();
    }
    return $request;
  }
  /**
   * Display the specified resource.
   *
   * @param  \Muserpol\RetirementFund  $retirementFund
   * @return \Illuminate\Http\Response
   */
  //public function show(RetirementFund $retirementFund)
  public function show($id)
  {
    $quota_aid = QuotaAidMortuary::find($id);
    //$this->authorize('view', $retirement_fund);
    $affiliate = Affiliate::find($quota_aid->affiliate_id);
    if (!sizeOf($affiliate->address) > 0) {
      $affiliate->address[] = array('zone' => null, 'street' => null, 'number_address' => null, 'city_address_id' => null);
    }
    $affiliate->phone_number = explode(',', $affiliate->phone_number);
    $affiliate->cell_phone_number = explode(',', $affiliate->cell_phone_number);

    $beneficiaries = QuotaAidBeneficiary::where('quota_aid_mortuary_id', $quota_aid->id)->with(['kinship', 'city_identity_card'])->orderByDesc('type')->orderBy('id')->get();

    foreach ($beneficiaries as $b) {
      $b->phone_number = explode(',', $b->phone_number);
      $b->cell_phone_number = explode(',', $b->cell_phone_number);
      if (!sizeOf($b->address) > 0 && $b->type == 'S') {
        $b->address[] = array('zone' => null, 'street' => null, 'number_address' => null);
      }
      //1 => tutor
      //2 => Apoderado
      $b->legal_representative = null;
      if ($beneficiary_advisor = $b->quota_aid_advisors->first()) {
        $b->legal_representative = 1;
        $b->advisor_identity_card = $beneficiary_advisor->identity_card;
        $b->advisor_city_identity_card_id = $beneficiary_advisor->city_identity_card_id;
        $b->advisor_first_name = $beneficiary_advisor->first_name;
        $b->advisor_second_name = $beneficiary_advisor->second_name;
        $b->advisor_last_name = $beneficiary_advisor->last_name;
        $b->advisor_mothers_last_name = $beneficiary_advisor->mothers_last_name;
        $b->advisor_surname_husband = $beneficiary_advisor->surname_husband;
        $b->advisor_birth_date = $beneficiary_advisor->birth_date;
        $b->advisor_gender = $beneficiary_advisor->gender;
        $b->advisor_name_court = $beneficiary_advisor->name_court;
        $b->advisor_resolution_number = $beneficiary_advisor->resolution_number;
        $b->advisor_resolution_date = $beneficiary_advisor->resolution_date;
      }
      if ($beneficiary_legal_guardian = $b->quota_aid_legal_guardians->first()) {
        $b->legal_representative = 2;
        $b->legal_guardian_identity_card = $beneficiary_legal_guardian->identity_card;
        $b->legal_guardian_city_identity_card_id = $beneficiary_legal_guardian->city_identity_card_id;
        $b->legal_guardian_first_name = $beneficiary_legal_guardian->first_name;
        $b->legal_guardian_second_name = $beneficiary_legal_guardian->second_name;
        $b->legal_guardian_last_name = $beneficiary_legal_guardian->last_name;
        $b->legal_guardian_mothers_last_name = $beneficiary_legal_guardian->mothers_last_name;
        $b->legal_guardian_surname_husband = $beneficiary_legal_guardian->surname_husband;
        $b->legal_guardian_gender = $beneficiary_legal_guardian->gender;
        $b->legal_guardian_number_authority = $beneficiary_legal_guardian->number_authority;
        $b->legal_guardian_notary_of_public_faith = $beneficiary_legal_guardian->notary_of_public_faith;
        $b->legal_guardian_notary = $beneficiary_legal_guardian->notary;
        $b->legal_guardian_date_authority = $beneficiary_legal_guardian->date_authority;
      }
    }

    $applicant = QuotaAidBeneficiary::where('type', 'S')->where('quota_aid_mortuary_id', $quota_aid->id)->first();

    if (QuotaAidAdvisorBeneficiary::where('quota_aid_beneficiary_id', $applicant->id)->first()){
      $beneficiary_avdisor = QuotaAidAdvisorBeneficiary::where('quota_aid_beneficiary_id', $applicant->id)->first();
    }
    if (isset($beneficiary_avdisor->id)){
      $advisor = QuotaAidAdvisor::find($beneficiary_avdisor->quota_aid_advisor_id);
    }else{
      $advisor = new QuotaAidAdvisor();
    }


    $beneficiary_guardian = QuotaAidBeneficiaryLegalGuardian::where('quota_aid_beneficiary_id', $applicant->id)->first();

    if (isset($beneficiary_guardian->id))
      $guardian = QuotaAidLegalGuardian::find($beneficiary_guardian->quota_aid_legal_guardian_id);
    else
      $guardian = new QuotaAidLegalGuardian();
    $procedures_modalities_ids = ProcedureModality::join('procedure_types', 'procedure_types.id', '=', 'procedure_modalities.procedure_type_id')
      ->where('procedure_types.module_id', '=', 4)
      ->orWhere('procedure_types.module_id', '=', 5)
      ->get()
      ->pluck('id'); //3 por el module 3 de fondo de retiro

    //return $procedures_modalities_ids;
    $procedures_modalities = ProcedureModality::whereIn('procedure_type_id', $procedures_modalities_ids)->get();
    $file_modalities = ProcedureModality::get();

    $requirements = ProcedureRequirement::where('procedure_modality_id', $quota_aid->procedure_modality_id)->whereNull('deleted_at')->get();

    $documents = QuotaAidSubmittedDocument::where('quota_aid_mortuary_id', $id)->orderBy('procedure_requirement_id', 'ASC')->get();
    $cities = City::get();
    $kinships = Kinship::get();

    $cities_pluck = City::all()->pluck('first_shortened', 'id');
    $birth_cities = City::all()->pluck('name', 'id');

    $states = ProcedureState::all();
    $financial_entities = FinancialEntity::all()->pluck('name', 'id');


    $quota_aid_records =  QuotaAidRecord::where('quota_aid_id', $id)->orderBy('id', 'desc')->get();


    ///proof
    $user = User::find(Auth::user()->id);
    $procedure_types = ProcedureType::where('module_id', 4)->get();
    $procedure_requirements = ProcedureRequirement::select('procedure_requirements.id', 'procedure_documents.name as document', 'number', 'procedure_modality_id as modality_id')
      ->leftJoin('procedure_documents', 'procedure_requirements.procedure_document_id', '=', 'procedure_documents.id')
      ->orderBy('procedure_requirements.procedure_modality_id', 'ASC')
      ->orderBy('procedure_requirements.number', 'ASC')
      ->get();

    $modalities = ProcedureModality::where('procedure_type_id', '<=', '2')->select('id', 'name', 'procedure_type_id')->get();

    $observation_types = ObservationType::where('module_id', 4)->get();

    //selected documents
    $submitted = QuotaAidSubmittedDocument::select('quota_aid_submitted_documents.id', 'procedure_requirements.number', 'quota_aid_submitted_documents.procedure_requirement_id', 'quota_aid_submitted_documents.comment', 'quota_aid_submitted_documents.is_valid')
      ->leftJoin('procedure_requirements', 'quota_aid_submitted_documents.procedure_requirement_id', '=', 'procedure_requirements.id')
      ->orderby('procedure_requirements.number', 'ASC')
      ->where('quota_aid_submitted_documents.quota_aid_mortuary_id', $id);

    // ->pluck('ret_fun_submitted_documents.procedure_requirement_id','procedure_requirements.number');
    /**for validate doc*/
    $rol = Util::getRol();
    $module = Role::find($rol->id)->module;
    $wf_current_state = WorkflowState::where('role_id', $rol->id)->where('module_id', '=', $module->id)->first();

    $can_validate = optional($wf_current_state)->id == $quota_aid->wf_state_current_id;
    $can_cancel = ($quota_aid->user_id == $user->id && $quota_aid->inbox_state == true);

    // workflow record
    $workflow_records = $quota_aid->wf_records()->orderBy('date', 'desc')->get();
    $first_wf_state = QuotaAidRecord::whereRaw("message like '%creo el Tr%'")->first();
    if ($first_wf_state) {
      $re = '/(?<= usuario )(.*)(?= cr.* )/mi';
      $str = $first_wf_state->message;
      preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
      $user_name = $matches[0][0];
      $rol = User::where('username', '=', $user_name)->first()->roles->first();
      $first_wf_state = WorkflowState::where('role_id', $rol->id)->first();
    }


    // dd($first_wf_state);

    $wf_states = WorkflowState::where('module_id', '=', $module->id)->where('sequence_number', '>', ($first_wf_state->sequence_number ?? 1))->orderBy('sequence_number')->get();

    //$correlatives = QuotaAidCorrelative::where('quota_aid_mortuary_id',$quota_aid->id)->get();

    $steps = [];
    //$data = $retirement_fund->getReceptionSummary();
    $is_editable = 1;
    //if(isset($quota_aid->id))
    //$is_editable = ID::getNonEditableId();


    $wf_sequences_back = DB::table("wf_states")
      ->where("wf_states.module_id", "=", $module->id)
      ->where('wf_states.sequence_number', '<', WorkflowState::find($quota_aid->wf_state_current_id)->sequence_number)
      ->whereNull('wf_states.deleted_at')
      ->select(
        'wf_states.id as wf_state_id',
        'wf_states.first_shortened as wf_state_name'
      )
      ->get();
    
      //devolver hacia adelante
      $return_sequence = $quota_aid->wf_records->first();
      if($return_sequence <> null && $return_sequence->record_type_id == 4 && $return_sequence->wf_state_id == $quota_aid->wf_state_current_id){
          $wf_back = DB::table("wf_states")
          ->where("wf_states.module_id", $module->id)
          ->where('wf_states.id', $return_sequence->old_wf_state_id)
          ->select(
              'wf_states.id as wf_state_id',
              'wf_states.first_shortened as wf_state_name'
          )
          ->get();
          $wf_sequences_back = $wf_sequences_back->merge($wf_back);
      }
      //

    //summary individual accounts
    /*en el caso de la modalidad Fallecimiento del titular ID 13 y sea nulo date_last_contribution ya que es requerido para la modalidad */
    $quota_aid_dates = $quota_aid->procedure_modality_id == 13 && is_null($affiliate->date_last_contribution)? []:$affiliate->getContributionsWithTypeQuotaAid($id);
    $quota_aid_contributions = $affiliate->getQuotaAidContributions($id);
    $total_dates = Util::sumTotalContributions($quota_aid_dates);
    $dates = array(
      'id' => 0,
      'dates' => $quota_aid_dates,
      'name' => "PERIODO DE APORTES CONSIDERADOS PARA EL CÁLCULO DEL BENEFICIO",
      'operator' => '**',
      'description' => "PERIODO DE APORTES CONSIDERADOS PARA EL CÁLCULO DEL BENEFICIO",
      'years' => intval($total_dates / 12),
      'months' => $total_dates % 12,
    );
    $discounts = $quota_aid->discount_types;
    $data = [
      'quota_aid' => $quota_aid,
      'affiliate' =>  $affiliate,
      'beneficiaries' =>  $beneficiaries,
      'applicant' => $applicant,
      'advisor'  =>  $advisor,
      'legal_guardian'    =>  $guardian,
      'procedure_modalities' => $procedures_modalities,
      'file_modalities'   =>  $file_modalities,
      'documents' => $documents,
      'cities'    =>  $cities,
      'kinships'   =>  $kinships,
      'cities_pluck' => $cities_pluck,
      'birth_cities' => $birth_cities,
      'financial_entities'    =>  $financial_entities,
      'states'    =>  $states,
      'quota_aid_records' => $quota_aid_records,
      'requirements'  =>  $procedure_requirements,
      'user'  =>  $user,
      'procedure_types'   =>  $procedure_types,
      'modalities'    =>  $modalities,
      'observation_types' => $observation_types,
      //'observations' => $retirement_fund->ret_fun_observations,
      'submitted' =>  $submitted->pluck('quota_aid_submitted_documents.procedure_requirement_id', 'procedure_requirements.number'),
      'submit_documents' => $submitted->get(),
      'can_validate' =>  $can_validate,
      'can_cancel' =>  $can_cancel,
      'workflow_records' =>  $workflow_records,
      'first_wf_state' =>  $first_wf_state,
      'wf_states' =>  $wf_states,
      'is_editable'  =>  $is_editable,
      'wf_sequences_back' => $wf_sequences_back,
      'dates' => $dates,
      'discounts' => $discounts
    ];
    //return $procedures_modalities;

    return view('quota_aid.show', $data);
  }
  public function updateBeneficiaries(Request $request, $id)
  {
    $i = 0;
    $ben = 0;
    $beneficiaries_array_request = [];
    foreach (array_pluck($request->all(), 'id') as $key => $value) {
      if ($value) {
        array_push($beneficiaries_array_request, $value);
      }
    }
    /* delete beneficiaries */
    $beneficiaries = QuotaAidMortuary::find($id)->quota_aid_beneficiaries;
    foreach ($beneficiaries as $key => $ben) {
      $index = array_search($ben->id, $beneficiaries_array_request);
      if ($index === false) {
        $ben->delete();
      }
    }
    $quota_aid = QuotaAidMortuary::find($id);
    /*update info beneficiaries*/
    $beneficiaries = QuotaAidMortuary::find($id)->quota_aid_beneficiaries->toArray();
    foreach ($request->all() as $key => $new_ben) {
      $found = [];
      if (isset($new_ben['id'])) {
        $found = array_filter($beneficiaries, function ($var) use ($new_ben) {
          return ($var['id'] == $new_ben['id']);
        });
      }
      if ($found) {
        $old_ben = QuotaAidBeneficiary::find($new_ben['id']);
        $old_ben->city_identity_card_id = $new_ben['city_identity_card_id'];
        $old_ben->kinship_id = $new_ben['kinship_id'];
        $old_ben->identity_card = mb_strtoupper(trim($new_ben['identity_card']));
        $old_ben->last_name = mb_strtoupper(trim($new_ben['last_name']));
        $old_ben->mothers_last_name = mb_strtoupper(trim($new_ben['mothers_last_name']));
        $old_ben->first_name = mb_strtoupper(trim($new_ben['first_name']));
        $old_ben->second_name = mb_strtoupper(trim($new_ben['second_name']));
        $old_ben->surname_husband = mb_strtoupper(trim($new_ben['surname_husband']));
        $old_ben->birth_date = Util::verifyBarDate($new_ben['birth_date']) ? Util::parseBarDate($new_ben['birth_date']) : $new_ben['birth_date'];
        $old_ben->gender = $new_ben['gender'];
        $old_ben->state = $new_ben['state'] ?? false;
        if (is_null($new_ben['legal_representative'])) {
          if ($ben_advisor = $old_ben->quota_aid_advisors->first()) {
            // delete
            $advisor_beneficiary = QuotaAidAdvisorBeneficiary::where('quota_aid_beneficiary_id', $old_ben->id)->where('quota_aid_advisor_id', $ben_advisor->id)->first();
            $advisor_beneficiary->delete();
          }
          if ($ben_legal_guardian = $old_ben->quota_aid_legal_guardians->first()) {
            //delete
            $ben_legal_guardian = QuotaAidBeneficiaryLegalGuardian::where('quota_aid_beneficiary_id', $old_ben->id)->where('quota_aid_legal_guardian_id', $ben_legal_guardian->id)->first();
            $ben_legal_guardian->delete();
          }
        } else {
          switch ($new_ben['legal_representative']) {
              //tutor
            case 1:
              //exists
              if ($ben_advisor = $old_ben->quota_aid_advisors->first()) { } else {
                $ben_advisor = new QuotaAidAdvisor();
              }
              $ben_advisor->city_identity_card_id = isset($new_ben['advisor_city_identity_card_id']) ? intval($new_ben['advisor_city_identity_card_id']) : null;
              $ben_advisor->kinship_id = null;
              $ben_advisor->identity_card = $new_ben['advisor_identity_card'] ?? null;
              $ben_advisor->last_name = strtoupper(trim($new_ben['advisor_last_name'] ?? null));
              $ben_advisor->mothers_last_name = strtoupper(trim($new_ben['advisor_mothers_last_name'] ?? null));
              $ben_advisor->first_name = strtoupper(trim($new_ben['advisor_first_name'] ?? null));
              $ben_advisor->second_name = strtoupper(trim($new_ben['advisor_second_name'] ?? null));
              $ben_advisor->surname_husband = strtoupper(trim($new_ben['advisor_surname_husband'] ?? null));
              $ben_advisor->gender = strtoupper(trim($new_ben['advisor_gender'] ?? null));
              $ben_advisor->birth_date = Util::verifyBarDate($new_ben['advisor_birth_date']) ? Util::parseBarDate($new_ben['advisor_birth_date']) : $new_ben['advisor_birth_date'];
              // $ben_advisor->phone_number = trim(implode(",", $new_ben['advisor_phone_number'] ?? []));
              // $ben_advisor->cell_phone_number = trim(implode(",", $new_ben['advisor_cell_phone_number'] ?? []));
              $ben_advisor->name_court = $new_ben['advisor_name_court'] ?? null;
              $ben_advisor->resolution_number = $new_ben['advisor_resolution_number'] ?? null;
              $ben_advisor->resolution_date = isset($new_ben['advisor_resolution_date']) ? (Util::verifyBarDate($new_ben['advisor_resolution_date']) ? Util::parseBarDate($new_ben['advisor_resolution_date']) : $new_ben['advisor_resolution_date']) : null;
              $ben_advisor->type = "Natural";
              $ben_advisor->save();
              if ($old_ben->quota_aid_advisors->first()) { } else {
                $advisor_beneficiary = new QuotaAidAdvisorBeneficiary();
                $advisor_beneficiary->quota_aid_beneficiary_id = $old_ben->id;
                $advisor_beneficiary->quota_aid_advisor_id = $ben_advisor->id;
                $advisor_beneficiary->save();
              }

              break;
              //apoderado
            case 2:
              if ($ben_legal_guardian = $old_ben->quota_aid_legal_guardians->first()) { } else {
                $ben_legal_guardian = new QuotaAidLegalGuardian();
                // $ben_legal_guardian->retirement_fund_id = $retirement_fund->id; // is necessary?
              }
              $ben_legal_guardian->identity_card = strtoupper(trim($new_ben['legal_guardian_identity_card'] ?? null));
              $ben_legal_guardian->city_identity_card_id = isset($new_ben['legal_guardian_city_identity_card_id']) ? intval($new_ben['legal_guardian_city_identity_card_id']) : null;
              $ben_legal_guardian->first_name = strtoupper(trim($new_ben['legal_guardian_first_name'] ?? null));
              $ben_legal_guardian->second_name = strtoupper(trim($new_ben['legal_guardian_second_name'] ?? null));
              $ben_legal_guardian->last_name = strtoupper(trim($new_ben['legal_guardian_last_name'] ?? null));
              $ben_legal_guardian->mothers_last_name = strtoupper(trim($new_ben['legal_guardian_mothers_last_name'] ?? null));
              $ben_legal_guardian->surname_husband = strtoupper(trim($new_ben['legal_guardian_surname_husband'] ?? null));
              /** !! TODO
               * phone and cellphone numbers
               */
              // $ben_legal_guardian->phone_number = trim(implode(",", $request->applicant_phone_number ?? []));
              // $ben_legal_guardian->cell_phone_number = trim(implode(",", $request->applicant_cell_phone_number ?? []));

              $ben_legal_guardian->gender = $new_ben['legal_guardian_gender'] ?? null;
              $ben_legal_guardian->number_authority = $new_ben['legal_guardian_number_authority'] ?? null;
              $ben_legal_guardian->notary_of_public_faith = $new_ben['legal_guardian_notary_of_public_faith'] ?? null;
              $ben_legal_guardian->notary = $new_ben['legal_guardian_notary'] ?? null;
              $ben_legal_guardian->date_authority = isset($new_ben['legal_guardian_date_authority']) ? (Util::verifyBarDate($new_ben['legal_guardian_date_authority']) ? Util::parseBarDate($new_ben['legal_guardian_date_authority']) : $new_ben['legal_guardian_date_authority']) : null;
              $ben_legal_guardian->save();
              if ($old_ben->quota_aid_legal_guardians->first()) { } else {
                $ben_legal_guardian_new = new QuotaAidBeneficiaryLegalGuardian();
                $ben_legal_guardian_new->quota_aid_beneficiary_id = $old_ben->id;
                $ben_legal_guardian_new->quota_aid_legal_guardian_id = $ben_legal_guardian->id;
                $ben_legal_guardian_new->save();
              }
              break;
            default:
              # code...
              break;
          }
        }

        if ($old_ben->type == 'S' && $old_ben->kinship_id == 1 && ($quota_aid->procedure_modality_id == 15 || $quota_aid->procedure_modality_id == 14)) {
          $update_affilaite = Affiliate::find($quota_aid->affiliate_id);
          $update_affilaite->identity_card = $old_ben->identity_card;
          $update_affilaite->first_name = $old_ben->first_name;
          $update_affilaite->second_name = $old_ben->second_name;
          $update_affilaite->last_name = $old_ben->last_name;
          $update_affilaite->mothers_last_name = $old_ben->mothers_last_name;
          $update_affilaite->gender = $old_ben->gender;
          $update_affilaite->birth_date = Util::verifyBarDate($old_ben->birth_date) ? Util::parseBarDate($old_ben->birth_date) : $old_ben->birth_date;
          $update_affilaite->city_identity_card_id = $old_ben->city_identity_card_id;
          $update_affilaite->surname_husband = $old_ben->surname_husband;
          $update_affilaite->save();
        }
        if ($old_ben->type == 'S') {
          $old_ben->phone_number = trim(implode(",", $new_ben['phone_number']));
          $old_ben->cell_phone_number = trim(implode(",", $new_ben['cell_phone_number']));

          //$old_ben->cell_phone_number = trim(implode(",", $new_ben['cell_phone_number']));
          /*Actualizar direccion  */
          if (sizeOf($old_ben->address) > 0) {
            $address_id = $old_ben->address()->first()->id;
            $address = Address::find($address_id);
            if ($new_ben['address'][0]['zone'] || $new_ben['address'][0]['street'] || $new_ben['address'][0]['number_address']) {
              $address->city_address_id = $new_ben['address'][0]['city_address_id'] ?? 1;
              $address->zone = $new_ben['address'][0]['zone'];
              $address->street = $new_ben['address'][0]['street'];
              $address->number_address = $new_ben['address'][0]['number_address'];
              $address->save();
              if (($quota_aid->procedure_modality_id == 14 || $quota_aid->procedure_modality_id == 15) && $old_ben->kinship_id == 1) {
                $update_affilaite = Affiliate::find($quota_aid->affiliate_id);
                if ($update_affilaite->address->contains($address->id)) { } else {
                  $update_affilaite->address()->save($address);
                }
              }
            } else {
              if (($quota_aid->procedure_modality_id == 14 || $quota_aid->procedure_modality_id == 15) && $old_ben->kinship_id == 1) {
                $update_affilaite = Affiliate::find($quota_aid->affiliate_id);
                $update_affilaite->address()->detach($address->id);
              }
              $old_ben->address()->detach($address->id);
              $address->delete();
            }
          } else {
            if ($new_ben['address']) {
              $address = new Address();
              $address->city_address_id = $new_ben['address'][0]['city_address_id'] ?? 1;
              $address->zone = $new_ben['address'][0]['zone'];
              $address->street = $new_ben['address'][0]['street'];
              $address->number_address = $new_ben['address'][0]['number_address'];
              $address->save();
              $old_ben->address()->save($address);
              if (($quota_aid->procedure_modality_id == 14 || $quota_aid->procedure_modality_id == 15) && $old_ben->kinship_id == 1) {
                $update_affilaite = Affiliate::find($quota_aid->affiliate_id);
                $update_affilaite->address()->save($address);
              }
            }
          }
        }
        $old_ben->save();
      } else {
        $beneficiary = new QuotaAidBeneficiary();
        $beneficiary->quota_aid_mortuary_id = $id;
        $beneficiary->city_identity_card_id = $new_ben['city_identity_card_id'];
        $beneficiary->kinship_id = $new_ben['kinship_id'];
        $beneficiary->identity_card = mb_strtoupper(trim($new_ben['identity_card']));
        $beneficiary->last_name = mb_strtoupper(trim($new_ben['last_name']));
        $beneficiary->mothers_last_name = mb_strtoupper(trim($new_ben['mothers_last_name']));
        $beneficiary->first_name = mb_strtoupper(trim($new_ben['first_name']));
        $beneficiary->second_name = mb_strtoupper(trim($new_ben['second_name']));
        $beneficiary->surname_husband = mb_strtoupper(trim($new_ben['surname_husband']));
        $beneficiary->birth_date = Util::verifyBarDate($new_ben['birth_date']) ? Util::parseBarDate($new_ben['birth_date']) : $new_ben['birth_date'];
        $beneficiary->gender = $new_ben['gender'];
        $beneficiary->state = $new_ben['state'];
        // $old_ben->state = $new_ben['state'];
        // $beneficiary->phone_number = trim(implode(",", $request->applicant_phone_number));
        // $beneficiary->cell_phone_number = trim(implode(",", $request->applicant_cell_phone_number));
        $beneficiary->type = ID::beneficiary()->normal;
        $beneficiary->save();


        // if (is_null($new_ben['legal_representative'])) {
        //     if ($old_ben->quota_aid_advisors->first()) {
        //         //delete
        //     }
        //     if ($old_ben->quota_aid_legal_guardians->first()) {
        //         //delete
        //     }
        // } else {
        switch ($new_ben['legal_representative']) {
            //tutor
          case 1:
            //exists
            // if ($ben_advisor = $old_ben->quota_aid_advisors->first()) {

            // } else {
            $ben_advisor = new QuotaAidAdvisor();
            // }
            $ben_advisor->city_identity_card_id = $new_ben['advisor_city_identity_card_id'];
            $ben_advisor->kinship_id = null;
            $ben_advisor->identity_card = $new_ben['advisor_identity_card'];
            $ben_advisor->last_name = strtoupper(trim($new_ben['advisor_last_name']));
            $ben_advisor->mothers_last_name = strtoupper(trim($new_ben['advisor_mothers_last_name']));
            $ben_advisor->first_name = strtoupper(trim($new_ben['advisor_first_name']));
            $ben_advisor->second_name = strtoupper(trim($new_ben['advisor_second_name']));
            $ben_advisor->surname_husband = strtoupper(trim($new_ben['advisor_surname_husband']));
            $ben_advisor->gender = strtoupper(trim($new_ben['advisor_gender']));
            $ben_advisor->birth_date = Util::verifyBarDate($new_ben['advisor_birth_date']) ? Util::parseBarDate($new_ben['advisor_birth_date']) : $new_ben['advisor_birth_date'];
            // $ben_advisor->phone_number = trim(implode(",", $new_ben['advisor_phone_number'] ?? []));
            // $ben_advisor->cell_phone_number = trim(implode(",", $new_ben['advisor_cell_phone_number'] ?? []));
            $ben_advisor->name_court = $new_ben['advisor_name_court'];
            $ben_advisor->resolution_number = $new_ben['advisor_resolution_number'];
            $ben_advisor->resolution_date = Util::verifyBarDate($new_ben['advisor_resolution_date']) ? Util::parseBarDate($new_ben['advisor_resolution_date']) : $new_ben['advisor_resolution_date'];
            $ben_advisor->type = "Natural";
            $ben_advisor->save();
            if ($old_ben->quota_aid_advisors->first()) { } else {
              $advisor_beneficiary = new QuotaAidAdvisorBeneficiary();
              $advisor_beneficiary->quota_aid_beneficiary_id = $beneficiary->id;
              $advisor_beneficiary->quota_aid_advisor_id = $ben_advisor->id;
              $advisor_beneficiary->save();
            }

            break;
            //apoderado
          case 2:
            // if ($ben_legal_guardian = $old_ben->quota_aid_legal_guardians->first()) {

            // } else {
            $ben_legal_guardian = new QuotaAidLegalGuardian();
            // $ben_legal_guardian->retirement_fund_id = $retirement_fund->id; // is necessary?
            // }
            $ben_legal_guardian->identity_card = strtoupper(trim($new_ben['legal_guardian_identity_card']));
            $ben_legal_guardian->city_identity_card_id = $new_ben['legal_guardian_city_identity_card_id'];
            $ben_legal_guardian->first_name = strtoupper(trim($new_ben['legal_guardian_first_name']));
            $ben_legal_guardian->second_name = strtoupper(trim($new_ben['legal_guardian_second_name']));
            $ben_legal_guardian->last_name = strtoupper(trim($new_ben['legal_guardian_last_name']));
            $ben_legal_guardian->mothers_last_name = strtoupper(trim($new_ben['legal_guardian_mothers_last_name']));
            $ben_legal_guardian->surname_husband = strtoupper(trim($new_ben['legal_guardian_surname_husband']));
            /** !! TODO
             * phone and cellphone numbers
             */
            // $ben_legal_guardian->phone_number = trim(implode(",", $request->applicant_phone_number ?? []));
            // $ben_legal_guardian->cell_phone_number = trim(implode(",", $request->applicant_cell_phone_number ?? []));

            $ben_legal_guardian->gender = $new_ben['legal_guardian_gender'];
            $ben_legal_guardian->number_authority = $new_ben['legal_guardian_number_authority'];
            $ben_legal_guardian->notary_of_public_faith = $new_ben['legal_guardian_notary_of_public_faith'];
            $ben_legal_guardian->notary = $new_ben['legal_guardian_notary_of_public_faith'];
            $ben_legal_guardian->date_authority = Util::verifyBarDate($new_ben['legal_guardian_date_authority']) ? Util::parseBarDate($new_ben['legal_guardian_date_authority']) : $new_ben['legal_guardian_date_authority'];
            $ben_legal_guardian->save();
            if ($old_ben->quota_aid_legal_guardians->first()) { } else {
              $ben_legal_guardian_new = new QuotaAidBeneficiaryLegalGuardian();
              $ben_legal_guardian_new->quota_aid_beneficiary_id = $beneficiary->id;
              $ben_legal_guardian_new->quota_aid_legal_guardian_id = $ben_legal_guardian->id;
              $ben_legal_guardian_new->save();
            }
            break;
          default:
            # code...
            break;
        }
        // }


      }
    }
    $beneficiaries = QuotaAidMortuary::find($id)->quota_aid_beneficiaries()->with(['kinship', 'city_identity_card', 'address'])->orderByDesc('type')->orderBy('id')->get();
    foreach ($beneficiaries as $b) {
      $b->phone_number = explode(',', $b->phone_number);
      $b->cell_phone_number = explode(',', $b->cell_phone_number);
      if (!sizeOf($b->address) > 0 && $b->type == 'S') {
        $b->address[] = array('zone' => null, 'street' => null, 'number_address' => null);
      }

      $b->legal_representative = null;
      if ($beneficiary_advisor = $b->quota_aid_advisors->first()) {
        $b->legal_representative = 1;
        $b->advisor_identity_card = $beneficiary_advisor->identity_card;
        $b->advisor_city_identity_card_id = $beneficiary_advisor->city_identity_card_id;
        $b->advisor_first_name = $beneficiary_advisor->first_name;
        $b->advisor_second_name = $beneficiary_advisor->second_name;
        $b->advisor_last_name = $beneficiary_advisor->last_name;
        $b->advisor_mothers_last_name = $beneficiary_advisor->mothers_last_name;
        $b->advisor_surname_husband = $beneficiary_advisor->surname_husband;
        $b->advisor_birth_date = $beneficiary_advisor->birth_date;        
        $b->advisor_gender = $beneficiary_advisor->gender;
        $b->advisor_name_court = $beneficiary_advisor->name_court;
        $b->advisor_resolution_number = $beneficiary_advisor->resolution_number;
        $b->advisor_resolution_date = $beneficiary_advisor->resolution_date;
      }
      if ($beneficiary_legal_guardian = $b->quota_aid_legal_guardians->first()) {
        $b->legal_representative = 2;
        $b->legal_guardian_identity_card = $beneficiary_legal_guardian->identity_card;
        $b->legal_guardian_city_identity_card_id = $beneficiary_legal_guardian->city_identity_card_id;
        $b->legal_guardian_first_name = $beneficiary_legal_guardian->first_name;
        $b->legal_guardian_second_name = $beneficiary_legal_guardian->second_name;
        $b->legal_guardian_last_name = $beneficiary_legal_guardian->last_name;
        $b->legal_guardian_mothers_last_name = $beneficiary_legal_guardian->mothers_last_name;
        $b->legal_guardian_surname_husband = $beneficiary_legal_guardian->surname_husband;
        $b->legal_guardian_gender = $beneficiary_legal_guardian->gender;
        $b->legal_guardian_number_authority = $beneficiary_legal_guardian->number_authority;
        $b->legal_guardian_notary_of_public_faith = $beneficiary_legal_guardian->notary_of_public_faith;
        $b->legal_guardian_notary = $beneficiary_legal_guardian->notary;
        $b->legal_guardian_date_authority = $beneficiary_legal_guardian->date_authority;
      }
    }
    $data = [
      'beneficiaries' => $beneficiaries,
    ];
    return $data;
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \Muserpol\RetirementFund  $retirementFund
   * @return \Illuminate\Http\Response
   */
  public function edit(RetirementFund $retirementFund)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Muserpol\RetirementFund  $retirementFund
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, RetirementFund $retirementFund)
  {
    //
  }
  /**
   * Remove the specified resource from storage.
   *
   * @param  \Muserpol\RetirementFund  $retirementFund
   * @return \Illuminate\Http\Response
   */
  public function destroy(RetirementFund $retirementFund)
  {
    //
  }

  public function generateProcedure(Affiliate $affiliate)
  {

    //return $affiliate;
    //$this->authorize('create',QuotaAidMortuary::class);
    if (!$affiliate->degree) {
      return redirect("/affiliate/$affiliate->id")->with('message', "Debe actualizar el grado antes");
    }
    $hierarchy = $affiliate->degree->hierarchy;
    /////Validar si tiene mas de un tramite de cuota para mostrar la modalidad
        $active_quota = QuotaAidMortuary::join('procedure_modalities','quota_aid_mortuaries.procedure_modality_id','=','procedure_modalities.id')
        ->where('affiliate_id',$affiliate->id)
        ->where('procedure_modalities.procedure_type_id',3)
        ->where('code','NOT LIKE','%A')->count();
        $active_auxilio = QuotaAidMortuary::join('procedure_modalities','quota_aid_mortuaries.procedure_modality_id','=','procedure_modalities.id')
        ->where('affiliate_id',$affiliate->id)
        ->where('procedure_modalities.procedure_type_id',4)
        ->where('code','NOT LIKE','%A')->count();
    ///


    if( $active_auxilio>=1 || $active_quota>=1){
      $procedure_types = ProcedureType::where('id', '4')->get();
    }else{
      $procedure_types = ProcedureType::where('id', '3')->orWhere('id', '4')->get();
    }

    $affiliate = Affiliate::select('affiliates.id', 'identity_card', 'city_identity_card_id', 'registration', 'first_name', 'second_name', 'last_name', 'mothers_last_name', 'surname_husband', 'birth_date', 'gender', 'degree_id', 'degrees.name as degree', 'civil_status', 'affiliate_states.name as affiliate_state', 'phone_number', 'cell_phone_number', 'date_death')
      ->leftJoin('degrees', 'affiliates.degree_id', '=', 'degrees.id')
      ->leftJoin('affiliate_states', 'affiliates.affiliate_state_id', '=', 'affiliate_states.id')
      ->find($affiliate->id);


    $procedure_requirements = ProcedureRequirement::select('procedure_requirements.id', 'procedure_documents.name as document', 'number', 'procedure_modality_id as modality_id')
      ->leftJoin('procedure_documents', 'procedure_requirements.procedure_document_id', '=', 'procedure_documents.id')
      ->whereNull('procedure_requirements.deleted_at')
      //->where('procedure_requirements.number','!=','0')
      ->orderBy('procedure_requirements.procedure_modality_id', 'ASC')
      ->orderBy('procedure_requirements.number', 'ASC')
      ->get();

    $spouse = Spouse::where('affiliate_id', $affiliate->id)->first();
    if (!isset($spouse->id))
      $spouse = new Spouse();
    $modalities = ProcedureModality::where('procedure_type_id', '3')->orWhere('procedure_type_id', '4')->select('id', 'procedure_type_id', 'name', 'shortened')->get();

    $kinships = Kinship::get();

    $cities = City::get();
    $degrees = Degree::all();
    $data = [
      'requirements' => $procedure_requirements,
      'modalities'    => $modalities,
      'affiliate'  => $affiliate,
      'kinships'  =>  $kinships,
      'cities'    =>  $cities,
      'degrees'    =>  $degrees,
      'ret'    =>  $cities,
      'spouse' =>  $spouse,
      'procedure_types'    =>  $procedure_types,
      'hierarchy' => $hierarchy,
    ];
    return view('quota_aid.create', $data);
  }

  /**
   * This function edit recepcioned documents
   *
   * @param object Request, int id
   * @return Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary
   */
  public function editRequirements(Request $request, $id)
  {
    $documents = QuotaAidSubmittedDocument::select('procedure_requirements.number', 'quota_aid_submitted_documents.procedure_requirement_id')
      ->leftJoin('procedure_requirements', 'quota_aid_submitted_documents.procedure_requirement_id', '=', 'procedure_requirements.id')
      ->orderby('procedure_requirements.number', 'ASC')
      ->where('quota_aid_submitted_documents.quota_aid_mortuary_id', $id)
      ->pluck('quota_aid_submitted_documents.procedure_requirement_id', 'procedure_requirements.number');

    $num = $num2 = 0;

    foreach ($request->requirements as $requirement) {
      $from = $to = 0;
      $comment = null;
      for ($i = 0; $i < count($requirement); $i++) {
        $from = $requirement[$i]['number'];
        if ($requirement[$i]['status'] == true) {
          $to = $requirement[$i]['id'];
          $comment = $requirement[$i]['comment'];
          $doc = QuotaAidSubmittedDocument::where('quota_aid_mortuary_id', $id)->where('procedure_requirement_id', $documents[$from])->first();
          $doc->procedure_requirement_id = $to;
          $doc->comment = $comment;
          $doc->save();
        }
      }
    }

    $procedure_requirements = ProcedureRequirement::select('procedure_requirements.id', 'procedure_documents.name as document', 'number', 'procedure_modality_id as modality_id')
      ->leftJoin('procedure_documents', 'procedure_requirements.procedure_document_id', '=', 'procedure_documents.id')
      ->where('procedure_requirements.number', '0')
      ->orderBy('procedure_requirements.procedure_modality_id', 'ASC')
      ->orderBy('procedure_requirements.number', 'ASC')
      ->get();

    $quota_aid = QuotaAidMortuary::select('id', 'procedure_modality_id')->find($id);

    $aditional =  $request->aditional_requirements;
    $num = "";

    foreach ($procedure_requirements as $requirement) {
      $needle = QuotaAidSubmittedDocument::where('quota_aid_mortuary_id', $id)
        ->where('procedure_requirement_id', $requirement->id)
        ->first();
      if (isset($needle)) {
        if (!in_array($requirement->id, $aditional)) {
          $num .= $requirement->id . ' ';
          $needle->delete();
          $needle->forceDelete();
        }
      } else {
        if (in_array($requirement->id, $aditional)) {
          $submit = new QuotaAidSubmittedDocument();
          $submit->quota_aid_mortuary_id = $quota_aid->id;
          $submit->procedure_requirement_id = $requirement->id;
          $submit->reception_date = date('Y-m-d');
          $submit->comment = "";
          $submit->save();
        }
      }
    }

    return $num;
  }
  public function getTestimonies($quota_aid_id)
  {
    $quota_aid = QuotaAidMortuary::find($quota_aid_id);
    $applicants = $quota_aid->quota_aid_beneficiaries()->where('type', 'S')->get()->all();
    $testimonies = [];
    if (count($applicants) > 0) {
      foreach ($applicants as $applicant) {
        $testimonies = array_merge($testimonies, $applicant->testimonies()->with('quota_aid_beneficiaries')->get()->all());
      }
    }
    // $affiliate = $quota_aid->affiliate;
    // $testimonies = $affiliate->testimony()->with('quota_aid_beneficiaries')->get();
    return $testimonies;
  }
  public function updateBeneficiaryTestimony(Request $request, $quota_aid_id)
  {
    $quota_aid = QuotaAidMortuary::find($quota_aid_id);
    $affiliate = $quota_aid->affiliate;

    $testimonies_array_request = array();
    foreach (array_pluck($request->all(), 'id') as $key => $value) {
      if ($value) {
        array_push($testimonies_array_request, $value);
      }
    }
    $testimonies = $affiliate->testimony;
    foreach ($testimonies as $key => $t) {
      $index = array_search($t->id, $testimonies_array_request);
      if ($index === false) {
        $beneficiaries = $t->quota_aid_beneficiaries()->where('type', 'S')->get();
        foreach ($beneficiaries as $b) {
          if ($b->quota_aid_mortuary_id == $quota_aid_id) {
            $t->delete();
            break;
          }
        }
      }
    }
    foreach ($request->all() as $key => $t) {
      if ($t['id'] == 'new') {
        $testimony = new Testimony();
      } else {
        $testimony = Testimony::find($t['id']);
      }
      $testimony->user_id = Util::getAuthUser()->id;
      $testimony->affiliate_id = $affiliate->id;
      $testimony->document_type = $t['document_type'];
      $testimony->number = $t['number'];
      $testimony->date = $t['date'];
      $testimony->court = $t['court'];
      $testimony->place = $t['place'];
      $testimony->notary = $t['notary'];
      $testimony->save();
      $ids_ben = array();
      foreach ($t['quota_aid_beneficiaries'] as $ben) {
        array_push($ids_ben, $ben['id']);
      }
      $testimony->quota_aid_beneficiaries()->sync($ids_ben);
    }
    return;
  }
  public function qualification($quota_aid_id)
  {
    $quota_aid = QuotaAidMortuary::find($quota_aid_id);
    $affiliate = $quota_aid->affiliate;
    if (is_null($quota_aid->quota_aid_procedure_id)) {
      return 'No aplicó a un cálculo de cuantía por modalidad';
    }
    if (!$quota_aid->getDeceased()->date_death) {
      return 'Verifique que el fallecido (a) tenga fecha de Fallecimiento';
    }
    if (!$affiliate->hasQuota() && !$affiliate->hasAid()) {
      return 'Verifique que el titular tenga un beneficio de cuota o auxilio.';
    }
    $degree = $affiliate->degree;
    $procedure = QuotaAidProcedure::where('id', $quota_aid->quota_aid_procedure_id)
      ->where('hierarchy_id', $degree->hierarchy_id)
      ->where('is_enabled', true)
      ->first();
    $quota_aid_dates = $affiliate->getContributionsWithTypeQuotaAid($quota_aid_id);
    $quota_aid_contributions = $affiliate->getQuotaAidContributions($quota_aid_id);
    /*if (!$quota_aid_contributions['is_continuous']) {
      return 'Verifique que el titular tenga la cantidad de aportes correctas';
    }
    rev
    */
    $total_dates = Util::sumTotalContributions($quota_aid_dates);
    $dates = array(
      'id' => 0,
      'dates' => $quota_aid_dates,
      'name' => "PERIODO DE APORTES CONSIDERADOS PARA EL CÁLCULO DEL BENEFICIO",
      'operator' => '**',
      'description' => "PERIODO DE APORTES CONSIDERADOS PARA EL CÁLCULO DEL BENEFICIO",
      'years' => intval($total_dates / 12),
      'months' => $total_dates % 12,
    );


    $group_dates[] = $dates;
    $data = [
      'quota_aid' => $quota_aid,
      'affiliate' => $affiliate,
      'contributions' => $quota_aid_contributions['contributions'],
      'is_continuous' => $quota_aid_contributions['is_continuous'],
      'procedure' => $procedure,
      'dates' => $dates,
    ];
    return view('quota_aid.qualification', $data);
  }
  // public function saveSubtotal($quota_aid_id)
  // {
  //     $quota_aid = QuotaAidMortuary::find($quota_aid_id);
  //     $affiliate = $quota_aid->affiliate;
  //     $quota_aid->subtotal = 100;
  //     $quota_aid->save();

  //     $discounts = $quota_aid->discount_types()->whereIn('discount_types.id', [1, 2, 3])->get();
  //     $guarantors = InfoLoan::where('quota_aid_mortuary_id', $quota_aid->id)->get();
  //     foreach ($guarantors as $value) {
  //         $value->full_name = $value->affiliate_guarantor->fullName();
  //         $value->identity_card = $value->affiliate_guarantor->identity_card;
  //     }
  //     $data = [
  //         'guarantors' => $guarantors,
  //         'discounts' => $discounts,
  //         'sub_total_quota_aid' => $quota_aid->subtotal,
  //         'total_quota_aid' => $quota_aid->total,
  //     ];
  //     return $data;
  // }
  public function calculateTotal(Request $request, $quota_aid_id)
  {
    $quota_aid = QuotaAidMortuary::find($quota_aid_id);
    $affiliate = $quota_aid->affiliate;
    $degree = $affiliate->degree;
    $procedure = QuotaAidProcedure::where('id', $quota_aid->quota_aid_procedure_id)
      ->where('hierarchy_id', $degree->hierarchy_id)
      ->where('is_enabled', true)
      ->first();
    $quota_aid->subtotal = $procedure->amount;
    $quota_aid->total = $procedure->amount;
    $quota_aid->save();
    $discounts = $quota_aid->discount_types()->whereIn('discount_types.id', [1])->get();
    $data = [
      'sub_total' => $quota_aid->subtotal,
      'total' => $quota_aid->total,
      'discounts' => $discounts,
    ];
    return $data;
  }
  public function saveDiscounts(Request $request, $quota_aid_id)
  {
    static $DISCOUNT_TYPE_RETENTION = 9;
    static $DISCOUNT_TYPE_ADVANCE = 1;

    $quota_aid = QuotaAidMortuary::find($quota_aid_id);
    $affiliate = $quota_aid->affiliate;
    $degree = $affiliate->degree;
    $procedure = QuotaAidProcedure::where('id', $quota_aid->quota_aid_prodedure_id)
      ->where('hierarchy_id', $degree->hierarchy_id)
      ->where('is_enabled', true)
      ->first();


    $advance_payment = $request->advancePayment ?? 0;
    $judicial_retention_amount = $request->judicialRetentionAmount ?? 0;
    // $retention_loan_payment = $request->retentionLoanPayment ?? 0;
    // $retention_guarantor = $request->retentionGuarantor ?? 0;
    $total = $quota_aid->subtotal - $advance_payment - $judicial_retention_amount; // - $retention_loan_payment - $retention_guarantor;
    $quota_aid->total = $total;
    $quota_aid->save();

    //mejorar
    $discount_type = DiscountType::where('id', $DISCOUNT_TYPE_ADVANCE)->first();
    if ($advance_payment >= 0) {
      if ($quota_aid->discount_types->contains($DISCOUNT_TYPE_ADVANCE) || $quota_aid->discount_types->contains($DISCOUNT_TYPE_RETENTION)) {
        if($judicial_retention_amount !== 0 && $judicial_retention_amount !== null) {
          $quota_aid->discount_types()->updateExistingPivot($DISCOUNT_TYPE_RETENTION, ['amount' => $judicial_retention_amount, 'date' => $request->judicialRetentionDate]);
        }
        if(!$quota_aid->discount_types->contains($DISCOUNT_TYPE_ADVANCE))
          $quota_aid->discount_types()->save($discount_type, ['amount' => $advance_payment, 'date' => $request->advancePaymentDate, 'code' => $request->advancePaymentCode, 'note_code' => $request->advancePaymentNoteCode, 'note_code_date' => $request->advancePaymentNoteCodeDate]);
        else
          $quota_aid->discount_types()->updateExistingPivot($DISCOUNT_TYPE_ADVANCE, ['amount' => $advance_payment, 'date' => $request->advancePaymentDate, 'code' => $request->advancePaymentCode, 'note_code' => $request->advancePaymentNoteCode, 'note_code_date' => $request->advancePaymentNoteCodeDate]);
      } else {
        $quota_aid->discount_types()->save($discount_type, ['amount' => $advance_payment, 'date' => $request->advancePaymentDate, 'code' => $request->advancePaymentCode, 'note_code' => $request->advancePaymentNoteCode, 'note_code_date' => $request->advancePaymentNoteCodeDate]);
      }
    } else {
      $quota_aid->discount_types()->detach($discount_type->id);
    }
    $discounts = $quota_aid->discount_types()->whereIn('discount_types.id', [$DISCOUNT_TYPE_ADVANCE, $DISCOUNT_TYPE_RETENTION])->get();
    $beneficiaries = $quota_aid->quota_aid_beneficiaries()->orderByDesc('type')->orderBy('id')->with('kinship')->get();
    //create function search spouse
    $spouse_id = ID::kinship()->conyuge;
    $spouse = $beneficiaries->filter(function ($item) use ($spouse_id) {
      return $item->kinship->id == $spouse_id;
    });
    $total_quota_aid = $quota_aid->total;

    if (sizeOf($spouse) > 0) {
      $has_spouse = true;
      $total_spouse = $total_quota_aid / 2;
      $total_spouse_percentage = 100 / 2;
      $total_derechohabientes_percentage = round($total_spouse_percentage / sizeOf($beneficiaries), 2);
      $total_spouse_percentage = round($total_spouse_percentage + $total_derechohabientes_percentage, 2);
      $total_spouse = $total_quota_aid / 2;
      $total_derechohabientes = round(($total_spouse / sizeOf($beneficiaries)), 2);
      $total_spouse = round(($total_spouse + $total_derechohabientes), 2);
    } else {
      $has_spouse = false;
      $total_derechohabientes = round($total_quota_aid / sizeOf($beneficiaries), 2);
      $total_derechohabientes_percentage = round(100 / sizeOf($beneficiaries), 2);
    }
    $one_spouse = 1;
    foreach ($beneficiaries as $beneficiary) {
      $beneficiary->full_name = $beneficiary->fullName();
      if ($beneficiary->kinship->id == $spouse_id) {
        if ($one_spouse <= 1) {
          // recalculate
          if ($request->reload) {
            $beneficiary->temp_percentage = $total_spouse_percentage;
            $beneficiary->temp_amount = $total_spouse;
          } else {
            $beneficiary->temp_percentage = $beneficiary->percentage ? $beneficiary->percentage : $total_spouse_percentage;
            $beneficiary->temp_amount = $beneficiary->paid_amount ? $beneficiary->paid_amount : $total_spouse;
          }
        } else {
          return response('error', 500);
        }
        $one_spouse++;
      } else {
        //recalculate
        if ($request->reload) {
          $beneficiary->temp_percentage = $total_derechohabientes_percentage;
          $beneficiary->temp_amount = $total_derechohabientes;
        } else {
          $beneficiary->temp_percentage = $beneficiary->percentage ? $beneficiary->percentage : $total_derechohabientes_percentage;
          $beneficiary->temp_amount = $beneficiary->paid_amount ? $beneficiary->paid_amount : $total_derechohabientes;
        }
      }
    }
    $data = [
      'discounts' => $discounts,
      'beneficiaries' => $beneficiaries,
      'sub_total' => $quota_aid->subtotal,
      'total' => $quota_aid->total,
    ];
    return $data;
  }
  public function savePercentages(Request $request, $quota_aid_id)
  {
    $quota_aid = QuotaAidMortuary::find($quota_aid_id);
    $affiliate = $quota_aid->affiliate;
    foreach ($request->beneficiaries as $beneficiary) {
      $new_beneficiary = $quota_aid->quota_aid_beneficiaries()->where('id', $beneficiary['id'])->first();
      if (!$new_beneficiary) {
        return response("error al buscar al beneficiario", 500);
      }
      $new_beneficiary->percentage = $beneficiary['temp_percentage'];
      $new_beneficiary->paid_amount = $beneficiary['temp_amount'];
      $new_beneficiary->save();
    }
    $beneficiaries = $quota_aid->quota_aid_beneficiaries;

    $data = [
      'beneficiaries' => $beneficiaries,
      'quota_aid' => $quota_aid,
    ];
    return $data;
  }
  public function updateInformation(Request $request)
  {
    $quota_aid = QuotaAidMortuary::find($request->id);
    $this->authorize('update', $quota_aid);
    $quota_aid->city_end_id = $request->city_end_id;
    $quota_aid->city_start_id = $request->city_start_id;
    $quota_aid->reception_date = $request->reception_date;
    $quota_aid->procedure_state_id = $request->procedure_state_id;
    $quota_aid->quota_aid_procedure_id = $request->quota_aid_procedure_id;
    if ($quota_aid->procedure_state_id == ID::state()->eliminado) {
      $quota_aid->code .= "A";
    }
    $quota_aid->save();
    $datos = array('quota_aid' => $quota_aid, 'procedure_modality' => $quota_aid->procedure_modality, 'city_start' => $quota_aid->city_start, 'city_end' => $quota_aid->city_end);
    return $datos;
  }
  public function createJudicialRetention(Request $request, $quota_aid_id) {
    $quota_aid = QuotaAidMortuary::find($quota_aid_id);
    $discount_type = DiscountType::where('shortened', 'Retención según Resolución Judicial')->first();
    if(!$quota_aid || !$discount_type)
        return response()->json([
          'error' => "No existe el trámite o el tipo de descuento"
        ], 409);
    $discount_type_quota_aid = $quota_aid->discount_types()
        ->wherePivot('discount_type_id', $discount_type->id)
        ->wherePivot('quota_aid_mortuary_id', $quota_aid_id)
        ->wherePivot('deleted_at', null)
        ->count();
    if($discount_type_quota_aid > 0)
        return response()->json([
          'error' => "ya existe la retención"
        ], 409);
    $quota_aid->discount_types()->save($discount_type, ['amount' => 0, 'date' => null, 'code' => null, 'note_code' => $request->detail, 'note_code_date' => Carbon::now(), ]);
    $discount = $quota_aid->discount_types()->whereIn('discount_types.id', [$discount_type->id])->get();
    return response()->json([
      'message' => 'Registro exitoso',
      'data' => $discount
    ]);
  }
  public function obtainJudicialRetention($quota_aid_id) {
    $quota_aid = QuotaAidMortuary::find($quota_aid_id);
    $discount_type = DiscountType::where('shortened', 'Retención según Resolución Judicial')->first();
    if($quota_aid && $discount_type) {
      $discounts = $quota_aid->discount_types()
        ->wherePivot('discount_type_id', $discount_type->id)
        ->wherePivot('quota_aid_mortuary_id', $quota_aid_id)
        ->get()
        ->pluck('pivot');

      if(count($discounts) > 0) {
        return response()->json([
          'message' => 'Obtención exitosa',
          'data' => $discounts
        ]);
      }
    }
    return response()->json([
      'error' => 'No existe la retención',
      'data' => []
    ], 200);
  }
  public function modifyJudicialRetention(Request $request, $quota_aid_id) {
    static $DISCOUNT_TYPE_RETENTION = 9;
    $quota_aid = QuotaAidMortuary::find($quota_aid_id);
    $discount_type = DiscountType::where('shortened', 'Retención según Resolución Judicial')->first();
    if($quota_aid && $discount_type) {
      $updated = $quota_aid->discount_types()->updateExistingPivot($DISCOUNT_TYPE_RETENTION, [ 'note_code' => $request->detail ]);
      return response()->json([
        'message' => 'Modificación de la retención exitosa',
        'data' => $updated
      ]);
    }
    return response()->json([
      'error' => 'No se pudo modificar la retención',
    ], 409);
  }
  public function cancelJudicialRetention($quota_aid) {
    static $DISCOUNT_TYPE_RETENTION = 9;
    $quota_aid = QuotaAidMortuary::find($quota_aid);
    $discount_type = DiscountType::where('shortened', 'Retención según Resolución Judicial')->first();
    if($quota_aid && $discount_type) {
      $deleted = $quota_aid->discount_types()->updateExistingPivot($DISCOUNT_TYPE_RETENTION, ['deleted_at' => now()]);
      return response()->json([
        'message' => 'Se ha eliminado la retención exitosamente',
        'data' => $deleted
      ]);
    }
    return response()->json([
      'error' => 'No se pudo eliminar la retención'
    ], 409);
  }
}
