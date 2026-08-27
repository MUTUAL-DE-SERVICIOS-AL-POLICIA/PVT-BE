<table class="table-info w-100">
    <thead class="bg-grey-darker">
        <tr class="font-medium text-white text-sm">
            <td class="px-15 py text-center">
                C.I.
            </td>
            <td class="px-15 py text-center">
                PRIMER NOMBRE
            </td>
            <td class="px-15 py text-center">
                SEGUNDO NOMBRE
            </td>
            <td class="px-15 py text-center">
                PRIMER APELLIDO
            </td>
            <td class="px-15 py text-center">
                SEGUNDO APELLIDO
            </td>
            @if ($affiliate->surname_husband)
                <td class="px-15 py text-center">
                    APELLIDO CASADO(A)
                </td>
            @endif
        </tr>
    </thead>
    <tbody>
        <tr class="text-sm">
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->identity_card }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->first_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->second_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->last_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->mothers_last_name }}</td>
            @if ($affiliate->surname_husband)
                <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->surname_husband }}</td>
            @endif
        </tr>
        @if($affiliate->date_death != null)
            <tr>
                <td class="px-15 py text-center" colspan="3">
                    FECHA DE FALLECIMIENTO
                </td>
                <td class="text-center uppercase font-bold px-5 py-3" colspan="3">{{ $affiliate->date_death }}</td>
            </tr>
        @endif
    </tbody>
</table>