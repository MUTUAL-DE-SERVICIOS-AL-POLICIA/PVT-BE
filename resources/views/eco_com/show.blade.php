@extends('layouts.app') 
@section('title', 'Complemento economico Padre') 
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-md-7">
        {!!Breadcrumbs::render('show_eco_com', $economic_complement)!!}
    </div>
    <div class="col-md-5 text-center" style="margin-top:12px;">
        <div class="pull-left">
            <span data-toggle="modal" data-target="#ModalRecordRetFun">
                <button type="button" class="btn btn-info btn-sm dim" data-toggle="tooltip" data-placement="top" title="Historial del Trámite">
                    <i class="fa fa-history" style="font-size:15px;"></i> Historial del Trámite
                </button>
            </span>
            @include('ret_fun.ret_fun_record', ['ret_fun_records' => $eco_com_records,])
            @if(Util::isReceptionEcoCom()||Util::isRegionalRole())
                <certification-button
                    type="ecoCom"
                    title="Imprimir"
                    doc-id="{{ $economic_complement->id }}"
                    url-print="{{ route('eco_com_print_reception', [$economic_complement->id])}}">
                </certification-button>
            @endif
            @if (Util::getRol()->id == 4)
                <certification-button
                    type="ecoCom"
                    title="Imprimir Calificacion"
                    doc-id="{{ $economic_complement->id }}"
                    url-print="{{ route('eco_com_print_qualification', [$economic_complement->id])}}">
                </certification-button>
            @endif
        </div>
        <div class="pull-right">
            <div class="form-inline">
                @if ($can_validate)
                <inbox-send-back-button-ret-fun :wf-sequence-back-list="{{ $wf_sequences_back }}" :doc-id="{{$economic_complement->id}}"
                    :wf-current-state-name="`{{$economic_complement->wf_state->name}}`" type="ecoCom"></inbox-send-back-button-ret-fun>
                <sweet-alert-modal inline-template :doc-id="{{$economic_complement->id}}" :inbox-state="{{$economic_complement->inbox_state ? 'true' : 'false'}}"
                    :doc-user-id="{{$economic_complement->user_id}}" :auth-id="{{ $user->id}}" type="ecoCom">
                    <transition name="fade" mode="out-in" :duration="300" enter-active-class="animated tada" leave-active-class="animated bounceOutRight">
                        <div style="display:inline-block" v-if="status == true" key="one" data-toggle="tooltip" data-placement="top" title="Cancelar Revision del Trámite">
                            <button class="btn btn-danger btn-circle btn-outline btn-lg active" type="button" @click="cancelModal()" v-if="itisMine"><i class="fa fa-times"></i></button>
                        </div>
                        <div style="display:inline-block" v-else key="two" data-toggle="tooltip" data-placement="top" title="Procesar Trámite">
                            <button class="btn btn-primary btn-circle btn-outline btn-lg" type="button" @click="confirmModal()" :disabled="! status == false "><i class="fa fa-check"></i></button>
                        </div>
                    </transition>
                </sweet-alert-modal>
                @endif
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
        <div class="widget-head-color-box blue-bg text-center">
            <div class="pull-left" style="background: #1a6a9d; border-radius: 5px 0px 5px 0px;" data-toggle="tooltip" data-placement="top" title="Codigo único de Afiliado">
                <h3 class=" p-xxs"><strong>{{  $affiliate->id }}</strong></h3>
            </div>
            <div class="p-sm">
                <h2 class="font-bold no-margins" data-toggle="tooltip" data-placement="top" title="Ver Afiliado ">
                    <a href="{{route('affiliate.show', $affiliate->id)}}" style="color: #fff"> {{ $affiliate->fullNameWithDegree() }}</a>
                </h2>
                <h3 class="text-center" data-toggle="tooltip" data-placement="top" title="Cédula de Identidad"><strong>{{  $affiliate->ciWithExt() }}</strong></h3>
                <h4 class="text-center" data-toggle="tooltip" data-placement="top" title="Matricula"><strong>{{  $affiliate->registration }}</strong></h4>
            </div>
        </div>
        <div class="widget-text-box">
            <ul class="list-group elements-list">
                <li class="list-group-item active" data-toggle="tab" href="#tab-eco-com"><a href="#"><i class="fa fa-puzzle-piece"></i> Informacion del Tramite</a></li>
                <li class="list-group-item " data-toggle="tab" href="#tab-police-info"><a href="#"><i class="fa fa-address-card"></i> Información Policial </a></li>
                <li class="list-group-item " data-toggle="tab" href="#tab-eco-com-beneficiary"><a href="#"><i class="fa fa-users"></i> Beneficiario</a></li>
                <li class="list-group-item " data-toggle="tab" href="#tab-eco-com-legal-guardian"><a href="#"><i class="fa fa-shield"></i> Apoderado</a></li>
                <li class="list-group-item " data-toggle="tab" href="#tab-summited-document"><a href="#"><i class="fa fa-file"></i> Documentos Presentados</a></li>
                <li class="list-group-item " data-toggle="tab" href="#tab-qualification"><a href="#"><i class="fa fa-dollar"></i> Calificacion</a></li>
                <li class="list-group-item " data-toggle="tab" href="#tab-observations"><a href="#"><i class="fa fa-eye-slash"></i> Observaciones</a></li>
                <li class="list-group-item " data-toggle="tab" href="#tab-record"><a href="#"><i class="fa fa-history"></i> Historial</a></li>
            </ul>
        </div>
        <br>
        <tag-list :doc-id="{{ $economic_complement->id }}" type="ecoCom"></tag-list>
    </div>
    <br>
    <div class="col-md-9" style="padding-left: 6px">

        <div class="tab-content">
            <div id="tab-eco-com" class="tab-pane active">
                <eco-com-info :eco-com="{{ $economic_complement }}" :eco-com-procedure="{{ $economic_complement->eco_com_procedure }}" :states="{{ $states }}"
                    :pension-entities="{{ $pension_entities }}" :degrees="{{ $degrees }}" :categories="{{ $categories }}" :affiliate="{{ $affiliate }}"
                    :cities="{{ $cities }}" inline-template>
    @include('eco_com.info')
                </eco-com-info>
            </div>
            <div id="tab-eco-coms" class="tab-pane">

            </div>
            <div id="tab-affiliate" class="tab-pane">
                <affiliate-show :affiliate="{{ $affiliate }}" :cities="{{$cities}}" inline-template>
    @include('affiliates.affiliate_personal_information',['affiliate'=>$affiliate,'cities'=>$cities_pluck,'birth_cities'=>$birth_cities,'is_editable'=>$is_editable])
                </affiliate-show>
            </div>
            <div id="tab-police-info" class="tab-pane">
                <affiliate-police :affiliate="{{ $affiliate }}" :eco-com-id="{{ $economic_complement->id }}" inline-template :type-eco-com="true"
                    :categories="{{$categories}}">
    @include('affiliates.affiliate_police_information', ['affiliate'=>$affiliate, 'affiliate_states'=>$affiliate_states, 'categories'
                    => $categories->pluck('name', 'id'), 'degrees'=> $degrees->pluck('name', 'id'), 'pension_entities'=>
                    $pension_entities->pluck('name', 'id')])
                </affiliate-police>
            </div>
            <div id="tab-eco-com-beneficiary" class="tab-pane">
                <eco-com-beneficiary :eco-com="{{ $economic_complement }}" :cities="{{ $cities }}" :permissions="{{ $permissions }}"
                >
                </eco-com-beneficiary>
            </div>
            <div id="tab-eco-com-legal-guardian" class="tab-pane">
                <eco-com-legal-guardian :eco-com="{{ $economic_complement }}" :eco-com-legal-guardian-types="{{ $eco_com_legal_guardian_types }}" :cities="{{ $cities }}" :permissions="{{ $permissions }}">
                </eco-com-legal-guardian>
                {{-- @include('eco_com.beneficiary', ['eco_com_beneficiary'=>$eco_com_beneficiary,
                'cities'=>$cities]) --}}
            </div>
            <div id="tab-summited-document" class="tab-pane">
                {{-- @can('view',new Muserpol\Models\RetirementFund\RetFunSubmittedDocument) --}}
                <eco-com-step1-requirements-edit :eco-com="{{ $economic_complement }}" :procedure-modalities="{{ $procedure_modalities }}"
                    :requirements="{{ $requirements }}" :user="{{ $user }}" :cities="{{ $cities }}" :procedure-types="{{$procedure_types}}"
                    :submitted="{{$submit_documents}}" :rol="{{Muserpol\Helpers\Util::getRol()->id}}" inline-template>
    @include('eco_com.step1_requirements_edit')
                </eco-com-step1-requirements-edit>
                {{-- @endcan --}}
            </div>
            <div id="tab-qualification" class="tab-pane">
            <eco-com-qualification :eco-com-id="{{ $economic_complement->id }}" :affiliate="{{ $affiliate }}" :permissions="{{ $permissions }}">
                </eco-com-qualification>
            </div>
            <div id="tab-observations" class="tab-pane">
            <eco-com-observations :observation-types="{{ $observation_types }}"  :eco-com="{{ $economic_complement }}" :permissions="{{ $permissions }}"></eco-com-observations>
                {{-- @include('eco_com.observation') --}}
            </div>
            <div id="tab-record" class="tab-pane">
                <eco-com-record :eco-com="{{ $economic_complement }}" :permissions="{{ $permissions }}"></eco-com-record>
            </div>
        </div>
    </div>
</div>
<br>
@endsection

@section('styles')
<link rel="stylesheet" href="{{asset('/css/datatable.css')}}">
<style>
.elements-list .list-group-item.active, .elements-list .list-group-item:hover {
    background: #f3f3f4;
    color: inherit;
    border-color: #e7eaec;
    border-radius: 0;
    border-left: 2px solid #1a6a9d;
}
</style>
@endsection
 
@section('jss')
<script src="{{ asset('/js/datatables.js')}}"></script>
@endsection