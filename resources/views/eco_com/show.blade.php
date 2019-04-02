@extends('layouts.app') 
@section('title', 'Complemento economico Padre') 
@section('styles')
<link rel="stylesheet" href="{{asset('/css/datatables.css')}}">
@endsection
 
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-md-7">
        {!!Breadcrumbs::render('show_eco_com', $economic_complement)!!}
    </div>
    <div class="col-md-5 text-center" style="margin-top:12px;">
        <div class="pull-left">
            {{--
            <eco-com-create-button :eco-com-process="{{ $eco_com_process }}" :eco-com-procedures="{{ json_encode($eco_com_procedures) }}">
            </eco-com-create-button> --}}
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
        <div class="widget-head-color-box blue-bg p-lg text-center">
            <div class="m-b-md">
                <h2 class="font-bold no-margins" data-toggle="tooltip" data-placement="top" title="Ir al tramite Inicial">
                    <a href="{{route('eco_com_process_show', $eco_com_process->id)}}" style="color: #fff"> Ir al Tramite Inicial {{ $eco_com_process->id }}</a>
                </h2>
                {{-- <h3 class="text-center" data-toggle="tooltip" data-placement="top" title="Cédula de Identidad"><strong>{{  $eco_com_process->affiliate->ciWithExt() }}</strong></h3>
                <h4 class="text-center" data-toggle="tooltip" data-placement="top" title="Matricula"><strong>{{  $eco_com_process->affiliate->registration }}</strong></h4> --}}
            </div>
        </div>
        <div class="widget-text-box">
            <ul class="list-group elements-list">
                <li class="list-group-item active" data-toggle="tab" href="#tab-eco-com"><a href="#"><i class="fa fa-puzzle-piece"></i> Informacion del Tramite</a></li>
                <li class="list-group-item active" data-toggle="tab" href="#tab-eco-com-process"><a href="#"><i class="fa fa-puzzle-piece"></i> Informacion del Tramite Inicial</a></li>
                @if($eco_com_process->procedure_modality_id != 25)
                <li class="list-group-item " data-toggle="tab" href="#tab-affiliate"><a href="#"><i class="fa fa-user"></i> Afiliado </a></li>
                @endif
                <li class="list-group-item " data-toggle="tab" href="#tab-police-info"><a href="#"><i class="fa fa-address-card"></i> Información Policial </a></li>
                <li class="list-group-item " data-toggle="tab" href="#tab-beneficiaries"><a href="#"><i class="fa fa-users"></i> Beneficiarios</a></li>
                <li class="list-group-item " data-toggle="tab" href="#tab-summited-document"><a href="#"><i class="fa fa-file"></i> Documentos Presentados</a></li>
                <li class="list-group-item " data-toggle="tab" href="#tab-qualification"><a href="#"><i class="fa fa-dollar"></i> Calificacion</a></li>
                {{--
                <li class="list-group-item " data-toggle="tab" href="#tab-observations"><a href="#"><i class="fa fa-eye-slash"></i> Observaciones</a></li> --}}
            </ul>
        </div>
        <br> {{--
        <tag-list :doc-id="{{ $eco_com_process->id }}" type="ecoCom"></tag-list> --}}
    </div>
    <br>
    <div class="col-md-9" style="padding-left: 6px">

        <div class="tab-content">
            <div id="tab-eco-com" class="tab-pane active">
                {{-- @can('update',$eco_com_process) --}}
                <eco-com-info :eco-com="{{ $economic_complement }}" :eco-com-procedure="{{ $economic_complement->eco_com_procedure }}" :states="{{ $states }}"
                    :pension-entities="{{ $pension_entities }}" :degrees="{{ $degrees }}" :categories="{{ $categories }}" :affiliate="{{ $affiliate }}"
                    :cities="{{ $cities }}" inline-template>
    @include('eco_com.info')
                </eco-com-info>
                {{-- @endcan --}}
            </div>
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
            <div id="tab-police-info" class="tab-pane">
            <affiliate-police :affiliate="{{ $affiliate }}" :eco-com-id="{{ $economic_complement->id }}" inline-template :type-eco-com="true" :categories="{{$categories}}">
    @include('affiliates.affiliate_police_information', ['affiliate'=>$affiliate, 'affiliate_states'=>$affiliate_states, 'categories' => $categories->pluck('name', 'id'), 'degrees'=> $degrees->pluck('name', 'id'), 'pension_entities'=> $pension_entities->pluck('name', 'id')])
                </affiliate-police>
            </div>
            <div id="tab-beneficiaries" class="tab-pane">
                {{-- @can('view',new Muserpol\Models\RetirementFund\RetFunBeneficiary) --}}
                    @include('eco_com_process.beneficiary', ['eco_com_beneficiary'=>$eco_com_beneficiary,
                'cities'=>$cities]) {{-- @endcan --}}
            </div>
            <div id="tab-summited-document" class="tab-pane">
                {{-- @can('view',new Muserpol\Models\RetirementFund\RetFunSubmittedDocument) --}}
                <eco-com-process-step1-requirements-edit :eco-com-process="{{ $eco_com_process }}" :procedure-modalities="{{ $procedure_modalities }}"
                    :requirements="{{ $requirements }}" :user="{{ $user }}" :cities="{{ $cities }}" :procedure-types="{{$procedure_types}}"
                    :submitted="{{$submit_documents}}" :rol="{{Muserpol\Helpers\Util::getRol()->id}}" inline-template>
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