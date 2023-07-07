
<table class="table-info w-100 m-b-5">
    <thead class="bg-grey-darker">
        <tr class="font-medium text-white text-sm">
             <td class="text-center">
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
             <td class="text-center font-bold">{!! $spouse->identity_card !!} </td> 
            <td class="text-center uppercase font-bold px-5 py-3">{{ $spouse->first_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $spouse->second_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $spouse->last_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $spouse->mothers_last_name }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $spouse->surname_husband }}</td>            
        </tr>
    </tbody>

    </table>