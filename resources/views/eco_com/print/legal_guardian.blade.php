<table class="table-info w-100 m-b-5">
    <thead class="bg-grey-darker">
        <tr class="font-medium text-white text-sm">
            <td class="px-20 py text-center">
                C.I.
            </td>
            <td class="px-15 py text-center ">
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
            <td class="px-15 py text-center">
                APELLIDO CASADA
            </td>
        </tr>
    </thead>
    <tbody>
        <tr class="text-sm">
            <td class="text-center font-bold">{{ $eco_com_legal_guardian->ciWithExt() }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $eco_com_legal_guardian->first_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $eco_com_legal_guardian->second_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $eco_com_legal_guardian->last_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $eco_com_legal_guardian->mothers_last_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $eco_com_legal_guardian->surname_husband }}</td>
        </tr>
    </tbody>
</table>
<table class="table-info w-100 m-b-5">
    <thead class="bg-grey-darker">
        <tr class="font-medium text-white text-sm">
            <td class="px-20 py text-center">
                TIPO DE APODERADO
            </td>
            <td class="px-15 py text-center">
                NRO DE PODER
            </td>
            <td class="px-15 py text-center">
                NOTARIO DE FE PUBLICA
            </td>
            <td class="px-15 py text-center">
                NOTARIO
            </td>
            <td class="px-15 py text-center">
                FECHA DE PODER
            </td>
        </tr>
    </thead>
    <tbody>
        <tr class="text-sm">
            <td class="text-center uppercase font-bold px-5 py-3">{{ $eco_com_legal_guardian->eco_com_legal_guardian_type->name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $eco_com_legal_guardian->number_authority }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $eco_com_legal_guardian->notary_of_public_faith }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $eco_com_legal_guardian->notary }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $eco_com_legal_guardian->date_authority }}</td>
        </tr>
    </tbody>
</table>