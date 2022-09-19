@extends('print_global.print')
@section('content')
<div>
        <div class="font-bold uppercase m-b-5 counter">
            Datos del Afiliado
        </div>
            @include('print_global.police_info', ['affiliate'=>$affiliate])
        <div class="font-bold uppercase m-b-5 counter">
            Datos Policiales del Afiliado
        </div>
        @include('print_global.only_police_info', ['affiliate'=>$affiliate])

    <div class="font-bold uppercase m-b-5 m-t-10 counter">
        Historial de datos generales del afiliado
    </div>
    <table class="table-info w-100">
        <thead class="bg-grey-darker">
            <tr class="font-medium text-white">
                <td class="px-15 py text-center ">
                Detalle
                </td>
                <td class="px-15 py text-center ">
                Fecha de verificación
                </td>
            </tr>
        </thead>
        <tbody>
            @foreach ($affiliate_police_records as $affiliate_police_record)
            <tr >
                <td class="text-left uppercase px-10 py-5">
                    {{ $affiliate_police_record->message}}
                </td>
                <td class="text-center uppercase px-10 py-5">
                    {{date("d/m/Y", strtotime($affiliate_police_record->date))}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p class="text-center">
        {{ $user->city->name ?? 'La Paz' }}, {{ Util::getTextDate() }}
    </p>
</div>
@endsection