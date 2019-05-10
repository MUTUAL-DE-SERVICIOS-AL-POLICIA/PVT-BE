@extends('layouts.app')
@section('styles')
<link rel="stylesheet" href="{{asset('/css/datatables.css')}}">
@endsection
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-md-12 no-padding no-margins">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="pull-left">Usuarios</h3>
                    <div class="text-right">
                        <a href="/usersLdapUpdate" title="Cambiar estados" class="btn btn-danger"><i class="fa fa-refresh" ></i></a>
                        <a href="{{url('user/create')}}" title="Nuevo usuario" class="btn btn-info"><i class="fa fa-plus" ></i></a>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-hover display" id="users-table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Roles</th>
                                <th>Usuario</th>
                                <th>Nombres y Apellidos</th>
                                <th>Celular</th>
                                <th>Departamento</th>
                                <th>Estado</th>
                                <th>Editar</th>
                                <th>Cambiar Estado</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<script src="{{ asset('/js/datatables.js')}}"></script>
<script>
$(function() {
var user_tables = $('#users-table').DataTable({
processing: true,
serverSide: true,
ajax: "{!! route('user_list') !!}",
columns: [
{"className":      'details-control',
 "orderable":      false,
 "searchable":      false,
 "data":           'button-roles',
 "name":            'button-roles',
 },
{ data: 'username', name: 'username', orderable: true },
{ data: 'first_name', name: 'first_name' },
{ data: 'phone', name: 'phone', orderable: true },
{ data: 'city_id', name: 'city_id' },
{ data: 'status', name: 'status' },
{data: 'action', name: 'action', orderable: false, searchable: false},
{"className":      'details-control',
 "name":         'state',
 "orderable":      false,
 "searchable":      false,
 "data":           'state',
 "defaultContent": ''
 },]});

$('#users-table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = user_tables.row(tr);
        var tableId = 'posts-' + row.data().id;
        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            row.child(format(row.data())).show();
            tr.addClass('shown');
        }
    });
});
function format (d) {
    var table = $('<table>').addClass('table-bordered');
        table.append($('<th>').text('Unidad')).append($('<th>').text('Role'));
        d.roles.forEach(element => {
            var tr = $('<tr>');

            var td = $('<td>');
        switch(element.module_id) {
        case '1':
                td.text('Tecnología');
        break;
        case '2':
                td.text('Complemento Económico');
        break;
        case '3':
                td.text('Fondo de Retiro');
        break;
        case '4':
                td.text('Cuota Mortuoria');
        break;
        case '5':
                td.text('Auxilio Mortuorio');
        break;
        case '6':
                td.text('Préstamos');
        break;
        case '7':
                td.text('Jurídica');
        break;
        case '8':
                td.text('Beneficios Económicos');
        break;
        case '9':
                td.text('Asuntos Financieros');
        break;
        case '10':
                td.text('Regional');
        break;
        default:
                td.text('');
        }
            tr.append(td);
            var td = $('<td>');
                td.text(element.name);
            tr.append(td);
            table.append(tr);
        });
        return table;
}
</script>
@endsection
