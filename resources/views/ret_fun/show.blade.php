@extends('layouts.app')

@section('title', 'Fondo de Retiro')
@section('styles')
<style>
.progressbar-container{

    /* height: 100px; */
  width: 100%;
  position: realtive;
  z-index: 1;
  display: block;
}
.progressbar li{
    float: left;
    width: 10%;
    position: relative;
    text-align: center;
}
.progressbar li:before{
  content:"1";
  width: 30px;
  height: 30px;
}
.progressbar li:before{
  content:"1";
  width: 30px;
  height: 30px;
  border: 2px solid #bebebe;
  display: block;
  margin: 0 auto 10px auto;
  border-radius: 50%;
  line-height: 27px;
  background: white;
  color: #bebebe;
  text-align: center;
  font-weight: bold;
}
.chosen-container .chosen-drop {
    border-bottom: 0;
    border-top: 1px solid #aaa;
    top: auto;
    bottom: 40px;
}
.progressbar{
  counter-reset: step;
}
.progressbar li:before{
  content:counter(step);
  counter-increment: step;
  width: 30px;
  height: 30px;
  border: 2px solid #bebebe;
  display: block;
  margin: 0 auto 10px auto;
  border-radius: 50%;
  line-height: 27px;
  background: white;
  color: #bebebe;
  text-align: center;
  font-weight: bold;
}
.progressbar li:after{
  content: '';
  position: absolute;
  width:100%;
  height: 3px;
  background: #979797;
  top: 15px;
  left: -50%;
  z-index: -1;
}
.progressbar li:first-child:after{
    content: none;
}
.progressbar li.active:first-child + li.active:after{
    border-color: #3aac5d;
    background: #3aac5d;
    color: white
}
.progressbar li.active + li:after{
    /* background: #3aac5d; */
}
.progressbar li.active + li:before{
    border-color: #3aac5d;
    background: #3aac5d;
    color: white
}
</style>
<link rel="stylesheet" href="{{asset('/css/datatables.css')}}">
@endsection
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-md-7">
        {!!Breadcrumbs::render('show_retirement_fund', $retirement_fund)!!}
    </div>
    <div class="col-md-5 text-center" style="margin-top:12px;">
            <div class="pull-left">
                <correlative doc-id="{{ $retirement_fund->id }}" wf-state-id="{{ $retirement_fund->wf_state_current_id }}" type="retFun"></correlative>
            
            @if(Util::getRol()->id == Muserpol\Helpers\ID::roles()->liquidationFR)
                <ret-fun-certification-button
                    title="Imprimir Liquidación"
                    ret-fun-id="{{ $retirement_fund->id }}"
                    url-print="{{ route('ret_fun_print_liquidation', $retirement_fund->id) }}"
                >
                </ret-fun-certification-button>
            @endif

            @if(Util::getRol()->id == 10 || Util::isRegionalRole())
                <ret-fun-certification-button
                    title="Imprimir recepción"
                    ret-fun-id="{{ $retirement_fund->id }}"
                    url-print="{{ route('ret_fun_print_reception', $retirement_fund->id) }}"
                    type="retFun"
                >
                </ret-fun-certification-button>
            @endif

            @if(Muserpol\Helpers\Util::getRol()->id == 15)
                <ret-fun-certification-button
                    title="Imprimir Certificacion de Archivo"
                    ret-fun-id="{{ $retirement_fund->id }}"
                    url-print="{{ route('ret_fun_print_file', $affiliate->id) }}"
                >
                </ret-fun-certification-button>
            @endif

            @if(Muserpol\Helpers\Util::getRol()->id == 28)
                <ret-fun-certification-button
                    title="Imprimir Revisi&oacute;n de Jefatura"
                    ret-fun-id="{{ $retirement_fund->id }}"
                    url-print="{{ route('ret_fun_print_headship_review', $retirement_fund->id)}}"
                >
                </ret-fun-certification-button>
            @endif

            @if(Muserpol\Helpers\Util::getRol()->id == 29)
                <ret-fun-certification-button
                    title="Imprimir Resoluci&oacute;n Legal"
                    ret-fun-id="{{ $retirement_fund->id }}"
                    url-print="{{ route('ret_fun_print_legal_resolution', $retirement_fund->id) }}"
                    message="true"
                >
                </ret-fun-certification-button>
            @endif
            @if(Muserpol\Helpers\Util::getRol()->id == 29)
                <button class="btn btn-primary dim" type="button" data-toggle="tooltip" data-placement="top" title="Imprimir Notificacion"
                onclick="printJS({printable:'{!! route('resolution_notification') !!}', type:'pdf', modalMessage: 'Generando documentos de impresión por favor espere un momento.', showModal:true})"><i class="fa fa-print"></i> Imprimir Notificacion</button>
            @endif

            @if(Muserpol\Helpers\Util::getRol()->id == 11)
                <ret-fun-certification-button
                    title="Imprimir Certificacion de Documentacion Presentada y Revisada"
                    ret-fun-id="{{ $retirement_fund->id }}"
                    url-print="{{ route('ret_fun_print_legal_review', $retirement_fund->id) }}"
                >
                </ret-fun-certification-button>
            @endif
            @if(Muserpol\Helpers\Util::getRol()->id == 12)
                @can('view', new Muserpol\Models\Contribution\Contribution)
                    <a  href="{{ url('ret_fun/'.$retirement_fund->id.'/selectcontributions')}}" >
                        <button class="btn btn-primary btn-sm dim"  data-toggle="tooltip" data-placement="top" title="Clasificar Aportes">
                        <i class="fa fa-list-alt" style="font-size:15px"></i> Clasificar Aportes
                        </button>
                    </a>
                @endcan
            @endif
            @if(Muserpol\Helpers\Util::getRol()->id == 13)
                <ret-fun-certification-button
                    title="Imprimir todos los documentos de Calificacion"
                    ret-fun-id="{{ $retirement_fund->id }}"
                    url-print="{{ route('ret_fun_print_all_qualification', $retirement_fund->id) }}"
                >
                </ret-fun-certification-button>
            @endif
            @can('qualify', $retirement_fund)
                <a href="{{route('ret_fun_qualification', $retirement_fund->id)}}">
                    <button class="btn btn-info btn-sm dim" type="button" data-toggle="tooltip" data-placement="top" title="Calificacion" ><i class="fa fa-dollar" style="font-size:15px;"></i> Calificacion</button>
                </a>
            @endcan
            <span data-toggle="modal" data-target="#ModalRecordRetFun">
                <button type="button" class="btn btn-info btn-sm dim" data-toggle="tooltip" data-placement="top" title="Historial del Trámite">
                    <i class="fa fa-history" style="font-size:15px;"></i> Historial del Trámite
                </button>
            </span>
            @include('ret_fun.ret_fun_record', ['ret_fun_records' => $ret_fun_records,])
            @if(Muserpol\Helpers\Util::getRol()->id == 29)
                <ret-fun-certification-button
                    title="Imprimir Dictamen Legal"
                    ret-fun-id="{{ $retirement_fund->id }}"
                    url-print="{{ route('ret_fun_print_legal_dictum', $retirement_fund->id)}}"
                    message="true"
                >
                </ret-fun-certification-button>
            @endif
        </div>
        <div class="pull-right">
            <div class="form-inline">
                @if ($can_validate)
                    <inbox-send-back-button-ret-fun
                        :wf-sequence-back-list="{{ $wf_sequences_back }}"
                        :doc-id="{{$retirement_fund->id}}"
                        :wf-current-state-name="`{{$retirement_fund->wf_state->name}}`"
                        type="retFun"
                    ></inbox-send-back-button-ret-fun>
                      <sweet-alert-modal inline-template :doc-id="{{$retirement_fund->id}}" :inbox-state="{{$retirement_fund->inbox_state ? 'true' : 'false'}}" :doc-user-id="{{$retirement_fund->user_id}}" :auth-id="{{ $user->id}}" type="retFun" >
                          <transition name="fade" mode="out-in" :duration="300" enter-active-class="animated tada" leave-active-class="animated bounceOutRight">
                              <div style="display:inline-block" v-if="status == true" key="one" data-toggle="tooltip" data-placement="top" title="Cancelar Revision del Trámite">
                                  <button  class="btn btn-danger btn-circle btn-outline btn-lg active" type="button" @click="cancelModal()" v-if="itisMine"><i class="fa fa-times"></i></button>
                              </div>
                              <div style="display:inline-block" v-else key="two" data-toggle="tooltip" data-placement="top" title="Procesar Trámite">
                                  <button class="btn btn-primary btn-circle btn-outline btn-lg" type="button" @click="confirmModal()" :disabled="! status == false " ><i class="fa fa-check"></i></button>
                              </div>
                          </transition>
                      </sweet-alert-modal>
                  @endif
            </div>
        </div>
    </div>
