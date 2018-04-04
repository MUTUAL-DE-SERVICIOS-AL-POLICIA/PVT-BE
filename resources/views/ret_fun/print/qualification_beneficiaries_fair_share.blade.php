<div>
    <table class="table-info w-100 m-b-10">
        <thead class="bg-grey-darker">
            <tr class="font-medium text-white text-sm uppercase">
                <td colspan='4' class="px-15 text-center">
                    CALCULO DE CUOTAS PARTE PARA DERECHOHABIENTES - AFILIADO FALLECIDO
                </td>
            </tr>
        </thead>
        <tbody class="table-striped">
            <tr>
                <td class="w-40 text-center font-bold px-10 py-3 uppercase">nombre del derechohabiente</td>
                <td class="w-20 text-center font-bold px-10 py-3 uppercase">% de asignacion</td>
                <td class="w-20 text-center font-bold px-10 py-3 uppercase">monto</td>
                <td class="w-20 text-center font-bold px-10 py-3 uppercase">parentesco</td>
            </tr>
            @foreach ($beneficiaries as $beneficiary)
            <tr class="text-sm">
                <td class="text-left uppercase px-5 py-3"> {{ $beneficiary->fullName() }} </td>
                <td class="text-center uppercase px-5 py-3"><div class="w-70 text-right">{!! $beneficiary->percentage !!}</div> </td>
                <td class="text-center uppercase font-bold px-5 py-3">{!! $beneficiary->identity_card !!}</td>
                <td class="text-center uppercase px-5 py-3">{{ $beneficiary->kinship->name ?? 'error' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>