@extends('layouts.sinmenu')

@section('title', 'Roles')

@section('content')
<div class="row">
    <h1 class="text-navy text-center">Plataforma Virtual de Trámites</h1>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row ng-scope text-center">


        @foreach($roles as $role)
        {!! Form::open(['url' => 'postchangerol', 'role' => 'form']) !!}
        
                @if($role->module_id==1)
                <button class="widget red-bg px-1 text-center col-lg-3" style="height: 200px">
                <span class="info-box-icon bg-red">
                    <i class="glyphicon glyphicon-hdd fa-4x"></i><br>
                    <b>{{$role->name}}</b>
                </span>
                @endif
                @if($role->module_id==2 || $role->module_id==8 )
                <button class="widget lazur-bg px-1 text-center col-lg-3" style="height: 200px">
                <span class="info-box-icon bg-aqua">
                    <i class="fa fa-puzzle-piece fa-5x"></i><br>
                    <b>{{$role->name}}</b>
                </span>
                @endif     
                @if($role->module_id==3)
                <button class="widget yellow-bg px-1 text-center col-lg-3" style="height: 200px">
                <span class="info-box-icon bg-yellow">
                    <i class="glyphicon glyphicon-piggy-bank fa-4x"></i><br>
                    <b>{{$role->name}}</b>
                </span>
                
                @endif

                @if($role->module_id==6 || $role->module_id==9)
                <button class="widget blue-bg px-1 text-center col-lg-3" style="height: 200px">
                <span class="info-box-icon bg-green">
                    <i class="fa fa-money fa-5x"></i><br>
                    <b>{{$role->name}}</b>
                </span>
                
                @endif
                @if($role->module_id==4 ||$role->module_id==5 )
                <button class="widget blue-bg px-1 text-center col-lg-3" style="height: 200px">
                <span class="info-box-icon bg-green">
                    <i class="fa fa-heartbeat fa-5x"></i><br>
                    <b>{{$role->name}}</b>
                </span>
                
                @endif

                @if($role->module_id==7)
                <button class="widget blue-bg px-1 text-center col-lg-3" style="height: 200px">
                <span class="info-box-icon bg-green">
                    <i class="fa fa-balance-scale fa-5x"></i><br>
                    <b>{{$role->name}}</b>
                </span>
                
                @endif
                @if($role->module_id==10)
                <button class="widget green-bg px-1 text-center col-lg-3" style="height: 200px">
                <span class="info-box-icon bg-green">
                    <i class="fa  fa-map fa-5x"></i><br>
                    <b>{{$role->name}}</b>
                </span>
                
                @endif
                @if($role->module_id == 11)
                <button class="widget green-bg px-1 text-center col-lg-3" style="height: 200px">
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

                @if($role->module_id==1)   
                <input type="submit" class="btn btn-block btn-raised btn-danger"><i class="glyphicon glyphicon-share-alt"></i>
                @endif 
                @if($role->module_id==2 || $role->module_id==8 )
                <input type="submit" class="btn btn-block btn-raised btn-info"><i class="glyphicon glyphicon-share-alt"></i>
                @endif     
                @if($role->module_id==3)
                <input type="submit" class="btn btn-block btn-raised btn-warning"><i class="glyphicon glyphicon-share-alt"></i>
                
                @endif
                @if($role->module_id==6 || $role->module_id==9)
                <input type="submit" class="btn btn-block btn-raised btn-success"><i class="glyphicon glyphicon-share-alt"></i>
                
                @endif
                @if($role->module_id==4 ||$role->module_id==5 ||$role->module_id==7 ||$role->module_id==10 || $role->module_id==11)
                <input type="submit" class="btn btn-block btn-raised btn-success"><i class="glyphicon glyphicon-share-alt"></i>
                
                @endif

                
              
              {{-- <span class="info-box-number">90<small>%</small></span> --}}
            </div>
            <!-- /.info-box-content -->
        </button>
        {!! Form::close() !!}
        @endforeach
    </div>
</div>
 
@endsection
