@extends('layouts.app') 
@section('title', 'Registro') 
@section('content')

<div class="row m-t-lg">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="pull-left">Edicion de una etiqueta</h3>
                <div class="text-right">
                    <br>
                </div>
            </div>
            <div class="ibox-content">
                <tag-create :edit="true" :tag-id="{{$tag->id}}">
                </tag-create>
            </div>
        </div>
    </div>
</div>
@endsection