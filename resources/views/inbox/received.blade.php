@extends('layouts.app')
@section('title', 'Mi bandeja')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {!!Breadcrumbs::render('inbox')!!}
    </div>
</div>
<div class="wrapper wrapper-content">
        {{-- <div class="col-md-6">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Tramites por revisar</h5>
                </div>
                <div class="ibox-content" style="">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover display" id="datatables-inbox-left" cellspacing="0"
                            width="100%" style="font-size: 10px">
                            <thead>
                                <tr class="success">
                                    <th>ID</th>
                                    <th>ci</th>
                                    <th>Nombre de beneficiario</th>
                                    <th>Regional</th>
                                    <th>Tŕamite</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th style="max-width: 100px; padding:5px;"><input type="text" class="form-control search-icon" style="width:100%; font-family:'Font Awesome'" placeholder="&#xf002;"></th>
                                    <th style="max-width: 60px;padding:5px;"><input type="text" class="form-control search-icon" style="width:100%; font-family:'Font Awesome'" placeholder="&#xf002;"></th>
                                    <th style="max-width: 60px;padding:5px;"><input type="text" class="form-control search-icon" style="width:100%; font-family:'Font Awesome'" placeholder="&#xf002;"></th>
                                    <th style="max-width: 60px;padding:5px;"><input type="text" class="form-control search-icon" style="width:100%; font-family:'Font Awesome'" placeholder="&#xf002;"></th>
                                    <th style="max-width: 60px;padding:5px;"><input type="text" class="form-control search-icon" style="width:100%; font-family:'Font Awesome'" placeholder="&#xf002;"></t>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-content mailbox-content">
                    <div class="file-manager">
                        {{-- <a class="btn btn-block btn-primary compose-mail" ui-sref="email_compose">Compose Mail</a> --}}
    
                        <div class="space-25"></div>
                        <h5>Tramites</h5>
                        <ul class="folder-list m-b-md" style="padding: 0">
                            <li>
                                <tabs-content :rol-id="{{Muserpol\Helpers\Util::getRol()}}" :user="{{Muserpol\Helpers\Util::getAuthUser()}}" :inbox-state="`received`"
                                    inline-template>
                                    <a href="{{ url('inbox/received') }}" class="btn-outline" style="background: #59B75C; color: #fff; font-weight: bold;"> <i class="fa fa-envelope-o "></i> Recibidos
                                        <span class="label label-warning pull-right">@{{totalDocs}}</span>
                                    </a>
                                </tabs-content>
                            </li>
                            <li>
                                <tabs-content :rol-id="{{Muserpol\Helpers\Util::getRol()}}" :user="{{Muserpol\Helpers\Util::getAuthUser()}}" :inbox-state="`edited`"
                                    inline-template>
                                    <a href="{{ url('inbox/edited') }}" class="btn-outline"> <i class="fa fa-check"></i> Revisados
                                        <span class="label label-warning pull-right">@{{totalDocs}}</span>
                                    </a>
                                </tabs-content>
                            </li>
                        </ul>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-9 animated fadeInRight">
            <div class="mail-box-header">
                {{-- <form method="get" action="index.html" class="pull-right mail-search ng-pristine ng-valid">
                    <div class="input-group">
                        <input type="text" class="form-control input-sm" name="search" placeholder="Search email">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-sm btn-primary">
                        Search
                    </button>
                        </div>
                    </div>
                </form> --}}
                <h2>
                    <tabs-content :rol-id="{{Muserpol\Helpers\Util::getRol()}}" :user="{{Muserpol\Helpers\Util::getAuthUser()}}" :inbox-state="`received`"
                        inline-template>
                        <span>Recibidos (@{{totalDocs}})</span>
                    </tabs-content>
                </h2>
                {{-- <div class="mail-tools tooltip-demo m-t-md">
                    <div class="btn-group pull-right">
                        <button class="btn btn-white btn-sm"><i class="fa fa-arrow-left"></i></button>
                        <button class="btn btn-white btn-sm"><i class="fa fa-arrow-right"></i></button>
                    </div>
                    <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="left" title="Refresh inbox"><i class="fa fa-refresh"></i> Refresh</button>
                    <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Mark as read"><i class="fa fa-eye"></i></button>
                    <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Mark as important"><i class="fa fa-exclamation"></i></button>
                    <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Move to trash"><i class="fa fa-trash-o"></i></button>
                </div> --}}
            </div>
            <div class="mail-box">
                <tabs-content :rol-id="{{Muserpol\Helpers\Util::getRol()}}" :inbox-state="'received'" inline-template>
                    <vue-tabs>
                        <v-tab
                            :title="`${itab.name} (${classification(itab.id).length})`"
                            v-for="(itab, index) in workflows"
                            :dataId="itab.id"
                            icon="fa fa-check"
                            :key="`tab-received-${index}`"
                            :suffix="`<span class='badge'> ${classification(itab.id).length} </span>`"
                        >
                            <inbox-content
                                :workflow-id="itab.id"
                                :documents="classification(itab.id)"
                            ></inbox-content>
                        </tab>
                    </vue-tabs>
                </tabs-content>
            </div>
        </div>
    </div>
</div>
@endsection
{{-- @section('styles')
<link rel="stylesheet" href="{{asset('/css/datatables.css')}}">
<style>
    td.highlight {
        background-color: #e3eaef !important;
    }

    .table-hover tbody tr:hover td,
    .table-hover tbody tr:hover th {
        background-color: #e3eaef;
    }
    .yellow-row {
        background-color: #ffe6b3 !important;
    }
    thead, tfoot { display: table-header-group; }
</style>
@endsection --}}
    {{-- @section('scripts')
    <script src="{{ asset('/js/datatables.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('body').addClass("mini-navbar");
            var datatableInboxLeft = $('#datatables-inbox-left').DataTable({
                responsive: true,
                fixedHeader: {
                    header: true,
                    footer: true,
                    headerOffset: $('#navbar-fixed-top').outerHeight()
                },
                order: [],
                /* para personalizar el orden
                columnDefs: [
                { type: 'monthYear', targets: 0 }
                ],
                */
                ajax: "{{ url('/api/documents', \Muserpol\Helpers\Util::getRol()->id) }}",
                dom:"<'row'<'col-sm-12'lB>><'row'<'col-sm-12't>><'row'<'col-sm-5'i>><'row'<'bottom'p>>",
                lengthMenu: [[10, 15, 25, 50,100, -1], [10, 15, 25, 50,100, "Todos"]],
                language: {
                    "lengthMenu": "Ver _MENU_ rgistros",
                    "zeroRecords": "No hay ningun registro",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(filtered from _MAX_ total entries)",
                    "paginate": {
                        "first":      "Primero",
                        "last":       "Ultimo",
                        "next":       "Siguiente",
                        "previous":   "Anterior"
                    },
                },
                buttons:[
                    {extend: 'colvis', columnText: function ( dt, idx, title ) { return (idx+1)+': '+title; }},
                    { extend: 'copy'},
                    { extend: 'csv'},
                    { extend: 'excel'},
                ],
                columns:[
                    {data: 'id'},
                    {data: 'ci'},
                    {data: 'name'},
                    {data: 'city'},
                    {data: 'code'},
                ],
                // "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                //     if ( aData.type == " RE " )
                //     {
                //         $('td', nRow).addClass('warning');
                //     }
                // }
            });
            datatableInboxLeft.columns().every( function () {
                var that = this;
                $('input', this.footer()).on('keyup change', function () {
                    if ( that.search() !== this.value ) {
                        that.search( this.value ).draw();
                    }
                });
            });
            /*  para personalizar el orden
            jQuery.extend(jQuery.fn.dataTableExt.oSort, {
                "monthYear-pre": function(s) {
                    var a = s.split('-');
                    // Date uses the American "MM DD YY" format
                    return new Date(a[0] + ' 01 ' + a[1]);
                },
                "monthYear-asc": function(a, b) {
                    return ((a < b) ? -1 : ((a > b) ? 1 : 0));
                },
                "monthYear-desc": function(a, b) {
                    return ((a < b) ? 1 : ((a > b) ? -1 : 0));
                }
            });
            */

            $('#datatables-inbox-left tbody').on('mouseenter', 'td', function () {
                var colIdx = datatableInboxLeft.cell(this).index().column;
                $( datatableInboxLeft.cells().nodes()).removeClass('highlight');
                $( datatableInboxLeft.column(colIdx).nodes()).addClass( 'highlight' );
            } );
            $('[data-toggle="tooltip"]').tooltip();
            $('.btn.btn-default.buttons-collection.buttons-colvis').on('click', function () {
                $('div.dt-button-background').remove()
            });
        })

    </script>
    @endsection --}}

