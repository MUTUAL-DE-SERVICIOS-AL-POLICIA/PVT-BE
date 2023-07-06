@extends('print_global.print')
@section('content')
    <div class="text-justify">
        <span>
            En aplicación del Artículo 18 del Reglamento de Cuota Mortuoria y Auxilio Mortuorio y a solicitud expresa, se suscribe el presente compromiso de pago, al tenor de lo siguiente:<br>
            <br>
            <strong>PRIMERA.- </strong>
            Yo, .....{!! $bene->last_name." ".$bene->first_name !!}....., con C.I. N° ....{!! $bene->identity_card !!}...., {{ $beneficiary }}, DECLARO que:
        </span>
        <ul>
            <li>
                {{ $glosa }}
            </li>
        </ul>
        <br>
        <span >
            Mediante (declaración de pensión/contrato N°) ..., de fecha ....... , motivo por el cual y para continuar aportando de manera regular al beneficio de Auxilio Mortuorio, expreso mi voluntad de realizar los aportes de forma directa, continua y mensual previa liquidación en oficina central u oficinas regionales, misma que debe hacerse efectiva en el área de Tesorería de la MUSERPOL, o a través de depósito bancario en las cuentas fiscales de la Institución del Banco Unión, el mismo día de la liquidación. De igual forma declaro que tomé conocimiento de los artículos del reglamento referidos al aporte (Artículos 12, 13, 14, 16, 17, 18 y 19).
            <br><br>
            <strong>SEGUNDA.- </strong>
            En caso de realizar el aporte a través de depósito bancario, me comprometo a presentar la boleta de pago original como constancia del depósito a la Oficina Central o mediante las Agencias Regionales de la MUSERPOL, en un plazo no mayor a 90 días calendario.
            <br><br>
            <strong>TERCERA.- </strong>
            {{ ucfirst( $beneficiary ) }}, expreso mi conformidad de aportar con el 2,03% sobre la totalidad de mi renta o pensión mensual para el beneficio de Auxilio Mortuorio, según lo determinado en el Estudio Matemático Actuarial 2016 – 2020.
            <br><br>
            <strong>CUARTA.- </strong>
            Habiendo dado lectura del presente compromiso, expreso mi conformidad y como constancia firmo al pie del presente.
        </span>
    </div>
    <p> Lugar y Fecha: {!! ucwords(strtolower($city)).", ".$date !!} </p>
    <br>
    <table class="w-100">
        <tr>
            <th class="no-border text-center">
                <p class="font-bold">----------------------------------------------------<br>
                    AFILIADO<br>
                {!! $bene->last_name." ".$bene->first_name !!}<br/>
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
