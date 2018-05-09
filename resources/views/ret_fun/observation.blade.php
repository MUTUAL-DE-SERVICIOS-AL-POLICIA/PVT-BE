<div class="col-lg-12">
      

    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="pull-left">Observaciones</h3>
            <div class="text-right">
                @can('create',Muserpol\Models\AffiliateFolder::class)
                <a href="" class="btn btn-primary" data-toggle="modal" data-target="#folderModalRe">
                        <i class="fa fa-plus"> </i>
                </a>
                @else
                <br>
                @endcan
            </div>
        </div>
        <div >
            {{-- <div class="ibox-content table-responsive"> --}}
                <table class="table table-hover table-sprite">
                    <thead>
                        <tr>
                            <th> Fecha </th>
                            <th> Tipo de Observación </th>
                            <th> Descripción </th>
                            <th> Habilidado </th>
                            <th> Opciones </th>
                        </tr>
                    </thead>
                    <tbody>
                    {{-- @foreach($folders as $folder )
                    <tr>
                        <td> {{ $folder->procedure_modality->name }} </td>
                        <td> {{ $folder->code_file }} </td>
                        <td> {{ $folder->folder_number }} </td>
                        @can('update', new Muserpol\Models\Affiliatefolder)
                            <td><button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#folderDialog" data-modid="{{ $folder->procedure_modality_id }}" data-id="{{$folder->id}}" data-codfile="{{ $folder->code_file }}" data-folnum="{{ $folder->folder_number }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></td>
                        @endcan
                        @can('delete', new Muserpol\Models\Affiliatefolder)
                            <td><button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#eliminar" data-elim="{{ $folder->id }}"><i class="fa fa-trash" aria-hidden="true" ></i></button></td>
                        @endcan
                    </tr>
                    @endforeach --}}
                    </tbody>
                </table>
            {{-- </div> --}}
        </div>
    </div>
</div>