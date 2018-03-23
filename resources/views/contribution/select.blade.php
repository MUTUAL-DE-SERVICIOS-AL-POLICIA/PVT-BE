@extends('layouts.app')

@section('title', 'Seleccion de Aportes')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    {{--  <contribution-create :contributions1="{{ json_encode($contributions) }}" :afid="{{ $affiliate->id }}" ></contribution-create>  --}}
    <contribution-select :cnormal ="{{json_encode($contribucion_normal)}}" :cdisponibilidad="{{json_encode($contribucion_disponibilidad)}}" :citem0="{{json_encode($contribucion_item_0)}}"></contribution-select>
</div>
@endsection