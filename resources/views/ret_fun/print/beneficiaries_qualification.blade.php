@extends('print_global.print')
@section('content')
<div>
    <div>
    @include('affiliates.print.full_personal_info', ['affiliate'=>$affiliate])
    </div>
</div>
@endsection