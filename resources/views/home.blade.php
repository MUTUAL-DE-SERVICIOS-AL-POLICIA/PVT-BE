@extends('layouts.app')

@section('content')
<br>
@if (Util::rolIsEcoCom())
    <eco-com-dashboard :eco-com-procedures="{{ $eco_com_procedures }}"></eco-com-dashboard>
@endif
<div class="container">
    <br>
    @if(Util::rolIsRetFun())
        <div class="row">
            <div class="col-md-6">
                <ret-fun-chart id="1" type="pie" title="Modalidades" :data-set="{{json_encode($procedure_modalities)}}">
                </ret-fun-chart>
            </div>
            <div class="col-md-6">
                <ret-fun-chart id="2" type="bar" title="Ubicación de los Trámites" :data-set="{{json_encode($wf_states)}}">
                </ret-fun-chart>
            </div>
        </div>
    @endif
</div>
@endsection