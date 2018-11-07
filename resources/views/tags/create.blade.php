@extends('layouts.app') 
@section('title', 'Registro') 
@section('content')

<div class="row m-t-lg">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="pull-left">Registro de nueva etiqueta</h3>
                <div class="text-right">
                    <br>
                </div>
            </div>
            <div class="ibox-content">
                <tag-create :edit="false" :wf-states="{{ $wf_states }}" :modules="{{ $modules }}">
                </tag-create>
            </div>
        </div>
    </div>
</div>
@endsection