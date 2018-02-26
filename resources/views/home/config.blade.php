@extends('layouts.app')

@section('title', 'Confuguraci&oacute;n')

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
            @include('config.ret_fun_procedure', ['ret_fun_procedure'=>$ret_fun_procedure])
        </div>
        
    </div>
    <div class="row">
        
    </div>
 
    
    
    
</div>
@endsection