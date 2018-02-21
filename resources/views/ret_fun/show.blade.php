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
        <button class="btn btn-primary dim" type="button" data-toggle="tooltip" data-placement="top" title="Imprimir recepción" onclick="printJS({printable:'{!! route('ret_fun_print_reception', $retirement_fund->id) !!}', type:'pdf', showModal:true})"><i class="fa fa-print"></i></button>        
        <button class="btn btn-primary dim" type="button" data-toggle="tooltip" data-placement="top" title="Imprimir Certificacion de Archivo" onclick="printJS({printable:'{!! route('ret_fun_print_file', $affiliate->id) !!}', type:'pdf', showModal:true})"><i class="fa fa-print"></i></button>        
        <button class="btn btn-primary dim" type="button" data-toggle="tooltip" data-placement="top" title="Imprimir Certificacion de Documentacion Presentada y Revisada" onclick="printJS({printable:'{!! route('ret_fun_print_legal_review', $retirement_fund->id) !!}', type:'pdf', showModal:true})"><i class="fa fa-print"></i></button>        
    </div>
    
    <div class="row">
        <div class="col-md-6">
            {{-- @if($retirement_fund->modality_id==1) --}}
            <affiliate-show  :affiliate="{{ $affiliate }}" inline-template> 
                   @include('affiliates.affiliate_personal_information',['affiliate'=>$affiliate,'cities'=>$cities_pluck,'birth_cities'=>$birth_cities])
            </affiliate-show>             
            {{-- @else --}}
                {{-- @include('ret_fun.applicant_info', ['affiliate'=>$retirement_fund->affiliate]) --}}
            {{-- @endif --}}
        </div>
            
        <div class="col-md-6">
            <ret-fun-info :retirement_fund="{{ $retirement_fund }}" :rf_city_start="{{$retirement_fund->city_start}}" :rf_city_end="{{$retirement_fund->city_end}}" :rf_procedure_modality=" {{$retirement_fund->procedure_modality}}" inline-template>
                @include('ret_fun.info', ['retirement_fund'=>$retirement_fund,'cities'=>$birth_cities])
            </ret-fun-info>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            @include('ret_fun.beneficiaries_list', ['beneficiaries'=>$beneficiaries,'cities'=>$cities,'kinships'=>$kinships])
        </div>
        <div class="col-md-6">
            @include('ret_fun.legal_review', ['affiliate'=>$affiliate,'retirement_fund'=>$retirement_fund,'documents'=>$documents])
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            @include('affiliates.folder', ['folders'=>$affiliate->affiliate_folders,'procedure_modalities'=>$procedure_modalities,'affiliate_id'=>$affiliate->id])
        </div>
    </div>
    
    
    
</div>
@endsection