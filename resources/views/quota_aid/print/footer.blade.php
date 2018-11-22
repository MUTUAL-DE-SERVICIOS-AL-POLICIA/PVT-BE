<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

<body>
    <div style="height:60px;padding-top:50px; vertical-align:middle;">
        <div style="border-top: 1px solid #22292f;">
            <div style="width: 100%;margin:0;paddin:0; display:inline">
                <img src="data:image/png;base64, {{ $bar_code }}" style="height: 41px; width: 40%;" />
            </div>
            <div style="float:right; text-align:right;font-family:sans-serif; font-size:14px; text-transform: uppercase">
                <span>PLATAFORMA VIRTUAL DE TRÁMITES - MUSERPOL &nbsp;</span><br>
                {{-- </span>{{ optional(optional($quota_aid->procedure_modality)->procedure_type)->second_name }} &nbsp;&nbsp;</span> --}}
            </div>
        </div>
    </div>
</body>

</html>
