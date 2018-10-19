<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PLATAFORMA VIRTUAL - MUSERPOL {{ $title ?? '' }}</title>
    <link rel="stylesheet" href="{{ asset('css/materialicons.css') }}" media="all" />

    <link rel="stylesheet" href="{{ asset('css/wkhtml.css') }}" media="all" />
    <style>
        p{
            page-break-after: avoid;
        }
    </style>
</head>

<body style="padding:0 65px 0px 65px; " class="no-border text-base-1">
    <div class="page-break">     
        <div class="block">
            <div class="text-right">
                <span class="block">
                    {{ $actual_city }}, {{ $actual_date }}
                 </span>
                <span class="block">
                   DBE/UFRPSCAM/AL-DL N° {{ $correlative->code }}
                 </span>
            </div>
            <div class="block">
                <div class="text-center text-2xl font-bold underline uppercase">
                    DICTAMEN LEGAL  
                </div>
            </div>
            <p class="text-left font-bold">
                <span class="uppercase">{!! $quota_aid->procedure_modality->procedure_type->second_name !!}</span> <br> Modalidad: <span class="uppercase">{{ $quota_aid->procedure_modality->name }}</span>
            </p>

            <div class="block">
                <p class="text-justify">
                    {!! $person !!}
                </p>
                <p class="text-justify">                    
                    {!! $law !!}
                </p>
                <ul class="m-l-30">
                    <li class="text-justify">
                        {!! $body_file !!}
                    </li>
                    <li class="text-justify">
                        {!! $body_finance !!}
                    </li>
                    <li class="text-justify">
                        {!! $body_legal_review !!}
                    </li>
                    <li class="text-justify">
                        {!! $body_accounts !!}
                    </li>
                    <li class="text-justify">
                        {!! $body_qualification !!}
                    </li>
                    <li class="text-justify">
                        {{ $body_due }}
                    </li>
                    @if($correlative->note!="")
                        <li class="text-justify">
                            {{ $correlative->note }}
                        </li>
                    @endif
                </ul>
                {{--
                <ul>
                    <li>
                        Que, mediante Certificación D.B.E/A.B.E./GMQ/N° {{ $file_code }}, de fecha {{ Util::getStringDate($file_date) }}, de Archivo
                        de Beneficios Económicos, se establece que el trámite signado con el N° {{ $quota_aid->code }}
                        @if ($has_file) si tiene expediente del referido titular y cuenta con anticipo de Fondo de Retiro
                        Policial. @else no tiene expediente del referido titular. @endif
                    </li>

                    <li>
                        Que, mediante nota de respuesta de la Dirección Administrativa Financiera con Cite: MUSERPOL/DAF/JF/UC/Nº {{ $admin_fin_cite
                        }} de fecha {{ Util::getStringDate($admin_fin_date) }}, @if ($has_admin_file) se evidencia anticipo
                        por concepto de Fondo de Retiro Policial en el monto de {{ $admin_fin_amount }} ({{ Util::convertir($admin_fin_amount)
                        }}). @else no se evidencia pagos o anticipos por concepto de Fondo de Retiro Policial. @endif
                    </li>
                    <li>
                        Que, mediante Certificación N° {{ $legal_code }} del Área Legal de la Unidad de Otorgación del Fondo de Retiro Policial Solidario,
                        Cuota y Auxilio Mortuorio, de fecha {{ Util::getStringDate($legal_date) }}, fue verificada y
                        validada la documentación presentada por el titular el trámite signado con el N° {{ $quota_aid->code
                        }}.
                    </li>
                    <li>
                        Que, mediante Certificación de Aportes N° {{ $aportes_code }} de Cuentas Individuales de la Unidad de Otorgación del Fondo
                        de Retiro Policial Solidario, Cuota y Auxilio Mortuorio, de fecha {{ Util::getStringDate($aportes_date)
                        }}, se verificó los últimos {{ $number_contributions }} aportes antes de su destino a disponibilidad
                        de las letras (reserva activa) del titular. Mediante Certificación de Aportes en Disponibilidad
                        N° {{ $availability_code }} de Cuentas Individuales de la Unidad de Otorgación del Fondo de Retiro
                        Policial Solidario, Cuota y Auxilio Mortuorio, de fecha {{ Util::getStringDate($availability_date)
                        }}, durante la permanencia en la reserva activa se verificó {{ $availability_number_contributions
                        }} aportes en disponibilidad.
                    </li>
                    <li>
                        Que, mediante Calificación Fondo de Retiro Policial Solidario N° {{ $qualification_code }} de la Encargada de Calificación
                        de la Unidad de Otorgación del Fondo de Retiro Policial Solidario, Cuota y Auxilio Mortuorio,
                        de fecha {{ Util::getStringDate($qualification_date) }}, se realizó el cálculo por el periodo
                        de {{ $qualification_years }} y {{ $qualification_months }}, determinando el beneficio de Fondo
                        de Retiro Policial Solidario por Jubilación de {{ $qualification_amount }} ({{ Util::convertir($qualification_amount)
                        }}){{Util::getDiscountCombinations($quota_aid->id)}} {{-- reserva date examples 2 anios 1 anio
                        y 1 mes 1 anio y 4 meses 1 anio 2 anios y 5 meses 2 anios y 1 mes ... --}} {{-- . Por concepto
                        de devolución de aportes durante la permanencia en la reserva activa, se realizó el cálculo por
                        el periodo de {{ $reserva_date }} , en base a la prima del 1,85%, más el {{ $annual_yield }}
                        anual de rendimiento, determinando el monto de {{ $reserva_amount }} ({{Util::convertir($reserva_amount)}});
                        haciendo un total de {{$quota_aid->total}} ({{Util::convertir($quota_aid->total)}}). --}} {{-- </li>
                </ul>
                --}}
                <p class="text-justify">
                    {!! $payment !!}
                </p>
                <br>
                <br>
                @include('ret_fun.print.signature_footer',['user'=>$user])
            </div>
            <footer>
                @yield('footer')
            </footer>
        </div>
    </div>
</body>

</html>