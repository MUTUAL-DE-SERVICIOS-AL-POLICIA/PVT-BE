<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $pdftitle }}</title>
    <link rel="stylesheet" href="css/materialicons.css" media="all" />
    <link rel="stylesheet" href="{{ public_path('css/wkhtml.css') }}" media="all" />
</head>
<body class="no-border">
    {{-- Sirve para marcar la mitad de la hoja --}}
    <div style="position: absolute; width: 100%; height: 50%; top: 0px; left: 0px; border-bottom: 1px solid #22292f;">
    </div>
    @for ($i = 1; $i <= 2; $i++)
    <div class="page-break" style="{{$i == 2 ? 'position: absolute; top: 50%; margin-top: 25px' : ''}}">
        <table class="w-100 ">
            <tr>
                <th class="w-15 text-left no-padding no-margins align-middle">
                    <div class="text-center">
                        <img src="{{ public_path('images/logo.jpg') }}" class="w-100">
                    </div>
                </th>
                <th class="w-50 align-top">
                    <span class="font-semibold uppercase leading-tight text-md">
                        MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"<br>
                        DIRECCIÓN DE BENEFICIOS ECONÓMICOS<br>
                        UNIDAD DE OTORGACIÓN DEL BENEFICIO DEL COMPLEMENTO ECONÓMICO
                    </span>
                </th>
                <th class="w-20 no-padding no-margins align-top">
                    <table class="table-code align-top no-padding no-margins">
                        <tbody>
                            <tr>
                                <td class="text-center bg-grey-darker text-xs text-white">N° de Trámite</td>
                                <td class="text-sm">{{ $eco_com->code }}</td>
                            </tr>
                            <tr>
                                <td class="text-center bg-grey-darker text-xs text-white">Usuario</td>
                                <td class="text-sm">{!! $user->username !!}</td>
                            </tr>
                            <tr>
                                <td class="text-center bg-grey-darker text-xs text-white">Fecha</td>
                                <td class="text-sm uppercase" style="white-space: nowrap; padding-left: 10px; padding-right: 10px;">{{ $date }}</td>
                            </tr>
                        </tbody>
                    </table>
                </th>
            </tr>
            <tr>
                <td colspan="3" style="border-bottom: 1px solid #22292f;"></td>
            </tr>
            <tr>
                <td colspan="3" class="font-bold text-center text-md uppercase">
                    DETALLE DEL PAGO DEL COMPLEMENTO ECONÓMICO
                </td>
            </tr>
            <tr>
                <td colspan="3" class="font-bold text-center text-md uppercase">
                    {{ $subtitle }}
                </td>
            </tr>
        </table>
        <br>
        <div class="font-bold uppercase m-b-5 counter">
            Datos del Beneficiario
        </div>
        <table class="table-info w-100 m-b-5">
            <thead class="bg-grey-darker">
                <tr class="font-bold text-white text-sm">
                    <td class="px-20 py text-center">
                        C.I.
                    </td>
                    <td class="px-15 py text-center">
                        PRIMER NOMBRE
                    </td>
                    <td class="px-15 py text-center">
                        SEGUNDO NOMBRE
                    </td>
                    <td class="px-15 py text-center">
                        PRIMER APELLIDO
                    </td>
                    <td class="px-15 py text-center">
                        SEGUNDO APELLIDO
                    </td>
                    @if ($applicant->surname_husband)
                    <td class="px-15 py text-center">
                        APELLIDO CASADA
                    </td>
                    @endif
                </tr>
            </thead>
            <tbody>
                <tr class="text-base">
                    <td class="text-center font-bold " style="white-space: nowrap; padding-left: 10px; padding-right: 10px;">{!! $applicant->ciWithExt() !!}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $applicant->first_name }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $applicant->second_name }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $applicant->last_name }}</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $applicant->mothers_last_name }}</td>
                    @if ($applicant->surname_husband)
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $applicant->surname_husband }}</td>
                    @endif
                </tr>
            </tbody>
        </table>
        <div class="font-bold uppercase m-b-5 counter">
            Datos del Trámite
        </div>
        <table class="table-info w-100 m-b-5">
            <thead class="bg-grey-darker">
                <tr class="font-bold text-white text-sm">
                    <td class="px-15 text-center uppercase">
                        TIPO DE TRÁMITE
                    </td>
                    <td class="px-15 text-center uppercase">
                        PRESTACIÓN
                    </td>
                    <td class="px-15 text-center uppercase">
                        MODALIDAD DE PAGO
                    </td>
                    <td class="px-15 text-center uppercase">
                        REGIONAL
                    </td>
                    <td class="px-15 text-center uppercase">
                        NRO DE TRAMITE
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr class="text-base">
                    <td class="text-center uppercase font-bold px-5 py-3">
                        {{ $eco_com->eco_com_reception_type->name }}
                    </td>
                    <td class="text-center uppercase font-bold px-5 py-3">
                        {{ $eco_com->eco_com_modality->procedure_modality->name }}
                    </td>
                    <td class="text-center uppercase font-bold px-5 py-3">
                        {{ $eco_com->eco_com_state->name }}
                    </td>
                    <td class="text-center uppercase font-bold px-5 py-3">
                        {{ $eco_com->city->name }}
                    </td>
                    <td class="text-center uppercase font-bold px-5 py-3">
                        {{ $eco_com->code }}
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="font-bold uppercase m-b-5 counter">
            Datos Policiales del Titular
        </div>
        @include('eco_com.print.only_police_info', ['affiliate'=>$affiliate])
        <div class="font-bold uppercase m-b-5 counter">
            Detalle de pago del beneficio del complemento económico
        </div>
        <table class="table-info w-100 m-b-10">
            <thead class="bg-grey-darker">
                <tr class="font-bold text-white text-sm uppercase">
                    <td class="px-15 text-center">
                        Detalle de pago
                    </td>
                    <td class="px-15 text-center">
                        Bolivianos
                    </td>
                </tr>
            </thead>
            <tbody class="table-striped text-xs">
                @if ($eco_com->hasDiscountTypes())
                <tr class="text-base">
                    <td class="w-60 text-left px-10 py-3 uppercase font-bold">TOTAL COMPLEMENTO ECONÓMICO CALCULADO</td>
                    <td class="w-15 text-right uppercase px-5 py-3 font-bold"> {{ Util::formatMoney($eco_com->getOnlyTotalEcoCom() )}} </td>
                </tr>
                @endif
                @foreach ($eco_com->discount_types as $d)
                    <tr class="text-base">
                        <td class="w-60 text-left px-20 py-3 uppercase"> - {{ $d->name }}</td>
                        <td class="w-15 text-right uppercase px-5 py-3"> - {{ Util::formatMoney($d->pivot->amount)}} </td>
                    </tr> 
                @endforeach
                <tr class="text-base">
                    <td class="text-left px-10 py-3 uppercase font-bold">{{$eco_com->hasDiscountTypes() ? 'TOTAL LÍQUIDO PAGADO' : 'TOTAL COMPLEMENTO ECONÓMICO CALCULADO'}}</td>
                    <td class="text-right uppercase px-5 py-3 font-bold"> {{ Util::formatMoney($eco_com->total) }} </td>
                </tr>
                <tr class="text-base">
                    <td class="text-left px-10 py-3 uppercase font-bold"  colspan=2>Son: {{ Util::convertir($eco_com->total) }} BOLIVIANOS</td>
                </tr>
            </tbody>
        </table>
        <p class="text-justify">
            <b>NOTA: </b> Este documento no se constituye en un comprobante de ejecución de recursos de pago, 
            ni garantía de futuras otorgaciones del Beneficio del Complemento Económico.
        </p>
    </div>
    {{-- Sirve para reiniciar la numeración que se realiza en el wkhtml.css --}}
    <div style="counter-reset: number 0 indext 0;"></div>
    @endfor
</body>
</html>