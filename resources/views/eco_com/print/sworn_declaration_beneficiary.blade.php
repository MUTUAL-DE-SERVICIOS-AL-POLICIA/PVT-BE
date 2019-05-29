@extends('print_global.print') 
@section('content')
<div>
    <div style="min-height:900px;height:900px; max-height:900px;">
        {{--
        <div class="font-bold uppercase m-b-5 counter">
            Datos del Trámite
        </div>
    @include('eco_com.print.info',['eco_com'=>$eco_com]) --}}
        <div class="font-bold uppercase m-b-5 counter">
            Datos del solicitante
        </div>
    @include('eco_com.print.applicant_info', ['applicant'=>$eco_com_beneficiary])
        <div class="font-bold uppercase m-b-5 counter">
            Datos Policiales del Titular
        </div>
    {{-- @include('eco_com.print.only_police_info', ['affiliate'=>$affiliate]) --}}
    @include('eco_com.print.police_info', ['affiliate' => $affiliate ])

        <div>
            <p class="justify"> Yo, <span class="font-bold uppercase">{{ $eco_com_beneficiary->fullName() }}</span> boliviano (a) de nacimiento
                con Cédula de Identidad <span class="font-bold uppercase">N° {{ $eco_com_beneficiary->ciWithExt() }} </span>.
                con estado civil <span class="font-bold uppercase">{{ $eco_com_beneficiary->getCivilStatus() }}</span> y
                con residencia actualmente en el Departamento de <span class="font-bold">{{ $economic_complement->city->name ?? '' }}</span>.;mayor
                de edad, y hábil por derecho; consiente de la responsabilidad que asumo ante la Mutual de Servicios al Policía
                – MUSERPOL, de manera voluntaria y sin que medie ningún tipo de presión, mediante la presente, <span class="font-bold">DECLARO:</span>
            </p>
            <p class="justify">
                Que mi persona no ha contraído nuevo matrimonio; por tanto, al no contar con el Certificado de Verificación de Partidas Matrimoniales emitido por el Servicio de Registro Cívico (SERECI), autorizo a la MUSERPOL efectuar la verificación de lo declarado ante las instancias que correspondan; protestando de mi parte a presentar el Certificado de Estado Civil y el Certificado de Matrimonio, ambos en originales y actualizados.
            </p>
            <p class="justify">
                En señal de conformidad en forma expresa y voluntaria firmo al pie de la presente en la ciudad de {{ $user->city->name ?? '' }} en fecha {{  Util::getTextDate() }}.
            </p>
            <table class="m-t-50">
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
                        <br/>
                        <span class="font-bold">C.I. {{ $eco_com_beneficiary->ciWithExt() }}</span>
                    </td>
                </tr>
            </table>
            <p class="m-t-40 font-bold text-xxs">Nota.- El presente documento tiene carácter de DECLARACIÓN JURADA, por lo que en caso de evidenciarse la falsedad de este, se procederá con las acciones legales pertinentes.</p>
        </div>
    </div>
</div>
@endsection