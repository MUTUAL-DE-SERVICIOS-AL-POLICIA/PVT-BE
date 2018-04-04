@extends('print_global.print') 
@section('content')
<div>
    @include('ret_fun.print.qualification_beneficiaries_fair_share', ['beneficiaries'=>$beneficiaries])
</div>
@endsection