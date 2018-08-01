<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PLATAFORMA VIRTUAL - MUSERPOL {{ $title ?? '' }}</title>
    <link rel="stylesheet" href="{{ asset('css/materialicons.css') }}" media="all" />
    <link rel="stylesheet" href="{{ asset('css/wkhtml.css') }}" media="all" />
</head>

<body style="padding:0 70px 0 60px; " class="no-border">
    <div class="page-break">
        <div class="block">
            <div class="text-right">
                <span class="block">
                   DBE/UFRPSCAM/AL-DL N° {{ $correlative }}
                 </span>
            </div>
            <div class="block">
                <div class="text-center text-2xl font-bold underline uppercase">
                    INFORME DE REVISIÓN
                </div>
            </div>
            <div>
                <table>
                    <tr>
                        <td class="align-top"><strong>A:</strong></td>
                        <td>{!! $to !!}</td>
                    </tr>
                    <tr><td colspan="2"></td></tr>
                    <tr><td colspan="2"></td></tr>
                    <tr>
                        <td class="align-top"><strong>DE:</strong></td>
                        <td> {!! $from !!}</td>
                    </tr>
                    <tr><td colspan="2"></td></tr>
                    <tr><td colspan="2"></td></tr>
                    <tr>
                        <td class="align-top"><strong>REF.:</strong></td>
                        <td> {!! $affiliate->fullNameWithDegree() !!}</td>
                    </tr>
                    <tr><td colspan="2"></td></tr>
                    <tr><td colspan="2"></td></tr>
                    <tr>
                        <td class="align-top w-10"><strong>FECHA:</strong></td>
                        <td>{{ $actual_city }}, {{ $actual_date }}</td>
                    </tr>
                </table> 
            </div>
            <p class="text-left font-bold">
                Fondo de Retiro Policial Solidario <br> Modalidad: <span class="uppercase">{{ $ret_fun->procedure_modality->name }}</span>
            </p>
            <div class="block">
                <div>
                    <p class="text-justify">
                        {!! $head !!}
                    </p>
                    <div class="m-l-10">
                     <strong><span class="m-r-20">I.</span> ANTECEDENTES.-</strong>
                    </div>
                    <div class="m-l-50">
                        <p class="text-justify">
                            {!! $past !!}
                        </p>
                    </div>
                    <p class="text-justify">
                        {!! $past_footer !!}
                    </p>
                    <div class="m-l-10">
                     <strong><span class="m-r-20">II.</span> PROCEDIMIENTOS REALIZADOS.-</strong>
                    </div>
                    <p class="text-justify">
                        {!! $process !!}
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
                    </ul>
                    <div class="m-l-10">
                        <strong><span class="m-r-20">III.</span> CONCLUSIONES.-</strong>
                    </div>
                    <p class="text-justify">
                        {!! $conclusion !!}
                    </p>
                    <ul class="m-l-30">
                        @foreach($payments as $payment)
                            <li class="text-justify">
                                {!! $payment !!}
                            </li>
                        @endforeach
                    </ul>
                    <p class="text-justify">
                        {!! $end_conclusion !!}
                    </p>
                    {{--
                 <p class="text-justify">
                    {!! $payment !!}
                </p> --}}
                </div>
    
    
            </div>
            <footer>
                @yield('footer')
            </footer>
        </div>
    </body>
    
    </html>