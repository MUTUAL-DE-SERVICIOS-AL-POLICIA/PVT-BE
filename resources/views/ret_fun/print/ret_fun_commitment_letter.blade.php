@extends('print_global.print')
@section('content')
    <div class="text-justify">
        <span>
            En aplicación del Artículo 18 del Reglamento de Cuota Mortuoria y Auxilio Mortuorio y a solicitud expresa, se suscribe el presente compromiso de pago, al tenor de lo siguiente:<br>
            <br>
            <strong>PRIMERA.- </strong>
            Yo, <span class="uppercase font-bold">{!! $affiliate->fullName() !!}</span>, con C.I. N° <b>{!! $affiliate->identity_card !!}</b>, como funcionario de público de la Policía Boliviana, DECLARO encontrarme en la siguiente situación de:
        </span> 
        <ul>
            <li>
                {{ $glosa }}
            </li>
        </ul>
        <br>
        <span >
            Mediante (resolución o memorándum) <b>{{ $commitment->number }}</b>, de fecha <b>{{ \Muserpol\Helpers\Util::getStringDate($commitment->commitment_date)}}</b>, motivo por el cual y para continuar aportando de manera regular al beneficio de Fondo de Retiro Policial Solidario, expreso mi voluntad de realizar los aportes de forma voluntaria, directa, continua y mensual previa liquidación en oficina central u oficinas regionales, hacerse efectiva en el área de Tesorería de la MUSERPOL, o a través de depósito bancario en las cuentas fiscales de la Institución del Banco Unión, el mismo día de la liquidación. Asimismo, a la conclusión de la situación laboral en la que me encuentro, deberé presentar el Memorándum de Repliegue o la Resolución de Restitución de Funciones y/o Derechos Institucionales. Al mismo tiempo declaro que tomé conocimiento de los artículos del reglamento referidos al aporte voluntario (Artículos 12, 13, 14, 16, 17, 18 y 19) y me apego a la modalidad de aportación, hasta la conclusión de la situación antes declarada.
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
    <table class="m-t-35">
        <tr>
            <td class="no-border text-center text-base w-50 align-bottom">
                <span class="font-bold">
                    ----------------------------------------------------
                </span>
            </td>
            <td class="no-border text-center text-base w-50 align-bottom">
                <span class="font-bold">
                    ----------------------------------------------------
                </span>
            </td>
        </tr>
        <tr>
            <td class="no-border text-center text-base w-50 align-top">
                <span class="font-bold">{!! strtoupper($affiliate->fullName()) !!}</span>
                <br/>
                <span class="font-bold">C.I. {!! $affiliate->identity_card !!}</span>
            </td>
            <td class="no-border text-center text-base w-50">
                <span class="font-bold block">{!! strtoupper($user->fullName()) !!}</span>
                <div class="text-xs text-center" style="width: 350px; margin:0 auto; font-weight:100">{!! $user->position !!}</div>
            </td>
        </tr>
    </table>

</div>
@endsection
