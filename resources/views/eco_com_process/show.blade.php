@extends('layouts.app') 
@section('title', 'Complemento economico Padre') 
@section('styles')
<link rel="stylesheet" href="{{asset('/css/datatables.css')}}">
@endsection
 
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-md-7">
        {!!Breadcrumbs::render('show_eco_com_process', $eco_com_process)!!}
    </div>
    <div class="col-md-5 text-center" style="margin-top:12px;">
        <div class="pull-left">
            {{--
            <correlative doc-id="{{ $eco_com_process->id }}" wf-state-id="{{ $eco_com_process->wf_state_current_id }}" type="retFun"></correlative> --}} @if(Util::getRol()->id == 10 || Util::isRegionalRole())
            <ret-fun-certification-button title="Imprimir recepción" ret-fun-id="{{ $eco_com_process->id }}" url-print="{{ route('ret_fun_print_reception', $eco_com_process->id) }}"
                type="retFun">
            </ret-fun-certification-button>
            @endif @if(Muserpol\Helpers\Util::getRol()->id == 15)
            <ret-fun-certification-button title="Imprimir Certificacion de Archivo" ret-fun-id="{{ $eco_com_process->id }}" url-print="{{ route('ret_fun_print_file', $affiliate->id) }}">
            </ret-fun-certification-button>
            @endif @if(Muserpol\Helpers\Util::getRol()->id == 14)
            <ret-fun-certification-button title="Imprimir Dictamen Legal" ret-fun-id="{{ $eco_com_process->id }}" url-print="{{ route('ret_fun_print_legal_dictum', $eco_com_process->id)}}"
                message="true">
            </ret-fun-certification-button>
            @endif
            <span data-toggle="modal" data-target="#ModalRecordRetFun">
                <button type="button" class="btn btn-info btn-sm dim" data-toggle="tooltip" data-placement="top" title="Historial del Trámite">
                    <i class="fa fa-history" style="font-size:15px;"></i> Historial del Trámite
                </button>
            </span> {{--
    @include('ret_fun.ret_fun_record', ['ret_fun_records' => $ret_fun_records,]) --}}
        </div>
        <div class="pull-right">
            <div class="form-inline">
                {{-- @if ($can_validate)
                <inbox-send-back-button-ret-fun :wf-sequence-back-list="{{ $wf_sequences_back }}" :doc-id="{{$eco_com_process->id}}" :wf-current-state-name="`{{$eco_com_process->wf_state->name}}`"
                    type="retFun"></inbox-send-back-button-ret-fun>
                <sweet-alert-modal inline-template :doc-id="{{$eco_com_process->id}}" :inbox-state="{{$eco_com_process->inbox_state ? 'true' : 'false'}}"
                    :doc-user-id="{{$eco_com_process->user_id}}" :auth-id="{{ $user->id}}" type="retFun">
                    <transition name="fade" mode="out-in" :duration="300" enter-active-class="animated tada" leave-active-class="animated bounceOutRight">
                        <div style="display:inline-block" v-if="status == true" key="one" data-toggle="tooltip" data-placement="top" title="Cancelar Revision del Trámite">
                            <button class="btn btn-danger btn-circle btn-outline btn-lg active" type="button" @click="cancelModal()" v-if="itisMine"><i class="fa fa-times"></i></button>
                        </div>
                        <div style="display:inline-block" v-else key="two" data-toggle="tooltip" data-placement="top" title="Procesar Trámite">
                            <button class="btn btn-primary btn-circle btn-outline btn-lg" type="button" @click="confirmModal()" :disabled="! status == false "><i class="fa fa-check"></i></button>
                        </div>
                    </transition>
                </sweet-alert-modal>
                @endif --}}
            </div>
        </div>
    </div>
</div>

@if(Session::has('message'))
<br>
<div class="alert alert-danger alert-dismissable">
    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button> {{Session::get('message')}}
</div>
@endif

