<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PLATAFORMA VIRTUAL - MUSERPOL {{ $title }}</title>
    <link rel="stylesheet" href="css/materialicons.css" media="all" />
    <link rel="stylesheet" href="{{ public_path('css/wkhtml.css') }}" media="all" />
</head>

<body class="no-border">

    <div class="page-break">
        <table class="w-100 " style="{{ isset($with_padding) ? 'padding-left:48px' : '' }}">
            <tr>
                <th class="w-25 text-left no-padding no-margins align-middle">
                    <div class="text-center">
                        <img src="{{ public_path('images/logo.jpg') }}" class="w-100">
                    </div>
                </th>
                <th class="w-75 text-right no-padding no-margins align-middle">
                    <span class="uppercase text-xs">
                        {{ $institution ?? 'MUTUAL DE SERVICIOS AL POLICÍA' }} <br>
                        {{ $direction ?? 'DIRECCIÓN DE BENEFICIOS ECONÓMICOS' }} <br>
                        {{ $unit ?? 'UNIDAD DE OTORGACIÓN DEL COMPLEMENTO ECONÓMICO' }} <br>
                    </span>
                </th>
            </tr>

            <tr>
                <td colspan="3" class="font-bold text-center text-2xl uppercase">
                    <br>{{ $title }}
                    @if (isset($subtitle))
                        <br>{{ $subtitle ?? '' }}
                    @endif
                    <br><br> <span class="font-bold text-center text-2xl underline uppercase"> {{ $text }}
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="font-bold text-right text-xl uppercase"><br>NUP: {{ $affiliate->id }}</td>
            </tr>
        </table>
    </div>

    @section('content')
    @endsection
    <div>
        <div style="min-height:900px; height:900px; max-height:900px;">

            <table class="table-info w-100 m-b-5">
                <thead class="bg-grey-darker">
                    <tr class="font-medium text-white text-sm">
                        <td class="text-left font-bold px-10 py-10" colspan = "2">I. DATOS PERSONALES DEL BENEFICIARIO
                            (TITULAR)
                            (VIUDA)
                            (HUÉRFANO ABSOLUTO)</td>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <tr>
                        <td class='text-left font-bold p-5 uppercase'>NOMBRES Y APELLIDOS</td>
                        <td class="text-left uppercase">{{ $eco_com_beneficiary->fullName() }}</td>
                    </tr>
                    <tr>
                        <td class='text-left font-bold p-5 uppercase'>NÚMERO DE CÉDULA DE IDENTIDAD</td>
                        <td class="text-left uppercase">{{ $eco_com_beneficiary->ciWithExt() }}</td>
                    </tr>
                    <tr>
                        <td class='text-left font-bold p-5 uppercase'>FECHA DE NACIMIENTO</td>
                        <td class="text-left uppercase">{{ $eco_com_beneficiary->birth_date }}</td>
                    </tr>
                </tbody>
            </table>
            <br>
            <table class="table-info w-100 m-b-5">
                <thead class="bg-grey-darker">
                    <tr class="font-medium text-white text-sm">
                        <td class="text-left font-bold px-10 py-10" colspan = "2">II. DATOS INSTITUCIONALES</td>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <tr>
                        <td class='text-left font-bold p-5 uppercase'>REGIONAL DE REGISTRO DEL TRÁMITE</td>
                        <td class="text-left uppercase">{{ $eco_com->city->name }}</td>
                    </tr>
                    <tr>
                        <td class='text-left font-bold p-5 uppercase'>NÚMERO DE TRÁMITE</td>
                        <td class="text-left uppercase">{{ $eco_com->code }}</td>
                    </tr>
                    <tr>
                        <td class='text-left font-bold p-5 uppercase'>GRADO</td>
                        <td class="text-left uppercase">{{ $affiliate->degree->name }}</td>
                    </tr>
                    <tr>
                        <td class='text-left font-bold p-5 uppercase'>AÑOS DE SERVICIO</td>
                        <td class="text-left uppercase">{{ $affiliate->service_years }} AÑOS Y
                            {{ $affiliate->service_months }} MESES </td>
                    </tr>
                    <tr>
                        <td class='text-left font-bold p-5 uppercase'>CATEGORÍA</td>
                        <td class="text-left uppercase">{{ $affiliate->category->name }}</td>
                    </tr>
                    <tr>
                        <td class='text-left font-bold p-5 uppercase'>TIPO DE TRÁMITE</td>
                        <td class="text-left uppercase">{{ $eco_com->eco_com_reception_type->name }}</td>
                    </tr>
                    <tr>
                        <td class='text-left font-bold p-5 uppercase'>TIPO DE PRESTACIÓN</td>
                        <td class="text-left uppercase">{{ $eco_com->eco_com_modality->procedure_modality->name }}</td>
                    </tr>
                    <tr>
                        <td class='text-left font-bold p-5 uppercase'>ESTADO</td>
                        <td class="text-left uppercase">{{ $affiliate->affiliate_state->name }}</td>
                    </tr>
                    <tr>
                        <td class='text-left font-bold p-5 uppercase'>FECHA DE DESVINCULACIÓN</td>
                        <td class="text-left uppercase">{{ $affiliate->date_derelict }}</td>
                    </tr>
                </tbody>
            </table>
            <br>
            @if (sizeof($eco_com_review_procedures) > 0)
                <table class="table-info w-100 m-b-5">
                    <thead class="bg-grey-darker">
                        <tr class="font-medium text-white text-sm">
                            <td class="text-left font-bold px-10 py-10" colspan = "2">III. PROCEDIMIENTOS DE REVISIÓN
                                REALIZADOS</td>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @foreach ($eco_com_review_procedures as $item)
                            <tr>
                                <td class="w-90 text-left font-bold p-5 uppercase">{{ $item->review_procedure->name }}
                                </td>
                                @if ($item->is_valid == true)
                                    <td class="w-10 text-center">
                                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAADhSURBVEhL7ZRJCsJAFETbG3kC1yIuRBRBHBEP4DyPiIiI4G0Vx6okH0SIJvqDmzx4pLvorr8IiQn5JzEYsZf61OENHqydMjXI8jtsMNCkCqW8xUCTCrxAlncYaFKEUt5joEkeSvmAgSY5KOUjBm5EnacfslDKJwzcWEAeLFg7b2TgGbJ8xuAdU8iDHMKX9Yk0PEHemTPwwhjKkBIDF1JQypcM/DCEMqTM4IUklPIVg2/oQxnCr1JIQClfM/iFLnweEodSvoEqtCELr/DorLdQlSZkMd0xCAL+bvf2MiQQjHkAzVw/sI3mdmoAAAAASUVORK5CYII=">
                                    </td>
                                @else
                                    <td class="w-10 text-center"> </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            <br>
            <div class="text-left uppercase">
                {{ $user->city->name ?? 'La Paz' }}, {{ Util::getTextDate() }}
            </div>
        </div>

        <div class="w-100 mx-auto">
            <table class="m-t-25 border table-info w-100">
                <tbody>
                    <tr>
                        <td class="no-border text-center text-base w-50 align-bottom py-50"
                            style="border-bottom:1px solid #5d6975!important;border-right:1px solid #5d6975!important; border-radius:0 !important">
                        </td>
                        <td class="no-border text-center text-base w-50 align-bottom py-50"
                            style="border-bottom:1px solid #5d6975!important;border-right:1px solid #5d6975!important; border-radius:0 !important">
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td class="no-border text-center text-base py-10 w-50 align-top"
                            style="border-right:1px solid #5d6975!important; border-radius:0 !important">
                            <span class="font-bold uppercase">Revisado por: <span class="font-bold lowercase">{{ $user->username }}</span> </span>
                        </td>
                        <td class="no-border text-center text-base py-10 w-50 align-top"
                            style="border-right:1px solid #5d6975!important; border-radius:0 !important">
                            <span class="font-bold uppercase">Verificado por:</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
</body>
</html>
