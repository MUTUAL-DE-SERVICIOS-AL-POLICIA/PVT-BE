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
                        {{ $unit ?? 'UNIDAD DE OTORGACIÓN DEL COMPLEMENTO ECONÓMICO' }}
                    </span>
                </th>
            </tr>
            <tr>
                <td colspan="3" style="border-bottom: 1px solid #22292f;"></td>
            </tr>
            <tr>
                <td colspan="3" class="font-bold text-center text-xl uppercase">
                    {{ $title }}
                    @if (isset($subtitle))
                        <br><span class="font-medium text-lg">{{ $subtitle ?? '' }}</span>
                    @endif
                </td>
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
                        <td class="text-center p-5" colspan = "2">I. DATOS PERSONALES DEL BENEFICIARIO (TITULAR)
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

            <table class="table-info w-100 m-b-5">
                <thead class="bg-grey-darker">
                    <tr class="font-medium text-white text-sm">
                        <td class="text-center p-5" colspan = "2">II. DATOS INSTITUCIONALES</td>
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
                        <td class="text-left uppercase">{{ $affiliate->service_years - $affiliate->service_months }}
                        </td>
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
                        <td class='text-left font-bold p-5 uppercase'>MOTIVO DE LA DESVINCULACIÓN</td>
                        <td class="text-left uppercase">{{ $affiliate->category->name }}</td>
                    </tr>
                    <tr>
                        <td class='text-left font-bold p-5 uppercase'>FECHA DE DESVINCULACIÓN</td>
                        <td class="text-left uppercase">{{ $affiliate->category->name }}</td>
                    </tr>
                </tbody>
            </table>

            {{-- @if (sizeof($eco_com_submitted_documents) > 0)
                    <table class="table-info w-100 m-b-5">
                        <thead class="bg-grey-darker">
                            <tr class="font-medium text-white text-sm">
                                <td class="text-center p-5" colspan = "2">III. PROCEDIMIENTOS DE REVISIÓN REALIZADOS</td>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach ($eco_com_submitted_documents as $item)
                                @if ($item->number > 0)
                                    <tr>
                                        <td class='text-justify p-5'>{{ $item->procedure_document->name }} </td>
                                        @if (true)
                                            <td class="text-center">
                                                <img
                                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAADhSURBVEhL7ZRJCsJAFETbG3kC1yIuRBRBHBEP4DyPiIiI4G0Vx6okH0SIJvqDmzx4pLvorr8IiQn5JzEYsZf61OENHqydMjXI8jtsMNCkCqW8xUCTCrxAlncYaFKEUt5joEkeSvmAgSY5KOUjBm5EnacfslDKJwzcWEAeLFg7b2TgGbJ8xuAdU8iDHMKX9Yk0PEHemTPwwhjKkBIDF1JQypcM/DCEMqTM4IUklPIVg2/oQxnCr1JIQClfM/iFLnweEodSvoEqtCELr/DorLdQlSZkMd0xCAL+bvf2MiQQjHkAzVw/sI3mdmoAAAAASUVORK5CYII=">
                                            </td>
                                        @else
                                        <td class="text-center">
                                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAEHSURBVEhL7ZVNCsIwEIXrz1HUYwiuBA8jbkU8h4ILxZ14J0X0DoKI+p5mIAzNJLZ1IfTBh3Uw81EzTbM6v0jbfaamCRqfy/SMwQ0sQIuFSLrgCA6gx0JKJuDpsQGWjJIzkN9fQAeYYcM78EUkJGNDXyIsQTQroBcSLQtJrqAPomEzNtUNiMgsyRAkx5LtQSUSiSXTFJZIUmSlJRLKdiBP8gAjUElCGy/IgJRKTCKUkuknXuDfpWukkCwk4cZzT9ZezecrmSWR6WKz0DQmyXjM8xTWi/NG2JLNgBm+U07AX2Q9JyHZFETD9wmP+phEomVbV0sKx5pH/eD9LR425l3M3XWdv0uWvQDq/6w9IEeDKwAAAABJRU5ErkJggg==">
                                            </td>
                                        @endif
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                @endif --}}

            @if ($eco_com->eco_com_reception_type_id == 2 || $eco_com->eco_com_reception_type_id == 3)
                <table style="margin-top: {{ $size_down }}px;" class="m-t-50 table-info">
                    <tbody>
                        <tr>
                            <td class="no-border text-center text-base w-50 align-bottom"
                                style="border-radius: 0.5em 0 0 0!important;">
                                <span class="font-bold">
                                    ----------------------------------------------------
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="no-border text-center text-base w-50 align-top">
                                <span class="font-bold">{!! strtoupper($eco_com_beneficiary->fullName()) !!}</span>
                                <br />
                                <span class="font-bold">C.I. {{ $eco_com_beneficiary->ciWithExt() }}</span>
                                <span class="font-bold">TEL. CEL. {{ $eco_com_beneficiary->phone_number }}
                                    {{ $eco_com_beneficiary->cell_phone_number }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>

        <tfoot>
            <tr>
                <td class="no-border text-center text-base py-10 w-33 align-top"
                    style="border-right:1px solid #5d6975!important; border-radius:0 !important">
                    <span class="font-bold uppercase">Revisado por:</span>
                </td>

                <td class="no-border text-center text-base py-10 w-33 align-top"
                    style="border-right:1px solid #5d6975!important; border-radius:0 !important">
                    <span class="font-bold uppercase">Verificado por:</span>
                </td>
            </tr>
        </tfoot>

    </div>

</body>

</html>
