@extends('layouts.app')

@section('title', 'Afiliados')

@section('content')

 <form action="{{asset('ret_fun')}}" method="post">
     {{ csrf_field() }}
  First name: <input type="text" name="name"><br>
  Last name: <input type="text" name="lastname"><br>
  <input type="submit" value="Submit">
</form> 
@endsection