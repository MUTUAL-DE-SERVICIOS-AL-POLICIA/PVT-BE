@extends('print_global.print')
@section('content')
    <div class="px-40 m-t-20">
        <div class="text-justify">
            {{ $head }}
        </div>
        <br>
        <div class="text-justify">
            <strong>PRIMERA.- </strong>
            {!! $one !!}
        </div>
        <br>
        <div class="text-justify">
            <strong>SEGUNDA.- </strong>
            En caso de realizar el aporte a través de depósito bancario, me comprometo a presentar la boleta de pago original como constancia del depósito a la Oficina Central o mediante las Agencias Regionales de la MUSERPOL, en un plazo no mayor a 90 días calendario.
        </div>
        <br>
        <div class="text-justify">
            <strong>TERCERA.- </strong>
            {{ $three  }}
        </div>
        <br>
        <div class="text-justify">
            <strong>CUARTA.- </strong>
                Habiendo dado lectura del presente compromiso, expreso mi conformidad y como constancia firmo al pie del presente.
        </div>
        <p> Lugar y Fecha: <span class="italic">{{ $city }} <span class="uppercase">{{ $date }}</span></span></p>
        <div class="mx-auto w-99 border rounded text-xs m-t-15 block text-center uppercase">
            <div class="text-center w-49 inline-block  align-top" style="height:150px;border-right: 1px solid #5d6975;">
                <div class="font-bold block m-t-100 ">{!! $user->fullName() !!}</div>
                <div class="block text-xxs">{!! $user->position !!}</div>
            </div>
            <div class="text-center w-49 inline-block align-top" style="height:150px;">
                <span class="font-bold block m-t-100 ">{!! $applicant->fullName() !!}</span>
                <div class="text-center ">{!! $applicant->ciWithExt() !!}</div>
            </div>
        </div>
    </div>
@endsection