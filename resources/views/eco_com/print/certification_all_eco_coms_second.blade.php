@extends('print_global.print')
@section('content')
<div>
    <p class="text-justify">
        En la ciudad de {{ $user->city->name ?? 'La Paz' }}, en fecha {{ Util::getTextDate() }}, a horas
        {{ now()->format("H:i")}} se entrega en
        forma personal al Sr. (a) {{ $eco_com_beneficiary->fullName() }} el Certificado
        D.B.E./U.C.E./CERT. Nº 001/2019 DE LA UNIDAD DE OTORGACIÓN DEL BENEFICIO DEL
        COMPLEMENTO ECONÓMICO - MUSERPOL de fecha {{ Util::getTextDate() }}, quien recibió en mano propia el
        original de dicho documento.
    </p>
    <div>
        <table class="m-t-50 border table-info">
            <tbody>
                <tr>
                    <td class="no-border text-center text-base w-33 align-bottom py-50"
                        style="border-bottom:1px solid #5d6975!important;border-right:1px solid #5d6975!important; border-radius:0 !important">
                    </td>
                    <td class="no-border  text-center text-base w-33 align-bottom"
                        style="border-bottom:1px solid #5d6975!important;border-right:1px solid #5d6975!important; border-radius:0 !important">
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="no-border text-center text-base py-10 w-33 align-top"
                        style="border-right:1px solid #5d6975!important; border-radius:0 !important">
                        <span class="font-bold uppercase">{!! $user->fullName() !!}</span>
                        <br />
                        <span class="font-bold">{{ $user->position }}</span>
                     </td>
                    <td class="no-border text-center text-base py-10 w-33 align-top"
                        style="border-right:1px solid #5d6975!important; border-radius:0 !important">
                        <span class="font-bold uppercase">{!! $eco_com_beneficiary->fullName() !!}</span>
                        <br />
                        <span class="font-bold">C.I. {{ $eco_com_beneficiary->ciWithExt() }}</span>
                     </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection