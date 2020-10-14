@extends('print_global.print')
<br/>
@section('content')

<div>

    <p class="text-justify">
    En aplicación del Artículo 18 del Reglamento de Auxilio Mortuorio, el (la) afiliado (a),
     suscribe el presente compromiso de pago, al tenor de lo siguiente:
    </p>
    <p class="text-justify">
        <span class="font-bold uppercase">
        PRIMERA.- 
        </span> 
        Yo,
        <span class="font-bold uppercase">
        {{ $eco_com_beneficiary->fullName() }} 
        </span> 
        con Cédula de Identidad N°<span class="font-bold uppercase"> {{ $eco_com_beneficiary->ciWithExt() }}</span>, 
        como miembro del servicio pasivo de la Policía Boliviana, mediante declaración de pensión/contrato (adjunto), DECLARO que
        <span class="font-bold">recibo una prestación en curso de pago del Sistema Integral de Pensiones (SIP)</span>
        , motivo por el cual para continuar aportando de manera regular al beneficio de Auxilio Mortuorio, 
        expreso mi voluntad de realizar los aportes MEDIANTE DESCUENTO DIRECTO DEL COMPLEMENTO ECONÓMICO, 
        DE FORMA CONTINUA (SEMESTRAL), HASTA LA SOLICITUD FORMAL DE CESAMIENTO DEL DESCUENTO EN ESTA MODALIDAD, 
        previa liquidación en la Oficina Central o Representaciones Regionales de la MUSERPOL, 
        mismo que se hará efectivo CON LA SOLICITUD DE JEFATURA DE LA UNIDAD DE FONDO DE RETIRO POLICIAL SOLIDARIO, 
        CUOTA Y AUXILIO MORTUORIO A LA JEFATURA DE UNIDAD DE COMPLEMENTO ECONÓMICO, ANTES DEL INICIO DE PAGO DE DICHO BENEFICIO, 
        CON EFECTO VENIDERO. De igual forma declaro que tomé conocimiento del Reglamento y sus artículos (12, 13, 14, 16, 17, 18, 19 y 36) 
        referidos al aporte para acceder al beneficio de Auxilio Mortuorio.
    </p>
    <p class="text-justify">
        <span class="font-bold uppercase">
        SEGUNDA.-  
        </span>
        Como miembro del servicio pasivo de la Policía Boliviana, 
        expreso mi conformidad de aportar de acuerdo al PORCENTAJE determinado por el Estudio Matemático Actuarial 
        vigente a la fecha, sobre la totalidad de mi renta o pensión mensual para el beneficio de Auxilio Mortuorio.
    </p>
    <p class="text-justify">
        <span class="font-bold uppercase">
        TERCERA.-  
        </span>
        Declaro expresamente que adecuaré mi afiliación y pago de aportes directos, 
        al nuevo Reglamento del Beneficio de Auxilio Mortuorio en caso de modificación.
    </p>
    <p class="text-justify">
        <span class="font-bold uppercase">
        CUARTA.-  
        </span>
        Habiendo dado lectura del presente compromiso, expreso mi conformidad y 
        como constancia firmo al pie del presente.
    </p>


    <div class="text-justify">Lugar y Fecha: {{ $user->city->name ?? 'La Paz' }}, {{ Util::getTextDate() }}.</div>
    <br/>
    <table class="m-t-50">
        <tr>
            <td class="no-border text-center text-base w-50 align-bottom">
                <span class="font-bold">
                ____________________________________________________
            </span>
            </td>
            
            <td class="no-border text-center text-base w-50 align-bottom">
                <span class="font-bold">
                ____________________________________________________
            </span>
            </td> 
        </tr>
        <tr>     
            <td class="no-border text-center text-base w-50 align-top">
                <span class="font-bold">{!! strtoupper($eco_com_beneficiary->fullName()) !!}</span>
                <br/>
                <span class="font-bold">C.I. {{ $eco_com_beneficiary->ciWithExt() }}</span>
            </td>
            <td class="no-border text-center text-base w-50">
                <span class="font-bold block">REPRESENTANTE</span>
                <div class="text-xs text-center" style="width: 350px; margin:0 auto; font-weight:100">MUSERPOL</div>
            </td>
        </tr>
    </table>
    <br/>
    <p class="text-justify text-xxs">   
        <span class="font-bold uppercase">
        Nota 1.-  
        </span>
        Una vez se confirme la otorgación del Beneficio de Complemento Económico, 
        se procederá con el descuento de las cuotas por concepto de Auxilio Mortuorio de forma anticipada.
    </p>
    <p class="text-justify text-xxs">   
        <span class="font-bold uppercase">
        Nota 2.-  
        </span>
        En caso de no otorgarse el beneficio de Complemento Económico, 
        se procederá a la anulación del presente documento y a la devolución de los requisitos presentados por el Titular, 
        debiendo efectivizar el pago de aportes de manera mensual.
    </p>
    <p class="text-justify text-xxs">   
        <span class="font-bold uppercase">
        Nota 3.-  
        </span>
        En caso de reajuste, recálculo o incremento de la Pensión del Sistema Integral de Pensiones (SIP), 
        deberá apersonarse por oficinas de la MUSERPOL a nivel nacional, a fin de regularizar los aportes 
        generados a partir del pago anticipado por Complemento Económico.
    </p>
</div>
@endsection