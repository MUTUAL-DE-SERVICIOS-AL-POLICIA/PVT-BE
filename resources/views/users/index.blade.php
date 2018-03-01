
@extends('layouts.app')

@section('content')
    <table class="table table-bordered" id="users-table">
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
@endsection
@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.3/css/fixedHeader.dataTables.min.css">
@endsection
@section('scripts')
    <!-- DataTables -->
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <!-- App scripts -->
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    {{--  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>  --}}
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.3/js/dataTables.fixedHeader.min.js"></script>

<script>
$(function() {
$('#users-table').DataTable({
processing: true,
serverSide: true,
ajax: "{!! route('user_list') !!}",
columns: [
{ data: 'username', name: 'username', orderable: false },
{ data: 'first_name', name: 'first_name' },
{ data: 'phone', name: 'phone', orderable: true },
{ data: 'city_id', name: 'city_id' },
{ data: 'position', name: 'position' },
{ data: 'status', name: 'status' }, 
{data: 'action', name: 'action', orderable: false, searchable: false},
], 
});
});
</script>

@endsection
