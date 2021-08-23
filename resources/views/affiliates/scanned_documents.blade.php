<div class="ibox">    
    <div class="ibox-content">
        <div class="row">
            <div class="pull-left"> <legend > Documentos Escaneados</legend></div>
        </div>
        <div class="row">
            <div class="pull-left"> 
            @if($file=='1')    
                <a href="{{url('document_scanned_upload/'.$affiliate->id)}}" data-animation="flip" class="btn btn-primary" ><i class="fa fa-file" ></i> Descargar </a>
            @else
                <span style="color: red; font-weight: bold;">DOCUMENTACI&Oacute;N NO ESCANEADA</span>
            @endif
            </div>
        </div>
    </div>
</div>