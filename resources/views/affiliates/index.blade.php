@extends('layouts.app')


@section('title', 'Afiliados')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{ Breadcrumbs::render('affiliate') }}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row" id="app">
        <div class="col-lg-12">
            <div class="text-center m-t-lg">
                <affiliate-index></affiliate-index>
                {{-- <table id="affiliates-table" class="table table-bordered">
                    <thead>
                    <tr>
                        <th>CI</th>
                        <th>Primer Nombre</th>
                        <th>Segundo Nombre</th>
                        <th>Apellido Paterno</th>
                        <th>Apellido Materno</th>
                    </tr>
                    </thead>
                </table> --}}
                <h2>
                    Welcome in INSPINIA Larasssvel Starter Project
                </h2>
                <small>
                    It is an application skeleton for a typical web app. You can use it to quickly bootstrap your webapp projects.
                </small>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#affiliates-table').DataTable({
            serverSide: true,
            processing: true,
            ajax: '/get_all_affiliates',
            columns: [
                {data: 'id'},
                {data: 'first_name'},
                {data: 'second_name'},
                {data: 'last_name'},
                {data: 'mothers_last_name'},
                // {data: 'action', orderable: false, searchable: false}
            ]
        });
    });
</script>
@endsection
