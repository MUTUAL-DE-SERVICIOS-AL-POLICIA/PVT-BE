
@extends('layouts.app')

@section('content')

<div class="row">
        <div class="col-md-12 no-padding no-margins">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="pull-left">Usuarios</h3>
                    <div class="text-right">
                            <a href="{!! url('user/create') !!}">
                                <span class="glyphicon glyphicon-plus"></span>Nuevo</a>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-hover display" id="users-table"  cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                    <th>Usuario</th>
                                    <th>Nombres y Apellidos</th>
                                    <th>Celular</th>
                                    <th>Departamento</th>
                                    <th>Cargo</th>
                                    <th>Estado</th>
                                    <th>Editar</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>       
</div>
@endsection
@section('styles')
    <link rel="stylesheet" href="{{asset('/css/datatables.css')}}">
@endsection
@section('scripts')
<script src="{{ asset('/js/datatables.js')}}"></script>
<script>    
$(function() {
$('#users-table').DataTable({
processing: true,
serverSide: true,
ajax: "{!! route('user_list') !!}",
//"https://datatables.yajrabox.com/eloquent/master-data",
columns: [
{ data: 'username', name: 'username', orderable: true },
{ data: 'first_name', name: 'first_name' },
{ data: 'phone', name: 'phone', orderable: true },
{ data: 'city_id', name: 'city_id' },
{ data: 'position', name: 'position' },
{ data: 'status', name: 'status' }, 
{data: 'action', name: 'action', orderable: false, searchable: false},
], 
});
});

function initTable(tableId, data) {
        $('#' + tableId).DataTable({
            processing: true,
            serverSide: true,
            ajax: data.details_url,
            columns: [
                { data: 'id', name: 'id' },
                { data: 'title', name: 'title' }
            ]
        })
    }
</script>

@endsection
