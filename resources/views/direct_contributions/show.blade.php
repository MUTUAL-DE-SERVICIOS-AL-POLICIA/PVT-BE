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
            @if ($contribution_process)
                <correlative doc-id="{{ $contribution_process->id }}" wf-state-id="{{ $contribution_process->wf_state_current_id }}" type="contributionProcess"></correlative>
                <certification-button
                type="contributionProcess"
                title="Imprimir Cotizacion"
                doc-id="{{ $contribution_process->id }}"
                url-print="{{ route('contribution_process_print_quotation', [$direct_contribution->id,$contribution_process->id]) }}"
            >
            </certification-button> 
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
                @if ($can_validate)
                <inbox-send-back-button-quota-aid
                    :wf-sequence-back-list="{{ $wf_sequences_back }}"
                    :doc-id="{{$contribution_process->id}}"
                    :wf-current-state-name="`{{$contribution_process->wf_state->name}}`"
                    type="contributionProcess"
                ></inbox-send-back-button-quota-aid>
                <sweet-alert-modal inline-template :doc-id="{{$contribution_process->id}}" :inbox-state="{{$contribution_process->inbox_state ? 'true' : 'false'}}"
                    :doc-user-id="{{$contribution_process->user_id}}" :auth-id="{{ $user->id}}" type="contributionProcess">
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
                <li class="list-group-item " data-toggle="tab" href="#tab-spouse-info"><a href="#"><i class="fa fa-user"></i> Cónyuge</a></li>
                <li class="list-group-item " data-toggle="tab" href="#tab-contributions"><a href="#"><i class="fa fa-users"></i> Contribuciones</a></li>
                <li class="list-group-item " data-toggle="tab" href="#tab-payment"><a href="#"><i class="fa fa-dollar"></i> Pagos</a></li>
                <li class="list-group-item " data-toggle="tab" href="#tab-summited-document"><a href="#"><i class="fa fa-file"></i> Documentos Presentados</a></li>                
                <li class="list-group-item " data-toggle="tab" href="#tab-observations"><a href="#"><i class="fa fa-eye-slash"></i> Observaciones</a></li>
            </ul>
        </div>
        <br>
        {{-- <tag-list :doc-id="{{ $direct_contribution->id }}" type="contributionProcess"></tag-list> --}}
    </div>
    <br>
    <div class="col-md-9" style="padding-left: 6px">
        <div class="tab-content">
            <div id="tab-ret-fun" class="tab-pane active">
                {{-- @can('update',$direct_contribution) --}}
                <direct-contribution-info :direct_contribution="{{ $direct_contribution }}" :city_start="{{json_encode($direct_contribution->city_start)}}" :city_end="{{json_encode($direct_contribution->city_end)}}"
                    :procedure_modality="{{$direct_contribution->procedure_modality}}" :states="{{ $states }}" inline-template>
                    @include('direct_contributions.info', ['direct_contribution'=>$direct_contribution,'cities'=>$birth_cities])
                </direct-contribution-info>
                {{-- @endcan --}}
            </div>
            <div id="tab-affiliate" class="tab-pane">
                <affiliate-show :affiliate="{{ $affiliate }}" :cities="{{$cities}}" inline-template>
                    @include('affiliates.affiliate_personal_information',['affiliate'=>$affiliate,'cities'=>$cities_pluck,'birth_cities'=>$birth_cities,'is_editable'=>$is_editable])
                </affiliate-show>

            </div>            
            <div id="tab-spouse-info" class="tab-pane">
                <spouse-show :spouse="{{ $spouse }}" :affiliate-id="{{ $affiliate->id }}" :cities="{{ $birth_cities }}" inline-template>
                    @include('spouses.spouse_personal_information', ['spouse'=>$spouse])
                </spouse-show>
            </div>
            <div id="tab-contributions" class="tab-pane">
                @if($direct_contribution->procedure_modality->procedure_type_id == 6)
                    @include('contribution.affiliate_contribution_show',
                    [
                        'contributions' =>  $contributions,
                        'month_end' =>  $month_end,
                        'month_start'  =>   $month_start,
                        'year_end'  =>  $year_end,
                        'year_start'    =>  $year_start
                    ])
                @endif
                @if($direct_contribution->procedure_modality->procedure_type_id == 7)
                    @include('contribution.affiliate_aid_contribution_show',
                    [
                        'contributions' =>  $aid_contributions,
                        'month_end' =>  $month_death,
                        'month_start'  =>   $month_end,
                        'year_end'  =>  $year_death,
                        'year_start'    =>  $year_end
                    ])
                @endif
                </div>
            <div id="tab-summited-document" class="tab-pane">                
                {{-- @can('view',new Muserpol\Models\RetirementFund\RetFunSubmittedDocument)  --}}
                <direct-contribution-step1-requirements-edit 
                    :direct_contribution="{{ $direct_contribution }}" 
                    :modalities="{{ $modalities }}" 
                    :requirements="{{ $requirements }}"                    
                    :cities="{{ $cities }}" 
                    :procedure-types="{{$procedure_types}}" 
                    :submitted="{{ json_encode($submitted_documents) }}"
                    :rol="{{Muserpol\Helpers\Util::getRol()->id}}" inline-template>
                    @include('direct_contributions.step1_requirements_edit')
                </direct_contribution-step1-requirements-edit>
                {{-- @endcan --}}
            </div>
            
            <div id="tab-payment" class="tab-pane">                
                @include('direct_contributions.payments', 
                [
                    'contribution_processes' => $contribution_processes, 
                    'affiliate_id'=>$affiliate->id,
                    //'voucher'   =>  $voucher,
                ]) 
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
<style>
    .elements-list .list-group-item:hover{ cursor: pointer; }
</style>
@endsection
@section('jss')
<script src="{{ asset('/js/datatables.js')}}"></script>
<script>
$(document).ready(function() {

    function moneyInputMask() {

            return {
                alias: "numeric",
                groupSeparator: ",",
                autoGroup: true,
                digits: 2,
                digitsOptional: false,
                placeholder: "0"
            };
        }
        $('.numberformat').each(function(i, obj) {
            Inputmask(moneyInputMask()).mask(obj);
        });
    //revisar dependecias XD
    //revisar dependecias XD
    // $('.file-box').each(function() {
    //     animationHover(this, 'pulse');
    // });
} );
</script>
@endsection
