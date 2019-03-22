<div class="modal inmodal fade" id="ModalRecordQuotaAid" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-footer">
                <div class="text-center">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h5 class="modal-title"> <i class="fa fa-folder"></i> Historial del Trámite</h5>
                </div>
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#ret-fun-tab"> Datos del trámite</a></li>
                        <li class=""><a data-toggle="tab" href="#workflow-tab"> Flujo del trámite</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="ret-fun-tab" class="tab-pane active">
                            <div class="panel-body">
                                <table id="example" class="table table-striped table-bordered datatablespanish">
                                    <thead>
                                        <tr>
                                            <th class="col-md-9 text-left">Detalle</th>
                                            <th class="col-md-2">Fecha</th>
                                            <th class="col-md-2">Hora</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($quota_aid_records as $quota_aid_record)
                                        <tr>
                                            <td class="col-md-9 text-left">{{$quota_aid_record->message}}</td>
                                            <td class="col-md-2">{{date("d/m/Y", strtotime($quota_aid_record->created_at))}}</td>
                                            <td class="col-md-2">{{date("H:i", strtotime($quota_aid_record->created_at))}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="workflow-tab" class="tab-pane">
                            <div class="panel-body">
                                <table id="workflow-table" class="table table-striped table-bordered datatablespanish">
                                    <thead>
                                        <tr>
                                            <th class="col-md-9 text-left">Detalle</th>
                                            <th class="col-md-2">Fecha</th>
                                            <th class="col-md-2">Hora</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($workflow_records as $w)
                                        <tr>
                                            <td class="col-md-9 text-left">{{$w->message}}</td>
                                            <td class="col-md-2">{{date("d/m/Y", strtotime($w->date))}}</td>
                                            <td class="col-md-2">{{date("H:i", strtotime($w->date))}}</td>
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