
<div class="inline">
    <span class="rounded-tl bg-grey-darker border px-15 py-4">
        holas
    </span>
    <span class="bg-grey-darker border px-15 py-4">
        holas
    </span>
    <span class="rounded-tr bg-grey-darker border px-15 py-4">
        holas
    </span>
</div>
<table class="w-100 table-collapse border">
    <tr>
        <td colspan="6" class="border border-black border-solid text-center py-4 bg-grey-darker">
            <span class="font-bold uppercase">INFORMACIÓN DEL BENEFICIARIO</span>
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