<div class="">
    <div class="inline-block align-top w-100">
        <table class="table-info w-100 m-b-10">
            <thead class="bg-grey-darker">
                <tr class="font-medium text-white text-sm uppercase">
                    <td colspan='2' class="px-15 text-center">
                        DATOS DEL FALLECIDO
                    </td>
                </tr>
            </thead>
            <tbody class="table-striped">
                <tr class="text-sm">
                    <td class="w-40 text-left px-10 py-3 uppercase">nombres y apellidos</td>
                    <td class="text-left uppercase font-bold px-5 py-3"> {{ $person->fullName() }} </td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">cédula de identidad</td>
                    <td class="text-left uppercase font-bold px-5 py-3">{!! $person->identity_card !!} </td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">fecha de Nacimiento</td>
                    <td class="text-left uppercase font-bold px-5 py-3">{{ $person->getBirthDate() }}</td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">Estado Civil</td>
                    <td class="text-left uppercase font-bold px-5 py-3">{{ $person->getCivilStatus() }}</td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">Matricula</td>
                    <td class="text-left uppercase font-bold px-5 py-3">{{ $person->registration }}</td>
                </tr>
                @if ($person->date_death)
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">FECHA DE FALLECIMIENTO</td>
                    <td class="text-left uppercase font-bold px-5 py-3">{{ $person->getDateDeath() }}</td>
                </tr>
                <tr class="text-sm">
                    <td class="text-left px-10 py-3 uppercase">CAUSA DE FALLECIMIENTO</td>
                    <td class="text-left uppercase font-bold px-5 py-3">{{ $person->reason_death }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>