@extends('layouts.app')

@section('title', 'Afiliados')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{ Breadcrumbs::render('affiliate') }}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row" id="affiliate_view">
        <div class="col-lg-12">
            <div class="text-center m-t-lg">
                <example></example>
                <h1>
                    Welcome in INSPINIA Laravel Starter Project
                </h1>
                <small>
                    It is an application skeleton for a typical web app. You can use it to quickly bootstrap your webapp projects.
                </small>
            </div>
        </div>
    </div>
</div>
@endsection
