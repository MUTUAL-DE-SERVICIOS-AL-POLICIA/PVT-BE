@extends('layouts.app')

@section('title', 'Afiliados')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{ Breadcrumbs::render('show_affiliate', $affiliate) }}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row text-center">
        {{-- <a href="{{route('create_quota_aid', $affiliate->id)}}" >
            <button class="btn btn-info btn-sm  dim" type="button" data-toggle="tooltip" data-placement="top" title="Initar tr&aacute;mite de CUOTA Y AUXILIO MORTUORIO"><i class="fa fa-paste"></i> </button>
        </a> --}}
        @can('create', new Muserpol\Models\RetirementFund\RetirementFund)
            @if($has_ret_fun)
                <a href="#" id="disabled-button-wrapper" class="tooltip-wrapper disabled" data-toggle="tooltip" data-placement="top" title="El Afiliado ya tiene un tr&aacute;mite de Fondo de Retiro">
                    <button class="btn btn-info btn-sm  dim" type="button"  disabled><i class="fa fa-paste"></i> </button>
                </a>
            @else
                <a href="{{route('create_ret_fun', $affiliate->id)}}">
                    <button class="btn btn-info btn-sm  dim" type="button" data-toggle="tooltip" data-placement="top" title="Iniciar tr&aacute;mite de FONDO DE RETIRO"><i class="fa fa-paste"></i> </button>
                </a>
            @endif
        @endcan
        <a href="{{route('create_quota_aid', $affiliate->id)}}">
            <button class="btn btn-info btn-sm  dim" type="button" data-toggle="tooltip" data-placement="top" title="Iniciar tr&aacute;mite de Cuota y Auxilio Morturorio"><i class="fa fa-paste"></i> </button>
        </a>
        <button type="button" class="btn btn-info btn-sm dim" data-toggle="modal" data-target="#ModalRecord" data-placement="top" title="Historial del afiliado">
            <i class="fa fa-history"></i>
        </button>
        @include('affiliates.affiliate_record', ['affiliate_records'=>$affiliate_records])
        @can('view',new Muserpol\Models\Contribution\Contribution)
        <a href="{{route('show_contribution', $affiliate->id)}}" >
            <button class="btn btn-info btn-sm  dim" type="button" data-toggle="tooltip" data-placement="top" title="Ver Aportes"><i class="fa fa-dollar"></i> </button>
        </a>
        <a href="{{route('show_aid_contribution', $affiliate->id)}}" >
            <button class="btn btn-info btn-sm  dim" type="button" data-toggle="tooltip" data-placement="top" title="Aportes Auxilio Mortuorio"><i class="fa fa-dollar"></i> </button>
        </a>
        @endcan
    </div>
<<<<<<< HEAD
</div>

