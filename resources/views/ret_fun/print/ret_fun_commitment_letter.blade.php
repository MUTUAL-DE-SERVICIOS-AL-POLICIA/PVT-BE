@extends('print_global.print')
@section('content')
    <div class="text-justify">
        <span>
            En aplicación del Artículo 18 del Reglamento de Cuota Mortuoria y Auxilio Mortuorio y a solicitud expresa, se suscribe el presente compromiso de pago, al tenor de lo siguiente:<br>
            <br>
            <strong>PRIMERA.- </strong>
            Yo, <span class="uppercase font-bold">{!! $affiliate->fullName() !!}</span>, con C.I. N° <b>{!! $affiliate->identity_card !!} {!! $affiliate->city_identity_card->first_shortened ?? " "!!}</b>, como funcionario de público de la Policía Boliviana, DECLARO encontrarme en la siguiente situación de:
        </span> 
        <ul>
            <li>
                {{ $glosa }}
            </li>
        </ul>
        <br>
        <span >
            Mediante (resolución o memorándum) <b>{{ $commitment->number }}</b>, de fecha <b>{{ $commitment->commitment_date}}</b>, motivo por el cual y para continuar aportando de manera regular al beneficio de Fondo de Retiro Policial Solidario, expreso mi voluntad de realizar los aportes de forma voluntaria, directa, continua y mensual previa liquidación en oficina central u oficinas regionales, hacerse efectiva en el área de Tesorería de la MUSERPOL, o a través de depósito bancario en las cuentas fiscales de la Institución del Banco Unión, el mismo día de la liquidación. Asimismo, a la conclusión de la situación laboral en la que me encuentro, deberé presentar el Memorándum de Repliegue o la Resolución de Restitución de Funciones y/o Derechos Institucionales. Al mismo tiempo declaro que tomé conocimiento de los artículos del reglamento referidos al aporte voluntario (Artículos 12, 13, 14, 16, 17, 18 y 19) y me apego a la modalidad de aportación, hasta la conclusión de la situación antes declarada.
            <br><br>
            <strong>SEGUNDA.- </strong>
            En caso de realizar el aporte a través de depósito bancario, me comprometo a presentar la boleta de pago original como constancia del depósito a la Oficina Central o mediante las Agencias Regionales de la MUSERPOL, en un plazo no mayor a 90 días calendario.
            <br><br>
            <strong>TERCERA.- </strong>
            Como funcionario público del servicio activo de la Policía Boliviana, expreso mi conformidad de aportar con el 5,86% {!! $glosa_pago !!}, para el Fondo de Retiro Policial Solidario y Cuota Mortuoria, determinado en el Estudio Matemático Actuarial 2016 – 2020.
            <br><br>
            <strong>CUARTA.- </strong>
            Habiendo dado lectura del presente compromiso, expreso mi conformidad y como constancia firmo al pie del presente.
        </span>
    </div>
    <p> Lugar y Fecha: {!! ucwords(strtolower($city)).", ".$date !!} </p>
    <br><br>
    <table class="w-100">
        <tr>
            <th class="no-border text-center">
                <p class="font-bold">----------------------------------------------------<br>
                    AFILIADO<br>
                {!! $affiliate->fullName() !!}<br/>
                </p>
            </th>
            <th class="no-border text-center">
                <p class="font-bold">----------------------------------------------------<br>
                    MUSERPOL<br>
                </p>
            </th>
        </tr>
    </table>

</div>
@endsection
