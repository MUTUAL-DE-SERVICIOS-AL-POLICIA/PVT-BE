
{{--  <div class="inline">
    <span class="rounded-tl bg-grey-darker border px-15 py-4">
        PRIMER NOMBRE
    </span>
    <span class="bg-grey-darker border px-15 py-4">
        SEGUNDO NOMBRE
    </span>
    <span class="bg-grey-darker border px-15 py-4">
        PRIMER APELLIDO
    </span>
    <span class="bg-grey-darker border px-15 py-4">
        SEGUNDO APELLIDO
    </span>
    <span class="rounded-tr bg-grey-darker border px-15 py-4">
        APELLIDO CASADA
    </span>
</div>  --}}
<table class="w-100 table-collapse">
    <tr>
        <td class="rounded-tl bg-grey-darker px-15 py-4">
            PRIMER NOMBRE
        </td>
        <td class="bg-grey-darker px-15 py-4">
            SEGUNDO NOMBRE
        </td>
        <td class="bg-grey-darker px-15 py-4">
            PRIMER APELLIDO
        </td>
        <td class="bg-grey-darker px-15 py-4">
            SEGUNDO APELLIDO
        </td>
        <td class="rounded-tr bg-grey-darker px-15 py-4">
            APELLIDO CASADA
        </td>
    </tr>
    <tr>
        <td class="border" colspan="1"><span class="font-bold">NOMBRE:</span></td>
        <td class="border" colspan="5" nowrap>{!! $applicant->last_name." ".$applicant->first_name !!}</td>
    </tr>
    <tr>
        <td class="border"><span class="font-bold">C.I.:</span></td>
        <td class="border">{!! $applicant->identity_card !!} {{$applicant->city_identity_card->first_shortened ?? ''}}</td>
        <td class="border"><span class="font-bold">DOMICILIO:</span></td>
        <td class="border"> {!! $applicant->cell_phone_number !!}</td>
    </tr>
    <tr>
        <td class="border"><span class="font-bold">TELÉFONO:</span></td>
        <td class="border">{!! $applicant->phone_number !!}<br/></td>
        <td class="border"><span class="font-bold">CELULAR:</span></td>
        <td class="border">{!! $applicant->cell_phone_number !!}</td>
    </tr>
</table>