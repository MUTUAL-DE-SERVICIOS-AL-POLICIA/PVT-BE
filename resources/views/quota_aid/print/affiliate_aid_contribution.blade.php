@extends('print_global.print')
@section('content')
    <div>
        @include('print_global.police_info', ['affiliate'=>$beneficiary, 'degree'=>$beneficiary->degree, 'exp'=>($beneficiary->city_identity_card->first_shortened ?? null)]) 
        <div>
            <p>Periodo: </p>
        </div>
    </div>
    <table class="table-info w-100">
        
        <tbody>
            <tr class="font-medium text-white text-sm font-bold bg-grey-darker">
                <td class="text-center p-5 w-20">MES/AÑO</td>
                <td class="text-center p-5 w-20">COTIZABLE (BS)</td>
                <td class="text-center p-5 w-20">CUOTA MORTUORIA (2.03 %) (BS)</td>
                <td class="text-center p-5 w-20">AJUSTE UFV (BS)</td>
                <td class="text-center p-5 w-20">TOTAL APORTE (BS)</td>
            </tr>
        @foreach($contributions as $i=>$item)
            <tr>
                <td class='text-left p-5'>{!! strtoupper($util::getStringDate($item->year."-".$item->month."-01",true)) !!}</td>
                {{-- <td class='text-right p-5'>{!! $util::formatMoney($item->sueldo) !!} </td>
                <td class='text-right p-5'>{!! $util::formatMoney($item->auxilio_mortuorio) !!} </td>
                <td class='text-right p-5'>{!! $util::formatMoney($item->interes) !!} </td>
                <td class='text-right p-5'>{!! $util::formatMoney($item->subtotal) !!} </td> --}}
                <td class='text-right p-5'>{!! ($item->sueldo) !!} </td>
                <td class='text-right p-5'>{!! ($item->auxilio_mortuorio) !!} </td>
                <td class='text-right p-5'>{!! ($item->interes) !!} </td>
                <td class='text-right p-5'>{!! ($item->subtotal) !!} </td>
            </tr>
        @endforeach
            <tr>
                {{-- <td colspan="4"></td> --}}
                <td colspan="4" class="text-center py-4 bg-grey-darker text-white font-bold">TOTAL</td>
                <td class='text-right p-5'><strong>{!! $util::formatMoney($total) !!}</strong></td>  
            </tr>
        </tbody>
    </table>
    <br>
    <table class="w-100 border rounded">
        <tbody>
            <tr>
                <td>Son:</td>
                <td class='text-justify p-5'>{!! ucwords(strtolower($total_literal)) !!} Bolivianos.</td>  
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
