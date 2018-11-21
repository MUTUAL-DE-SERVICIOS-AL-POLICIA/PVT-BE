@extends('layouts.sinmenu')

@section('title', 'Roles')

@section('content')
<div class="row">
    <h1 class="text-navy text-center"><b>Plataforma Virtual de Trámites</b></h1>
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
                <button class="widget style1 red-bg px-1 text-center" style="height: 100px; width: 100%; border: none">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="glyphicon glyphicon-hdd fa-4x"></i>
                        </div>
                        <div class="col-xs-9">
                            <span>
                                <b><p style="font-size: 1.5em;">{{$role->name}}</p></b>
                            </span>
                @endif
                @if($role->module_id==2 || $role->module_id==8 )
                <button class="widget style1 lazur-bg px-1 text-center" style="height: 100px; width: 100%; border: none">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-puzzle-piece fa-5x"></i>
                            </div>
                            <div class="col-xs-9">
                                <span>
                                    <b><p style="font-size: 1.5em;">{{$role->name}}</p></b>
                                </span>
                @endif     
                @if($role->module_id==3)
                <button class="widget style1 yellow-bg px-1 text-center" style="height: 100px; width: 100%; border: none">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="glyphicon glyphicon-piggy-bank fa-4x"></i>
                            </div>
                            <div class="col-xs-9">
                                <span>
                                    <b><p style="font-size: 1.5em;">{{$role->name}}</p></b>
                                </span>
                @endif
                @if($role->module_id==6 || $role->module_id==9)
                <button class="widget style1 blue-bg px-1 text-center" style="height: 100px; width: 100%; border: none">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-money fa-5x"></i>
                            </div>
                            <div class="col-xs-9">
                                <span>
                                    <b><p style="font-size: 1.5em;">{{$role->name}}</p></b>
                                </span>
                @endif
                @if($role->module_id==4 ||$role->module_id==5 )
                <button class="widget style1 blue-bg px-1 text-center" style="height: 100px; width: 100%; border: none">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-heartbeat fa-5x"></i>
                            </div>
                            <div class="col-xs-9">
                                <span>
                                    <b><p style="font-size: 1.5em;">{{$role->name}}</p></b>
                                </span>
                @endif
                @if($role->module_id==7)
                <button class="widget style1 blue-bg px-1 text-center" style="height: 100px; width: 100%; border: none">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-balance-scale fa-5x"></i>
                            </div>
                            <div class="col-xs-9">
                                <span>
                                    <b><p style="font-size: 1.5em;">{{$role->name}}</p></b>
                                </span>
                @endif
                @if($role->module_id==10)
                <button class="widget style1 green-bg px-1 text-center" style="height: 100px; width: 100%; border: none">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa  fa-map fa-5x"></i>
                            </div>
                            <div class="col-xs-9">
                                <span>
                                    <b><p style="font-size: 1.5em;">{{$role->name}}</p></b>
                                </span>
                @endif
                @if($role->module_id == 11)
                <button class="widget style1 green-bg px-1 text-center" style="height: 100px; width: 100%; border: none">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa  fa-map fa-5x"></i>
                            </div>
                            <div class="col-xs-9">
                                <span>
                                    <b><p style="font-size: 1.5em;">{{$role->name}}</p></b>
                                </span>
                @endif

                <input type="hidden" name="rol_id" value={!! $role->id !!}>
                <b>
                <span class="info-box-text"><p style="font-size: 1em">{!! $role->module->name !!}</p>  </span>
                </b>
                <br>
            <!-- /.info-box-content -->
        </div>
        </div>
        </button>
    </div>
        {!! Form::close() !!}
        @endforeach
    </div>
</div>
 
@endsection
