<div class="modal inmodal fade" id="ModalRecord" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
{{--<div class="modal inmodal" id="ModalRecord" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content animated bounceInRight">--}}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> <i class="fa fa-folder"></i> Historial del Afiliado</h4>
            </div>
            
            <div class="modal-body">
                <table id="example" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>Detalle</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($affiliate_records as $affiliate_record)
                        <tr>
                        <td>{{$affiliate_record->message}}</td>
                            <td class="size-date">{{$affiliate_record->created_at}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

