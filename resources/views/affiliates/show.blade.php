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
        <a href="{{route('create_ret_fun', $affiliate->id)}}" >
            <button class="btn btn-info btn-sm  dim" type="button" data-toggle="tooltip" data-placement="top" title="Iniciar tr&aacute;mite de FONDO DE RETIRO"><i class="fa fa-paste"></i> </button>
        </a>
        @endcan
        <button type="button" class="btn btn-info btn-sm  dim" data-toggle="modal" data-target="#ModalRecord" data-placement="top"  title="Historial del afiliado">
            <i class="fa fa-hourglass-3"></i>
        </button>
        @include("affiliates.affiliate_record")
        @can('view',new Muserpol\Models\Contribution\Contribution)
        <a href="{{route('show_contribution', $affiliate->id)}}" >
            <button class="btn btn-info btn-sm  dim" type="button" data-toggle="tooltip" data-placement="top" title="Ver Aportes"><i class="fa fa-dollar"></i> </button>
        </a>
        <a href="{{route('show_aid_contribution', $affiliate->id)}}" >
            <button class="btn btn-info btn-sm  dim" type="button" data-toggle="tooltip" data-placement="top" title="Aportes Auxilio Mortuorio"><i class="fa fa-dollar"></i> </button>
        </a>
        @endcan
    </div>
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
{{$affiliate_record}}
@endsection

@section('styles')
<link rel="stylesheet" href="{{resource_path('assets/css/plugins/dataTables/datatables.min.css')}}">
<style>
    td.highlight {
        background-color: #e3eaef !important;
    }
    .table-hover tbody tr:hover td,
    .table-hover tbody tr:hover th {
        background-color: #e3eaef;
    }
    .yellow-row {
        background-color:#ffe6b3 !important;
        
    }
</style>
@endsection
@section('scripts')
<script src="{{ asset('/js/datatables.js')}}"></script>
<script>
$(document).ready(function() {
    console.log( "del show... " );
    $('#example').DataTable();
} );
</script>
@endsection
