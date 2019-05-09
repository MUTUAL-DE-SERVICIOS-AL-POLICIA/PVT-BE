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
      $icons = [
        1 => ['color' => 'red-bg', 'icon' => 'glyphicon glyphicon-hdd fa-4x'],
        2 => ['color' => 'lazur-bg', 'icon' => 'fa fa-puzzle-piece fa-5x'],
        3 => ['color' => 'yellow-bg', 'icon' => 'glyphicon glyphicon-piggy-bank fa-4x'],
        4 => ['color' => 'blue-bg', 'icon' => 'fa fa-heartbeat fa-5x'],
        6 => ['color' => 'blue-bg', 'icon' => 'fa fa-money fa-5x'],
        7 => ['color' => 'blue-bg', 'icon' => 'fa fa-balance-scale fa-5x'],
        10 => ['color' => 'green-bg', 'icon' => 'fa fa-map fa-5x'],
      ];
      $icons[8]=$icons[2];
      $icons[9]=$icons[6];
      $icons[5]=$icons[4];
      $icons[11]=$icons[10];

      switch($count) {
        case(1):
          $style = "<div class='col-md-3 col-md-offset-4'>";
          break;
        case(2):
          $style = "<div class='col-md-3 col-md-offset-2'>";
          break;
        case(3):
          $style = "<div class='col-md-3 col-md-offset-1'>";
          break;
        default:
          $style = "<div class='col-md-3'>";
      }
    @endphp
    @foreach($roles as $role)
    {!! Form::open(['url' => 'postchangerol', 'role' => 'form']) !!}
      @php
        echo $style;
      @endphp
        <button class="widget style1 {{$icons[$role->module_id]['color']}} px-1 text-center" style="height: 100px; width: 100%; border: none">
          <div class="row">
            <div class="col-xs-3">
              <i class="{{$icons[$role->module_id]['icon']}}"></i>
            </div>
            <div class="col-xs-9">
              <span>
                <b><p style="font-size: 1.5em;">{{$role->name}}</p></b>
              </span>
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