@extends('layouts.app') 
@section('title', 'Registro') 
@section('content')

<div class="row m-t-lg">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="pull-left">Asignacion de etiquetas al Area</h3>
                <div class="text-right">
                    <br>
                </div>
            </div>
            <div class="ibox-content">
                <tag-wf-state :wf-states="{{ $wf_states }}" :tags="{{ $tags }}">
                </tag-wf-state>
            </div>
        </div>
    </div>
</div>
@endsection