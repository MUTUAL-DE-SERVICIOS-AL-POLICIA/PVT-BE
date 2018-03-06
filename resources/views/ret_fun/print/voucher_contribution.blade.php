@extends('print_global.print')
@section('content')
    <div>    
        <table class="table-code w-25" align="right">
            <tbody>
                <tr>
                    <td class="p-5 bg-grey-darker">N°:</td>
                    <td class="text-center p-5" colspan="3">{!! $voucher->code !!}</td>
                </tr>
                <tr>
                    <td class="p-5 bg-grey-darker">Bs.:</td>
                    <td class="text-center p-5" colspan="3">{!! $voucher->total !!}.-</td>                </tr>
            </tbody>
        </table>
    </div>
    <br><br><br>
    <div>
        <span>Recibimos de:</span>
        @include('print_global.applicant_info', ['applicant'=>$bene    ])
    </div>
    <table class="table w-100">
        <tbody>
            <tr>
                <td class="p-5">La suma de:</td>
                <td class="p-5" colspan="3">.......... {!! ucwords(strtolower($total_literal)) !!}.......... Bolivianos</td>
            </tr>
            <tr>
                <td class="p-5">Por concepto de:</td>
                <td class="p-5" colspan="3">
                    .......... {!! $descripcion->name !!} del ..........<br>
                    .......... {!! $payment_date !!} ..........
                </td>
            </tr>
            <tr>
                <td class="p-5">Forma de Pago:</td>
                {{--  <td class="text-center p-5">.......... {!! $item->procedure_requirement->number !!} ..........</td>  --}}
                <td class="text-left py-4 bg-grey-darker">Nro.:</td>
                {{--  <td class="text-center p-5">.......... {!! $item->procedure_requirement->number !!} ..........</td>  --}}
            </tr>
            <tr>
                <td colspan="2"></td>
                <td class="text-left py-4 bg-grey-darker">Banco:</td>
                    {{--  <td class="text-center p-5">.......... {!! $item->procedure_requirement->number !!} ..........</td>  --}}
                </tr>
        </tbody>
    </table>
    <p class="text-right"> {{ $date }} </p>
    <table class="m-t-35">
        <tr>
            <th class="no-border text-center" style=" width:60%">
                <p class="font-bold">----------------------------------------------------<br>
                    COBRADO POR<br>
                {!! $name_user_complet !!}<br/>
                </p>
            </th>
        </tr>
    </table>

</div>
@endsection
