@extends('layouts.app')
@section('title', 'Registro')
@section('content')

<div class="row m-t-lg">
<div class="col-md-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="pull-left">Datos Personales del Usuario</h3>
            <div class="text-right">
                <br>
            </div>
        </div>
        <div class="ibox-content">
            <edit-user
                @if(isset($users)) :users="{{ json_encode($users) }}" @endif
                @if(isset($user)) :user="{{ json_encode($user) }}" @endif
                :modules="{{ json_encode($modules) }}"
                :cities="{{ json_encode($cities) }}"
                :roles="{{ json_encode($roles) }}"
                :token="{{ json_encode(csrf_token()) }}"
            ></edit-user>
        </div>
    </div>
</div>
</div>
@endsection