<div class="row">
           
    <div class="col-md-3" style="padding-right: 3px">
            <div class="widget-head-color-box navy-bg p-lg text-center">
                <div class="m-b-md">
                        <h3 class="pull-left"><strong>ID: {{  $affiliate->id }}</strong></h3><br>
                        <h2 class="font-bold no-margins" data-toggle="tooltip" data-placement="top" title="Ver Affiliado "><br>
                            <a  href="{{route('affiliate.show', $affiliate->id)}}"  style="color: #fff"> {{ $affiliate->fullName() }}</a>    
                        </h2>
                        <h4 class="text-center"><strong>CI: {{  $affiliate->identity_card }}</strong></h4>
                </div>
            </div>
            <div class="widget-text-box">
                    <ul class="list-group elements-list">
                        <li class="list-group-item active"><a data-toggle="tab" href="#tab-affiliate"><i class="fa fa-address-book"></i> Información Personal </a></li>
                        <li class="list-group-item "><a data-toggle="tab" href="#tab-police-info"><i class="fa fa-address-card"></i> Información Policial </a></li>
                        <li class="list-group-item "><a data-toggle="tab" href="#tab-ret-fun"><i class="{{ Muserpol\Helpers\Util::IconModule(3)}}"></i> Fondo de Retiro</a></li>
                        <li class="list-group-item "><a data-toggle="tab" href="#tab-eco-com"><i class="{{ Muserpol\Helpers\Util::IconModule(2)}}"></i> Complemento</a></li>
                        {{-- <li class="list-group-item "><a data-toggle="tab" href="#tab-aid-cuot-mortuory"><i class="{{ Muserpol\Helpers\Util::IconModule(4)}}"></i> Cuota Mortuorio </a></li>
                        <li class="list-group-item "><a data-toggle="tab" href="#tab-aid-mortuory"><i class="{{ Muserpol\Helpers\Util::IconModule(5)}}"></i> Auxilio Mortuorio </a></li> --}}
                        <li class="list-group-item "><a data-toggle="tab" href="#tab-observations"><i class="fa fa-eye-slash"></i> Observaciones</a></li>
                        
                    </ul>
            </div>
    </div>
    <br>
    <div class="col-md-9" style="padding-left: 6px">
        
            <div class="tab-content">
                   
                    <div id="tab-affiliate" class="tab-pane active">
                        
                        <affiliate-show  :affiliate="{{ $affiliate }}" inline-template> 
                            @include('affiliates.affiliate_personal_information',['affiliate'=>$affiliate,'cities'=>$cities,'birth_cities'=>$birth_cities])
                        </affiliate-show>
                        
                    </div>
                    <div id="tab-police-info" class="tab-pane">
                            
                        <affiliate-police :affiliate="{{ $affiliate }}" inline-template>
                            @include('affiliates.affiliate_police_information', ['affiliate'=>$affiliate])
                        </affiliate-police>

                    </div>
                    <div id="tab-ret-fun" class="tab-pane">
                            @can('update',$retirement_fund)
                            <ret-fun-info :retirement_fund="{{ $retirement_fund }}" :rf_city_start="{{$retirement_fund->city_start}}" :rf_city_end="{{$retirement_fund->city_end}}" :rf_procedure_modality=" {{$retirement_fund->procedure_modality}}" :states="{{ $states }}" :only-read="true" inline-template>
                                @include('ret_fun.info', ['retirement_fund'=>$retirement_fund,'cities'=>$birth_cities])
                            </ret-fun-info>
                        @endcan
                    </div>
                    <div id="tab-eco-com" class="tab-pane">
                        
                            <div class="ibox">
    
                                <div class="ibox-content">
                                        <table class="table table-bordered table-hover" id="economic_complements-table">
                                            <thead>
                                                <tr class="success">
                                                    <th class="text-center"><div data-toggle="tooltip" data-placement="top" data-container="body" data-original-title="Número de Trámite">Número de Trámite</div></th>
                                                    <th class="text-center"><div data-toggle="tooltip" data-placement="top" data-container="body" data-original-title="Gesion ">Gestión</div></th>
                                                    <th class="text-center"><div data-toggle="tooltip" data-placement="top" data-container="body" data-original-title="Fecha de Emisión">Fecha de Ingreso del Trámite</div></th>
                                                    <th class="text-center"><div data-toggle="tooltip" data-placement="top" data-container="body" data-original-title="Ubicación">Ubicación</div></th>
                                                    <th class="text-center"><div data-toggle="tooltip" data-placement="top" data-container="body" data-original-title="Estado">Estado</div></th>
                                                    <th class="text-center"><div data-toggle="tooltip" data-placement="top" data-container="body" data-original-title="Total">Total</div></th>
                                                    <th class="text-center"><div data-toggle="tooltip" data-placement="top" data-container="body" data-original-title="Opciones">Opciones</div></th>
                                            
                                                </tr>
                                            </thead>
                                        </table>
                                </div>
                            </div>
                           
                        
                    </div>
                    {{-- <div id="tab-cuot-mortuory" class="tab-pane"> //cuota mortuoria
                        
                        

                    </div>
                    
                    <div id="tab-aid-mortuory" class="tab-pane"> //auxilio mortuorio
                        
                          
    
                    </div> --}}
                   
                    <div id="tab-observations" class="tab-pane">
                         
                    </div>
                    
                    

                </div>
           
    </div>
 
</div>



{{-- <div class="wrapper wrapper-content animated fadeInRight">
   
=======
>>>>>>> parent of dee2497... reajustando vista del affiliado
    <div class="row">
        <div class="col-md-6">
            <affiliate-show  :affiliate="{{ $affiliate }}" inline-template> 
                   @include('affiliates.affiliate_personal_information',['affiliate'=>$affiliate,'cities'=>$cities,'birth_cities'=>$birth_cities])
            </affiliate-show>
        </div>
        <div class="col-md-6">
            <affiliate-police :affiliate="{{ $affiliate }}" inline-template>
                @include('affiliates.affiliate_police_information', ['affiliate'=>$affiliate])
            </affiliate-police>
        </div>
        <div class="col-md-6">
            @include('affiliates.information_of_the_procedure')
        </div>
    </div>

</div>

@endsection

@section('styles')
<link rel="stylesheet" href="{{asset('/css/datatable.css')}}">

@endsection
@section('jss')
<script src="{{ asset('/js/datatables.js')}}"></script>
<script>
$(document).ready(function() {
    console.log( "del show... " );
    $('#example').DataTable();
} );
</script>
@endsection
