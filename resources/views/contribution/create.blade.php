@extends('layouts.app')

@section('title', 'Pago de Aportes')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
       {{--  {!!Breadcrumbs::render('show_retirement_fund', $retirement_fund)!!} --}}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    
    <contribution-create></contribution-create>
    
    
</div>
@endsection