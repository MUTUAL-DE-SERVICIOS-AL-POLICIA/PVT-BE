show blade 


@extends('layouts.app') 
@section('title', 'Complemento economico Padre') 
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-md-7">
        {!!Breadcrumbs::render('show_eco_com', $economic_complement)!!}
    </div>
    <div class="col-md-5 text-center" style="margin-top:12px;">
  
        <div class="pull-left">
            @if(Util::isReceptionEcoCom()||Util::isRegionalRole())
                @if ($economic_complement->isLagging())
                    <certification-button
                        type="ecoCom"
                        title="Imprimir Rezagado"
                        doc-id="{{ $economic_complement->id }}"
                        url-print="{{ route('eco_com_print_lagging', [$economic_complement->id])}}">
                    </certification-button>
                @endif
            @endif
            @if (Util::getRol()->id == 4)
                <certification-button
                    type="ecoCom"
                    title="Imprimir Calificacion"
                    doc-id="{{ $economic_complement->id }}"
                    message="true"
                    url-print="{{ route('eco_com_print_qualification', [$economic_complement->id])}}">
                </certification-button>
            @endif
            @if ((Util::getRol()->id == 4 ) and ($economic_complement->eco_com_reception_type->name =='Inclusión' or $economic_complement->eco_com_reception_type->name =='Habitual-Rehabilitacion'))
                <certification-button
                    type="ecoCom"
                    title="Imprimir Revisión"
                    doc-id="{{ $economic_complement->id }}"
                    message="false"
                    url-print="{{ route('eco_com_print_revision_certificate', [$economic_complement->id])}}">
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
@if(!$economic_complement->hasFixedPension($economic_complement))
<div class="alert alert-danger alert-dismissable ">
El trámite es de tipo <strong>{{$economic_complement->eco_com_reception_type->name}}</strong> y no tiene el registro de rentas fijas.
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
                <li class="list-group-item active tab-eco-com" data-toggle="tab" href="#tab-eco-com"><a href="#"><i class="fa fa-puzzle-piece"></i> Información del Trámite</a></li>
                <li class="list-group-item tab-police-info" data-toggle="tab" href="#tab-police-info"><a href="#"><i class="fa fa-address-card"></i> Información del Policia Actualizada</a></li>
                <li class="list-group-item tab-eco-com-beneficiary" data-toggle="tab" href="#tab-eco-com-beneficiary"><a href="#"><i class="fa fa-users"></i> Beneficiario</a></li>
                <li class="list-group-item tab-eco-com-legal-guardian" data-toggle="tab" href="#tab-eco-com-legal-guardian"><a href="#"><i class="fa fa-shield"></i> Apoderado</a></li>
                <li class="list-group-item tab-eco-com-summited-document" data-toggle="tab" href="#tab-eco-com-summited-document"><a href="#"><i class="fa fa-file"></i> Documentos Presentados</a></li>
                <li class="list-group-item tab-eco-com-qualification" data-toggle="tab" href="#tab-eco-com-qualification"><a href="#"><i class="fa fa-dollar"></i> Calificacion</a></li>
                <li class="list-group-item tab-eco-com-review" data-toggle="tab" href="#tab-eco-com-review"><a href="#"><i class="fa fa-check"></i> Revisión</a></li>
                <li class="list-group-item tab-eco-com-observations" data-toggle="tab" href="#tab-eco-com-observations"><a href="#"><i class="fa fa-eye-slash"></i> Observaciones</a></li>
                <li class="list-group-item tab-eco-com-record" data-toggle="tab" href="#tab-eco-com-record" ><a href="#"><i class="fa fa-history"></i> Historial</a></li>
               <!-- <li class="list-group-item tab-eco-com-record" data-toggle="tab" href="#tab-eco-com-boleta" ><a href="#"><i class="fa fa-camera-retro"></i> Boleta</a></li>-->
            </ul>
        </div>
        <br>
        <tag-list :doc-id="{{ $economic_complement->id }}" type="ecoCom"></tag-list>
    </div>
    <br>
    <div class="col-md-9" style="padding-left: 6px">
        <div class="tab-content">
            <div id="tab-eco-com" class="tab-pane active">
                <eco-com-info
                    :eco-com="{{ $economic_complement }}"
                    :affiliate="{{$affiliate}}"
                    :eco-com-procedure="{{ $economic_complement->eco_com_procedure }}"
                    :states="{{ $states }}"
                    :cities="{{ $cities }}"
                    :degrees="{{$degrees}}"
                    :categories="{{ $categories }}"
                    :permissions="{{ $permissions }}"
                    :role-id="{{ Util::getRol()->id }}"
                    :user="{{ Auth::user() }}"
                    :wf-current-state="{{ $wf_current_state }}"
                >
                </eco-com-info>
            </div>
            <div id="tab-eco-coms" class="tab-pane">

            </div>
            <div id="tab-affiliate" class="tab-pane">
                <affiliate-show
                    :affiliate="{{ $affiliate }}"
                    :cities="{{$cities}}"
                    inline-template
                >
                    @include('affiliates.affiliate_personal_information',
                        ['affiliate'=>$affiliate,
                         'cities'=>$cities_pluck,
                         'birth_cities'=>$birth_cities,
                         'is_editable'=>$is_editable
                        ]
                    )
                </affiliate-show>
            </div>
            <div id="tab-police-info" class="tab-pane">
                <affiliate-police
                    :affiliate="{{ $affiliate }}"
                    :eco-com-id="{{ $economic_complement->id }}"
                    :eco-com="{{ $economic_complement }}"
                    :categories="{{$categories}}"
                    :user="{{ Auth::user() }}"
                    :wf-current-state="{{ $wf_current_state }}"
                    inline-template
                >
                    @include('affiliates.affiliate_police_information',
                        ['affiliate'=>$affiliate,
                         'affiliate_states'=>$affiliate_states,
                         'categories' => $categories->pluck('name', 'id'),
                         'degrees'=> $degrees->pluck('name', 'id'),
                         'pension_entities'=> $pension_entities->pluck('name', 'id'),
                         'wf_current_state' => $wf_current_state->role_id
                        ]
                    )
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
            </div>
            <div id="tab-eco-com-summited-document" class="tab-pane">
                <eco-com-step1-requirements-edit :affiliate="{{ $affiliate }}" :eco-com="{{ $economic_complement }}" :requirements='@json($requirements)' :submitted="{{$submit_documents}}" :rol="{{Muserpol\Helpers\Util::getRol()->id}}">
                </eco-com-step1-requirements-edit>
            </div>
            <div id="tab-eco-com-qualification" class="tab-pane">
            <eco-com-qualification :eco-com-id="{{ $economic_complement->id }}" :affiliate="{{ $affiliate }}" :permissions="{{ $permissions }}" :role-id="{{ Util::getRol()->id }}" :observations="{{ $observations }}">
                </eco-com-qualification>
            </div>
            <div id="tab-eco-com-review" class="tab-pane">
                <eco-com-review :eco-com="{{ $economic_complement->id }}" :user="{{ $user }}" :rol="{{Muserpol\Helpers\Util::getRol()->id}}">
                </eco-com-review>
            </div>
            <div id="tab-eco-com-observations" class="tab-pane">
            <eco-com-observations :observation-types="{{ $observation_types }}"  :eco-com="{{ $economic_complement }}" :permissions="{{ $permissions }}"></eco-com-observations>
            </div>
            <div id="tab-eco-com-record" class="tab-pane">
                <eco-com-record :eco-com="{{ $economic_complement }}" :permissions="{{ $permissions }}"></eco-com-record>
            </div>
        
        </div>
    </div>
</div>
<br>
@endsection

@section('styles')
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