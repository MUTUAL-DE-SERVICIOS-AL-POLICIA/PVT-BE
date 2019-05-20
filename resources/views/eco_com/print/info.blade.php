<table class="table-info w-100 m-b-5">
    <thead class="bg-grey-darker">
        <tr class="font-medium text-white text-sm">
            <td class="px-15 text-center text-sm uppercase">
                TIPO DE TRÁMITE:
            </td>
            <td class="px-15 text-center text-sm uppercase">
                modalidad
            </td>
            <td class="px-15 text-center text-sm uppercase">
                regional
            </td>
            <td class="px-15 text-center text-sm uppercase">
                Fecha de recepcion
            </td>
            <td class="px-15 text-center text-sm uppercase">
                PERIODO
            </td>
        </tr>
    </thead>
    <tbody>
        <tr class="text-sm">
            <td class="text-center uppercase font-bold px-5 py-3">
                {{ $eco_com->eco_com_reception_type->name }}
            </td>
            <td class="text-center uppercase font-bold px-5 py-3">
                {{ $eco_com->eco_com_modality->procedure_modality->name }}
            </td>
            <td class="text-center uppercase font-bold px-5 py-3">
                {{ $eco_com->city->name }}
            </td>
            <td class="text-center uppercase font-bold px-5 py-3">
                {{ Util::getDateFormat($eco_com->reception_date) }}
            </td>
            <td class="text-center uppercase font-bold px-5 py-3">
                {{ $eco_com->eco_com_procedure->fullName() }}
            </td>
        </tr>
    </tbody>
</table>