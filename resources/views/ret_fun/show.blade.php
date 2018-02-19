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
            @include('ret_fun.applicant_info', ['affiliate'=>$retirement_fund->affiliate])
        </div>
        <div class="col-md-6">
            <ret-fun-info :retirement-fund="{{ $retirement_fund }}" inline-template>
                @include('ret_fun.info', ['retirement_fund'=>$retirement_fund])
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