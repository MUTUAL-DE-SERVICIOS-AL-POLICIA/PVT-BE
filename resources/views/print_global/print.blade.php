<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PLATAFORMA VIRTUAL - MUSERPOL {{-- $title --}}</title>
    
    <link rel="stylesheet" href="{{ asset('css/materialicons.css') }}" media="all" />
    <link rel="stylesheet" href="{{ asset('css/wkhtml.css') }}" media="all" />
</head>
<body>
    <table class="w-100 m-b-10">
        <tr>
            <th class="w-20 text-left">
                <div class="text-center">
                    <img src="{{ asset('images/logo.jpg') }}" class="w-100">
                </div>
            </th>
            <th class="w-50">
                <p class="font-bold uppercase leading-none text-sm" >
                    {{ $institution ?? 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"' }} <br>
                    {{ $direction ?? 'DIRECCIÓN DE BENEFICIOS ECONÓMICOS' }} <br>
                    {{ $unit ?? 'UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO' }}
                </p>
            </th>
            <th class="w-20">
                <table class="table-code">
                    <tbody>
                        <tr>
                            <td class="text-center bg-grey-darker text-xxs text-white">Nº de Trámite</td>
                            <td class="text-bold text-sm">{!! $number ?? 'ERROR' !!}</td>
                        </tr>
                        <tr>
                            <td class="text-center bg-grey-darker text-xxs text-white">Fecha de Emisión</td>
                            <td class="text-xs">{{ $date }}</td>
                        </tr>
                        <tr>
                            <td class="text-center bg-grey-darker text-xxs text-white">Usuario</td>
                            <td class="text-xs">{!! $username !!}</td>
                        </tr>
                    </tbody>
                </table>
            </th>
        </tr>
        <tr>
            <td colspan="3" class="font-bold text-center text-xl uppercase">
                {{ $title }}
                @if (isset($subtitle))
                    <br><span class="font-medium">{{ $subtitle ?? '' }}</span>
                @endif
            </td>
        </tr>
    </table>

    <div class="block">
        
        @yield('content')
    </div>
    <footer>
        @yield('footer')
    </footer>
</body>
</html>