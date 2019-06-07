@extends('layouts.app')
@section('title', 'Reportes')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{ Breadcrumbs::render('treasury_select_report') }}
    </div>
    <div class="col-lg-3 text-right" style="margin-top:12px;">
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="text-center m-t-lg">
                <treasury-select-report :types="{{ $voucher_types }}" :from-date="`{{ now()->format('d/m/Y') }}`" :to-date="`{{ now()->format('d/m/Y') }}`"></treasury-select-report>
            </div>
        </div>
    </div>
</div>
@endsection