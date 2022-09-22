<div class="block">
    <table class="table-info w-100 m-b-10">
        <thead class="bg-grey-darker">
            <tr class="font-medium text-white text-sm uppercase">
                <td colspan='5' class="px-15 text-center">
                    APORTES Y PERIODOS CONSIDERADOS
                </td>
            </tr>
        </thead>
        <tbody class="table-striped">
            <tr>
                <td class="w-50"></td>
                <td class="w-25 text-center font-bold px-10 py-3 uppercase" colspan="1">inicio</td>
                <td class="w-25 text-center font-bold px-10 py-3 uppercase" colspan="1">fin</td>
            </tr>
            @if ($type == 'availability')
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 font-bold uppercase" >Disponibilidad general </td>
                        <td colspan="2">
                            <table class="no-border" style="border:none">
                                <tr class="no-border" style="border:none">
                                    <td class="text-center uppercase font-bold px-5 py-3" style="border:none"> {{ $ret_fun->affiliate->getDateEntryAvailability()!='-'? Util::formatMonthYear($ret_fun->affiliate->getDateEntryAvailability()): '-' }}  </td>
                                    <td class="text-center uppercase font-bold px-5 py-3" style="border:none"> {{ $ret_fun->affiliate->getDateEntryAvailability()!='-'? Util::formatMonthYear($ret_fun->affiliate->getDateFinishAvailability()): '-' }}  </td>
                                </tr>
                            </table>
                        </td>
                </tr>
            @endif
            @foreach ($contributions['contribution_types'] as $c)
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase" >{{ $c['name'] }}</td>
                    <td colspan="2">
                        <table class="no-border" style="border:none">
                            @foreach ($c['dates'] as $d)
                            <tr class="no-border" style="border:none">
                                <td class="text-center uppercase font-bold px-5 py-3" style="border:none"> {{ Util::formatMonthYear($d->start) ?? 'error' }} </td>
                                <td class="text-center uppercase font-bold px-5 py-3" style="border:none"> {{ Util::formatMonthYear($d->end) ?? 'error' }} </td>
                            </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="block">
    <table class="table-info w-100 m-b-10">
        <thead class="bg-grey-darker">
            <tr class="font-medium text-white text-sm uppercase">
                <td class="px-15 text-center">Tipo de contribución</td>
                <td class="px-15 text-center">Años</td>
                <td class="px-15 text-center">Meses</td>
            </tr>
        </thead>
        <tbody class="table-striped">
            @if ($type == 'availability')
             <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase font-bold">Disponibilidad general</td>
                <td class="text-center px-10 py-3 uppercase font-bold"> {{ $ret_fun->affiliate->getDateIntervalAvailability()['years'] ?? 'error' }}  </td>
                <td class="text-center px-10 py-3 uppercase font-bold">  {{ $ret_fun->affiliate->getDateIntervalAvailability()['months'] ?? 'error' }}  </td>
            </tr>
            @endif
            @foreach ($contributions['contribution_types'] as $c)
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">{{ $c['name'] }}</td>
                <td class="text-center px-10 py-3 uppercase">{{ $c['years'] }}</td>
                <td class="text-center px-10 py-3 uppercase">{{ $c['months'] }}</td>
            </tr>
            @endforeach
            @if ($type != 'availability')
                <tr>
                    <td class="text-left px-10 py-3 uppercase font-bold">Total de cotizaciones para Calificación</td>
                    <td class="text-center"><strong>{{ $contributions['years'] }}</strong></td>
                    <td class="text-center"><strong>{{ $contributions['months'] }}</strong></td>
                </tr>
            @endif
        </tbody>
    </table>
</div>