@extends('print_global.print')
@section('content')
    <div>
         @include('print_global.applicant_info', ['applicant'=>$beneficiary]) 
        <div>
            <p>Periodo: </p>
        </div>
    </div>
    <table class="table-info w-100">
        
        <tbody>
            <tr class="font-medium text-white text-sm font-bold bg-grey-darker">
                <td class="text-center p-5 w-20">MES/AÑO</td>
                <td class="text-center p-5 w-15">COTIZABLE (BS)</td>
                <td class="text-center p-5 w-10">APORTE (BS)</td>
                <td class="text-center p-5 w-10">F.R.P. (4.77%) (BS)</td>
                <td class="text-center p-5 w-20">CUOTA MORTUORIA (1.09%) (BS)</td>
                <td class="text-center p-5 w-10">AJUSTE IPC (BS)</td>
                <td class="text-center p-5 w-15">TOTAL APORTE (BS)</td>
            </tr>
        @foreach($contributions as $i=>$item)
            <tr>
                <td class='text-left p-5'>{!! $item->monthyear !!}</td>
                <td class='text-right p-5'>{!! $util::formatMoney($item->sueldo) !!} </td>
                <td class='text-right p-5'>{!! $util::formatMoney($item->fr + $item->cm) !!} </td>
                <td class='text-right p-5'>{!! $util::formatMoney($item->fr) !!} </td>
                <td class='text-right p-5'>{!! $util::formatMoney($item->cm) !!} </td>
                <td class='text-right p-5'>{!! $util::formatMoney($item->interes) !!} </td>
                <td class='text-right p-5'>{!! $util::formatMoney($item->subtotal) !!} </td>
            </tr>
        @endforeach
            <tr>
                {{-- <td colspan="4"></td> --}}
                <td colspan="6" class="text-center py-4 bg-grey-darker text-white font-bold">TOTAL</td>
                <td class='text-right p-5'>{!! $util::formatMoney($total) !!} </td>  
            </tr>
        </tbody>
    </table>
<br>
    <table class="w-100 border rounded">
        <tbody>
            <tr>
                <td>Son:</td>
                <td class='text-justify p-5'>{!! ucwords(strtolower($total_literal)) !!} </td>  
            </tr>
            </tr>
        </tbody>
    </table>
    <br><br><br><br>
    <table class="w-100">
        <tbody>
            {{-- <tr>
                <th class="no-border text-center" colspan="2"></th>
                <th class="no-border text-center">
                    <p class="font-bold">
                        Impresión de Refrendo y Sello de Tesorería
                    </p>
                </th>
            </tr> --}}
            <tr>
                <th class="no-border text-center">
                    <span>
                        <strong>
                            ----------------------------------------------------<br>
                            ELABORADO POR
                        </strong><br>
                        {{$name_user_complet}}
                    </span>
                </th>
                {{-- <th class=" text-center">
                    <span>
                        <strong>
                            ----------------------------------------------------<br>
                            PAGADO POR
                        </strong><br>
                        {{ $name_beneficiary_complet }}
                    </span>
                </th> --}}
                {{-- <th class="border text-center" rowspan="5">
                    <p class="font-bold"><br><br><br><br><br>
                        ----------------------------------------------------<br>
                        COBRADO POR: <br> {{$name_user_complet}}
                    </p>
                </th> --}}
                
            </tr>
            {{-- <tr>
                <th class="no-border text-left">
                    <p>
                        ***Esta liquidación no es válida sin el Refrendo y Sello de Tesorería***
                    </p>
                </th>
            </tr> --}}
        </tbody>
       
    </table>

</div>
@endsection
