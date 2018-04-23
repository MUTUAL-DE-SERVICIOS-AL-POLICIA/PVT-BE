@extends('layouts.app')

@section('title', 'Fondo de Retiro')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {!!Breadcrumbs::render('show_retirement_fund', $retirement_fund)!!}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    
    <div class="row text-center">
        
        @if(Muserpol\Helpers\Util::getRol()->id == 10)
        <button class="btn btn-primary dim" type="button" data-toggle="tooltip" data-placement="top" title="Imprimir recepción" onclick="printJS({printable:'{!! route('ret_fun_print_reception', $retirement_fund->id) !!}', type:'pdf', showModal:true})"><i class="fa fa-print"></i></button> 
        @endif
        
        @if(Muserpol\Helpers\Util::getRol()->id == 15)
        <button class="btn btn-prfolder_idimary dim" type="button" data-toggle="tooltip" data-placement="top" title="Imprimir Certificacion de Archivo" onclick="printJS({printable:'{!! route('ret_fun_print_file', $affiliate->id) !!}', type:'pdf', showModal:true})"><i class="fa fa-print"></i></button>        
        @endif
        
        @if(Muserpol\Helpers\Util::getRol()->id == 11)
        <button class="btn btn-primary dim" type="button" data-toggle="tooltip" data-placement="top" title="Imprimir Certificacion de Documentacion Presentada y Revisada" onclick="printJS({printable:'{!! route('ret_fun_print_legal_review', $retirement_fund->id) !!}', type:'pdf', showModal:true})"><i class="fa fa-print"></i></button>
        @endif       
    <a  href="{{ url('ret_fun/'.$retirement_fund->id.'/selectcontributions')}}" >
            <button class="btn btn-primary dim"  data-toggle="tooltip" data-placement="top" title=" Clasificar Aportes " >
            <i class="fa fa-list-alt"></i>
            </button>
        </a>
        
    </div>
    
    <div class="row">
        <div class="col-md-6">
            {{-- @if($retirement_fund->modality_id==1) --}}
            <affiliate-show  :affiliate="{{ $affiliate }}" :cities="{{$cities}}" inline-template> 
                   @include('affiliates.affiliate_personal_information',['affiliate'=>$affiliate,'cities'=>$cities_pluck,'birth_cities'=>$birth_cities])
            </affiliate-show>             
            {{-- @else --}}
                {{-- @include('ret_fun.applicant_info', ['affiliate'=>$retirement_fund->affiliate]) --}}
            {{-- @endif --}}
        </div>
        @can('update',$retirement_fund)
        <div class="col-md-6">
            <ret-fun-info :retirement_fund="{{ $retirement_fund }}" :rf_city_start="{{$retirement_fund->city_start}}" :rf_city_end="{{$retirement_fund->city_end}}" :rf_procedure_modality=" {{$retirement_fund->procedure_modality}}" inline-template>
                @include('ret_fun.info', ['retirement_fund'=>$retirement_fund,'cities'=>$birth_cities])
            </ret-fun-info>
        </div>
        @endcan
    </div>
    <div class="row">
        @can('view',new Muserpol\Models\RetirementFund\RetFunBeneficiary)
        <div class="col-md-6">
            @include('ret_fun.beneficiaries_list', ['beneficiaries'=>$beneficiaries,'cities'=>$cities,'kinships'=>$kinships])
        </div>
        @endcan
        @can('view',new Muserpol\Models\RetirementFund\RetFunSubmittedDocument)
        <div class="col-md-6">
            @include('ret_fun.legal_review', ['affiliate'=>$affiliate,'retirement_fund'=>$retirement_fund,'documents'=>$documents])
        </div>
        @endcan
    </div>
    @can('view',new Muserpol\Models\AffiliateFolder)
    <div class="row">
        <div class="col-md-6">
            @include('affiliates.folder', ['folders'=>$affiliate->affiliate_folders,'procedure_modalities'=>$procedure_modalities,'affiliate_id'=>$affiliate->id])
        </div>
    </div>
    @endcan
</div>
@endsection
@section('jss')
<script>
    $( document ).ready(function() {
    console.log( "ready!" );
        $('#folderDialog').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var id=button.data('id')
            var cod_folder = button.data('codfile')
            var num_folder = button.data('folnum')
            var moda_id =button.data('modid');
            console.log(cod_folder)
            console.log(num_folder)
            console.log(id)
            console.log('modalidad'+moda_id)
            var modal = $(this)
            $('#id_folder').val(id)
            modal.find('.modal-body #cod_folder').val(cod_folder)
            modal.find('.modal-body #num_folder').val(num_folder)
            console.log($('#mod_id').val(moda_id))
        //modal.find('.modal_body #modid option[value='+ mod_id +']').prop('selected', true)
        });
        $('#eliminar').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            console.log('metodo 2')
            var cod_folder = button.data('elim')
            console.log(cod_folder)
            console.log($('#cod_file_eli').val(cod_folder))
        //modal.find('.modal_body #modid option[value='+ mod_id +']').prop('selected', true)
        });
    });
</script>
@endsection