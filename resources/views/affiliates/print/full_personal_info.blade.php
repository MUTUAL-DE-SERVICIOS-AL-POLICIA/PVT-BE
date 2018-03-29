


<div class="" >
    <div class="inline-block align-top w-60">
        <table class="table-info w-100 m-b-10">
            <thead class="bg-grey-darker">
                <tr class="font-medium text-white text-sm uppercase">
                    <td colspan='2' class="px-15 text-center">
                        DATOS DEL TITULAR
                    </td>
                </tr>
            </thead>
            <tbody class="table-striped">
                <tr class="text-sm">
                    <td class="w-40 text-left px-10 py-3 uppercase">nombres y apellidos</td>
                    <td class="text-center uppercase font-bold px-5 py-3"> {{ $affiliate->fullName() }} </td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">Carnet de identidad</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{!! $affiliate->identity_card !!} {{$affiliate->city_identity_card->name ?? ''}}</td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">fecha de Nacimiento</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->birth_date }}</td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">Edad</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->calcAge(true, true) }}</td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">Estado Civil</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->getCivilStatus() }}</td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">Matricula</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->registration }}</td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">CUA/NUA</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->nua }}</td>
                </tr>
                @if ($affiliate->date_death)
                    <tr class="text-sm">
                        <td class="text-left px-10 py-3 uppercase">FECHA DE FALLECIMIENTO</td>
                        <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->date_death }}</td>
                    </tr>
                    <tr class="text-sm">
                        <td class="text-left px-10 py-3 uppercase">CAUSA DE FALLECIMIENTO</td>
                        <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->reason_death }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="inline-block align-top w-39">
        <table class="table-info w-100 m-b-10">
            <thead class="bg-grey-darker">
                <tr class="font-medium text-white text-sm uppercase">
                    <td class="px-15 text-center">
                        DATOS DOMICILIO
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr class="text-sm bg-grey-lightest">
                    <td class="w-33 text-left px-10 py-3 text-center uppercase">departamento</td>
                </tr>
                <tr class="text-sm">
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->registration }}</td>
                </tr>
                <tr class="text-sm bg-grey-lightest">
                    <td class="text-left px-10 py-3 text-center uppercase">Zona</td>
                </tr>
                <tr class="text-sm">
                    <td class="text-center uppercase font-bold px-5 py-3">{!! $affiliate->identity_card !!} {{$affiliate->city_identity_card->name ?? ''}}</td>
                </tr>
                <tr class="text-sm bg-grey-lightest">
                    <td class="text-left px-10 py-3 text-center uppercase">av. calle</td>
                </tr>
                <tr class="text-sm">
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->birth_date }}</td>
                </tr>
                <tr class="text-sm bg-grey-lightest">
                    <td class="text-left px-10 py-3 text-center uppercase">numero</td>
                </tr>
                <tr class="text-sm">
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->birth_date }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="block">
        <table class="table-info w-100 m-b-10">
            <thead class="bg-grey-darker">
                <tr class="font-medium text-white text-sm uppercase">
                    <td colspan='2' class="px-15 text-center">
                        DATOS INSTITUCIONALES
                    </td>
                </tr>
            </thead>
            <tbody class="table-striped">
                <tr class="text-sm">
                    <td class="w-40 text-left px-10 py-3 uppercase">grado</td>
                    <td class="text-center uppercase font-bold px-5 py-3"> {{ $affiliate->degree->shortened ?? 'ERROR' }}  ({{ $affiliate->degree->name ?? 'ERROR' }}) </td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">categoria</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{!! $affiliate->category->name ?? 'ERROR' !!}</td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">fecha de alta - policia</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->getDateEntry() }}</td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">fecha de ingreso a disponibilidad</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->getDateEntryDisponibilidad() }}</td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">fecha de baja</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->getDateEntryDisponibilidad() }}</td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">motivo de baja</td>
                    <td class="text-center uppercase font-bold px-5 py-3">{{ $affiliate->getDateEntryDisponibilidad() }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
