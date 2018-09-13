<div class="col-lg-12">
    {!! Form::open(['action' => 'AffiliateFolderController@updateFileCode']) !!}
        <div class="ibox">
            <div class="ibox-content">
               
                <div class="pull-left">
                    <legend> Folder de Afiliado</legend>
                </div>
                <div class="text-right">
                    
                </div>
               
                <div class="row">
                    <input type="hidden" name ="affiliate_id" value="{{$affiliate_id}}">
                    <div class="ibox-content table-responsive">
                        <div class="form-group"><label>C&oacute;digo de archivo</label>
                        <input name="file_code" type="text" placeholder="C&oacute;odigo de folder" class="form-control" value="{{ $affiliate->file_code }}">
                        </div>
                    </div>
                </div>
                <div>                    
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </div>    
    {!!Form::close()!!}
</div>
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
                            <th> Tr&aacute;mite </th>                            
                            <th> Pagado </th>
                            <th> Nota </th>
                            <th colspan="2"> Editar </th>                            
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($folders as $folder )
                        <tr>                            
                            <td> {{ $folder->procedure_modality->name }} </td>                            
                            <td> @if($folder->is_paid === true) SI @endif @if($folder->is_paid === false)NO @endif </td>
                            <td> {{ $folder->procedure_modality->note }} </td>
                            @can('update', new Muserpol\Models\Affiliatefolder)
                             <td><button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#folderDialog"
                                data-modid="{{ $folder->procedure_modality_id }}"
                                data-id="{{$folder->id}}"
                                data-codfile="{{ $folder->code_file }}"
                                data-folnum="{{ $folder->folder_number }}"
                                data-ispaid = "{{ $folder->is_paid }}"
                                data-note="{{ $folder->note }}">
                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                            @endcan
                            {{-- @can('delete', new Muserpol\Models\Affiliatefolder) --}}
                                <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#eliminar" data-elim="{{ $folder->id }}"><i class="fa fa-trash" aria-hidden="true" ></i></button></td>
                            {{-- @endcan --}}
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
                <div class="form-group"><label>Tr&aacute;mite</label>
                     <select class="form-control" name="procedure_modality_id">
                        <option></option>
                         @foreach($file_modalities as $modality)                         
                         <option value={{$modality->id}}>{{$modality->name." (".$modality->procedure_type->name.")" }}</option>
                         @endforeach
                     </select>
                </div>                                                
                <div class="form-group"><label>Pago</label>                     
                    <div class="toggle">
                        <label><input type="radio" name="is_paid" value="paid"><span>Pagado</span></label>    
                    </div>
                    <div class="toggle">
                        <label><input type="radio" name="is_paid"  value="nopaid"><span>No Pagado</span></label>
                    </div>                    
                </div>
                
                <div class="form-group"><label>Nota</label> <input name="note" type="text" placeholder="Nota adicionales" class="form-control"></div>
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
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                <i class="fa fa-folder modal-icon"></i>
                <h4 class="modal-title" id="exampleModalLabel">Registro de Antecedente</h4>
                <input name="folder_id" type="hidden" id="id_folder" value="id_folder">
            </div>
            <div class="modal-body">
                <input type="hidden" name ="affiliate_id" value="{{$affiliate_id}}">
                <div class="form-group"><label>Tr&aacute;mite</label>
                    <select class="form-control" name="procedure_modality_id" id="mod_id">
                        @foreach($procedure_modalities as $modality)
                        <option value={{$modality->id}}>{{$modality->name}}</option>
                        @endforeach
                    </select>
                </div>                                
                <div class="form-group"><label>Pago</label>                     
                    <div class="toggle">
                        <label><input type="radio" name="is_paid" id="paid" value="paid"><span>Pagado</span></label>    
                    </div>
                    <div class="toggle">
                        <label><input type="radio" name="is_paid"  id="nopaid" value="nopaid"><span>No Pagado</span></label>
                    </div>                    
                </div>
                <div class="form-group"><label>Nota</label> <input name="note" id="note" type="text" placeholder="Nota adicionales" class="form-control"></div>                
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
                    <input name="folder_id" type="hidden" class="form-control" id="folder_id">
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</form>



