@extends('layouts.app')

@section('title', 'Seleccion de Aportes')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
{{$contribuciones}}}
    {{--  <contribution-create :contributions1="{{ json_encode($contributions) }}" :afid="{{ $affiliate->id }}" ></contribution-create>  --}}
<contribution-select :contribuciones ="{{$contribuciones}}" ></contribution-select>
</div>
@endsection