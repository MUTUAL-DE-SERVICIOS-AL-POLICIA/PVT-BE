@extends('layouts.sinmenu')

@section('title', 'Autentificacion')

@section('content')


    <div>
        <div>

            <h1 class="logo-name">M+</h1>

        </div>
        
        <p></p>

        
        @foreach($roles as $role)
        {!! Form::open(['url' => 'postchangerol', 'role' => 'form']) !!}
        <div class="info-box">
            
                @if($role->module_id==1)
                <span class="info-box-icon bg-red">
                    <i class="glyphicon glyphicon-hdd"></i>,
                </span>
                @endif
                @if($role->module_id==2 || $role->module_id==8 )
                <span class="info-box-icon bg-aqua">
                    <i class="fa fa-fw fa-puzzle-piece fa-lg"></i>
                </span>
                @endif     
                @if($role->module_id==3)
                <span class="info-box-icon bg-yellow">
                    <i class="glyphicon glyphicon-piggy-bank"></i>
                </span>
                
                @endif

                @if($role->module_id==6 || $role->module_id==9)
                <span class="info-box-icon bg-green">
                    <i class="fa fa-fw fa-money "></i>
                </span>
                
                @endif
                @if($role->module_id==4 ||$role->module_id==5 )
                <span class="info-box-icon bg-green">
                    <i class="fa fa-fw fa-heartbeat "></i>
                </span>
                
                @endif

                @if($role->module_id==7)
                <span class="info-box-icon bg-green">
                    <i class="fa  fa-balance-scale "></i>
                </span>
                
                @endif
                @if($role->module_id==10)
                <span class="info-box-icon bg-green">
                    <i class="fa  fa-map "></i>
                </span>
                
                @endif
                
               

            <div class="info-box-content">
                <input type="hidden" name="rol_id" value={!! $role->id !!}"">

                <span class="info-box-text"> {!! $role->module->name !!} </span>
                <br>

                @if($role->module_id==1)   
                <button type="submit" class="btn btn-block btn-raised btn-danger">  {{$role->name}} <i class="glyphicon glyphicon-share-alt"></i></a>
                @endif 
                @if($role->module_id==2 || $role->module_id==8 )
                <button type="submit" class="btn btn-block btn-raised btn-info">  {{$role->name}} <i class="glyphicon glyphicon-share-alt"></i></a>
                @endif     
                @if($role->module_id==3)
                <button type="submit" class="btn btn-block btn-raised btn-warning">  {{$role->name}} <i class="glyphicon glyphicon-share-alt"></i></a>
                
                @endif
                @if($role->module_id==6 || $role->module_id==9)
                <button type="submit" class="btn btn-block btn-raised btn-success">  {{$role->name}} <i class="glyphicon glyphicon-share-alt"></i></a>
                
                @endif
                @if($role->module_id==4 ||$role->module_id==5 ||$role->module_id==7 ||$role->module_id==10 )
                <button type="submit" class="btn btn-block btn-raised btn-success">  {{$role->name}} <i class="glyphicon glyphicon-share-alt"></i></a>
                
                @endif

                
              
              {{-- <span class="info-box-number">90<small>%</small></span> --}}
            </div>
            <!-- /.info-box-content -->
        </div>
        {!! Form::close() !!}
        @endforeach

    </div>
 
@endsection
