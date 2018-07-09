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
            <div class="text-right">                
                <span class="block">
                   DBE/UFRPSCAM/AL-DL N° {{ $correlative }}
                 </span>
            </div>
            <div class="block">
                <div class="text-center text-2xl font-bold underline uppercase">
                        NFORME DE REVISIÓN
                </div>
                
            </div>
            <div>
                <table>
                    <tr>
                        <td><b>A:</b></td>
                        <td>{!! $to !!}</td>
                    </tr>
                    <tr>
                        <td><b>DE:</b></td>
                        <td> {!! $from !!}</td>
                    </tr>
                    <tr>
                        <td><b>REF:.</b></td>
                        <td> {!! $affiliate->fullNameWithDegree() !!}</td>
                    </tr>
                    <tr>
                        <td><b>FECHA:</b></td>
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
                    <div >
                     <b>I.</b> <b>ANTECEDENTES.-   </b>
                    </div>
                    <p class="text-justify">
                        {!! $past !!}
                    </p>
                    <p class="text-justify">
                        {!! $past_footer !!}
                    </p>
                    <div >
                        <b>II.</b> <b>PROCEDIMIENTOS REALIZADOS.-   </b>
                    </div>
                    <p class="text-justify">
                        {!! $process !!}
                    </p>

                    <ul>
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
                    <div >
                        <b>III.</b> <b>CONCLUSIONES.-   </b>
                    </div>          
                    <p class="text-justify">
                        {!! $conclusion !!}
                    </p>
                    <ul>
                        @foreach($payments as $payment)
                            <li class="text-justify">
                                {{ $payment }}
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