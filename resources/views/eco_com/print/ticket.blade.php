<div style="margin:0; padding:0; width:100%; display:block; margin-top:50px">
    <div class="main-left">
        <table>
            <tr>
                <td colspan="2" class="no-border"></td>
            </tr>
            <tr class="tableh1">
                <th colspan="2" style="width: 50%;border: 0px;padding-top:10px" class="text-xxs">
                    <b>MUTUAL DE SERVICIOS AL POLICÍA<br>
                        {!! $direction !!}<br>{!! $unit !!}
                        @yield('title')
                        <br> <em class="uppercase">"{{ $eco_com->eco_com_procedure->fullName() }}
                            @if ($eco_com->old_eco_com && $eco_com->total_repay > 0)
                            (REINTEGRO)
                            @endif
                            "</em>
                    </b>
                </th>
            </tr>
            <tr>
                <td colspan="2" class="no-border">
                    <em class="capitalize">{{ $user->city->name ?? 'La Paz' }}, {!! $date !!} </em> </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border">
                    <strong><em>REGIONAL:</em></strong> {{ $eco_com->city->name ?? '' }} <br>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border">
                    <strong><em>GRADO:</em></strong> {{ $eco_com->degree->shortened ?? '' }} <br>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border">
                    <strong><em>CATEGORÍA:</em></strong> {{ $eco_com->category->name ?? '' }} <br>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border">
                    <strong><em>NOMBRES Y APELLIDOS:</em></strong><br>
                    {{ $eco_com->eco_com_beneficiary->fullName() ?? '' }} <br>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border">
                    <strong><em>C.I.:</em></strong> {!!
                    $eco_com->eco_com_beneficiary->identity_card !!}
                    {{$eco_com->eco_com_beneficiary->city_identity_card ? $eco_com->eco_com_beneficiary->city_identity_card->first_shortened.'.' : ''}}
                    <br>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border">
                    <strong><em>TRÁMITE Nº:</em></strong> {!! $eco_com->code ?? ''!!} <br>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border">
                    <em class="text-xxs">{{ Util::convertir($eco_com->getOnlyTotalEcoCom())   }} BOLIVIANOS.</em>
                </td>
            </tr>
            <tr>
                <td class="no-border uppercase">
                    {{ $eco_com->eco_com_modality->procedure_modality->name ?? '' }}
                </td>
                <td class="no-border text-center text-base">
                    <span class="code border-radius">
                        Bs. {{ Util::formatMoney($eco_com->getOnlyTotalEcoCom() ) }}
                    </span>
                </td>
            </tr>
        </table>

    </div>
    <div class="main-right">
        <table>
            <tr>
                <td colspan="4" class="no-border"></td>
            </tr>
            <tr class="tableh1">
                <th colspan="4" style="width: 50%;border: 0px;padding-top:10px" class="text-xxs">
                    <b>MUTUAL DE SERVICIOS AL POLICÍA<br>
                        {!! $direction !!}<br>{!! $unit !!}
                        @yield('title')
                        <br> <em>"{{ strtoupper($eco_com->eco_com_procedure->fullName() ?? '') }}
                            {{-- @if ($eco_com->old_eco_com && $eco_com->total_repay > 0)
                            (REINTEGRO)
                            @endif --}}
                            "</em>
                    </b>
                </th>

            </tr>
            <tr>
                <td class="no-border" colspan="4">
                    <em>{{ ucwords(strtolower($user->city->name ?? '')) ?? 'La Paz' }}, {!! $date !!}</em>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="no-border">
                    <strong><em>REGIONAL:</em></strong> {{ $eco_com->city->name ?? '' }} <br>
                    <strong><em>C.I.:</em></strong> {!!
                    $eco_com->eco_com_beneficiary->identity_card !!}
                    {{$eco_com->eco_com_beneficiary->city_identity_card ? $eco_com->eco_com_beneficiary->city_identity_card->first_shortened.'.' : ''}}
                    <br>
                    <strong><em>TRÁMITE Nº:</em></strong> {!! $eco_com->code ?? ''!!} <br>
                </td>
                <td colspan="2" class="text-center no-border">
                    <span>
                        {{ strtoupper($eco_com->eco_com_modality->procedure_modality->name) ?? '' }}
                    </span>
                    <br>
                    <br>
                    <strong class="code border-radius size-16 ">Bs.
                        {{ Util::formatMoney($eco_com->getOnlyTotalEcoCom()) }}</strong>
                </td>
            <tr>
                <td colspan="4" class="no-border">
                    <strong><em>PÁGUESE A LA ORDEN DE:</em></strong><br>
                    <span class="margin-l-10">{{ $eco_com->eco_com_beneficiary->fullName() ?? '' }}
                    </span><br>
                </td>

            </tr>
            <tr>
                <td colspan="4" class="no-border">
                    <strong>LA SUMA DE:</strong><br>
                    <em class="size-10">{{ Util::convertir($eco_com->getOnlyTotalEcoCom())   }}BOLIVIANOS.</em>
                </td>
            </tr>
            <tr>
                <td class="width-30-por no-border text-center">
                    <div class="code border-radius">{{ $eco_com->degree->shortened ?? '' }} <br>
                        <em>GRADO</em></div>
                </td>
                <td class="width-20-por no-border text-center">
                    <div class="code border-radius">{{ $eco_com->category->name ?? '' }} <br>
                        <em>CATEGORÍA</em></div>
                </td>
                <td class="width-20-por no-border text-center">
                    <div class="code border-radius">6<br> <em>MESES</em></div>
                </td>
                <td class="width-30-por no-border text-center">
                    <div class="code border-radius">
                        <strong>{{ Util::formatMoney($eco_com->getOnlyTotalEcoCom()) ?? '' }}</strong>
                        <br> <em>LIQUIDO PAGABLE</em></div>
                </td>
            </tr>
            <tr>
                <td class="no-border"></td>
            </tr>
            <tr>
                <td class="no-border"></td>
            </tr>
            <tr>
                <td class="no-border"></td>
            </tr>
            <tr>
                <td class="no-border"></td>
            </tr>
        </table>
    </div>
</div>