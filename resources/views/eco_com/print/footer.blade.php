<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{asset('/css/wkhtml.css') }} ">
</head>

<body class="no-border">
    <div class="w-99">
        <div class="w-95 mx-auto">
            <table class="w-100 table-collapse ">
                <tr>
                    <th class="no-padding no-margins align-middle" style="width:63px; border-top: 2px solid; ">
                        <div class="text-right no-padding no-margins">
                            <img src="data:image/png;base64, {{ $bar_code }}" />
                        </div>
                    </th>
                    <th class="align-top text-left text-xs font-normal align-middle px-10"
                        style="border-top: 2px solid; ">
                        Procesado por: <span class="italic">{{ $user->fullName() }}</span><br>
                        PLATAFORMA VIRTUAL DE TRÁMITES
                        MUTUAL DE SERVICIOS AL POLICÍA - MUSERPOL
                    </th>
                    <th class="no-padding no-margins align-middle text-right px-10"
                        style="border-top: 2px solid; border-left: 2px solid; ">
                        PVT
                        <br>
                        MUSERPOL
                    </th>
                </tr>
            </table>
        </div>
    </div>
    {{-- <div style="width: 100%;margin:0;paddin:0; display:inline">
        <img src="data:image/png;base64, {{ $bar_code }}" />
    </div>
    <div style="float:right; font-family:sans-serif; font-size:14px;">
        <span>PLATAFORMA VIRTUAL DE TRÁMITES - MUSERPOL &nbsp;</span>
    </div> --}}
</body>

</html>