@extends('layouts.app')

@section('title', 'Seleccion de Aportes')

@section('content')
    
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-7">
        {!!Breadcrumbs::render('document_scanned',$affiliate)!!}
    </div>
    
</div>

<br>
    <div class="ibox">    
        <div class="ibox-content">
            {!! Form::open(['action' => 'ScannedDocumentController@store','files' => true]) !!}
            <legend> Subir Documento Escaneado</legend>
            <input type="hidden" name="affiliate_id" value="{{$affiliate->id}}">
            <div class="row">
                <div class="col-md-3">
                    <label> Tipo de Documento:</label>   
                </div>
                <div class="col-md-9">
                        <div class="input-group">
                            <input type="hidden" id="procedure_document_id" class="form-control" name="procedure_document_id" >
                            <input type="text" id="procedure_document_name" class="form-control" >
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" data-toggle="modal" data-target="#SelectModal"> <i class="fa fa-file"></i> </button>
                            </span>
                        </div><!-- /input-group -->
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3"><label>Documento PDF:</label> </div>
                <div class="col-md-9">
                    <div class="fileinput fileinput-new input-group" data-provides="fileinput" >
                        <div class="form-control" data-trigger="fileinput">
                            <i class="glyphicon glyphicon-file fileinput-exists"></i>
                        <span class="fileinput-filename"></span>
                        </div>
                        <span class="input-group-addon btn btn-primary btn-file">
                            <span class="fileinput-new"><i class="fa fa-upload"></i></span>
                            <span class="fileinput-exists"><i class="fa fa-refresh"></i></span>
                            <input type="file" name="archivo" accept=".pdf"/>
                        </span>
                        <a href="#" class="input-group-addon btn btn-danger fileinput-exists" data-dismiss="fileinput"><i class="fa fa-trash"></i> </a>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3">
                    <label>Comentario:</label>
                </div>
                <div class="col-md-9">
                    <textarea class="form-control" name="comment"></textarea>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-3">
                    <label>Fecha de vencimiento:</label>
                </div>
                <div class="col-md-9">
                    <input class="form-control" type="date" name="due_date">
                </div>
            </div>
            <br>
            <div class="row">
                <div class="text-center">
                    <button type="button" class="btn btn-danger" onclick="cyk()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="modal inmodal"  id="SelectModal"  tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Seleccione un Tipo de Documento</h4>
                
            </div>
            <div class="modal-body">
                    <table id="ProcedureDocuments" class="table" style="width:100%">
                        <thead>
                            <tr>
                                <th>id</th>
                                <th>Nombre</th>
                                <th>Accion</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($procedure_documents as $procedure_document)
                            <tr>
                                <td>{{$procedure_document->id}}</td>
                                <td>{{$procedure_document->name}}</td>
                            <td><button class="btn btn-success select_procedure" data-id="{{$procedure_document->id}}" data-name="{{$procedure_document->name}}"  data-dismiss="modal">Seleccionar</button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
        </div>
        </div>
    </div>

@endsection
@section('styles')
<link rel="stylesheet" href="{{asset('/css/datatable.css')}}">
@endsection
@section('jss')
<script>
    function cyk() {
        window.history.back();
    }
</script>
<script src="{{ asset('/js/datatables.js')}}"></script>
<script>
$(document).ready(function() {
  
    $('#ProcedureDocuments').DataTable();
    $('.select_procedure').click(function(){
        let id = $(this).data('id');
        let name =$(this).data('name');
        $('#procedure_document_id').val(id);
        $('#procedure_document_name').val(name);
        console.log(name);
    });
} );
</script>
@endsection