@extends('layouts.app')




@section('title', 'Afiliados')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{ Breadcrumbs::render('affiliate') }}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <ret_fun-step1.requirements></ret_fun-step1.requirements>
</div>
@endsection
