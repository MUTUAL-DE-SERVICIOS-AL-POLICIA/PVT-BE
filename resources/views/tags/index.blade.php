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
                    <h3 class="pull-left">Etiquetas</h3>
                    <div class="text-right">
                        <a href="{{url('tag/create')}}" class="btn btn-primary"><i class="fa fa-plus" ></i></a>
                    </div>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-hover display" id="tags-table" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Shortened</th>
                                <th>slug</th>
                                <th>Editar</th>
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
  var tags_tables = $("#tags-table").DataTable({
    processing: true,
    serverSide: true,
    ajax: "{!! route('tag_list') !!}",
    columns: [
      { data: "name"},
      { data: "shortened"},
      { data: "slug"},
      { data: "action"},
    ]
  });
});

 </script>
@endsection