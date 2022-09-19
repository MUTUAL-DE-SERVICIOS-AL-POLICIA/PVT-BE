<div class="block">
        <table class="table-info w-100 m-b-10">
            <thead class="bg-grey-darker">
                <tr class="font-medium text-white text-sm uppercase">
                    <td colspan='9' class="px-15 text-center">
                        cálculo {{$name_procedure_type}}
                    </td>
                </tr>
                <tr class="font-medium text-white text-sm">
                    <td class="px-15 py text-center ">
                        Nº
                    </td>
                    <td class="px-15 py text-center ">
                        MES
                    </td>
                    <td class="px-15 py text-center">
                        AÑO
                    </td>
                    <td class="px-15 py text-center">
                        TOTAL GANADO
                    </td>
                    <td class="px-15 py text-center">
                        SUELDO
                    </td>
                    <td class="px-15 py text-center">
                        ANTIGUEDAD
                    </td>
                    <td class="px-15 py text-center">
                        APORTE F.R.P.S
                    </td>
                    <td class="px-15 py text-center">
                        APORTE C.M
                    </td>
                    <td class="px-15 py text-center">
                        APORTE
                    </td>
                </tr>
             </thead><br>
            <tbody class="table-striped">
                    @foreach($contributionsPlus as $contribution)
                        <tr class="text-sm">
                            <td class="text-center uppercase font-bold px-5 py-3">{{ $num=$num+1}}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ date('m', strtotime($contribution->month_year)) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ date('Y', strtotime($contribution->month_year)) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($contribution->gain) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($contribution->base_wage) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($contribution->seniority_bonus) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($contribution->retirement_fund) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($contribution->mortuary_quota) }}</td>
                            <td class="text-center uppercase font-bold px-5 py-3">{{ Util::formatMoney($contribution->total) }}</td>
                        </tr>
                    @endforeach
                <tr>
                    <td colspan="6" class="text-center uppercase font-bold">TOTAL</td>
                    <td class="text-center uppercase font-bold px-5 py-3" >{{ $total_retirement_fund }}</td>
                    <td colspan="2" class="text-center"></td>
                </tr>
            </tbody>
        </table>
    </div>