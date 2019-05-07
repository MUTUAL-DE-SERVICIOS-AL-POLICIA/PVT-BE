@extends('layouts.app')

@section('content')
<div class="container">
    <br>
    @if(!Util::rolIsEcoCom())
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
