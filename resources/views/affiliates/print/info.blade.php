<table class="table-info w-100 m-b-10">
    <thead class="bg-grey-darker">
        <tr class="font-medium text-white text-sm">
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
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->first_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->second_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->last_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->mothers_last_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->surname_husband }}</td>
        </tr>
    </tbody>
</table>
<table class="table-info w-100">
    <thead class="bg-grey-darker">
        <tr class="font-medium text-white text-sm uppercase">
            <td class="px-15 text-center">
                NUP
            </td>
            <td class="px-15 text-center">
                C.I
            </td>
            <td class="px-15 text-center">
                matrícula
            </td>
            <td class="px-15 text-center">
                GRADO
            </td>
            {{-- <td class="px-15 text-center">
                categoría
            </td> --}}
        </tr>
    </thead>
    <tbody>
        <tr class="text-sm">
            <td class="text-center uppercase font-bold px-5 py-3">{!! $affiliate->id !!} </td>
            <td class="text-center uppercase font-bold px-5 py-3">{!! $affiliate->identity_card !!} </td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->registration }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->degree->shortened }}</td>
            {{-- <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->category->name }}</td> --}}
        </tr>
    </tbody>
</table>