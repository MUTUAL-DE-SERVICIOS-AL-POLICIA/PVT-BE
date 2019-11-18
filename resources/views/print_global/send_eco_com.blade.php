<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PLATAFORMA VIRTUAL - MUSERPOL {{ $title ?? '' }}</title>
    <link rel="stylesheet" href="{{ public_path('css/wkhtml.css') }}" media="all" />
</head>

<body>
    <div class="page-break">
        <table class="w-100 ">
            <tr>
                <th class="w-15 text-left no-padding no-margins align-middle">
                    <div class="text-center">
                        <img src="{{ public_path('images/logo.jpg') }}" class="w-100">
                    </div>
                </th>
                <th class="w-50 align-top">
                    <span class="font-semibold uppercase leading-tight text-md">
                        {{ $institution ?? 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"' }} <br>
                        {{ $direction ?? 'DIRECCIÓN DE BENEFICIOS ECONÓMICOS' }} <br>
                        @if(isset($unit1))
                        {!! $unit1 !!}
                        @endif
                        {{ $unit ?? 'UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO' }}
                    </span>
                </th>
                <th class="w-20 no-padding no-margins align-top">
                </th>
            </tr>
            <tr>
                <td colspan="3" style="border-bottom: 1px solid #22292f;"></td>
            </tr>
            <tr>
                <td colspan="3" class="font-bold text-center text-xl uppercase">
                    {{ $title ?? 'custom title' }} @if (isset($subtitle))
                    <br><span class="font-medium text-lg">{!! $subtitle ?? '' !!}</span>
                    <br><span class="font-medium text-lg">{{ $procedures->first()->eco_com_procedure->semester }} SEMESTRE {{  date('Y',strtotime($procedures->first()->eco_com_procedure->year)) }}</span> @endif
                </td>
            </tr>

        </table>

        <div class="block">

            @yield('content')
            {{-- <table class="table-info w-100 m-b-5">
                <thead class="bg-grey-darker">
                    <tr class="font-medium text-white text-sm text-center">
                        <th>Nº</th>
                        <th>C.I. BENEFICIARIO</th>
                        <th>NOMBRE BENEFICIARIO</th>
                        <th>TIPO DE PRESTACIÓN</th>
                        <th>GRADO</th>
                        <th>CATEGORIA</th>
                        <th>NRO TRÁMITE</th>
                        <th>TIPO</th>
                        <th>FECHA RECEP</th>
                        <th>TOTAL</th>
                    <tr></tr>
                </thead>
                <tbody>
                @php($sum = 0)
                    @foreach ($procedures as $procedure)
                    @php($sum += $procedure->getOnlyTotalEcoCom())
                    <tr class="text-sm">
                        <td class="uppercase px-5 text-right">{{ $loop->iteration }}</td>
                        <td class="uppercase px-5 text-left">{{ $procedure->eco_com_beneficiary->ciWithExt() }}</td>
                        <td class="uppercase px-5 text-left">{{ $procedure->eco_com_beneficiary->fullName() }}</td>
                        <td class="uppercase px-5 text-center">
                            {{ $procedure->eco_com_modality->procedure_modality->name }}</td>
                        <td class="uppercase px-5 text-center">{{ $procedure->degree->shortened }}</td>
                        <td class="uppercase px-5 text-center">{{ $procedure->category->name }}</td>
                        <td class="uppercase px-5 text-left">{{ $procedure->code }}</td>
                        <td class="uppercase px-5 text-center">{{ $procedure->eco_com_reception_type->name }}</td>
                        <td class="uppercase px-5 text-left">{{ date('d/m/Y',strtotime($procedure->reception_date)) }}
                        </td>
                        <td class="uppercase px-5 text-right">Bs. {{ Util::formatMoney($procedure->getOnlyTotalEcoCom()) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table> --}}
            <table class="table-info w-100 m-b-5">
                <thead class="bg-grey-darker">
                    <tr class="font-medium text-white text-sm text-center">
                        <th>Nº</th>
                        <th>NRO TRÁMITE</th>
                        <th>FECHA RECEP</th>
                        <th>C.I. BENEFICIARIO</th>
                        <th>NOMBRE BENEFICIARIO</th>
                        <th>MODALIDAD</th>
                        <th>TIPO</th>
                        <th>GRADO</th>
                        <th>CATEGORIA</th>
                        <th>TOTAL</th>
                    <tr></tr>
                </thead>
                <tbody>
                @php($sum = 0)
                    @foreach ($procedures as $procedure)
                    @php($sum += $procedure->getOnlyTotalEcoCom())
                    <tr class="text-sm">
                        <td class="uppercase px-5 text-right">{{ $loop->iteration }}</td>
                        <td class="uppercase px-5 text-left">{{ $procedure->code }}</td>
                        <td class="uppercase px-5 text-left">{{ date('d/m/Y',strtotime($procedure->reception_date)) }}
                        </td>
                        <td class="uppercase px-5 text-left">{{ $procedure->eco_com_beneficiary->ciWithExt() }}</td>
                        <td class="uppercase px-5 text-left">{{ $procedure->eco_com_beneficiary->fullName() }}</td>
                        <td class="uppercase px-5 text-center">
                            {{ $procedure->eco_com_modality->procedure_modality->name }}</td>
                        <td class="uppercase px-5 text-center">{{ $procedure->eco_com_reception_type->name }}</td>
                        <td class="uppercase px-5 text-center">{{ $procedure->degree->shortened }}</td>
                        <td class="uppercase px-5 text-center">{{ $procedure->category->name }}</td>
                        <td class="uppercase px-5 text-right">Bs. {{ Util::formatMoney($procedure->getOnlyTotalEcoCom()) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th class="uppercase px-5 text-right" colspan=9>TOTAL</th><th class="uppercase px-5 text-right">{{Util::formatMoney($sum)}}</th>
                    </tr>
                </tfoot>
            </table>
            <div class="w-60 mx-auto">
                <table class="m-t-25 border table-info w-100">
                    <tbody>
                        <tr>
                            <td class="no-border text-center text-base w-33 align-bottom py-50"
                                style="border-bottom:1px solid #5d6975!important;border-right:1px solid #5d6975!important; border-radius:0 !important">
                            </td>
                            @if ($from_area->id == 3)
                            <td class="no-border text-center text-base w-33 align-bottom py-50"
                                style="border-bottom:1px solid #5d6975!important;border-right:1px solid #5d6975!important; border-radius:0 !important">
                            </td>
                            @endif
                            <td class="no-border text-center text-base w-33 align-bottom"
                                style="border-bottom:1px solid #5d6975!important; border-radius:0 !important">
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="no-border text-center text-base py-10 w-33 align-top"
                                style="border-right:1px solid #5d6975!important; border-radius:0 !important">
                                <span class="font-bold uppercase">Elaborado
                                    {{$from_area->id == 3 ? '' : 'y Revisado '}}por</span>
                            </td>
                            @if ($from_area->id == 3)
                            <td class="no-border text-center text-base py-10 w-33 align-top"
                                style="border-right:1px solid #5d6975!important; border-radius:0 !important">
                                <span class="font-bold uppercase">Revisado por</span>
                            </td>
                            @endif
                            <td class="no-border  text-center text-base py-10 w-33 align-top">
                                <span class="font-bold uppercase">V° B°</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <footer>
            @yield('footer')
        </footer>
    </div>
</body>

</html>