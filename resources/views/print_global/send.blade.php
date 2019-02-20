<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PLATAFORMA VIRTUAL - MUSERPOL {{ $title ?? '' }}</title>
    <link rel="stylesheet" href="{{ asset('css/materialicons.css') }}" media="all" />
    <link rel="stylesheet" href="{{ asset('css/wkhtml.css') }}" media="all" />
</head>

<body>
    <div class="page-break">
        <table class="w-100 ">
            <tr>
                <th class="w-15 text-left no-padding no-margins align-middle">
                    <div class="text-center">
                        <img src="{{ asset('images/logo.jpg') }}" class="w-100">
                    </div>
                </th>
                <th class="w-50 align-top">
                    <span class="font-semibold uppercase leading-tight text-md">
                    {{ $institution ?? 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"' }} <br>
                    {{ $direction ?? 'DIRECCIÓN DE BENEFICIOS ECONÓMICOS' }} <br>
                    @if(isset($unit1))
                        {!! $unit1 !!}
                    @endif
                    {{ $unit ?? 'UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO' }}
                </span>
                </th>
                <th class="w-20 no-padding no-margins align-top">
                </th>
            </tr>
            <tr>
                <td colspan="3" style="border-bottom: 1px solid #22292f;"></td>
            </tr>
            <tr>
                <td colspan="3" class="font-bold text-center text-xl uppercase">
                    {{ $title ?? 'custom title' }} @if (isset($subtitle))
                    <br><span class="font-medium text-lg">{!! $subtitle ?? '' !!}</span> @endif
                </td>
            </tr>

        </table>

        <div class="block">

            @yield('content')
            <table class="table-info w-100 m-b-5 text-xl">
                <thead class="bg-grey-darker">
                    <tr class="font-medium text-white text-m text-center">
                        <td><strong>Nº</strong></td>
                        <td><strong>TRÁMITE</strong></td>
                        <td><strong>FECHA RECEP.</strong></td>
                        <td><strong>CERT. </strong></td>
                        <td><strong>FECHA CERT.</strong></td>
                        <td><strong>C.I.</strong></td>
                        <td><strong>NOMBRE</strong></td>
                        <td><strong>MODALIDAD</strong></td>
                        <td><strong>CIUDAD</strong></td>
                    <tr></tr>
                </thead>
                <tbody>
                    @php
                        $index = 1
                    @endphp
                    @foreach ($retirement_funds as $retirement_fund)
                        <tr>
                            <td class="uppercase px-5 text-right">{{ $index++ }}</td>
                            <td class="uppercase px-5 text-right">{{ $retirement_fund->code }}</td>
                            <td class="uppercase px-5 text-right">{{ date('d/m/Y',strtotime($retirement_fund->reception_date)) }}</td>
                            <td class="uppercase px-5 text-right">{{ $retirement_fund->getCorrelative($from_area->id)->code }}</td>
                            <td class="uppercase px-5 text-right">{{ date('d/m/Y',strtotime($retirement_fund->getCorrelative($from_area->id)->date)) }}</td>
                            <td class="uppercase px-15 text-right">{{ $retirement_fund->affiliate->ciWithExt() }}</td>
                            <td class="uppercase px-15 text-left">{{ $retirement_fund->affiliate->fullName() }}</td>
                            <td class="uppercase px-15 text-center">{{ $retirement_fund->procedure_modality->name }}</td>
                            <td class="uppercase px-15 text-center">{{ $retirement_fund->city_start->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br><br>
            <table class="m-t-35">
                <tr>
                    <td class="no-border text-center text-base w-50 align-bottom">
                        <span class="font-bold">
                            ----------------------------------------------------
                        </span>
                    </td>
                    <td class="no-border text-center text-base w-50 align-bottom">
                        <span class="font-bold">
                            ----------------------------------------------------
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="no-border text-center text-base w-50 align-top">
                        <span class="font-bold">{!! mb_strtoupper($from_area->name) !!}</span>
                        <br/>
                        <span class="font-bold block">{!! strtoupper($user->fullName()) !!}</span>
                    </td>
                    <td class="no-border text-center text-base w-50">
                        <span class="font-bold block">{!! mb_strtoupper($to_area->name) !!}</span>
                        {{-- <div class="text-xs text-center" style="width: 350px; margin:0 auto; font-weight:100">{!! $user->position !!}</div> --}}
                    </td>
                </tr>
            </table>
        </div>
        <footer>
            @yield('footer')
        </footer>
    </div>
</body>

</html>