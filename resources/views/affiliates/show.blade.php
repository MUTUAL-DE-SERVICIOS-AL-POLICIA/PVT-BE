@extends('layouts.app')

@section('title', 'Afiliados')
@section('styles')
<style>
#fixedheight {
    table-layout: fixed;
}

#fixedheight td {
    width: 25%;
}

#fixedheight td div {
    height: 20px;
    overflow: hidden;
    text-overflow: ellipsis;

}
th.ellipsis-text {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.table-hover>tbody>tr:hover {
        background-color: #DBDBDB
    }
/* .table-striped-1>tbody>tr:nth-child(2n+1){background-color:#f2f2f2} */
</style>
<link rel="stylesheet" href="{{asset('/css/datatables.css')}}">
@endsection
@section('content')
<div class="row  wrapper border-bottom white-bg page-heading">
    <div class="col-lg-7">
        {{ Breadcrumbs::render('show_affiliate', $affiliate) }}
    </div>
    <div class="col-lg-5" style="margin-top:12px;">
        @can('create', new Muserpol\Models\RetirementFund\RetirementFund)
            @if($has_ret_fun)
                <a href="#" id="disabled-button-wrapper" class="tooltip-wrapper disabled" data-toggle="tooltip" data-placement="top" title="El Afiliado ya tiene un tr&aacute;mite de Fondo de Retiro">
                    <button class="btn btn-info btn-sm  dim" type="button"  disabled><i class="fa fa-paste"></i> </button>
                </a>
            @else
                <a href="{{route('create_ret_fun', $affiliate->id)}}">
                    <button class="btn btn-info btn-sm  dim" type="button" data-toggle="tooltip" data-placement="top" title="Iniciar tr&aacute;mite de FONDO DE RETIRO"><i class="fa fa-paste"></i> </button>
                </a>
                <a href="{{route('create_quota_aid', $affiliate->id)}}">
                    <button class="btn btn-info btn-sm  dim" type="button" data-toggle="tooltip" data-placement="top" title="Iniciar tr&aacute;mite de Cuota y Auxilio Morturorio"><i class="fa fa-paste"></i> </button>
                </a>
            @endif
        @endcan
        {{-- @can('view',new Muserpol\Models\Contribution\Contribution)
        <a href="{{route('show_contribution', $affiliate->id)}}" >
            <button class="btn btn-info btn-sm  dim" type="button" data-toggle="tooltip" data-placement="top" title="Ver Aportes"><i class="fa fa-dollar"> </i> APORTES ACTIVO </button>
        </a>
        <a href="{{route('show_aid_contribution', $affiliate->id)}}" >
            <button class="btn btn-info btn-sm  dim" type="button" data-toggle="tooltip" data-placement="top" title="Aportes Auxilio Mortuorio"><i class="fa fa-dollar"> </i> APORTES PASIVO </button>
        </a>
        @endcan --}}
        <span data-toggle="modal" data-target="#ModalRecord">
            <button type="button" class="btn btn-info btn-sm dim" data-toggle="tooltip" data-placement="top" title="Historial del Afiliado">
                <i class="fa fa-history" style="font-size:15px;"></i> HISTORIAL
            </button>
        </span>
        @include('affiliates.affiliate_record', ['affiliate_records'=>$affiliate_records])
    </div>
</div>
@if(Session::has('message'))
    <br>
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        {{Session::get('message')}}
    </div>

@endif
<div class="row">

    <div class="col-md-3" style="padding-right: 3px">
            <div class="widget-head-color-box navy-bg p-sm text-center">
                <div class="m-b-md">
                    <div class="row m-l-sm">
                        <h3 class="pull-left" data-toggle="tooltip" data-placement="top" title="Codigo único de Afiliado"><strong>{{  $affiliate->id }}</strong></h3>
                    </div>
                    <h2 class="font-bold no-margins link-affiliate" data-toggle="tooltip" data-placement="top" title="Ir al afiliado ">
                        <a  href="{{route('affiliate.show', $affiliate->id)}}" class="link-affiliate"> {{ $affiliate->fullNameWithDegree() }}</a>
                    </h2>
                    <h3 class="text-center" data-toggle="tooltip" data-placement="top" title="Cedula de Identidad"><strong>{{  $affiliate->ciWithExt() }}</strong></h3>
                    <h4 class="text-center" data-toggle="tooltip" data-placement="top" title="Matricula"><strong>{{  $affiliate->registration }}</strong></h4>
                </div>
            </div>
            <div class="widget-text-box">
                    <ul class="list-group elements-list">
                        <li class="list-group-item active" data-toggle="tab" href="#tab-affiliate"><a href="#"><i class="fa fa-address-book"></i> Información Personal </a></li>
                        <li class="list-group-item " data-toggle="tab" href="#tab-police-info"><a href="#"><i class="fa fa-address-card"></i> Información Policial </a></li>
                        <li class="list-group-item " data-toggle="tab" href="#tab-spouse-info"><a href="#"><i class="fa fa-user"></i> Información de Conyuge </a></li>
                        <li class="list-group-item " data-toggle="tab" href="#tab-contributions"><a href="#" ><i class="fa fa-money "></i> Aportes</a></li>
                        <li class="list-group-item " data-toggle="tab" href="#tab-documents-scanned"><a href="#" ><i class="fa fa-upload"></i> Documentos Escaneados</a></li>
                        <li class="list-group-item " data-toggle="tab" href="#tab-ret-fun"><a href="#"><i class="{{ Muserpol\Helpers\Util::IconModule(3)}}"></i> Fondo de Retiro</a></li>
                        <li class="list-group-item " data-toggle="tab" href="#tab-eco-com"><a href="#"><i class="{{ Muserpol\Helpers\Util::IconModule(2)}}"></i> Complemento</a></li>
                        {{-- <li class="list-group-item " data-toggle="tab"><a href="#tab-aid-cuot-mortuory"><i class="{{ Muserpol\Helpers\Util::IconModule(4)}}"></i> Cuota Mortuorio </a></li>
                        <li class="list-group-item " data-toggle="tab"><a href="#tab-aid-mortuory"><i class="{{ Muserpol\Helpers\Util::IconModule(5)}}"></i> Auxilio Mortuorio </a></li> --}}
                        <li class="list-group-item " data-toggle="tab" href="#tab-observations"><a href="#"><i class="fa fa-eye-slash"></i> Observaciones</a></li>

                    </ul>
            </div>
    </div>
    <br>
    <div class="col-md-9" style="padding-left: 6px">

            <div class="tab-content">

                    <div id="tab-affiliate" class="tab-pane active">
                        <affiliate-show  :affiliate="{{ $affiliate }}" :cities="{{ $cities }}" inline-template>
                            @include('affiliates.affiliate_personal_information',['affiliate'=>$affiliate,'cities'=>$cities,'birth_cities'=>$birth_cities,'is_editable'=>$is_editable])
                        </affiliate-show>

                    </div>
                    <div id="tab-police-info" class="tab-pane">

                        <affiliate-police :affiliate="{{ $affiliate }}" inline-template>
                            @include('affiliates.affiliate_police_information', ['affiliate'=>$affiliate])
                        </affiliate-police>

                    </div>
                    <div id="tab-spouse-info" class="tab-pane">

                        <spouse-show :spouse="{{ $spouse }}" :affiliate-id="{{ $affiliate->id }}" :cities="{{ $cities }}" inline-template>
                            @include('spouses.spouse_personal_information', ['spouse'=>$spouse])
                        </spouse-show>

                    </div>
                    <div id="tab-contributions" class="tab-pane">
                        @include('contribution.affiliate_contribution_show',
                        [
                        'contributions' =>  $contributions,
                        'month_end' =>  $month_end,
                        'month_start'  =>   $month_start,
                        'year_end'  =>  $year_end,
                        'year_start'    =>  $year_start
                        ])
                    </div>
                    <div id="tab-documents-scanned" class="tab-pane">
                        @include('affiliates.scanned_documents',['affiliate'=>$affiliate,'scanned_documents'=>$affiliate->scanned_documents])
                    </div>
                    <div id="tab-ret-fun" class="tab-pane">
                        @can('update',$retirement_fund)
                            <ret-fun-info :retirement_fund="{{ $retirement_fund }}" :rf_city_start="{{$retirement_fund->city_start}}" :rf_city_end="{{$retirement_fund->city_end}}" :rf_procedure_modality=" {{$retirement_fund->procedure_modality}}" :states="{{ $states }}" :read="true" inline-template>
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

</div> --}}

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
