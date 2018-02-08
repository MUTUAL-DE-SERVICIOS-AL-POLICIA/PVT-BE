@extends('layouts.app')


@section('title', 'Quota y Auxilio Mortuoria')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{ Breadcrumbs::render('affiliate') }}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="text-center m-t-lg">
                <quota-aid-mortuary-index></quota-aid-mortuary-index>         
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

@endsection
