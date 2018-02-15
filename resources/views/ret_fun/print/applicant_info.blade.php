
<table class="table-info w-100">
    <thead class="bg-grey-darker">
        <tr class="font-medium text-white text-sm">
            {{--  <td class="text-center">
                C.I.
            </td>  --}}
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
            {{--  <td class="text-center font-bold">{!! $applicant->identity_card !!} {{$applicant->city_identity_card->first_shortened ?? ''}}</td>  --}}
            <td class="text-center font-bold px-5 py-3">{{ $applicant->first_name }}</td>
            <td class="text-center font-bold px-5 py-3">{{ $applicant->second_name }} {{ $applicant->second_name }} {{ $applicant->second_name }}</td>
            <td class="text-center font-bold px-5 py-3">{{ $applicant->last_name }}</td>
            <td class="text-center font-bold px-5 py-3">{{ $applicant->mothers_last_name }}{{ $applicant->mothers_last_name }}{{ $applicant->mothers_last_name }}</td>
            <td class="text-center font-bold px-5 py-3">{{ $applicant->surname_husband }}</td>
        </tr>
    </tbody>

    </table>