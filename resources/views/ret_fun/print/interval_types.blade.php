<div class="block">
    <table class="table-info w-100 m-b-10">
        <thead class="bg-grey-darker">
            <tr class="font-medium text-white text-sm uppercase">
                <td colspan='5' class="px-15 text-center">
                    APORTES Y PERIODOS CONSIDERADOS
                </td>
            </tr>
        </thead>
        <tbody class="table-striped">
            <tr>
                <td class="w-50"></td>
                <td class="w-25 text-center font-bold px-10 py-3 uppercase" colspan="2">inicio</td>
                <td class="w-25 text-center font-bold px-10 py-3 uppercase" colspan="2">fin</td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">periodo de aportes</td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ ($ret_fun->getDateContributions()->start) ?? 'error' }} </td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ ($ret_fun->getDateContributions()->start) ?? 'error' }} </td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ ($ret_fun->getDateContributions()->end) ?? 'error' }} </td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ ($ret_fun->getDateContributions()->end) ?? 'error' }} </td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">destinos en letras de disponibilidad</td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $ret_fun->getDateAvailability()->start ?? 'error' }} </td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $ret_fun->getDateAvailability()->start ?? 'error' }} </td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $ret_fun->getDateAvailability()->end ?? 'error' }} </td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $ret_fun->getDateAvailability()->end ?? 'error' }} </td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">periodo de aportes en aportes en item "0"</td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $ret_fun->getDateItemZero()->start ?? 'error' }} </td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $ret_fun->getDateItemZero()->start ?? 'error' }} </td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $ret_fun->getDateItemZero()->end ?? 'error' }} </td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $ret_fun->getDateItemZero()->end ?? 'error' }} </td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">periodo de aportes batallon de seguridad fisica</td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $ret_fun->getDateSecurityBattalion()->start ?? 'error' }} </td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $ret_fun->getDateSecurityBattalion()->start ?? 'error' }} </td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $ret_fun->getDateSecurityBattalion()->end ?? 'error' }} </td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $ret_fun->getDateSecurityBattalion()->end ?? 'error' }} </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="block">
    <table class="table-info w-100 m-b-10">

        <tbody class="table-striped">
            <tr>
                <td class="w-50"></td>
                <td class="w-25 text-center font-bold px-10 py-3 uppercase" >años</td>
                <td class="w-25 text-center font-bold px-10 py-3 uppercase" >meses</td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">años de serivcio segun certificacion del comando general de la policia</td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ ($ret_fun->getDateContributions()->start) ?? 'error' }} </td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ ($ret_fun->getDateContributions()->end) ?? 'error' }} </td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">cantidad de aportes item "0"</td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $ret_fun->getDateAvailability()->start ?? 'error' }} </td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $ret_fun->getDateAvailability()->end ?? 'error' }} </td>
            </tr>
            <tr class="text-sm">
                <!-- TODO mayo 1976 -->
                <td class="text-left px-10 py-3 uppercase">aportes anteriores a mayo de 1976</td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $ret_fun->getDateAvailability()->start ?? 'error' }} </td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $ret_fun->getDateAvailability()->end ?? 'error' }} </td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">periodos en disponibilidad</td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $ret_fun->getDateAvailability()->start ?? 'error' }} </td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $ret_fun->getDateAvailability()->end ?? 'error' }} </td>
            </tr>
            <tr class="text-sm">
                <!-- TODO Nro 151/2017 -->
                <td class="text-left px-10 py-3 uppercase">periodos descontados segun certificacion Nro 151/2017</td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $ret_fun->getDateAvailability()->start ?? 'error' }} </td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $ret_fun->getDateAvailability()->end ?? 'error' }} </td>
            </tr>
            <tr class="text-sm">
                <td class="text-left px-10 py-3 uppercase">total de cotizaciones para calificacion</td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $ret_fun->getDateAvailability()->start ?? 'error' }} </td>
                <td class="text-center uppercase font-bold px-5 py-3"> {{ $ret_fun->getDateAvailability()->end ?? 'error' }} </td>
            </tr>
        </tbody>
    </table>
</div>