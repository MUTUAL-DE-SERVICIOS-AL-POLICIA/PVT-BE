@extends('layouts.app')

@section('title', 'Configuraci&oacute;n')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">    
    <div class="row text-center">       
    </div>    
    <div class="row">
        <div class="col-md-6">
            @include('setting.ret_fun_procedures', ['ret_fun_procedure'=>$ret_fun_procedure])
        </div>
        
    </div>
    <div class="row">
        
    </div>
 
    
    
    
</div>
@endsection