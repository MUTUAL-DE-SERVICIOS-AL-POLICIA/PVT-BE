<div>
    <table class="table-info w-100 m-b-10">
        <thead class="bg-grey-darker">
            <tr class="font-medium text-white text-sm uppercase">
                <td colspan='4' class="px-15 text-center">
                    @if ($retirement_fund->procedure_modality->id == 1 || $retirement_fund->procedure_modality->id == 4 || $retirement_fund->procedure_modality->id == 63)
                        CALCULO DE CUOTAS PARTE PARA DERECHOHABIENTES
                    @else
                        CALCULO DEL TOTAL
                    @endif
                </td>
            </tr>
        </thead>
        <tbody class="table-striped">
            <tr>
                <td class="w-40 text-center font-bold px-10 py-3 uppercase">
                nombre del {{($retirement_fund->procedure_modality->id == 1 || $retirement_fund->procedure_modality->id == 4 || $retirement_fund->procedure_modality->id == 63) ? 'derechohabiente' : 'titular' }}
                </td>
                <td class="w-20 text-center font-bold px-10 py-3 uppercase">% de asignacion</td>
                <td class="w-20 text-center font-bold px-10 py-3 uppercase">monto</td>
                <td class="w-20 text-center font-bold px-10 py-3 uppercase">parentesco</td>
            </tr>
            @foreach ($beneficiaries as $beneficiary)
            <tr class="text-sm">
                <td class="text-left uppercase px-5 py-3"> {{ $beneficiary->fullName() }} </td>
                <td class="text-center uppercase px-5 py-3"><div class="w-70 text-right">{!! $beneficiary->percentage !!}</div> </td>
                @if($type == 'availability')
                    <td class="text-center uppercase font-bold px-5 py-3">{!! Util::formatMoney($beneficiary->amount_availability) !!}</td>
                @elseif($type == 'refund')
                    <td class="text-center uppercase font-bold px-5 py-3">{!! Util::formatMoney($beneficiary->ret_fun_refund_amounts) !!}</td>
                @else
                    @if($type == 'total')
                        <td class="text-center uppercase font-bold px-5 py-3">{!! Util::formatMoney($beneficiary->amount_total) !!}</td>
                    @else
                    {{-- normal --}}
                        <td class="text-center uppercase font-bold px-5 py-3">{!! Util::formatMoney($beneficiary->amount_ret_fun) !!}</td>
                    @endif
                @endif
                <td class="text-center uppercase px-5 py-3">{{ $beneficiary->kinship->name ?? 'error' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>