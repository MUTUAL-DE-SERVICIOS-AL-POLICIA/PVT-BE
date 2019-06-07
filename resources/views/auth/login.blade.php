@extends('layouts.login')

@section('title', 'Autentificacion')

@section('content')


    <div>
        <div>

            <h1 class="logo-name">M+</h1>

        </div>
        
        <p></p>
        <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

            <div class="form-group">
                <input type="text" name="username" placeholder="Usuario" class="form-control" <?php if(Session::has('b_user')){ echo "value='".Session::get('b_user')."'";}?>   required/>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Contraseña" class="form-control" <?php if(Session::has('b_pass')){ echo "value='".Session::get('b_pass')."'";}?> required/>
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">Iniciar Sesión </button>

            
           
        </form>
        <p class="m-t"> <small>Muserpol &copy; {{ now()->year }}</small> </p>
    </div>
 
@endsection
