@extends('layouts.app')

@section('title', 'Fondo de Retiro')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-md-7">
        {!!Breadcrumbs::render('show_retirement_fund', $retirement_fund)!!}
    </div>
    <div class="col-md-5 text-center" style="margin-top:12px;">
            <div class="pull-left">
                @if(Muserpol\Helpers\Util::getRol()->id == 10)
            <button class="btn btn-primary dim" type="button" data-toggle="tooltip" data-placement="top" title="Imprimir recepción" onclick="printJS({printable:'{!! route('ret_fun_print_reception', $retirement_fund->id) !!}', type:'pdf', modalMessage: 'Generando documentos de impresión por favor espere un momento.', showModal:true})"><i class="fa fa-print"></i></button> 
            @endif
            
            @if(Muserpol\Helpers\Util::getRol()->id == 15)
            <button class="btn btn-primary dim" type="button" data-toggle="tooltip" data-placement="top" title="Imprimir Certificacion de Archivo" onclick="printJS({printable:'{!! route('ret_fun_print_file', $affiliate->id) !!}', type:'pdf', showModal:true})"><i class="fa fa-print"></i></button>
            @endif
            
            @if(Muserpol\Helpers\Util::getRol()->id == 11)
            <button class="btn btn-primary dim" type="button" data-toggle="tooltip" data-placement="top" title="Imprimir Certificacion de Documentacion Presentada y Revisada" onclick="printJS({printable:'{!! route('ret_fun_print_legal_review', $retirement_fund->id) !!}', type:'pdf', showModal:true})"><i class="fa fa-print"></i></button>
            @endif
            @can('view', new Muserpol\Models\Contribution\Contribution)   
            <a  href="{{ url('ret_fun/'.$retirement_fund->id.'/selectcontributions')}}" >
                <button class="btn btn-primary dim"  data-toggle="tooltip" data-placement="top" title=" Clasificar Aportes " >
                <i class="fa fa-list-alt"></i>
                </button>
            </a>
            <a href="{{route('ret_fun_qualification', $retirement_fund->id)}}">
                <button class="btn btn-info btn-sm dim" type="button" data-toggle="tooltip" data-placement="top" title="Calificacion" ><i class="fa fa-dollar"></i></button>
            </a>
            @endcan
            <button type="button" class="btn btn-info btn-sm dim" data-toggle="modal" data-target="#ModalRecordRetFun" data-placement="top" title="Historial del Trámite">
                <i class="fa fa-history"></i>
            </button>
            @include('ret_fun.ret_fun_record', ['ret_fun_records' => $ret_fun_records,])
        </div>
        <div class="pull-right">
            @if ($has_validate)
                <swal-modal inline-template :doc-id="{{$retirement_fund->id}}" :inbox-state="{{$retirement_fund->inbox_state ? 'true' : 'false'}}">
                    <div>
                        <div v-if="status == true" data-toggle="tooltip" data-placement="top" title="Trámite ya procesado">
                            <button data-toggle="tooltip" data-placement="top" title="Trámite ya procesado" class="btn btn-primary btn-circle btn-outline btn-lg active" type="button" :disabled="! status == false " ><i class="fa fa-check"></i></button>
                        </div>
                        <div v-else>
                            <button data-toggle="tooltip" data-placement="top" title="Procesar Trámite" class="btn btn-primary btn-circle btn-outline btn-lg" type="button" @click="showModal()" :disabled="! status == false " ><i class="fa fa-check"></i></button>
                        </div>
                    </div>
                </swal-modal>
            @endif
        </div>
    </div>
