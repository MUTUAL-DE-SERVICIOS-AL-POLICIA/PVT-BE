@extends('print_global.print')
@section('content')
    <div>
        {{--  @include('ret_fun.print.applicant_info', ['applicant'=>$applicant])  --}}
        <div>
            <hr color=black>
            <p>Periodo: </p>
        </div>
    </div>
    <table class="table-info w-100">
        
        <tbody>
            <tr class="font-medium text-white text-sm font-bold bg-grey-darker">
                <td class="text-center p-5">COTIZABLE</td>
                <td class="text-center p-5">APORTE</td>
                <td class="text-center p-5">F.R.P.</td>
                <td class="text-center p-5">CUOTA MORTUORIA</td>
                <td class="text-center p-5">AJUSTE IPC</td>
                <td class="text-center p-5">TOTAL APORTE</td>
            </tr>
        {{--  @foreach($submitted_documents as $i=>$item)
            <tr>
                <td class='text-center p-5'>{!! $item->procedure_requirement->number !!}</td>
                <td class='text-justify p-5'>{!! $item->procedure_requirement->procedure_document->name !!} </td>
                <td class='text-justify p-5'>{!! $item->procedure_requirement->procedure_document->name !!} </td>
                <td class='text-justify p-5'>{!! $item->procedure_requirement->procedure_document->name !!} </td>
                <td class='text-justify p-5'>{!! $item->procedure_requirement->procedure_document->name !!} </td>
                <td class='text-justify p-5'>{!! $item->procedure_requirement->procedure_document->name !!} </td>
            </tr>
        @endforeach  --}}
            <tr>
                <td colspan="4"></td>
                <td class="text-right py-4 bg-grey-darker">Total:</td>
                {{--  <td class='text-justify p-5 bg-grey-darker'>{!! $item->procedure_requirement->procedure_document->name !!} </td>  --}}
            </tr>
        </tbody>
    </table>
<br>
    <table class="w-100 border">
        <tbody>
            <tr>
                <td>Son:</td>
                {{--  <td class='text-justify p-5'>{!! $item->procedure_requirement->procedure_document->name !!} </td>  --}}
            </tr>
            </tr>
            <tr>
                <td>Glosa:</td>
                {{--  <td class='text-justify p-5'>{!! $item->procedure_requirement->procedure_document->name !!} </td>  --}}
            </tr>
        </tbody>
    </table>

    <table class="w-100">
        <tbody>
            <tr>
                <th class="no-border text-center" colspan="2"></th>
                <th class="no-border text-center">
                    <p class="font-bold">
                        Impresión de Refrendo y Sello de Tesorería
                    </p>
                </th>
            </tr>
            <tr>
                <th class="no-border text-center">
                    <p class="font-bold"><br><br><br><br><br><br><br>
                        ----------------------------------------------------<br>
                        <strong>ELABORADO POR</strong>
                    </p>
                </th>
                <th class="no-border text-center">
                    <p class="font-bold"><br><br><br><br><br><br><br>
                        ----------------------------------------------------<br>
                        PAGADO POR
                    </p>
                </th>
                <th class="border text-center" rowspan="5">
                    <p class="font-bold"><br><br><br><br><br>
                        ----------------------------------------------------<br>
                        COBRADO POR
                    </p>
                </th>
                
            </tr>
            <tr>
                <th class="no-border text-left" colspan="2">
                    <p>
                        """Esta liquidación no es válida sin el Refrendo y Sello de Tesorería"""
                    </p>
                </th>
            </tr>
        </tbody>
       
    </table>

</div>
@endsection
