@extends('print_global.print')
@section('content')
<div>
    <div class="page-break">
        <div class="block">
            <div class="text-right">
            </div>
            <div class="w-100" >
                <div class="block">
                    <div class="text-center text-xl font-bold underline uppercase">
                        CERTIFICACIÓN DE REVISIÓN
                    </div>
                </div>
                @php $i = 1 @endphp
            </div>
            <br>
            {{-- <div style="border-top: 1px solid #22292f;"></div> --}}
            <div class="block">
                <div class="inline-block align-top w-100">
                    <table class="table-info w-100 m-b-10">
                        <thead class="bg-grey-darker">
                            <tr class="font-medium text-white text-sm uppercase">
                                <td colspan='2' class="px-15 text-left">
                                    {{ $i++ }}. DATOS DEL TITULAR
                                </td>
                            </tr>
                        </thead>
                        <tbody class="table-striped">
                            <tr class="text-sm">
                                <td class="w-40 text-left px-10 py-3 uppercase">nombres y apellidos</td>
                                <td class="text-left uppercase font-bold px-5 py-3"> {{ $affiliate->fullName() }} </td>
                            </tr>
                            <tr class="text-sm">
                                <td class="text-left px-10 py-3 uppercase">cédula de identidad</td>
                                <td class="text-left uppercase font-bold px-5 py-3">{!! $affiliate->identity_card !!} </td>
                            </tr>
                            <tr class="text-sm">
                                <td class="text-left px-10 py-3 uppercase">fecha de Nacimiento</td>
                                <td class="text-left uppercase font-bold px-5 py-3">{{ $affiliate->getBirthDate() }}</td>
                            </tr>
                            <tr class="text-sm">
                                <td class="text-left px-10 py-3 uppercase">Matricula</td>
                                <td class="text-left uppercase font-bold px-5 py-3">{{ $affiliate->registration }}</td>
                            </tr>
                            <tr class="text-sm">
                                <td class="text-left px-10 py-3 uppercase">Estado Civil</td>
                                <td class="text-left uppercase font-bold px-5 py-3">{{ $affiliate->getCivilStatus() }}</td>
                            </tr>
                            <tr class="text-sm">
                                <td class="text-left px-10 py-3 uppercase">Edad</td>
                                <td class="text-left uppercase font-bold px-5 py-3">{{ $affiliate->calcAge(true, true) }}</td>
                            </tr>
                            <tr class="text-sm">
                                <td class="text-left px-10 py-3 uppercase">Dirección Domicilio</td>
                                <td class="text-left uppercase font-bold px-5 py-3">
                                    {{ $affiliate->address()->first() ? $affiliate->address()->first()->city->name.',' ?? '' : '' }}
                                    {{ $affiliate->address()->first() ? 'Zona. '.$affiliate->address()->first()->zone.',' : '' }}
                                    {{ $affiliate->address()->first() ? 'Av. Calle '.$affiliate->address()->first()->street.',' : '' }}
                                    {{ $affiliate->address()->first() ? '#'.$affiliate->address()->first()->number_address.'.' : '' }}
                                </td>
                            </tr>
                            @if ($affiliate->date_death)
                                <tr class="text-sm">
                                    <td class="text-left px-10 py-3 uppercase">FECHA DE FALLECIMIENTO</td>
                                    <td class="text-left uppercase font-bold px-5 py-3">{{ $affiliate->getDateDeath() }}</td>
                                </tr>
                                <tr class="text-sm">
                                    <td class="text-left px-10 py-3 uppercase">CAUSA DE FALLECIMIENTO</td>
                                    <td class="text-left uppercase font-bold px-5 py-3">{{ $affiliate->reason_death }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @if($quota_aid->procedure_modality_id == 14 || $quota_aid->procedure_modality_id == 15)
                <br>
                <div class="inline-block align-top w-100">
                        <table class="table-info w-100 m-b-10">
                            <thead class="bg-grey-darker">
                                <tr class="font-medium text-white text-sm uppercase">
                                    <td colspan='2' class="px-15 text-left">
                                        {{ $i++ }}. DATOS DEL CAUSANTE
                                    </td>
                                </tr>
                            </thead>
                            <tbody class="table-striped">
                                <tr class="text-sm">
                                    <td class="w-40 text-left px-10 py-3 uppercase">nombres y apellidos</td>
                                    <td class="text-left uppercase font-bold px-5 py-3"> {{ Util::fullName($spouse) }} </td>
                                </tr>
                                <tr class="text-sm">
                                    <td class="text-left px-10 py-3 uppercase">cédula de identidad</td>
                                    <td class="text-left uppercase font-bold px-5 py-3">{!! $spouse->identity_card !!} </td>
                                </tr>
                                <tr class="text-sm">
                                    <td class="text-left px-10 py-3 uppercase">fecha de Nacimiento</td>
                                    <td class="text-left uppercase font-bold px-5 py-3">{{ $spouse->getBirthDate() }}</td>
                                </tr>
                                <tr class="text-sm">
                                    <td class="text-left px-10 py-3 uppercase">Matricula</td>
                                    <td class="text-left uppercase font-bold px-5 py-3">{{ $spouse->registration }}</td>
                                </tr>
                                <tr class="text-sm">
                                    <td class="text-left px-10 py-3 uppercase">Estado Civil</td>
                                    <td class="text-left uppercase font-bold px-5 py-3">{{ $spouse->getCivilStatus() }}</td>
                                </tr>
                                <tr class="text-sm">
                                    <td class="text-left px-10 py-3 uppercase">Edad</td>
                                    <td class="text-left uppercase font-bold px-5 py-3">{{ Util::calculateAgeYears($spouse->birth_date, $spouse->date_death) }}</td>
                                </tr>
                                {{-- <tr class="text-sm">
                                    <td class="text-left px-10 py-3 uppercase">Dirección Domicilio</td>
                                    <td class="text-left uppercase font-bold px-5 py-3">
                                        {{ $spouse->address()->first() ? $spouse->address()->first()->city->name.',' ?? '' : '' }}
                                        {{ $spouse->address()->first() ? 'Zona. '.$spouse->address()->first()->zone.',' : '' }}
                                        {{ $spouse->address()->first() ? 'Av. Calle '.$spouse->address()->first()->street.',' : '' }}
                                        {{ $spouse->address()->first() ? '#'.$spouse->address()->first()->number_address.'.' : '' }}
                                    </td>
                                </tr> --}}
                                @if ($spouse->date_death)
                                    <tr class="text-sm">
                                        <td class="text-left px-10 py-3 uppercase">FECHA DE FALLECIMIENTO</td>
                                        <td class="text-left uppercase font-bold px-5 py-3">{{ $spouse->getDateDeath() }}</td>
                                    </tr>
                                    <tr class="text-sm">
                                        <td class="text-left px-10 py-3 uppercase">CAUSA DE FALLECIMIENTO</td>
                                        <td class="text-left uppercase font-bold px-5 py-3">{{ $spouse->reason_death }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @endif
                    <br>
                <div class="inline-block align-top w-100">
                    <table class="table-info w-100 m-b-10">
                        <thead class="bg-grey-darker">
                            <tr class="font-medium text-white text-sm uppercase">
                                <td colspan='2' class="px-15 text-left">
                                        {{ $i++ }}. DATOS INSTITUCIONALES
                                </td>
                            </tr>
                        </thead>
                        <tbody class="table-striped">
                            <tr class="text-sm">
                                <td class="w-40 text-left px-10 py-3 uppercase">grado</td>
                                <td class="text-left uppercase font-bold px-5 py-3"> {{ $affiliate->degree->name }} </td>
                            </tr>
                            <tr class="text-sm">
                                <td class="text-left px-10 py-3 uppercase">fecha de alta - policia</td>
                                <td class="text-left uppercase font-bold px-5 py-3">{{ $affiliate->getDateEntry() }}</td>
                            </tr>
                            <tr class="text-sm">
                                <td class="text-left px-10 py-3 uppercase">fecha de ingreso a disponibilidad</td>
                                <td class="text-left uppercase font-bold px-5 py-3">{{$affiliate->getDateEntryAvailability()!='-'?(Util::getDateFormat($affiliate->getDateEntryAvailability())):$affiliate->getDateEntryAvailability() }}</td>
                            </tr>
                            <tr class="text-sm">
                                <td class="text-left px-10 py-3 uppercase">fecha de baja</td>
                                <td class="text-left uppercase font-bold px-5 py-3">{!! $affiliate->getDateDerelict() !!}</td>
                            </tr>
                            <tr class="text-sm">
                                <td class="text-left px-10 py-3 uppercase">motivo</td>
                                <td class="text-left uppercase font-bold px-5 py-3">{{ $quota_aid->procedure_modality->name }}</td>
                            </tr>
                            <tr class="text-sm">
                                <td class="text-left px-10 py-3 uppercase">regional de registro</td>
                                <td class="text-left uppercase font-bold px-5 py-3">{{ $quota_aid->city_start->name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="inline-block align-top w-100">
                        <table class="table-info w-100 m-b-10">
                            <thead class="bg-grey-darker">
                                <tr class="font-medium text-white text-sm uppercase">
                                    <td colspan='2' class="px-15 text-left">
                                        {{ $i++ }}. PROCEDIMIENTOS REALIZADOS CORRECTAMENTE
                                    </td>
                                </tr>
                            </thead>
                            <tbody class="table-striped">
                                @foreach($documents as $document)
                                    <tr class="text-sm">
                                        <td class="text-left px-10 py-3 uppercase">{{ $document }}</td>
                                        <td class="text-center">
                                            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAADhSURBVEhL7ZRJCsJAFETbG3kC1yIuRBRBHBEP4DyPiIiI4G0Vx6okH0SIJvqDmzx4pLvorr8IiQn5JzEYsZf61OENHqydMjXI8jtsMNCkCqW8xUCTCrxAlncYaFKEUt5joEkeSvmAgSY5KOUjBm5EnacfslDKJwzcWEAeLFg7b2TgGbJ8xuAdU8iDHMKX9Yk0PEHemTPwwhjKkBIDF1JQypcM/DCEMqTM4IUklPIVg2/oQxnCr1JIQClfM/iFLnweEodSvoEqtCELr/DorLdQlSZkMd0xCAL+bvf2MiQQjHkAzVw/sI3mdmoAAAAASUVORK5CYII=">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                    </table>
                </div>
                @include('ret_fun.print.signature_footer',['user'=>$user])
            </div>
            <footer>
                @yield('footer')
            </footer>
        </div>
        @endsection