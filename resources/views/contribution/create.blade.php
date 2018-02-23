@extends('layouts.app')

@section('title', 'Pago de Aportes')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        
        {{ Breadcrumbs::render('payment_contributions', $affiliate) }}
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
   
    <p><pre>query: {{ json_encode($contributions) }}</pre></p>
    <contribution-create :contributions1="{{ json_encode($contributions) }}" :cont1="{{ json_encode($contributions) }}"></contribution-create>
</div>
@endsection