<div class="row">
    <div class="col-md-3" style="padding-right: 3px">
        <div class="widget-head-color-box yellow-bg p-lg text-center">
            <div class="m-b-md">
                <h2 class="font-bold no-margins" data-toggle="tooltip" data-placement="top" title="Ver Afiliado ">
                    <a href="{{route('affiliate.show', $affiliate->id)}}" style="color: #fff"> {{ $eco_com_process->affiliate->fullNameWithDegree() }}</a>
                </h2>
                <h3 class="text-center" data-toggle="tooltip" data-placement="top" title="Cédula de Identidad"><strong>{{  $eco_com_process->affiliate->ciWithExt() }}</strong></h3>
                <h4 class="text-center" data-toggle="tooltip" data-placement="top" title="Matricula"><strong>{{  $eco_com_process->affiliate->registration }}</strong></h4>
            </div>
        </div>
        <div class="widget-text-box">
            <ul class="list-group elements-list">
                <li class="list-group-item active" data-toggle="tab" href="#tab-eco-com-process"><a href="#"><i class="fa fa-puzzle-piece"></i> Informacion de Tramite Inicial</a></li>
                <li class="list-group-item" data-toggle="tab" href="#tab-eco-coms"><a href="#"><i class="fa fa-puzzle-piece"></i> Complementos Economicos</a></li>
                @if($eco_com_process->procedure_modality_id != 25)
                <li class="list-group-item " data-toggle="tab" href="#tab-affiliate"><a href="#"><i class="fa fa-user"></i> Afiliado </a></li>
                @endif
                <li class="list-group-item " data-toggle="tab" href="#tab-beneficiaries"><a href="#"><i class="fa fa-users"></i> Beneficiarios</a></li>
                <li class="list-group-item " data-toggle="tab" href="#tab-summited-document"><a href="#"><i class="fa fa-file"></i> Documentos Presentados</a></li>
                <li class="list-group-item " data-toggle="tab" href="#tab-qualification"><a href="#"><i class="fa fa-dollar"></i> Calificacion</a></li>
                {{-- <li class="list-group-item " data-toggle="tab" href="#tab-observations"><a href="#"><i class="fa fa-eye-slash"></i> Observaciones</a></li> --}}
            </ul>
        </div>
        <br> {{--
        <tag-list :doc-id="{{ $eco_com_process->id }}" type="ecoCom"></tag-list> --}}
    </div>
    <br>
    <div class="col-md-9" style="padding-left: 6px">

        <div class="tab-content">
            <div id="tab-eco-com-process" class="tab-pane active">
                {{-- @can('update',$eco_com_process) --}}
                <eco-com-process-info :eco-com-process="{{ $eco_com_process }}" :states="{{ $states }}" :pension-entities="{{ $pension_entities }}"
                    inline-template>
    @include('eco_com_process.info')
                </eco-com-process-info>
                {{-- @endcan --}}
            </div>
            <div id="tab-eco-coms" class="tab-pane">
                {{-- @can('update',$eco_com_process)
                <ret-fun-info :eco_com_process="{{ $eco_com_process }}" :rf_city_start="{{$eco_com_process->city_start}}" :rf_city_end="{{$eco_com_process->city_end}}"
                    :rf_procedure_modality=" {{$eco_com_process->procedure_modality}}" :states="{{ $states }}" inline-template>
    @include('ret_fun.info', ['eco_com_process'=>$eco_com_process,'cities'=>$birth_cities])
                </ret-fun-info>
                @endcan --}}
            </div>
            <div id="tab-affiliate" class="tab-pane">
                <affiliate-show :affiliate="{{ $affiliate }}" :cities="{{$cities}}" inline-template>
    @include('affiliates.affiliate_personal_information',['affiliate'=>$affiliate,'cities'=>$cities_pluck,'birth_cities'=>$birth_cities,'is_editable'=>$is_editable])
                </affiliate-show>
            </div>
            <div id="tab-beneficiaries" class="tab-pane">
                {{-- @can('view',new Muserpol\Models\RetirementFund\RetFunBeneficiary) --}}
    @include('eco_com_process.beneficiary', ['eco_com_beneficiary'=>$eco_com_beneficiary, 'cities'=>$cities])
                {{-- @endcan --}}
            </div>
            <div id="tab-summited-document" class="tab-pane">
                {{-- @can('view',new Muserpol\Models\RetirementFund\RetFunSubmittedDocument) --}}
                <eco-com-process-step1-requirements-edit :eco-com-process="{{ $eco_com_process }}" :procedure-modalities="{{ $procedure_modalities }}" :requirements="{{ $requirements }}"
                    :user="{{ $user }}" :cities="{{ $cities }}" :procedure-types="{{$procedure_types}}" :submitted="{{$submit_documents}}"
                    :rol="{{Muserpol\Helpers\Util::getRol()->id}}" inline-template>
                    @include('eco_com_process.step1_requirements_edit')
                </eco-com-process-step1-requirements-edit>
                {{-- @endcan --}}
            </div>
            <div id="tab-qualification" class="tab-pane">
                {{--
    @include('ret_fun.summary_qualification', ['eco_com_process'=>$eco_com_process,'affiliate'=>$affiliate]) --}}
            </div>
            <div id="tab-observations" class="tab-pane">
                {{--
    @include('ret_fun.observation') --}}
            </div>

        </div>

    </div>

</div>
<br>
@endsection
 
@section('styles')
<link rel="stylesheet" href="{{asset('/css/datatable.css')}}">
@endsection
 
@section('jss')
<script src="{{ asset('/js/datatables.js')}}"></script>
@endsection