<div class="modal inmodal fade" id="ModalRecord" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-footer">
                <div class="text-center">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h5 class="modal-title"> <i class="fa fa-folder"></i> Historial del Afiliado</h5>
                </div>
                <table id="example" class="table table-striped table-bordered" >
                    <thead>
                        <tr>
                            <th class="col-md-10 text-left">Detalle</th>
                            <th class="col-md-2">Fecha</th>
                        </tr>
                    </thead>
                        <tbody>
                            @foreach($affiliate_records as $affiliate_record)
                            <tr>
                                <td class="col-md-10 text-left">{{$affiliate_record->message}}</td>
                                <td class="col-md-2">{{$affiliate_record->created_at}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

