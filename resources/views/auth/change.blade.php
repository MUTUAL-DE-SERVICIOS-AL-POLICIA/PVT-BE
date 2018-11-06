@extends('layouts.sinmenu')

@section('title', 'Roles')

@section('content')
<div class="row">
    <h1 class="text-navy text-center">Plataforma Virtual de Trámites</h1>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row text-center">
        @php
            $count = count($roles);
            $style = "";  
        @endphp
        @if($count == 1)
            @php
                $style = "<div class='col-md-3 col-md-offset-4'>";
            @endphp
        @elseif($count == 2)
            @php
                $style = "<div class='col-md-3 col-md-offset-2'>";
            @endphp
        @elseif($count == 3)
            @php
                $style = "<div class='col-md-3 col-md-offset-1'>";
            @endphp
        @else
            @php
                $style = "<div class='col-md-3'>";
            @endphp
        @endif
        @foreach($roles as $role)
        {!! Form::open(['url' => 'postchangerol', 'role' => 'form']) !!}
                @php
                    echo $style;    
                @endphp
                @if($role->module_id==1)
                <button class="widget red-bg px-1 text-center" style="height: 200px; width: 100%">
                <span class="info-box-icon bg-red">
                    <i class="glyphicon glyphicon-hdd fa-4x"></i><br>
                    <b>{{$role->name}}</b>
                </span>
                @endif
                @if($role->module_id==2 || $role->module_id==8 )
                <button class="widget lazur-bg px-1 text-center" style="height: 200px; width: 100%">
                <span class="info-box-icon bg-aqua">
                    <i class="fa fa-puzzle-piece fa-5x"></i><br>
                    <b>{{$role->name}}</b>
                </span>
                @endif     
                @if($role->module_id==3)
                <button class="widget yellow-bg px-1 text-center" style="height: 200px; width: 100%">
                <span class="info-box-icon bg-yellow">
                    <i class="glyphicon glyphicon-piggy-bank fa-4x"></i><br>
                    <b>{{$role->name}}</b>
                </span>
                
                @endif

                @if($role->module_id==6 || $role->module_id==9)
                <button class="widget blue-bg px-1 text-center" style="height: 200px; width: 100%">
                <span class="info-box-icon bg-green">
                    <i class="fa fa-money fa-5x"></i><br>
                    <b>{{$role->name}}</b>
                </span>
                
                @endif
                @if($role->module_id==4 ||$role->module_id==5 )
                <button class="widget blue-bg px-1 text-center" style="height: 200px; width: 100%">
                <span class="info-box-icon bg-green">
                    <i class="fa fa-heartbeat fa-5x"></i><br>
                    <b>{{$role->name}}</b>
                </span>
                
                @endif

                @if($role->module_id==7)
                <button class="widget blue-bg px-1 text-center" style="height: 200px; width: 100%">
                <span class="info-box-icon bg-green">
                    <i class="fa fa-balance-scale fa-5x"></i><br>
                    <b>{{$role->name}}</b>
                </span>
                
                @endif
                @if($role->module_id==10)
                <button class="widget green-bg px-1 text-center" style="height: 200px; width: 100%">
                <span class="info-box-icon bg-green">
                    <i class="fa  fa-map fa-5x"></i><br>
                    <b>{{$role->name}}</b>
                </span>
                
                @endif
                @if($role->module_id == 11)
                <button class="widget green-bg px-1 text-center" style="height: 200px; width: 100%">
                <span class="info-box-icon bg-green">
                    <i class="fa  fa-map fa-5x"></i><br>
                    <b>{{$role->name}}</b>
                </span>
                @endif

            <div class="info-box-content">
                <input type="hidden" name="rol_id" value={!! $role->id !!}>
                <b>
                <span class="info-box-text"> {!! $role->module->name !!} </span>
                </b>
                <br>
            </div>
            <!-- /.info-box-content -->
        </button>
    </div>
        {!! Form::close() !!}
        @endforeach
    </div>
</div>
 
@endsection
