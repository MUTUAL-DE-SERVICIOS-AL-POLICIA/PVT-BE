<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>PLATAFORMA VIRTUAL - MUSERPOL</title>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" media="all"/>
    </head>
    <body>
        <table class="tableh">
            <tr>
                <th style="width: 25%;border: 0px;">
                    <div id="logo">
                        <img src="{{ asset('images/logo.jpg') }}" >
                    </div>
                </th>
                <th style="width: 50%;border: 0px">
                    <h4>
                        <b>
                            <u>
                                MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"<br>
                                DIRECCIÓN DE BENEFICIOS ECONOMICOS<br>
                                UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO<br>
                            </u>
                        </b>
                    </h4>
                </th>
                <th style="width: 25%;border: 0px">
                    <div id="logo2">
                        <img src="{{ asset('images/escudo.jpg') }}" >
                    </div>
                </th>
            </tr>
        </table>
        <table class="table">
            <tr>
                <td style="border: 0px;text-align:left;">
                    <div class="title">
                        <b>
                            Fecha Emisión:
                        </b>
                        {!! $fec_emi !!}
                    </div>
                </td>
                {{--  @if(isset($user))  --}}
                <td style="border: 0px;text-align:right;">
                    <div class="title">
                        <b>
                            Usuario: 
                        </b> 
                        {!! $usuario !!} <br>
                    </div>
                </td>
                {{--  @endif  --}}
            </tr>
        </table>
        
        <div>
            <h1>{{ $title }} </h1>
            @yield('content')
        </div>
        
        <footer>
            @yield('footer')
        </footer>
        
    </body>
</html>