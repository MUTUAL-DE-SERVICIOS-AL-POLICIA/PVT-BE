@extends('print_global.print')
@section('content')
<div>
    <div class="font-bold uppercase m-b-5 counter">
        RECONOCIMIENTO DE OBLIGACIÓN
    </div>
    <p class="text-justify">
        Yo <strong>{{ $eco_com_beneficiary->fullName() }}</strong>, mayor de edad con Cédula de Identidad Nº {!!
        $eco_com_beneficiary->ciWithExt() !!}, domiciliado en la Zona {{ $affiliate->address()->first() ? $affiliate->address()->first()->zone : '-' }},
        calle {{ $affiliate->address()->first() ? $affiliate->address()->first()->street: '-' }}, Nro. {{ $affiliate->address()->first() ? $affiliate->address()->first()->number_address : '-' }}, de la ciudad de
        {{ $affiliate->address()->first() ? $affiliate->address()->first()->city->name: '-' }}, hábil por derecho y en mi calidad de beneficiario (a) del Complemento
        Económico que otorga la Mutual de Servicios al Policía – MUSERPOL al sector pasivo de la Policía Boliviana, que
        habiendo sido notificado por haber percibido pagos en defecto del Complemento Económico correspondiente al
        {{ $semesters}} por un importe de Bs. {{ Util::formatMoney($devolution->total) }}
        ({{ Util::convertir($devolution->total) }} BOLIVIANOS), <strong>
            @if($devolution->percentage)
            expreso mi conformidad de manera voluntaria para que se efectúe el descuento con el
            {{ $devolution->percentage * 100 }}% del beneficio del Complemento Económico a partir del
            {{ $current_semester->semester }} Semestre de la gestión {{ $current_semester->getYear() }}, hasta
            cubrir el monto determinado.
            @else
            {{-- @if($devolution->deposit_number && $devolution->payment_date)
                    expreso mi conformidad de manera voluntaria para efectuar la devolución del total del monto
                    en defecto inicialmente determinado.
                    @else
                    expreso mi conformidad de manera voluntaria para que se efectúe el descuento del total del monto en defecto inicialmente determinado, con el beneficio del Complemento Económico del {{ $current_semester->semester }}
            semestre de la gestión {{ $current_semester->getYear() }}.
            @endif --}}
            @endif
    </p>
    <div class="font-bold uppercase m-b-5 counter">
        MONTOS ADEUDADOS POR SEMESTRE Y GESTIÓN
    </div>
    <table class="table-info w-100">
        <thead class="bg-grey-darker">
            <tr class="font-medium text-white">
                <td class="px-15 py text-center">
                    GESTION
                </td>
                <td class="px-15 py text-center">
                    MONTO ADEUDADO
                </td>
            </tr>
        </thead>
        <tbody>
        @php($sum = 0)
        @foreach ($duess as $dd)
            @foreach ($dues as $d)
             @if($dd== $d->id)
             @php($sum += $d->amount)
            <tr>
                <td class="text-left uppercase px-10 py-3">
                    {{ $d->eco_com_procedure->getTextName()}}
                </td>
                <td class="text-right px-10 py-3">
                    {{ Util::formatMoney($d->amount) }}

                </td>
            </tr>
            @endif
            @endforeach
          
            
        @endforeach
        </tbody>
    </table>
    <div class="w-80 mx-auto m-t-20">
        <table class=" w-100 ">
            <tr>
                <td class="border uppercase font-bold p-5">DEUDA TOTAL</td>
                <td class="border font-bold  p-5"> Bs.
                    {{ Util::formatMoney($sum) }}({{ Util::convertir($sum) }} BOLIVIANOS)
                </td>
            </tr>
        </table>
    </div>
    <div class="font-bold uppercase m-b-5 counter">
        Datos del Trámite
    </div>
    @include('eco_com.print.info',['eco_com'=>$eco_com])
    <div class="font-bold uppercase m-b-5 counter">
        Datos del Beneficiario
    </div>
    @include('eco_com.print.applicant_info', ['applicant'=>$eco_com_beneficiary])
    <div>
        <table class="m-t-75">
            <tr>
                <td class="no-border text-center text-base w-50 align-bottom">
                    <span class="font-bold">
                        ----------------------------------------------------
                    </span>
                </td>
                {{--
                <td class="no-border text-center text-base w-50 align-bottom">
                    <span class="font-bold">
                    ----------------------------------------------------
                </span>
                </td> --}}
            </tr>
            <tr>
                {{--
                <td class="no-border text-center text-base w-50">
                    <span class="font-bold block">{!! strtoupper($user->fullName()) !!}</span>
                    <div class="text-xs text-center" style="width: 350px; margin:0 auto; font-weight:100">{!! $user->position !!}</div>
                </td> --}}
                <td class="no-border text-center text-base w-50 align-top">
                    <span class="font-bold">{!! strtoupper($eco_com_beneficiary->fullName()) !!}</span>
                    <br />
                    <span class="font-bold">C.I. {{ $eco_com_beneficiary->ciWithExt() }}</span>
                </td>
            </tr>
        </table>
    </div>
    <p class="m-t-40 font-bold text-xxs">NOTA: En caso de incumplimiento al presente compromiso este podrá ser elevado a
        Instrumento Público de acuerdo a las normas que rigen nuestro ESTADO, en señal de plena conformidad firmo al pie
        del presente documento.</p>
</div>
@endsection