<!DOCTYPE html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>PLATAFORMA VIRTUAL - MUSERPOL {{ $title ?? '' }}</title>
    <link rel="stylesheet" href="{{ asset('css/materialicons.css') }}" media="all" />
    <link rel="stylesheet" href="{{ asset('css/wkhtml.css') }}" media="all" />
    <style>
        .courier {
            padding-top: 3px;
            font-family: 'Courier new', monospace;
            font-weight: 500 !important;
        }
    </style>
</head>

<body class="no-border text-xs">
    @for ($i = 0; $i< 3; $i++)
    <div class="m-b-20" style="height:490px !important; border-bottom: 1px dashed #3c3c3c ">
        <table class="w-100 ">
            <tr>
                <th class="w-20 text-left no-padding no-margins align-middle">
                    <div class="text-center">
                        <img src="{{ asset('images/logo.jpg') }}" class="w-75">
                    </div>
                </th>
                <th class="w-50 align-middle">
                    <div class="font-semibold uppercase leading-tight text-lg">
                        {{ $institution ?? 'MUTUAL DE SERVICIOS AL POLICÍA' }} <br>
                        {{ $direction ?? '"MUSERPOL"' }} <br>
                        {{ $unit ?? '' }}
                    </div>
                </th>
                <th class="w-20 no-padding no-margins align-top">
                    <table class="table-code no-padding no-margins">
                        <tbody>
                            <tr>
                                <td class="text-center bg-grey-darker text-xxs text-white">Nº de Recibo</td>
                                <td class="text-bold text-base">{!! $code !!}</td>
                            </tr>
                            <tr>
                                <td class="text-center bg-grey-darker text-xxs text-white">Usuario</td>
                                <td class="text-xs">{!! $user->username !!}</td>
                            </tr>
                            <tr>
                                <td class="text-center bg-grey-darker text-xxs text-white">Fecha</td>
                                <td class="text-xs uppercase">{!! $date !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </th>
            </tr>
            <tr>
                <td colspan="3" style="border-bottom: 1px solid #22292f;"></td>
            </tr>
            <tr>
                <td colspan="3" class="font-bold text-center text-lg uppercase">
                    {{ $title }}
                </td>
            </tr>
        </table>
        <div class="block m-b-10">
            <div class="font-bold m-b-5 w-15 inline-block align-bottom uppercase">
                Recibimos de:
            </div>
            <div class="inline-block align-top" style="width:84.8%;">
                <div class="border rounded px-10 py-5 inline-block w-68 align-bottom m-r-7">
                    {{ $applicant->fullName() }}
                    {{-- <br> <span class="uppercase">{{ $applicant->ciWithExt() }} </span> --}}
                </div>
                <div class="inline-block " style="width: 212px;">
                    <div class="border rounded font-bold text-2xl px-15 py-5 text-center">
                        Bs. {{ Util::formatMoney($voucher->paid_amount) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="block m-b-10">
            <div class="font-bold m-b-5 w-15 inline-block align-middle uppercase">
                La suma de:
            </div>
            <div class="border rounded py-5 inline-block px-10 w-82 align-top">
                {{ Util::convertir($voucher->paid_amount) }} Bolivianos
            </div>
        </div>
        <div class="block m-b-10 w-100">
            <div class="font-bold m-b-5 w-15 inline-block align-top uppercase">
                Por concepto de:
            </div>
            <div class="border rounded py-5 inline-block px-10 w-82 align-top text-justify uppercase" style="height:35px;">
                {{ $description }}
                
            </div>
        </div>
        <div class="block m-b-10 uppercase">
            <div class="font-bold m-b-5 w-15 inline-block align-top">
                Forma de pago
            </div>
            <div class="border rounded px-10 py-5 inline-block w-20 align-top m-r-15 text-center">
                {!! optional($voucher->payment_type)->name ?? "&nbsp;" !!}
            </div>
            <div class="font-bold m-b-5 m-r-10 inline-block align-top">
                BANCO
            </div>
            <div class="border rounded px-10 py-5 inline-block w-20 align-top m-r-15 text-center">
                {!! $voucher->bank ?? "&nbsp;" !!}
            </div>
            <div class="font-bold m-b-5 m-r-10 inline-block align-top">
                Nro.
            </div>
            <div class="border rounded px-10 py-5 w-15 inline-block align-top text-center">
                {!! $voucher->bank_pay_number ?? "&nbsp;" !!}
            </div>
        </div>
        <div class="mx-auto w-99 border rounded text-xs m-t-15 block text-center uppercase">
            <div class="text-center w-49 inline-block  align-top" style="height:150px;border-right: 1px solid #5d6975;">
                <div class="font-bold block m-t-100 ">{!! $user->fullName() !!}</div>
                <div class="block text-xxs" >{!! $user->position !!}</div>
            </div>
            <div class="text-center w-49 inline-block align-top" style="height:150px;">
                <span class="font-bold block m-t-100 ">{!! $applicant->fullName() !!}</span>
                <div class="text-center " >{!! $applicant->ciWithExt() !!}</div>
            </div>
        </div>
    </div>
    @endfor
</body>

</html>