</div>

{{-- <div class="row">
    <div class="col-lg-12" style="margin-top: 15px;">
        <div class="progressbar-container">
            <ul class="progressbar" style="list-style:none">
                <li class="">{{ $first_wf_state->name }}</li>
                @foreach ($wf_states as $index=>$w)
                    @if($w->sequence_number+1 <= $retirement_fund->wf_state->sequence_number)
                        <li class="active">{{ $w->name }}</li>
                    @else
                        <li class="">{{ $w->name }}</li>
                    @endif
                @endforeach
            </ul>
        </div>
    </div>
</div> --}}


@if(Session::has('message'))
    <br>
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        {{Session::get('message')}}
    </div>

@endif
<!---Alerta de pago garantes----->
<ret-fun-alert
        :affiliate-id="{{$affiliate->id}}"
    >
    </ret-fun-alert>
<!-------->

    <div class="row">

            <div class="col-md-3" style="padding-right: 3px">
                    <div class="widget-head-color-box yellow-bg p-lg text-center">
                        <div class="m-b-md">
                        <h2 class="font-bold no-margins" data-toggle="tooltip" data-placement="top" title="Ver Afiliado ">
                        <a  href="{{route('affiliate.show', $affiliate->id)}}"  style="color: #fff"> {{ $retirement_fund->affiliate->fullNameWithDegree() }}</a>
                        </h2>
                            <h3 class="text-center" data-toggle="tooltip" data-placement="top" title="Cédula de Identidad"><strong>{{  $retirement_fund->affiliate->identity_card }}</strong></h3>
                            <h4 class="text-center" data-toggle="tooltip" data-placement="top" title="Matricula"><strong>{{  $retirement_fund->affiliate->registration }}</strong></h4>
                        </div>
                    </div>
                    <div class="widget-text-box">
                            <ul class="list-group elements-list">
                                <li class="list-group-item active" data-toggle="tab" href="#tab-ret-fun"><a href="#"><i class="glyphicon glyphicon-piggy-bank"></i> Fondo de Retiro</a></li>
                                @if($retirement_fund->procedure_modality_id == 4 || $retirement_fund->procedure_modality_id == 1 || $retirement_fund->procedure_modality_id == 63 )
                                <li class="list-group-item " data-toggle="tab" href="#tab-affiliate" ><a href="#"><i class="fa fa-user"></i> Afiliado </a></li>
                                @endif
                                <li class="list-group-item " data-toggle="tab" href="#tab-beneficiaries"><a href="#"><i class="fa fa-users"></i> Beneficiarios</a></li>
                                <li class="list-group-item " data-toggle="tab" href="#tab-folder"><a href="#"><i class="fa fa-copy"></i> Archivos</a></li>
                                <li class="list-group-item " data-toggle="tab" href="#tab-summited-document"><a href="#"><i class="fa fa-file"></i> Documentos Presentados</a></li>
                                <li class="list-group-item " data-toggle="tab" href="#tab-individual-accounts"><a href="#"><i class="fa fa-list"></i> Cuentas Individuales</a></li>
                                <li class="list-group-item " data-toggle="tab" href="#tab-qualification"><a href="#"><i class="fa fa-dollar"></i> Calificacion</a></li>
                                {{-- <li class="list-group-item " data-toggle="tab" href="#tab-headship"><a href="#"><i class="fa fa-dollar"></i> Jefatura</a></li> --}}
                                <li class="list-group-item " data-toggle="tab" href="#tab-observations"><a href="#"><i class="fa fa-eye-slash"></i> Observaciones</a></li>
                            </ul>
                    </div>
                    <br>
                <tag-list :doc-id="{{ $retirement_fund->id }}" type="retFun"></tag-list>
            </div>
            <br>
            <div class="col-md-9" style="padding-left: 6px">

                    <div class="tab-content">
                            <div id="tab-ret-fun" class="tab-pane active">

                                        @can('update',$retirement_fund)
                                            <ret-fun-info :retirement_fund="{{ $retirement_fund }}" :rf_city_start="{{$retirement_fund->city_start}}" :rf_city_end="{{$retirement_fund->city_end}}" :rf_procedure_modality=" {{$retirement_fund->procedure_modality}}" :states="{{ $states }}" :rf_procedure_type=" {{$retirement_fund->procedure_modality->procedure_type}}" :rf_wf_state ="{{$retirement_fund->wf_state}}" inline-template>
                                                @include('ret_fun.info', ['retirement_fund'=>$retirement_fund,'cities'=>$birth_cities])
                                            </ret-fun-info>
                                        @endcan

                            </div>
                            <div id="tab-affiliate" class="tab-pane">

                                    <affiliate-show  :affiliate="{{ $affiliate }}" :cities="{{$cities}}" inline-template>
                                        @include('affiliates.affiliate_personal_information',['affiliate'=>$affiliate,'cities'=>$cities_pluck,'birth_cities'=>$birth_cities,'is_editable'=>$is_editable])
                                    </affiliate-show>

                            </div>
                            <div id="tab-beneficiaries" class="tab-pane">

                                    @can('view',new Muserpol\Models\RetirementFund\RetFunBeneficiary)
                                        @include('ret_fun.beneficiaries_list', ['beneficiaries'=>$beneficiaries,'cities'=>$cities,'kinships'=>$kinships,'kinship_beneficiaries'=>$kinship_beneficiaries])
                                    @endcan

                            </div>
                            <div id="tab-summited-document" class="tab-pane">

                                @can('view',new Muserpol\Models\RetirementFund\RetFunSubmittedDocument)
                                        {{-- @include('ret_fun.legal_review', ['affiliate'=>$affiliate,'retirement_fund'=>$retirement_fund,'documents'=>$documents]) --}}                                        
                                    <ret-fun-step1-requirements-edit :ret_fun="{{ $retirement_fund }}" :modalities="{{ $modalities }}" :requirements="{{ $requirements }}" :user="{{ $user }}" :cities="{{ $cities }}" :procedure-types="{{$procedure_types}}" :submitted="{{$submit_documents}}" :rol="{{Muserpol\Helpers\Util::getRol()->id}}" inline-template>
                                        @include('ret_fun.step1_requirements_edit')
                                    </ret-fun-step1-requirements-edit>
                                @endcan
                            </div>
                            <div id="tab-headship" class="tab-pane">
                                {{-- @can('view',new Muserpol\Models\RetirementFund\RetFunSubmittedDocument) --}}
                                    {{-- <ret-fun-heaship :ret_fun="{{ $retirement_fund }}" user="{{ $user }}" inline-template>
                                        @include('ret_fun.headship')
                                    </ret-fun-heaship> --}}
                                {{-- @endcan --}}
                            </div>

                            <div id="tab-individual-accounts" class="tab-pane">
                                @can('view',new Muserpol\Models\Contribution\Contribution)
                                    <ret-fun-qualification
                                    inline-template
                                    :retirement-fund-id="{{$retirement_fund->id}}"
                                    :contributions="{{$all_contributions}}"
                                    :affiliate="{{$retirement_fund->affiliate}}"
                                    >
                                        @include('ret_fun.summary_individual_accounts')
                                    </ret-fun-qualification>
                                    {{-- <summary-select-contributions
                                        :contributions="{{json_encode($contributions_select)}}"
                                        :retfunid="{{$retirement_fund->id}}"
                                        :contype="{{true}}"
                                        :types="{{json_encode($contribution_types)}}"
                                        :start-date="{{json_encode($date_entry)}}"
                                        :end-date="{{json_encode($date_derelict)}}"
                                        inline-template
                                        >
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="ibox">
                                                    <div class="ibox-content forum-container">
                                                        <div class="forum-title">
                                                            <h3>Tabla de contribuciones</h3>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-1">
                                                                <label style="font-size:80%;"> @{{ endDate | monthYear }} </label>
                                                                <div style="border-style: solid; border-width: 1px; ">
                                                                    <div v-for="(contribution,index) in contributions" :key="index">
                                                                        <div :style="{background: getColor1(contribution.contribution_type_id), display: 'block', width: '100%', height: row_higth+'px'}"
                                                                            @click="selectRow(index)" data-toggle="tooltip" data-placement="left" :title="contribution.month_year | monthYear"></div>
                                                                    </div>
                                                                </div>
                                                                <label style="font-size:80%;"> @{{ startDate | monthYear }} </label>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <table class="table table-fixed">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="col-md-2">Fecha </th>
                                                                            <th class="col-md-2">Total</th>
                                                                            <th class="col-md-4">Tipo</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="contenedor">
                                                                        <tr v-for="(contribution,index) in contributions" :key="`contribution-${index}`" :style="{'background':getColor1(contribution.contribution_type_id)}">
                                                                            <td class="col-md-2">@{{ contribution.month_year | monthYear }}</td>
                                                                            <td class="col-md-2">@{{ contribution.total }}</td>
                                                                            <td class="col-md-4">
                                                                                @{{ types.find(i => i.id == contribution.contribution_type_id) ? types.find(i => i.id == contribution.contribution_type_id).name : 'sin clasificar' }}
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <div class="col-md-2" style="padding-left:0px;padding-right: 2px">
                                                                <h3>Tipos de Aportes</h3>
                                                                <ul class="list-group">
                                                                    <transition-group name="custom" enter-active-class="animated bounceInRight" leave-active-class="animated bounceOutRight">
                                                                        <li v-for="(ct, index) in types" :key="`ct-ul-${index}`" v-if="count1(ct.id)" class="list-group-item comando" :style="{background: getColor1(ct.id)}"><span class="badge">@{{ count1(ct.id) }}</span> @{{ ct.name }} </li>
                                                                    </transition-group>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="ibox-footer">
                                                        <div class="pull-right">
                                                            <strong class=" text-info m-r-md"> Clasificados: @{{ countTotal()}} </strong>
                                                            <strong class=" text-danger m-r-md"> Faltantes: @{{ count1(null)}} </strong>
                                                            <strong> Cantidad Total: @{{contributions.length}} </strong>
                                                        </div>
                                                        <br>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </summary-select-contributions> --}}
                                @endcan

                            </div>
                            <div id="tab-qualification" class="tab-pane">

                                    {{-- @can('view',new Muserpol\Models\AffiliateFolder) --}}
                                        @include('ret_fun.summary_qualification', ['retirement_fund'=>$retirement_fund,'affiliate'=>$affiliate])
                                    {{-- @endcan --}}

                            </div>
                            <div id="tab-folder" class="tab-pane">

                                    @can('view',new Muserpol\Models\AffiliateFolder)
                                        @include('affiliates.folder', ['folders'=>$affiliate->affiliate_folders,'procedure_modalities'=>$procedure_modalities,'affiliate_id'=>$affiliate->id])
                                    @endcan

                            </div>
                            <div id="tab-observations" class="tab-pane">
                                    {{-- @can('view',new Muserpol\Models\ObservationType)--}}
                                    @include('ret_fun.observation', ['retirement_fund'=>$retirement_fund,'observations_delete'=>$retirement_fund->ret_fun_observations_delete])
                                    {{-- @endcan --}}
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
<script>
    $( document ).ready(function() {
        $('#folderDialog').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id=button.data('id')
            var cod_folder = button.data('codfile')
            var num_folder = button.data('folnum')
            var mod_id =button.data('modid');
            var note = button.data('note');
            var is_paid = button.data('ispaid');

            var modal_folder = $(this)
            $('#id_folder').val(id)
            //revisar esta parte con el nuevo disenio
            //if(typeof(is_paid) === "boolean"){
                if(is_paid == true){
                    $(".modal-body #paid").prop("checked", true);
                }
                if(is_paid == false){
                    $(".modal-body #nopaid").prop("checked", true);
                }
            //}

            modal_folder.find('.modal-body #cod_folder').val(cod_folder)
            modal_folder.find('.modal-body #num_folder').val(num_folder)
            modal_folder.find('.modal-body #note').val(note)
            modal_folder.find('.modal-body #mod_id').val(mod_id)
            // console.log($('#mod_id').val(moda_id))
        });
        $('#eliminar').on('show.bs.modal', function (event) {
            var modal_folder = $(this)
            var button = $(event.relatedTarget)
            // console.log('metodo 2')
            var folder_id = button.data('elim')
            // console.log($('#cod_file_eli').val(cod_folder))
            modal_folder.find('.modal-header #folder_id').val(folder_id)
        });
        // console.log( "del show... " );
        $('#editObservation').on('show.bs.modal', function (event) {
            var button_obs = $(event.relatedTarget)
            var id_obs=button_obs.data('id_obs')
            var moda_id =button_obs.data('modid');
            var message = button_obs.data('message');
            var enabled = button_obs.data('enabled');
            var name_obs = button_obs.data('name_obs');

            var modal_obs = $(this)
        $('#id_observation').val(id_obs)
                if(enabled == true){
                    $(".modal-body #is_enable").prop("checked", true);
                }
                if(enabled == false){
                    $(".modal-body #no_enable").prop("checked", true);
                }
            modal_obs.find('.modal-body #message').val(message)
            modal_obs.find('.modal-body #name_obs').val(name_obs)
            modal_obs.find('.modal-body #id_obs').val(id_obs)
            //console.log($('#mod_id').val(moda_id))
        });
        $('#eliminarObs').on('show.bs.modal', function (event) {
            var modal_obs = $(this)
            var button_obs = $(event.relatedTarget)
            // console.log('metodo 2')
            var id_observation = button_obs.data('elim')
            // console.log($('#cod_file_eli').val(cod_folder))
            modal_obs.find('.modal-header #id_observation').val(id_observation)
        });

    });
    var clic = 1;

    function showContent() {
        element = document.getElementById("content");
        check = document.getElementById("check");
        if (check.checked) {
            element.style.display='block';
        }
        else {
            element.style.display='none';
        }
    }
</script>
@endsection