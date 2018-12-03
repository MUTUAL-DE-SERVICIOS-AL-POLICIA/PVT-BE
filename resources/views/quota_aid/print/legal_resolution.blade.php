<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PLATAFORMA VIRTUAL - MUSERPOL {{ $title ?? '' }}</title>
    <link rel="stylesheet" href="{{ asset('css/materialicons.css') }}" media="all" />
    <link rel="stylesheet" href="{{ asset('css/wkhtml.css') }}" media="all" />
</head>
<body style="padding:0 65px 0px 65px; " class="no-border {{ Session::get('size') ?? 'text-base' }}">
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
                        REF: {{$quota_aid->procedure_modality->procedure_type->second_name}}-{{$quota_aid->procedure_modality->name}}
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
                    <div class="text-justify">
                        {!! $reception !!}
                        <br>
                    </div>
                    <div class="text-justify">
                        {!! $body_qualification !!}
                        <br>
                    </div>
                    <div class="text-justify">
                        <br>
                        {!! $body_legal_dictum !!}
                        <br>
                    </div>
                    <div class="text-justify">
                    <br>
                        <b><u>POR TANTO:</u></b>
                        <br><br>
                    </div>
                    <div class="text-justify">
                        {!! $then !!}
                        <br>
                    </div>
                    <div class="text-justify">
                        <br>
                        <b><u>RESUELVE:</u></b>
                        <br><br>
                    </div>
                    <div class="text-justify">
                        {!! $body_resolution !!}
                        <br>
                    </div>
                <div >

        </div>
        {{-- @include('ret_fun.print.commission_signature',['user'=>$user]) --}}
    </div>
</body>