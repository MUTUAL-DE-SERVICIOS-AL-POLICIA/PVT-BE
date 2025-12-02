@extends('gateway.plantilla')

@section('title', 'Documentos Analizados')

@php($verification = (
  ($data['nonNumericIds'] ?? false) ||
  ($data['dataErrorReadFolder'] ?? false) ||
  ($data['dataErrorReadFiles'] ?? false) ||
  ($data['duplicateData'] ?? false)
))

@section('notice1')
  <div class="content2">
    <div>
      @if($verification)
        <p style="color: #f53636;"> Aun no se puede importar los documentos por que se encontraron errores en la estructura de la carpeta </p>
      @else
        <ul style="color: #12b61a">
          <li>NO SE ENCONTRARON ERRORES EN EL ANÁLISIS</li>
          <li>La importación de los documentos puede procesarse sin problemas. </li>
          <li>Revise los archivos que se importarán.</li>
        </ul>
      @endif
    </div>
  </div>
@endsection

@section('content')
  
  @if($verification)
    <div class="column">
      <table>
        <thead>
          <tr>
            <th width="65%" 
            @if($verification) style="background-color: #f53636;" @endif
            >
            Descripción</th>
            <th width="35%" 
            @if($verification) style="background-color: #f53636;" @endif
            >Resultados</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="sub_description">N° Total Carpetas</td>
            <td class="result">{{ $data['totalFolder'] }}</td>
          </tr>
          <tr>
            <td class="sub_description">N° Total Carpetas con nombres VÁLIDOS</td>
            <td class="result">{{ $data['readFolder'] }}</td>
          </tr>
          <tr>
            <td>Carpetas con nombres NO VALIDOS</td>
            @if($data['nonNumericIds'])
              <td>
                  @foreach($data['nonNumericIds'] as $nonNumericId)
                    &#128193;{{ $nonNumericId }}<br>
                  @endforeach
              </td>
            @else 
              <td class="result">0</td>
            @endif
          </tr>
          <tr>
            <td class="sub_description">N° Total Carpetas con NUP VÁLIDOS</td>
            <td class="result">{{ $data['validFolder']}}</td>
          </tr>
          <tr>
            <td class="sub_description">Carpetas con NUP de Afiliados NO ENCONTRADOS</td>
            @if($data['dataErrorReadFolder'])
              <td>
                  @foreach($data['dataErrorReadFolder'] as $dataErrorReadFolder)
                    &#128193;{{ $dataErrorReadFolder }}<br>
                  @endforeach
              </td>
            @else 
              <td class="result">0</td>
            @endif
          </tr>
          <tr>
            <td class="sub_description">N° Total de Archivos de las carpetas</td>
            <td class="result">{{ $data['filesValidFolder'] }}</td>
          </tr>
          <tr>
            <td class="sub_description">N° Total de Archivos .pdf con nombres VÁLIDOS </td>
            <td class="result">{{ $data['filesValid'] }}</td>
          </tr>
          <tr>
            <td class="sub_description">Archivos .pdf con nombres NO VÁLIDOS</td>
              
            @if($data['dataErrorReadFiles'])
              <td>
                @foreach($data['dataErrorReadFiles'] as $id => $files)
                  &#128193; {{ $id }} <br>
                  @foreach($files as $file)
                    &nbsp;&#128196;{{ $file }} <br>
                  @endforeach
                  @if (!$loop->last) <br> @endif
                @endforeach
              </td>
            @else
              <td class="result">0</td>
            @endif
          </tr>
          <tr>
            <td class="sub_description">Archivos .pdf duplicados</td>
              
            @if($data['duplicateData'])
              <td>
                @foreach($data['duplicateData'] as $id => $files)
                  &#128193; {{ $id }} <br>
                  @foreach($files as $file)
                    &nbsp;&#128196;{{ $file }} <br>
                  @endforeach
                  @if (!$loop->last) <br> @endif
                @endforeach
              </td>
            @else
              <td class="result">0</td>
            @endif
          </tr>
        </tbody>
      </table>
    </div>
  @else
    <div class="column">
      <table>
        <thead>
          <tr>
            <th colspan="2" style="background-color: #ffffff; color: #201e1e">Archivos listos para importar</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="sub_description">Archivos EXISTENTES para ACTUALIZAR</td>
            @if($data['dataValidRealExist'])
              <td>
                  @php($affiliate_id = null)
                  @foreach($data['dataValidRealExist'] as $dataValidRealExist)
                    @if($affiliate_id != $dataValidRealExist['affiliate_id'] && $affiliate_id != null)
                      <br>
                    @endif

                    @if($affiliate_id != $dataValidRealExist['affiliate_id'] || $affiliate_id == null)
                      &#128193; {{ $dataValidRealExist['affiliate_id'] }} <br>
                    @endif

                    @php($affiliate_id = $dataValidRealExist['affiliate_id'])
                    &nbsp; &#128196;{{ $dataValidRealExist['shortened'] }}<br>
                  @endforeach
              </td>
            @else 
              <td class="result">0</td>
            @endif
          </tr>
          <tr>
            <td class="sub_description">Archivos NUEVOS para IMPORTAR</td>
            @if($data['dataValidRealNotExist'])
            <td>
                @php($affiliate_id = null)
                @foreach($data['dataValidRealNotExist'] as $dataValidRealNotExist)
                  @if($affiliate_id != $dataValidRealNotExist['affiliate_id'] && $affiliate_id != null)
                    <br>
                  @endif

                  @if($affiliate_id != $dataValidRealNotExist['affiliate_id'] || $affiliate_id == null)
                    &#128193; {{ $dataValidRealNotExist['affiliate_id'] }} <br>
                  @endif

                  @php($affiliate_id = $dataValidRealNotExist['affiliate_id'])
                  &nbsp; &#128196;{{ $dataValidRealNotExist['shortened'] }}<br>
                @endforeach
            </td>
          @else 
            <td class="result">0</td>
          @endif
          </tr>
          <tr>
            <td colspan="2" style="text-align: center">Total &#128193; Carpetas: {{ $data['validFolder'] }}</td>
          </tr>
          <tr>
            <td colspan="2" style="text-align: center">Total &#128196; Archivos: {{ $data['filesValid'] }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  @endif
@endsection

@section('notice2')
  <div class="content2">
    <div>
      @if($verification)
        <p> Existen errores en la carpeta de análisis, revise y corrija, para continuar con el proceso </p>
        <br>
        <a class="button" style="width: 50%;" href="{{ route('gateway.auth') }}"> Volver a la página de inicio</a>
      @else
        <p>El análisis de los documentos puede realizarse en el siguiente botón</p>
        <br>
        <form action="{{ route('gateway.importsDocuments') }}" method="POST" id="importForm">
          <input type="hidden" name="data" value="{{ json_encode($data) }}">
          <button type="submit" style="width: 50%" onclick="openModal(event)">Realizar Importación</button>
        </form>
      @endif
    </div>
  </div>
@endsection

<div id="confirmationModal" class="modal">
  <div class="modal-content">
    <h2>Confirmación</h2>
    <p>¿Está seguro de que desea realizar la importación?</p>
    <button id="confirmButton" class="modal-button">Sí</button>
    <button id="cancelButton" class="modal-button">No</button>
  </div>
</div>