
<table class="table-info w-100">
    <thead class="bg-grey-darker">
        <tr class="font-medium text-white text-xxs">
            <td class="px-15 py text-center ">
                NUP
            </td>
            <td class="px-15 py text-center ">
                GRADO
            </td>
            <td class="px-15 py text-center ">
                PRIMER NOMBRE
            </td>
            <td class="px-15 py text-center">
                SEGUNDO NOMBRE
            </td>
            <td class="px-15 py text-center">
                APELLIDO PATERNO
            </td>
            <td class="px-15 py text-center">
                APELLIDO MATERNO
            </td>
            <td class="px-15 py text-center">
                APELLIDO DE CASADA
            </td>
            <td class="px-15 py text-center">
                C.I.
            </td>
        </tr>
    </thead>  
    <tbody> 
        <tr class="text-sm">
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->id ?? '' }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $degree->shortened ?? $affiliate->degree->shortened ?? '' }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->first_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->second_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->last_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->mothers_last_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->surname_husband }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->identity_card }}</td>
        </tr>
    </tbody>
</table>