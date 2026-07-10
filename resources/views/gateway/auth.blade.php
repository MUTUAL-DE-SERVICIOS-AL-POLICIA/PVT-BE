@extends('gateway.plantilla')

@section('title', 'Importación de Documentos')

@section('content')

  <div class="column">
    <form class="form" method="POST" action="{{ route('gateway.analysisDocuments') }}">
        <div>
            <label>Usuario</label>
            <input type="text" name="user" required>
        </div>
        <div>
            <label>Contraseña</label>
            <input type="password" name="pass" required>
        </div>
        <input type="hidden" name="path" value="documentos_pvtbe">
        <button type="submit">Analizar Documentos</button>
    </form>
  </div>

@endsection