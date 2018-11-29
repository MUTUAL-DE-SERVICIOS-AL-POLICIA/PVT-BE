<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PLATAFORMA VIRTUAL - MUSERPOL {{ $title ?? '' }}</title>
    <link rel="stylesheet" href="{{ asset('css/materialicons.css') }}" media="all" />
    <link rel="stylesheet" href="{{ asset('css/wkhtml.css') }}" media="all" />
    <style>
        .courier{
            padding-top: 3px;
            font-family: 'Courier new', monospace;
            font-weight: 500 !important;
        }
    </style>
</head>
<body class="no-border text-xs">
    @for ($i = 0; $i < 3; $i++)
        <div class="m-b-20" style="height:490px !important; border-bottom: 1px dashed #3c3c3c ">
            <table class="w-100 ">
                <tr>
                    <th class="w-20 text-left no-padding no-margins align-middle">
                        <div class="text-center">
                            <img src="{{ asset('images/logo.jpg') }}" class="w-75">
                        </div>
                    </th>
                    <th class="w-50 align-top">
                        <div class="font-semibold uppercase leading-tight text-sm">
                        {{ $institution ?? 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"' }} <br>
                        {{ $direction ?? 'DIRECCIÓN DE BENEFICIOS ECONÓMICOS' }} <br>
                        {{ $unit ?? 'UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO' }}
                        </div>
                    </th>
                    <th class="w-20 no-padding no-margins align-top">
                        <table class="table-code no-padding no-margins">
                            <tbody>
                                <tr>
                                    <td class="text-center bg-grey-darker text-xxs text-white">Nº de Trámite</td>
                                    <td class="text-bold text-base">{!! $code !!}</td>
                                </tr>
                                <tr>
                                    <td class="text-center bg-grey-darker text-xxs text-white">Usuario</td>
                                    <td class="text-xs">{!! $user->username !!}</td>
                                </tr>
                                <tr>
                                    <td class="text-center bg-grey-darker text-xxs text-white">Fecha</td>
                                    <td class="text-xs uppercase">{!! $date !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </th>
                </tr>
                <tr>
                    <td colspan="3" style="border-bottom: 1px solid #22292f;"></td>
                </tr>
                <tr>
                    <td colspan="3" class="font-bold text-center text-lg uppercase">
                        {{ $title }} @if (isset($subtitle))
                        <br><span class="font-medium text-lg">{!! $subtitle ?? '' !!}</span> @endif
                    </td>
                </tr>
            </table>
            <div class="block">
                <div class="font-bold uppercase m-b-5">
                    1.- Datos del TitulaR
                </div>
                <table class="table-info w-100 m-b-5">
                    <thead class="bg-grey-darker">
                        <tr class="font-medium text-white text-xs">
                            <td class="px-15 text-left uppercase">
                                NOMBRE COMPLETO
                            </td>
                            <td class="px-15 text-left uppercase w-30">
                                Cédula de identidad
                            </td>
                            <td class="w-10 px-15 text-right uppercase">
                                NUP
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-xs">
                            <td class="text-left uppercase font-bold px-10 py-3">
                                {{ $applicant->fullName() }}
                            </td>
                            <td class="text-left uppercase font-bold px-10 py-3">
                                {{ $applicant->ciWithExt() }}
                            </td>
                            <td class="text-right uppercase font-bold px-10 py-3">
                                {{ $applicant instanceof Muserpol\Models\Affiliate ?  $applicant->id : $applicant->affiliate_id }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="font-bold uppercase m-b-5">
                    2.- PERIODOS
                </div>
                <div style="height: 138px !important;">
                    @if ($direct_contribution->procedure_modality->procedure_type_id == 6)
                        <table class="table-info table-background">
                            <thead class="bg-grey-darker text-white">
                                <tr class="text-center text-sm">
                                    <td>
                                        Mes/Año
                                    </td>
                                    <td>
                                        Total Ganado Bs.
                                    </td>
                                    <td>
                                        F.R.P. (4.77 %)
                                    </td>
                                    <td>
                                        Cuota Mortuoria (1.09 %)
                                    </td>
                                    <td>
                                        Ajuste UFV Bs.
                                    </td>
                                    <td>
                                        Subtotal Aporte
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contributions as $c)
                                <tr class="courier text-base">
                                    <td class="px-5 text-left uppercase"> {{ Util::printMonthYear($c->month_year) }} </td>
                                    <td class="px-5 text-right"> {{ $c->quotable }} </td>
                                    <td class="px-5 text-right"> {{ $c->retirement_fund }} </td>
                                    <td class="px-5 text-right"> {{ $c->mortuary_quota }} </td>
                                    <td class="px-5 text-right"> {{ $c->interest }} </td>
                                    <td class="px-5 text-right"> {{ $c->total }} </td>
                                </tr>
                                @endforeach
                                <tr class="text-base">
                                    <td></td>
                                    <td colspan="4" class="text-left px-10 ">
                                        Son: <span class="font-bold uppercase"> {{ Util::convertir($contribution_process->total) }} </span>
                                    </td>
                                    <td class="text-lg text-right courier font-bold">
                                        <span class="font-bold">
                                            {{ Util::formatMoney($contribution_process->total) }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @else
                        <table class="table-info table-background">
                            <thead class="bg-grey-darker text-white">
                                <tr class="text-xs text-center font-bold">
                                    <td>
                                        Mes/Año
                                    </td>
                                    <td>
                                        Renta Bs.
                                    </td>
                                    <td>
                                        Renta Dignidad Bs.
                                    </td>
                                    <td>
                                        Auxilio Mortuorio (2.03 %)
                                    </td>
                                    <td>
                                        Ajuste UFV Bs.
                                    </td>
                                    <td>
                                        Subtotal Aporte
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contributions as $c)
                                <tr class="courier">
                                    <td class="px-5 text-left uppercase"> {{ Util::printMonthYear($c->month_year) }} </td>
                                    <td class="px-5 text-right"> {{ $c->rent }} </td>
                                    <td class="px-5 text-right"> {{ $c->dignity_rent }} </td>
                                    <td class="px-5 text-right"> {{ $c->mortuary_aid }} </td>
                                    <td class="px-5 text-right"> {{ $c->interest }} </td>
                                    <td class="px-5 text-right"> {{ $c->total }} </td>
                                </tr>
                                @endforeach
                                <tr>
                                    <td></td>
                                    <td colspan="4" class="text-sm text-left px-10 ">
                                        Son: <span class="font-bold uppercase"> {{ Util::convertir($contribution_process->total) }} </span>
                                    </td>
                                    <td class="text-lg text-right courier font-bold">
                                        <span class="font-bold">
                                            {{ Util::formatMoney($contribution_process->total) }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
                <div style="width:500px;height:120px;" class="mx-auto border rounded text-xs m-t-5">
                        <table class="pt-45">
                            <tr>
                                <td class="no-border text-center w-100 align-bottom">
                                    <span class="font-bold">&nbsp;
                                        {{-- ---------------------------------------------------- --}}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="no-border text-center w-100">
                                    <span class="font-bold block">{!! strtoupper($user->fullName()) !!}</span>
                                    <div class="text-center uppercase" style="width: 450px; margin:0 auto; font-weight:100">{!! $user->position !!}</div>
                                </td>
                            </tr>
                        </table>
                </div>

            </div>
        </div>
    @endfor
</body>
</html>