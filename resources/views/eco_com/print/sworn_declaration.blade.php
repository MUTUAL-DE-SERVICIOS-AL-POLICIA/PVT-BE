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
                – MUSERPOL, de manera voluntaria y sin que medie ningún tipo de presión, mediante la presente, <span class="font-bold">DECLARO LO SIGUIENTE:</span>
            </p>
            <table class="table-info w-100 m-b-5">
                <thead class="bg-grey-darker">
                    <tr class="font-medium text-white text-sm">
                        <td class="text-center p-5">N°</td>
                        <td class="text-center p-5">.............</td>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <tr>
                        <td class="text-center p-5 intext"></td>
                        <td class="text-justify p-5">
                            @if($eco_com->isOldAge()) No percibo una pensión de jubilación por Riesgo Común y/o Profesional
                            o Invalidez Común y/o Profesional, por lo cual, la prestación o renta @endif @if($eco_com->isWidowhood()) No percibo una pensión por Riesgo Común y/o Profesional o Invalidez Común y/o Profesional
                            o Muerte, por lo cual, por lo cual, la prestación o renta @endif @if($eco_com->isOrphanhood()) No percibo una pensión de jubilación por Riesgo Común y/o Profesional e Invalidez Común
                            y/o Profesional o Muerte, por lo cual, la pensión @endif en curso de pago que percibo por parte
                            de las Administradoras del Fondo de Pensiones (AFP’s), Aseguradoras o del Sistema de Reparto
                            corresponde a una
                            <span class="font-bold uppercase">
                            PRESTACIÓN POR {{ $eco_com->eco_com_modality->procedure_modality->name }} {{ $eco_com->eco_com_modality->procedure_modality_id != 3 ? 'O RENTA DE JUBILACIÓN' : 'DERIVADA DE VEJEZ DEL CAUSAHABIENTE'}}
                        </span>, por lo que estaré a la espera de la respectiva valoración de
                            la documentación presentada a efecto que se determine si mi prestación se enmarca en la normativa
                            de acceso al beneficio del Complemento Económico, detallado en los Decreto Supremo N° 1446, 3231
                            y otros.
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center p-5 intext"></td>
                        <td class="text-justify p-5">
                            @if($eco_com->isOldAge()) Pertenezco al sector pasivo de la Policía Boliviana y acredito con
                            la @else Mi causahabiente perteneció al sector pasivo de la Policía Boliviana y acredita en la
                            @endif Certificación de Años de Servicio emitido por el Comando General de la Policía Boliviana
                            como mínimo de 16 años de servicio, asimismo, <strong>No</strong>                            {{ $eco_com->isOldAge() ? 'fui':'fue'}} dado de baja en forma obligatoria
                            o voluntaria de la Policía Boliviana.
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center p-5 intext"></td>
                        <td class="text-justify p-5">
                            La información y documentación proporcionada por mi persona, tanto verbal como la contenida en documentos respecto a los
                            requisitos mínimos para acceder al Beneficio del Complemento Económico, es totalmente <strong>legal y fidedigna</strong>,
                            por lo que me hago totalmente responsable de la misma.
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center p-5 intext"></td>
                        <td class="text-justify p-5">
                            {{ $eco_com->isOldAge() ? 'No tengo': 'Mi causahabiente y mi persona no tienen' }} sentencia
                            condenatoria ejecutoriada por delitos cometidos contra la MUSEPOL y/o MUSERPOL.
                        </td>
                    </tr>
                    @if($eco_com->isWidowhood())
                    <tr>
                        <td class="text-center p-5 intext"></td>
                        <td class="text-justify p-5">
                            No he contraído nuevo matrimonio y/o registro de partidas de Unión Libre; estado que será verificado según certificación
                            original de verificación de partidas matrimoniales y partida de Unión Libre emitida por el SERECI
                            y a través de la Contrastación de información respectiva, de ser necesario me comprometo a presentar
                            el Certificado de Estado Civil y el Certificado de Matrimonio Actualizado.
                        </td>
                    </tr>
                    @endif

                    <tr>
                        <td class="text-center p-5 intext"></td>
                        <td class="text-justify p-5">
                            Estoy consciente de que existe la probabilidad de ser excluido (a) por salario, por percibir una prestación por vejez <strong>IGUAL O SUPERIOR</strong>                            al haber básico más categoría que perciban los miembros del servicio activo de la Policía Boliviana
                            en el grado correspondiente, tal como lo señala en el Decreto Supremo N° 1446, modificado mediante
                            Decreto Supremo N° 3231 de 28 de junio de 2017, Artículo 17, Parágrafo I y el Reglamento del
                            Beneficio del Complemento Económico.
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center p-5 intext"></td>
                        <td class="text-justify p-5">
                            En caso de no informar oportunamente que percibo una prestación por @if($eco_com->isOldAge())
                            vejez o solidaria de vejez y simultáneamente una prestación por invalidez (concurrencia), @else
                            {{$eco_com->isWidowhood() ? 'Viudedad':'Orfandad Absoluta'}} derivada
                            de vejez o solidaria de vejez y simultáneamente una prestación por Riesgo Común y/o Profesional
                            e Invalidez Común y/o Profesional o Muerte, @endif me comprometo y acepto a proceder con la devolución
                            de posibles pagos en defecto determinados a través de la Contrastación de información proporcionada
                            por la Autoridad competente.
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center p-5 intext"></td>
                        <td class="text-justify p-5">
                            Estoy de acuerdo en proceder con la devolución de montos {{ $eco_com->isOldAge() ? 'cobrados
                            indebidamente o subsanar cualquier observación' : 'percibidos indebidamente' }} en caso de producirse
                            alguna inconsistencia a causa del contenido de la documentación presentada, información proporcionada
                            por entidades externas, error del sistema u otros que se presenten @if($eco_com->eco_com_modality->procedure_modality_id != 26) , conforme prevé el Art. 28° del reglamento del Complemento Económico @endif .
                        </td>
                    </tr>
                    @if($eco_com->eco_com_modality->procedure_modality_id != 24)
                    <tr>
                        <td class="text-center p-5 intext"></td>
                        <td class="text-justify p-5">
                            De presentarse una tercera persona que acredite igual o mayor derecho para acceder al Beneficio del Complemento Económico
                            por mi causahabiente {{ $eco_com->isWidowhood() ? ', estoy de acuerdo
                            en que': 'ya que se encuentra debidamente regulado por ley, ' }} la Mutual de Servicios al Policía
                            no se hace responsable por la suspensión del mencionado Beneficio y estoy de acuerdo a realizar
                            devolución de montos cobrados.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
            <p class="justify">En mérito de los datos registrados en forma precedente, el interesado aprueba y ratifica su tenor de manera íntegra, quien en señal de conformidad en forma expresa y voluntaria firma el presente documento en la ciudad de {{ $user->city->name ?? 'La Paz' }}, {{ now() }}.</p>
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
            <p class="m-t-40 font-bold text-xxs">Nota.- El presente documento tiene carácter de DECLARACIÓN JURADA, por lo que en caso de evidenciarse la falsedad
                de este, se procederá con las acciones legales pertinentes.</p>
        </div>
    </div>
</div>
@endsection