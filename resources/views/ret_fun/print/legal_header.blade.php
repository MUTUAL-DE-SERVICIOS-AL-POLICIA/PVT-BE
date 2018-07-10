<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        *{
            font-family: 'Noto sans'
        }
        .w-20{
            width: 20%;
        }
        .w-60{
            width: 60%;
        }
        .w-100{
            width: 100%;
        }
        .inline{
            display: inline
        }
        .no-border{
            border:none;
        }
    </style>
</head>
<body>
    <div style="width:850px; margin:0 auto;">
        <table class="w-100">
            <tr>
                <th class="w-20 text-left no-padding no-margins align-middle" style="width:20%">
                    <div class="text-center">
                        <img src="{{asset('images/logo.jpg')}}" class="w-100">
                    </div>
                </th>
                <th class="w-60 align-top" style="width:60%">
                    <span class="font-semibold uppercase leading-tight text-md">
                                                MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"
                                                <br> DIRECCIÓN DE BENEFICIOS ECONÓMICOS
                                                <br> UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO
                                              </span>
                </th>
                <th class="w-20 no-padding no-margins align-top" style="width:20%">
                    <div class="text-center">
                        <img src="{{asset('images/escudo.jpg')}}" class="w-60">
                    </div>
                </th>
            </tr>
            <tr class="no-border">
                <td colspan="3" class="no-border" style="border-bottom: 1px solid #22292f;"></td>
            </tr>
            <tr class="no-border">
                <td colspan="3" class="no-border"></td>
            </tr>
            <tr class="no-border">
                <td colspan="3" class="no-border"></td>
            </tr>
        </table>
    </div>
    {{-- <table style="width:100%">
        <tr>
            <th class="w-20 text-left no-padding no-margins align-middle" style="width:20%">
                <div class="text-center">
                    <img src="{{asset('images/logo.jpg')}}" class="w-100">
                </div>
            </th>
            <th class="w-60 align-top" style="width:60%">
                <span class="font-semibold uppercase leading-tight text-md">
                                MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"
                                <br> DIRECCIÓN DE BENEFICIOS ECONÓMICOS
                                <br> UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO
                              </span>
            </th>
            <th class="w-20 no-padding no-margins align-top" style="width:20%">
                <div class="text-center">
                    <img src="{{asset('images/escudo.jpg')}}" class="w-60">
                </div>
            </th>
        </tr>
        
    </table> --}}
</body>
</html>