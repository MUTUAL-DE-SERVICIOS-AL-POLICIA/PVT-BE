<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PLATAFORMA VIRTUAL - MUSERPOL {{ $title }}</title>
    <link rel="stylesheet" href="css/materialicons.css" media="all" />
    <link rel="stylesheet" href="{{ public_path('css/wkhtml.css') }}" media="all" />
</head>
<body class="no-border">
    <div class="page-break">
        <table class="w-100 " style="{{isset($with_padding) ? 'padding-left:48px' : ''}}">
        <tr>
            <th class="w-20 text-left no-padding no-margins align-middle">
                <div class="text-center">
                    <img src="{{ public_path('images/logo.jpg') }}" class="w-100">
                </div>
            </th>
            <th class="w-50 align-top">
                <span class="font-semibold uppercase leading-tight text-md" >
                    {{ $institution ?? 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"' }} <br>
                    {{ $direction ?? 'DIRECCIÓN DE BENEFICIOS ECONÓMICOS' }} <br>
                    @if(isset($unit1))
                        {!! $unit1 !!}
                    @endif
                    {{ $unit ?? 'UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO' }}
                </span>
            </th>
            <th class="w-20 no-padding no-margins align-top">
                @if(isset($code))
                    <table class="table-code no-padding no-margins">
                        <tbody>
                            <tr>
                                <td class="text-center bg-grey-darker text-xxs text-white">Nº de Trámite</td>
                                <td class="text-bold text-base">{!! $code !!}</td>
                            </tr>
                            @if(isset($area))
                            <tr>
                                <td class="text-center bg-grey-darker text-xxs text-white">Área</td>
                                <td class="text-xs">{!! $area !!}</td>
                            </tr>
                            @endif
                            @if(isset($user))
                            <tr>
                                <td class="text-center bg-grey-darker text-xxs text-white">Usuario</td>
                                <td class="text-xs">{!! $user->username !!}</td>
                            </tr>
                            @endif
                            <tr>
                                <td class="text-center bg-grey-darker text-xxs text-white">Fecha</td>
                                <td class="text-xs uppercase">{!! $date !!}</td>
                            </tr>
                        </tbody>
                    </table>
                @else
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
                @endif
            </th>
        </tr>
        <tr><td colspan="3" style="border-bottom: 1px solid #22292f;"></td></tr>
        <tr>
            <td colspan="3" class="font-bold text-center text-xl uppercase">
                {{ $title }}
                @if (isset($subtitletwo))
                <br><span class="font-bold text-lg">{!! $subtitletwo ?? '' !!}</span>
                @endif
                @if (isset($subtitle))
                <br><span class="font-medium text-lg">{!! $subtitle ?? '' !!}</span>
                @endif
            </td>
        </tr>
        {{-- <tr><td colspan="3"></td></tr>
        <tr><td colspan="3"></td></tr> --}}

    </table>

    <div class="block" style="{{isset($with_padding) ? 'padding-left:48px' : ''}}">
        @yield('content')
    </div>
    <div class="block" style="padding:0px">
        @yield('other_content')
    </div>
    <footer class="absolute pin-b no-margins no-paddings w-100">
        @yield('footer')
    </footer>
    </div>
</body>
</html>