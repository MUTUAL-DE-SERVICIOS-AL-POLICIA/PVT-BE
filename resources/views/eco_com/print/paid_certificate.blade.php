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
    <div class="page-break">
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
                                <td class="text-center bg-grey-darker text-xxs text-white">Área</td>
                                <td class="text-xs">{!! $area !!}</td>
                            </tr>
                            <tr>
                                <td class="text-center bg-grey-darker text-xxs text-white">Usuario</td>
                                <td class="text-xs">{!! $user->username !!}</td>
                            </tr>
                            <tr>
                                <td class="text-center bg-grey-darker text-xxs text-white">Fecha</td>
                                <td class="text-xs uppercase">{{ $date }}</td>
                            </tr>
                        </tbody>
                    </table>
                </th>
            </tr>
            <tr>
                <td colspan="3" style="border-bottom: 1px solid #22292f;"><br></td>
            </tr>
            <tr>
                <td colspan="3" class="font-bold text-center text-xl uppercase">
                    BOLETA DE PAGO DEL BENEFICIO DEL COMPLEMENTO ECONÓMICO
                </td>
            </tr>
            <tr>
                <td colspan="3" class="font-bold text-center text-xl uppercase">
                    {{ $subtitle }}
                </td>
            </tr>
            <tr>
                <td colspan="3" class="text-right text-xl">
                    <br>
                </td>
            </tr>
        </table>
        <br>
        <div class="font-bold uppercase m-b-5 counter">
            Datos del Beneficiario
        </div>
        <table class="table-info w-100 m-b-5">
            <thead class="bg-grey-darker">
                <tr class="font-medium text-white text-sm">
                    <td class="px-20 py text-center">
                        C.I.
                    </td>
                    <td class="px-15 py text-center ">
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
                <tr class="text-sm">
                    <td class="text-center font-bold">{!! $applicant->ciWithExt() !!}</td>
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
                <tr class="font-medium text-white text-sm">
                    <td class="px-15 text-center text-sm uppercase">
                        TIPO DE TRÁMITE
                    </td>
                    <td class="px-15 text-center text-sm uppercase">
                        MODALIDAD
                    </td>
                    <td class="px-15 text-center text-sm uppercase">
                        REGIONAL
                    </td>
                    <td class="px-15 text-center text-sm uppercase">
                        NRO DE TRAMITE
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr class="text-sm">
                    <td class="text-center uppercase font-bold px-5 py-3">
                        {{ $eco_com->eco_com_reception_type->name }}
                    </td>
                    <td class="text-center uppercase font-bold px-5 py-3">
                        {{ $eco_com->eco_com_modality->procedure_modality->name }}
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
                <tr class="font-medium text-white text-sm uppercase">
                    <td class="px-15 text-center">
                        Detalle de pago
                    </td>
                    <td class="px-15 text-center">
                        Montos
                    </td>
                    <td class="px-15 text-center">
                        Descuento
                    </td>
                </tr>
            </thead>
            <tbody class="table-striped text-xs">
                @if ($eco_com->hasDiscountTypes())
                <tr class="text-sm">
                    <td class="w-60 text-left px-10 py-3 uppercase">TOTAL COMPLEMENTO ECONÓMICO EN BOLIVIANOS</td>
                    <td class="w-15 text-right uppercase px-5 py-3"> {{ Util::formatMoney($eco_com->getOnlyTotalEcoCom() )}} </td>
                    <td class="w-15  text-center uppercase px-5 py-3"></td>
                </tr>
                @endif
                @foreach ($eco_com->discount_types as $d)
                    <tr class="text-sm">
                        <td class="w-60 text-left px-20 py-3 uppercase"> - {{ $d->name }}</td>
                        <td class="w-15  text-center uppercase px-5 py-3"></td>
                        <td class="w-15 text-right uppercase px-5 py-3"> {{ Util::formatMoney($d->pivot->amount)}} </td>
                    </tr> 
                @endforeach
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase font-bold">{{$eco_com->hasDiscountTypes() ? 'LIQUIDO PAGADO EN BOLIVIANOS' : 'TOTAL COMPLEMENTO ECONÓMICO EN BOLIVIANOS'}}</td>
                    <td class="text-right uppercase px-5 py-3 font-bold"> {{ Util::formatMoney($eco_com->total) }} </td>
                    <td class="text-center uppercase px-5 py-3"></td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase font-bold"  colspan=3>Son: {{ Util::convertir($eco_com->total) }} BOLIVIANOS</td>
                </tr>
            </tbody>
        </table>
        <br>
        <p class="text-justify">
            Sin embargo, cabe señalar que el monto individual del Complemento Económico es variable, 
            determinado semestralmente en base a un Estudio Técnico Financiero y reglamentación aprobado por el Directorio de la MUSERPOL, 
            en función a las transferencias determinadas por Ley para el pago del Complemento Económico. 
            Asimismo, el Complemento Económico no se encuentra comprendido como salario o sueldo, derecho laboral, 
            beneficio social o emergente de aportes a la Seguridad Social de Largo Plazo, en razón a su fuente de financiamiento y variabilidad de pago, 
            no siendo parte del Sistema de Reparto ni del Sistema Integral de Pensiones, 
            de conformidad a lo establecido en el Decreto Supremo Nº 1446 del 19 de diciembre de 2012, 
            modificado mediante Decreto Supremo Nº 3231 del 28 de junio de 2017.
        </p>
        <p class="text-justify">
            <b>NOTA: </b> Este documento no se constituye en un comprobante de ejecución de recursos de pago, 
            ni garantía de futuras otorgaciones del Beneficio del Complemento Económico.
        </p>
    </div>
    <footer class="absolute pin-b no-margins no-paddings w-100">
        <div class="w-99">
            <div class="w-95 mx-auto">
                <table class="w-100 table-collapse ">
                    <tr>
                        <th class="no-padding no-margins align-middle" style="width:63px; border-top: 2px solid; ">
                            <div class="text-right no-padding no-margins">
                                <img src="data:image/png;base64, {{ $bar_code }}" />
                            </div>
                        </th>
                        <th class="align-top text-left text-xs font-normal align-middle px-10"
                            style="border-top: 2px solid; ">
                            Procesado por: <span class="italic">{{ $user->fullName() }}</span><br>
                            PLATAFORMA VIRTUAL DE TRÁMITES
                            MUTUAL DE SERVICIOS AL POLICÍA - MUSERPOL <br>
                            <span class="italic">http://www.muserpol.gob.bo</span>
                        </th>
                        <th class="no-padding no-margins align-middle text-right px-10"
                            style="border-top: 2px solid; border-left: 2px solid; ">
                            PVT
                            <br>
                            MUSERPOL
                        </th>
                    </tr>
                </table>
            </div>
        </div>
    </footer>
</body>
</html>