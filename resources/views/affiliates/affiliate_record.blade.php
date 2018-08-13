<div class="modal inmodal fade" id="ModalRecord" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-footer">
                <div class="text-center">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h5 class="modal-title"> <i class="fa fa-folder"></i> Historial del Afiliado</h5>
                </div>
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#affiliate-tab"> Datos del Policia </a></li>
                        <li class=""><a data-toggle="tab" href="#record-tab"> Vida Policial</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="affiliate-tab" class="tab-pane active">
                            <div class="panel-body">
                                <table id="example" class="table table-striped table-bordered datatablespanish" >
                                    <thead>
                                        <tr>
                                            <th class="col-md-10 text-left">Detalle</th>
                                            <th class="col-md-2">Fecha</th>
                                            <th class="col-md-2">Hora</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($affiliate_records as $affiliate_record)
                                        <tr>
                                            <td class="col-md-10 text-left">{{$affiliate_record->message}}</td>
                                            {{-- <td class="col-md-2">{{$affiliate_record->created_at}}</td> --}}
                                            <td class="col-md-2">{{date("d/m/Y", strtotime($affiliate_record->created_at))}}</td>
                                            <td class="col-md-2">{{date("H:i", strtotime($affiliate_record->created_at))}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="record-tab" class="tab-pane table">
                            <div class="panel-body">
                                <table id="record-table" class="table table-striped table-bordered datatablespanish">
                                    <thead>
                                        <tr>
                                            <th class="col-md-9 text-left">Detalle</th>
                                            <th class="col-md-2">Fecha de Verificación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($affiliate_police_records as $a)
                                        <tr>
                                            <td class="col-md-9 text-left">{{$a->message}}</td>
                                            {{-- <td class="col-md-2">{{$a->date}}</td> --}}
                                            <td class="col-md-2">{{date("d/m/Y", strtotime($a->date))}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
