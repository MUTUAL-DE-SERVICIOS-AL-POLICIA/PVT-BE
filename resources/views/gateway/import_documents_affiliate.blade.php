@extends('gateway.plantilla')

@section('title', 'Importación exitosa')

@section('content')

<div class="column">
    <table>
    <thead>
        <tr>
        <th width="65%">Descripción</th>
        <th width="35%">Resultados</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="sub_description">+&#128193; N° Total Carpetas </td>
            <td class="result">{{ $data['totalFolder'] }}</td>
        </tr>
        <tr>
            <td class="sub_description">+&#128196; N° Total de Archivos </td>
            <td class="result">{{ $data['totalFiles'] }}</td>
        </tr>
        <tr>
            <td class="sub_description">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Archivos Actualizados </td>
            <td class="result">{{ $data['updateFIles'] }}</td>
        </tr>
        <tr>
            <td class="sub_description">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Archivos Nuevos </td>
            <td class="result">{{ $data['newFiles'] }}</td>
        </tr>

    </tbody>
    </table>
</div>

@endsection

@section('notice2')
<div class="content2">
<br>
<a class="button" style="width: 50%;" href="{{ route('gateway.auth') }}"> Volver a la página de inicio</a>
</div>
@endsection