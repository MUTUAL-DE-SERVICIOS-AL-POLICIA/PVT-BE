<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('/css/wkhtml.css') }}">
    <title>REPORT</title>
    <style>
        .table-info tr:nth-child(2n){
            background: #f0f0f0
        }
    </style>
</head>
<body class="no-border text-xs">
        <table class="w-100 ">
            <tr>
                <th class="w-20 text-left no-padding no-margins align-middle">
                    <div class="text-center">
                        <img src="{{ asset('images/logo.jpg') }}" class="w-100">
                    </div>
                </th>
                <th class="w-50 align-top">
                    <span class="font-semibold uppercase leading-tight text-md" >
                        {{ $institution ?? 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"' }} <br>
                        {{ $direction ?? 'DIRECCIÓN DE BENEFICIOS ECONÓMICOS' }} <br>
                        {{ $unit ?? 'UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO' }}
                    </span>
                </th>
                <th class="w-20 no-padding no-margins align-top">
                    <table class="table-code align-top no-padding no-margins">
                        <tbody>
                            <tr>
                                <td class="text-center bg-grey-darker text-xxs text-white">Área</td>
                                <td class="text-xs">{!! $area !!}</td>
                            </tr>
                            <tr>
                                <td class="text-center bg-grey-darker text-xxs text-white">Usuario</td>
                                <td class="text-xs">{!! $user->username !!}</td>
                            </tr>
                            <tr>
                                <td class="text-center bg-grey-darker text-xxs text-white">Fecha</td>
                                <td class="text-xs uppercase">{{ $date }}</td>
                            </tr>
                        </tbody>
                    </table>
                </th>
            </tr>
            <tr><td colspan="3" style="border-bottom: 1px solid #22292f;"></td></tr>
            <tr>
                <td colspan="3" class="font-bold text-center text-xl uppercase">
                    {{ $title }}
                    @if (isset($subtitle))
                    <br><span class="font-medium text-lg">{!! $subtitle ?? '' !!}</span>
                    @endif
                </td>
            </tr>
            {{-- <tr><td colspan="3"></td></tr>
            <tr><td colspan="3"></td></tr> --}}
    
        </table>
    <div class="block">
        <table class="table-info w-100 m-b-10">
            <thead class="bg-grey-darker">
                <tr class="text-white text-xs uppercase">
                    @foreach ($headers as $item => $value)
                        <td class="font-medium text-center px-10 py-5">{{ $value }}</td>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr>
                        @foreach ($headers as $item => $h)
                            <td class="font-medium text-center px-5 py-5">{{ $row[$item] }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    @foreach ($footer as $item)
                        <td class="{{ $item['class'] ?? 'text-center' }} font-bold uppercase " colspan="{{ $item['colspan'] ?? 1 }}">{{ $item['text'] }}</td>
                    @endforeach
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>