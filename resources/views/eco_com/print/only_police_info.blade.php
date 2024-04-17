<table class="table-info w-100 m-b-5">
    <thead class="bg-grey-darker">
        <tr class="font-bold text-white text-sm">
            <td class="px-15 text-center uppercase">
                NUP
            </td>
            <td class="px-15 text-center uppercase">
                GRADO
            </td>
            <td class="px-15 text-center uppercase">
                categoría
            </td>
            <td class="px-15 text-center uppercase">
                ente gestor
            </td>
        </tr>
    </thead>
    <tbody>
        <tr class="">
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->id }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->degree->name ?? null }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->category->name ?? null }}</td>
            <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->pension_entity->name ?? null }}</td>
        </tr>
    </tbody>
</table>