<div class="block">
    <table class="table-info w-100 m-b-10">
        <thead class="bg-grey-darker">
            <tr class="font-medium text-white text-sm uppercase">
                <td colspan='2' class="px-15 text-center">
                    APODERADO
                </td>
            </tr>
        </thead>
        <tbody class="table-striped">
            <tr class="text-sm">
                <td class="w-40 text-left px-10 py-3 uppercase">nombres y apellidos</td>
                <td class="text-left uppercase font-bold px-5 py-3"> {{ $legal_guardian->fullName() }} </td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">cédula de identidad</td>
                <td class="text-left uppercase font-bold px-5 py-3">{!! $legal_guardian->identity_card !!}</td>
            </tr>
            @if ($legal_guardian->phone_number)
            <tr class="text-sm">
                {{-- TODO limite maximo de telefonos 4 por si acaso --}}
                <td class="text-left px-10 py-3 uppercase">Teléfono</td>
                <td class="text-left uppercase font-bold px-5 py-3">{{ $legal_guardian->phone_number }}</td>
            </tr>
            @endif @if ($legal_guardian->cell_phone_number)
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">celular</td>
                <td class="text-left uppercase font-bold px-5 py-3">{{ $legal_guardian->cell_phone_number }}</td>
            </tr>
            @endif
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">Nro de Poder</td>
                <td class="text-left uppercase font-bold px-5 py-3">{!! $legal_guardian->number_authority !!}</td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">Notaria de Fé Pública Nro</td>
                <td class="text-left uppercase font-bold px-5 py-3">{!! $legal_guardian->notary_of_public_faith !!}</td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">Notario</td>
                <td class="text-left uppercase font-bold px-5 py-3">{!! $legal_guardian->notary !!}</td>
            </tr>
        </tbody>
    </table>
</div>