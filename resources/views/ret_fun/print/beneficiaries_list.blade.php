<div class="block">
    <table class="table-info w-100 m-b-10">
        <thead class="bg-grey-darker">
            <tr class="font-medium text-white text-sm uppercase">
                <td colspan='2' class="px-15 text-center">
                    DATOS DE LOS DERECHOHABIENTES
                </td>
            </tr>
        </thead>
        <tbody class="table-striped">
            @foreach ($beneficiaries as $beneficiary)
            <tr class="text-sm">
                <td class="w-40 text-left px-10 py-3 uppercase">nombres y apellidos</td>
                <td class="text-left uppercase font-bold px-5 py-3"> {{ $beneficiary->fullName() }} </td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">cédula de identidad</td>
                <td class="text-left uppercase font-bold px-5 py-3">{!! $beneficiary->identity_card !!}</td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">fecha de Nacimiento</td>
                <td class="text-left uppercase font-bold px-5 py-3">{{ $beneficiary->getBirthDate() }}</td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase" style="border-bottom:2px solid #22292f">parentesco con el titular</td>
                <td class="text-left uppercase font-bold px-5 py-3" style="border-bottom:2px solid #22292f">{{ $beneficiary->kinship->name ?? 'error' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>