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
                      <b><u>CONSIDERANDO:</u></b>
                    </div>
                    <p class="text-justify">
                        {!! $law !!}
                    </p>
                    <div >
                        <b><u>CONSIDERANDO:</u></b>
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
                    <br>
                        <b><u>POR TANTO:</u></b>                        
                        <br><br>
                    </div>
                    <div>
                        {!! $then !!}
                        <br>
                    </div>
                    <div >
                        <br>
                        <b><u>RESUELVE:</u></b>
                        <br><br>
                    </div>
                    <div>
                        {!! $body_resolution !!}
                        <br>
                    </div>
                <div >

        </div>
        @include('ret_fun.print.signature_footer',['user'=>$user])
    </div>
</body>