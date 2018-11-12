@extends('layouts.login')

@section('title', 'Acceso Restringido')

@section('content')


    <div>
        <div>

            <h1 class="logo-name">M+</h1>

        </div>
        
        <p>No tiene permitido realizar esta accion</p>
        <button class="btn btn-info" onclick="cyk()"> Regresar Atras <i class="glyphicon glyphicon-share-alt"></i></button>

        
        <p class="m-t"> <small>Muserpol &copy; 2018</small> </p>
    </div>
    <script>
        function cyk() {
            window.history.back();
        }
    </script>
@endsection
