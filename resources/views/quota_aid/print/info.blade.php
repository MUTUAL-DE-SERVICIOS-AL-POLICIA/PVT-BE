<table class="table-info w-100 m-b-5">
    <thead class="bg-grey-darker">
        <tr class="font-medium text-white text-sm">
            <td class="px-15 text-center text-sm uppercase">
                modalidad
            </td>
            <td class="px-15 text-center text-sm uppercase">
                regional
            </td>
            <td class="px-15 text-center text-sm uppercase">
                Fecha de recepcion
            </td>
        </tr>
    </thead>
    <tbody>
        <tr class="text-sm">
            <td class="text-center uppercase font-bold px-5 py-3">
                {{ $quota_aid->procedure_modality->name }}
            </td>
            <td class="text-center uppercase font-bold px-5 py-3">
                {{ $quota_aid->city_start->name }}
            </td>
            <td class="text-center uppercase font-bold px-5 py-3">
                {{ Util::getDateFormat($quota_aid->reception_date) }}
            </td>
        </tr>
    </tbody>
</table>