</div>


    <div class="row">
           
            <div class="col-md-3" style="padding-right: 3px">
                    <div class="widget-head-color-box navy-bg p-lg text-center">
                        <div class="m-b-md">
                        <h2 class="font-bold no-margins" data-toggle="tooltip" data-placement="top" title="Ver Affiliado ">
                        <a  href="{{route('affiliate.show', $affiliate->id)}}"  style="color: #fff"> {{ $retirement_fund->affiliate->fullName() }}</a>    
                        </h2>
                            <small><strong>{{  $retirement_fund->affiliate->degree->name }}</strong></small>
                        </div>
                    </div>
                    <div class="widget-text-box">
                            <ul class="list-group elements-list">
                                <li class="list-group-item active"><a data-toggle="tab" href="#tab-ret-fun"><i class="glyphicon glyphicon-piggy-bank"></i> Fondo de Retiro</a></li>
                                <li class="list-group-item "><a data-toggle="tab" href="#tab-affiliate"><i class="fa fa-user"></i> Affiliado </a></li>
                                <li class="list-group-item "><a data-toggle="tab" href="#tab-beneficiaries"><i class="fa fa-users"></i> Beneficiarios</a></li>
                                <li class="list-group-item "><a data-toggle="tab" href="#tab-summited-document"><i class="fa fa-file"></i> Documentos Presentados</a></li>
                                <li class="list-group-item "><a data-toggle="tab" href="#tab-legal-review"><i class="fa fa-legal "></i> Revisi&oacute;n Legal</a></li>
                                <li class="list-group-item "><a data-toggle="tab" href="#tab-folder"><i class="fa fa-copy"></i> Archivos</a></li>
                                <li class="list-group-item "><a data-toggle="tab" href="#tab-observations"><i class="fa fa-eye-slash"></i> Observaciones</a></li>
                                
                            </ul>
                    </div>
            </div>
            <br>
            <div class="col-md-9" style="padding-left: 6px">
                
                    <div class="tab-content">
                            <div id="tab-ret-fun" class="tab-pane active">
                                
                                        @can('update',$retirement_fund)
                                            <ret-fun-info :retirement_fund="{{ $retirement_fund }}" :rf_city_start="{{$retirement_fund->city_start}}" :rf_city_end="{{$retirement_fund->city_end}}" :rf_procedure_modality=" {{$retirement_fund->procedure_modality}}" :states="{{ $states }}" inline-template>
                                                @include('ret_fun.info', ['retirement_fund'=>$retirement_fund,'cities'=>$birth_cities])
                                            </ret-fun-info>
                                        @endcan
                                
                            </div>
                            <div id="tab-affiliate" class="tab-pane">
                                
                                    <affiliate-show  :affiliate="{{ $affiliate }}" :cities="{{$cities}}" inline-template> 
                                        @include('affiliates.affiliate_personal_information',['affiliate'=>$affiliate,'cities'=>$cities_pluck,'birth_cities'=>$birth_cities])
                                    </affiliate-show>  
                                
                            </div>
                            <div id="tab-beneficiaries" class="tab-pane">
                                    
                                    @can('view',new Muserpol\Models\RetirementFund\RetFunBeneficiary)
                                        @include('ret_fun.beneficiaries_list', ['beneficiaries'=>$beneficiaries,'cities'=>$cities,'kinships'=>$kinships])
                                    @endcan
                                
                            </div>
                            <div id="tab-summited-document" class="tab-pane">
                                
                                    @can('view',new Muserpol\Models\RetirementFund\RetFunSubmittedDocument)
                                        {{-- @include('ret_fun.legal_review', ['affiliate'=>$affiliate,'retirement_fund'=>$retirement_fund,'documents'=>$documents]) --}}
                                        <ret-fun-step1-requirements-edit :ret_fun="{{ $retirement_fund }}" :modalities="{{ $modalities }}" :requirements="{{ $requirements }}" :user="{{ $user }}" :cities="{{ $cities }}" :procedure-types="{{$procedure_types}}" :submitted="{{$submit_documents}}"
                                            inline-template>
                                            @include('ret_fun.step1_requirements_edit')
                                        </ret-fun-step1-requirements-edit>
                                    @endcan
                                
                            </div>
                            <div id="tab-legal-review" class="tab-pane">
                                
                                    @can('view',new Muserpol\Models\RetirementFund\RetFunSubmittedDocument)
                                        @include('ret_fun.legal_review', ['affiliate'=>$affiliate,'retirement_fund'=>$retirement_fund,'documents'=>$documents])                        
                                    @endcan
                                
                            </div>
                            <div id="tab-folder" class="tab-pane">
                                
                                    @can('view',new Muserpol\Models\AffiliateFolder)
                                        @include('affiliates.folder', ['folders'=>$affiliate->affiliate_folders,'procedure_modalities'=>$procedure_modalities,'affiliate_id'=>$affiliate->id])
                                    @endcan
                                
                            </div>
                            <div id="tab-observations" class="tab-pane">
                                    @include('ret_fun.observation')
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
    console.log( "ready!" );
        $('#folderDialog').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id=button.data('id')
            var cod_folder = button.data('codfile')
            var num_folder = button.data('folnum')
            var moda_id =button.data('modid');
            var note = button.data('note');
            var is_paid = button.data('ispaid');
            // console.log(num_folder)
            // console.log(id)
            // console.log('modalidad'+moda_id)
            var modal = $(this)
            $('#id_folder').val(id)
            //revisar esta parte con el nuevo disenio
            //if(typeof(is_paid) === "boolean"){
                if(is_paid == true){
                    console.log('paid');
                    $(".modal-body #paid").prop("checked", true);
                }
                if(is_paid == false){
                    console.log('nopaid');
                    $(".modal-body #nopaid").prop("checked", true);
                }
            //}

            modal.find('.modal-body #cod_folder').val(cod_folder)
            modal.find('.modal-body #num_folder').val(num_folder)
            modal.find('.modal-body #note').val(note)
            // console.log($('#mod_id').val(moda_id))
        });
        $('#eliminar').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            // console.log('metodo 2')
            var cod_folder = button.data('elim')
            // console.log(cod_folder)
            // console.log($('#cod_file_eli').val(cod_folder))
        });
        // console.log( "del show... " );
        $('#example').DataTable();
    });
</script>
@endsection 