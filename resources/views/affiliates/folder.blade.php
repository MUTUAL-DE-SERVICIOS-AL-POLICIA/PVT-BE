
<div class="col-lg-12">
    <div class="ibox">
        <div class="ibox-content">
           
            <div class="pull-left">
                <legend> Archivos</legend>
            </div>
            <div class="text-right">
                @can('create',Muserpol\Models\AffiliateFolder::class)
                <a href="" class="btn btn-primary" data-toggle="modal" data-target="#folderModalRe">
                        <i class="fa fa-plus"> </i>
                </a>
                @else
                <br>
                @endcan
            </div>
           
            <div class="row">
                <div class="ibox-content table-responsive">
                    <table class="table table-hover table-sprite">
                        <thead>
                            <tr>
                            <th> Modalidad </th>
                            <th> Código </th>
                            <th> Número de Folder </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($folders as $folder )
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
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</div>
{{--@include(folderModal)--}}
{!! Form::open(['action' => 'AffiliateFolderController@store']) !!}
<div class="modal inmodal" id="folderModalRe" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <i class="fa fa-folder modal-icon"></i>
                <h4 class="modal-title">Registro de Antecedente</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name ="affiliate_id" value="{{$affiliate_id}}">
                <div class="form-group"><label>Modalidad</label>
                     <select class="form-control" name="procedure_modality_id">
                        <option></option>
                         @foreach($file_modalities as $modality)                         
                         <option value={{$modality->id}}>{{$modality->procedure_type->name ." - " .$modality->name }}</option>
                         @endforeach
                     </select>
                </div>                
                <div class="form-group"><label>N&uacute;mero de Folder</label> <input name="folder_number" type="text" placeholder="Numero de Folder" class="form-control"></div>
                
                <div class="form-group"><label>Pago</label>                     
                    <div class="toggle">
                        <label><input type="radio" name="is_paid" value="paid"><span>Pagado</span></label>    
                    </div>
                    <div class="toggle">
                        <label><input type="radio" name="is_paid"  value="nopaid"><span>No Pagado</span></label>
                    </div>                    
                </div>
                
                <div class="form-group"><label>Nota</label> <input name="note" type="text" placeholder="Nota adicionales" class="form-control"></div>

                <div class="form-group"><label>Codigo De Archivo</label> <input name="code_file" type="text" placeholder="Codigo generado por achivo" class="form-control"></div>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
{!!Form::close()!!}

<form class="form-horizontal" action="{{route('editFolder')}}" method="POST">
    <input type="hidden" name="_method" value="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="modal inmodal" id="folderDialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <i class="fa fa-folder modal-icon"></i>
                <h4 class="modal-title" id="exampleModalLabel">Registro de Antecedente</h4>
                <input name="folder_id" type="hidden" id="id_folder" value="id_folder">
            </div>
            <div class="modal-body">
                <input type="hidden" name ="affiliate_id" value="{{$affiliate_id}}">
                <div class="form-group"><label>Modalidad</label>
                    <select class="form-control" name="procedure_modality_id" id="mod_id">
                        @foreach($procedure_modalities as $modality)
                        <option value={{$modality->id}}>{{$modality->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group"><label>Codigo De Archivo</label> <input name="code_file" type="text" placeholder="Codigo generado por achivo" class="form-control" id="cod_folder"></div>
                <div class="form-group"><label>Numero de Folder</label> <input name="folder_number" type="text" placeholder="Numero de Folder" class="form-control" id="num_folder"></div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
</form>

<form class="form-horizontal" action="{{route('deleteFolder')}}" method="POST">
    <input type="hidden" name="_method" value="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div id="eliminar" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">¿Estás seguro?</h4>
                    <input name="code_file" type="hidden" class="form-control" id="cod_file_eli" value="cod_file_eli">
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</form>



