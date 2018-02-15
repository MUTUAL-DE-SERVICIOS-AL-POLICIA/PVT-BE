<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PLATAFORMA VIRTUAL - MUSERPOL {{-- $title --}}</title>
    <link rel="stylesheet" href="{{ asset('css/wkhtml.css') }}" media="all" />
</head>
<body>
    <table class="table-header">
        <tr>
            <th class="w-20 p-5">
                <div class="logo-left">
                    <img src="{{ asset('images/logo.jpg') }}" class="w-100">
                </div>
            </th>
            <th class="w-50 p-5">
                <h4 class="font-bold uppercase">
                    {{ $institution ?? 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"' }} <br>
                    {{ $direction ?? 'DIRECCIÓN DE BENEFICIOS ECONÓMICOS' }} <br>
                    {{ $unit ?? 'UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO' }} <br>
                </h4>
            </th>
            <th class="w-20 p-5">
                <div class="logo-right">
                    <img src="{{ asset('images/escudo.jpg') }}" class="w-75">
                </div>
            </th>
        </tr>
    </table>
    <table class="w-100">
        <tr>
            <td class="w-50 text-left">
                <span class="font-bold capitalize">Fecha Emisión: </span>{!! $date !!}
            </td>
            <td class="w-50 text-right">
                <span class="font-bold capitalize">Usuario: </span>{!! $username !!}
            </td>
        </tr>
    </table>
    <div class="block">
        <h2 class="text-center uppercase">{{ $title }}</h2>
        @if (isset($subtitle))
            <h3 class="text-center uppercase">{{ $subtitle ?? '' }}</h3>
        @endif
        @yield('content')
    </div>
    <footer>
        @yield('footer')
    </footer>
</body>
</html>