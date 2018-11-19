@extends('layouts.app')
@section('title', 'Contribuciones')
@section('styles')
<link rel="stylesheet" href="{{asset('/css/datatables.css')}}">
@endsection
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-md-7">
        {!!Breadcrumbs::render('show_direct_contribution', $direct_contribution)!!}
    </div>
    <div class="col-md-5 text-center" style="margin-top:12px;">
        <div class="pull-left">
            {{-- <correlative doc-id="{{ $retirement_fund->id }}" wf-state-id="{{ $retirement_fund->wf_state_current_id }}" type="retFun"></correlative> --}}
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
                <inbox-send-back-button-ret-fun :wf-sequence-back-list="{{ $wf_sequences_back }}" :doc-id="{{$retirement_fund->id}}" :wf-current-state-name="`{{$retirement_fund->wf_state->name}}`"
                    type="retFun"></inbox-send-back-button-ret-fun>
                <sweet-alert-modal inline-template :doc-id="{{$retirement_fund->id}}" :inbox-state="{{$retirement_fund->inbox_state ? 'true' : 'false'}}"
                    :doc-user-id="{{$retirement_fund->user_id}}" :auth-id="{{ $user->id}}" type="retFun">
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
                    <a href="{{route('affiliate.show', $affiliate->id)}}" style="color: #fff"> {{ $direct_contribution->affiliate->fullNameWithDegree() }}</a>
                </h2>
                <h3 class="text-center" data-toggle="tooltip" data-placement="top" title="Cédula de Identidad"><strong>{{  $direct_contribution->affiliate->ciWithExt() }}</strong></h3>
                <h4 class="text-center" data-toggle="tooltip" data-placement="top" title="Matricula"><strong>{{  $direct_contribution->affiliate->registration }}</strong></h4>
            </div>
        </div>
        <div class="widget-text-box">
            <ul class="list-group elements-list">
                <li class="list-group-item active" data-toggle="tab" href="#tab-ret-fun"><a href="#"><i class="glyphicon glyphicon-piggy-bank"></i> Aporte Directo</a></li>                
                <li class="list-group-item " data-toggle="tab" href="#tab-affiliate"><a href="#"><i class="fa fa-user"></i> Afiliado</a></li>
                <li class="list-group-item " data-toggle="tab" href="#tab-affiliate"><a href="#"><i class="fa fa-user"></i> Cónyuge</a></li>
                <li class="list-group-item " data-toggle="tab" href="#tab-beneficiaries"><a href="#"><i class="fa fa-users"></i> Contribuciones</a></li>
                <li class="list-group-item " data-toggle="tab" href="#tab-folder"><a href="#"><i class="fa fa-copy"></i> Pagos</a></li>
                <li class="list-group-item " data-toggle="tab" href="#tab-summited-document"><a href="#"><i class="fa fa-file"></i> Documentos Presentados</a></li>                
                <li class="list-group-item " data-toggle="tab" href="#tab-observations"><a href="#"><i class="fa fa-eye-slash"></i> Observaciones</a></li>
            </ul>
        </div>
        <br>
        <tag-list :doc-id="{{ $direct_contribution->id }}" type="contributionProcess"></tag-list>
    </div>
    <br>
    <div class="col-md-9" style="padding-left: 6px">
        <div class="tab-content">
            <div id="tab-ret-fun" class="tab-pane active">
                {{-- @can('update',$retirement_fund)
                <ret-fun-info :retirement_fund="{{ $retirement_fund }}" :rf_city_start="{{$retirement_fund->city_start}}" :rf_city_end="{{$retirement_fund->city_end}}"
                    :rf_procedure_modality=" {{$retirement_fund->procedure_modality}}" :states="{{ $states }}" inline-template>
                    @include('ret_fun.info', ['retirement_fund'=>$retirement_fund,'cities'=>$birth_cities])
                </ret-fun-info>
                @endcan --}}
            </div>
            <div id="tab-affiliate" class="tab-pane">
                <affiliate-show :affiliate="{{ $affiliate }}" :cities="{{$cities}}" inline-template>
                    @include('affiliates.affiliate_personal_information',['affiliate'=>$affiliate,'cities'=>$cities_pluck,'birth_cities'=>$birth_cities,'is_editable'=>$is_editable])
                </affiliate-show>

            </div>
            <div id="tab-beneficiaries" class="tab-pane">

                @can('view',new Muserpol\Models\RetirementFund\RetFunBeneficiary)
                    {{-- @include('ret_fun.beneficiaries_list', ['beneficiaries'=>$beneficiaries,'cities'=>$cities,'kinships'=>$kinships]) --}}
                @endcan

            </div>
            <div id="tab-summited-document" class="tab-pane">

                {{-- @can('view',new Muserpol\Models\RetirementFund\RetFunSubmittedDocument) 
                <ret-fun-step1-requirements-edit :ret_fun="{{ $retirement_fund }}" :modalities="{{ $modalities }}" :requirements="{{ $requirements }}"
                    :user="{{ $user }}" :cities="{{ $cities }}" :procedure-types="{{$procedure_types}}" :submitted="{{$submit_documents}}"
                    :rol="{{Muserpol\Helpers\Util::getRol()->id}}" inline-template>
                    @include('ret_fun.step1_requirements_edit')
                </ret-fun-step1-requirements-edit>
                @endcan --}}

            </div>

            <div id="tab-individual-accounts" class="tab-pane">
            </div>
            <div id="tab-qualification" class="tab-pane">
                {{-- @include('ret_fun.summary_qualification', ['retirement_fund'=>$retirement_fund,'affiliate'=>$affiliate]) --}}
            </div>
            <div id="tab-folder" class="tab-pane">
                @can('view',new Muserpol\Models\AffiliateFolder)
                    {{-- @include('affiliates.folder', ['folders'=>$affiliate->affiliate_folders,'procedure_modalities'=>$procedure_modalities,'affiliate_id'=>$affiliate->id]) --}}
                @endcan
            </div>
            <div id="tab-observations" class="tab-pane">
                {{-- @include('ret_fun.observation') --}}
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