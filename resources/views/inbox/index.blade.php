@extends('layouts.app')
@section('title', 'Mi bandeja')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {!!Breadcrumbs::render('inbox')!!}
    </div>
</div>
<div class="wrapper wrapper-content">
    <div class="row">
        {{-- left --}}
        <div class="col-md-6">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Aportes y periodos considerados</h5>
                    {{-- <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div> --}}
                </div>
                <div class="ibox-content" style="">
                </div>
            </div>
        </div>
        {{-- /left --}}

        {{-- rigth --}}
        <div class="col-md-6">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Aportes y periodos considerados</h5>
                    {{-- <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div> --}}
                </div>
                <div class="ibox-content" style="">
                </div>
            </div>
        </div>
        {{-- /right --}}
    </div>
</div>
@endsection
