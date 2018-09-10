<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PLATAFORMA VIRTUAL - MUSERPOL {{ $title ?? '' }}</title>
    <link rel="stylesheet" href="{{ asset('css/materialicons.css') }}" media="all" />
    <link rel="stylesheet" href="{{ asset('css/wkhtml.css') }}" media="all" />
</head>


<body style="border: none">
    <div class="page-break">
        <table class="w-100 ">
            <tr>
                <th class="w-20 text-left no-padding no-margins align-middle">
                    <div class="text-center">
                        <img src="{{asset('images/logo.jpg')}}" class="w-100">
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
                        <img src="{{asset('images/escudo.jpg')}}" class="w-100">
                    </div>
                </th>
            </tr>
            <tr class="no-border">
                <td colspan="3" class="no-border" style="border-bottom: 1px solid #22292f;"></td>
            </tr>
        </table>
        <div class="block">
                <div class="text-center text-2xl font-bold underline uppercase">
                        RESOLUCI&Oacute;N DE LA COMISI&Oacute;N DE BENEFICIOS ECON&Oacute;MICOS
                    </div>
            <span class="block text-center">
                N° {{ $correlative->code }}
              </span>
        </div>
        <div class="block">
                <div class="text-center text-2 font-bold underline uppercase">
                        REF: FONDO DE RETIRO POLICIAL SOLIDARIO-{{$retirement_fund->procedure_modality->name}}
                    </div>
            <span class="block text-center">
                    <td>{{ $actual_city }}, <b>{{ $actual_date }}</b></td>
              </span>
        </div>
        <div class="block">
            

                <div>
                    
                    <div >
                      <b>CONSIDERANDO:   </b>
                    </div>
                    <p class="text-justify">
                        {!! $law !!}
                    </p>
                    <div >
                        <b>CONSIDERANDO:   </b>
                    </div>
                    <div>
                        {!! $body_finance !!} 
                        <br>
                    </div>
                    <div>
                        {!! $reception !!}
                        <br>
                    </div>
                    <div>
                        {!! $body_qualification !!}
                        <br>
                    </div>
                <div >

        </div>
    </div>
</body>