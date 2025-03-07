@extends('gateway.plantilla')

@section('title', $data['message'])

@section('content')

  <div class="column">
    <a class="button" style="width: 50%;" href="{{ route('gateway.auth') }}"> Volver a la página de inicio</a>
  </div>

@endsection