<div class="block">
    <table class="table-info w-100 m-b-10">
        <thead class="bg-grey-darker">
            <tr class="font-medium text-white text-sm uppercase">
                <td colspan='2' class="px-15 text-center">
                    DATOS DEL SOLICITANTE
                </td>
            </tr>
        </thead>
        <tbody class="table-striped">
            <tr class="text-sm">
                <td class="w-40 text-left px-10 py-3 uppercase">nombres y apellidos</td>
                <td class="text-left uppercase font-bold px-5 py-3"> {{ $applicant->fullName() }} </td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">cédula de identidad</td>
                <td class="text-left uppercase font-bold px-5 py-3">{!! $applicant->identity_card !!}</td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">parentesco con el titular</td>
                <td class="text-left uppercase font-bold px-5 py-3">{{ $applicant->kinship->name ?? 'error' }}</td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">dirección de domicilio</td>
                <td class="text-left uppercase font-bold px-5 py-3"> {{ $applicant->getAddress() }} </td>
            </tr>
            @if ($applicant->phone_number)
                <tr class="text-sm">
                    {{-- TODO
                        limite maximo de telefonos 4 por si acaso
                        --}}
                    <td class="text-left px-10 py-3 uppercase">Teléfono</td>
                    <td class="text-left uppercase font-bold px-5 py-3">{{ $applicant->phone_number }}</td>
                </tr>
            @endif
            @if ($applicant->cell_phone_number)
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">celular</td>
                    <td class="text-left uppercase font-bold px-5 py-3">{{ $applicant->cell_phone_number }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>