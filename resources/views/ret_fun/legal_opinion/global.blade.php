<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PLATAFORMA VIRTUAL - MUSERPOL {{ $title ?? '' }}</title>
    <link rel="stylesheet" href="{{ public_path('css/materialicons.css') }}" media="all" />
    <link rel="stylesheet" href="{{ public_path('css/wkhtml.css') }}" media="all" />
</head>

<body>
    <div class="page-break">
        <table class="w-100 ">
            <tr>
                <th class="w-20 text-left no-padding no-margins align-middle">
                    <div class="text-center">
                        <img src="{{public_path('images/logo.jpg')}}" class="w-100">
                    </div>
                </th>
                <th class="w-60 align-top">
                    <span class="font-semibold uppercase leading-tight text-md">
                    MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"
                    <br> DIRECCIÓN DE BENEFICIOS ECONÓMICOS
                    <br> UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO
                  </span>
                </th>
                <th class="w-20 no-padding no-margins align-top">
                    <div class="text-center">
                        <img src="{{public_path('images/escudo.jpg')}}" class="w-60">
                    </div>
                </th>
            </tr>
            <tr class="no-border">
                <td colspan="3" class="no-border" style="border-bottom: 1px solid #22292f;"></td>
            </tr>
        </table>
        <div class="block">
            <div class="text-right">
                <span class="block">
                   La Paz, 29 de diciembre de 2017
                 </span>
                <span class="block">
                   DBE/UFRPSCAM/AL-DL N° 381/2017
                 </span>
            </div>
            <div class="block">
                <div class="text-center text-2xl font-bold underline uppercase">
                    DICTAMEN LEGAL
                </div>
            </div>
            <p class="text-left font-bold">
                Fondo de Retiro Policial Solidario <br> Modalidad: <span class="uppercase">JUBILACIÓN</span>
            </p>

        <div class="block">
            @yield('content')
        </div>
        <footer>
            @yield('footer')
        </footer>
    </div>
</body>

</html>