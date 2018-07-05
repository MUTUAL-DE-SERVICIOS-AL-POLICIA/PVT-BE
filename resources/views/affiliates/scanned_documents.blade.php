<div class="ibox">    
    <div class="ibox-content">
            <div class="row">
                <div class="pull-left"> <legend > Documentos Escaneados</legend></div>
                
                <div class="text-right">
                    
                <a href="{{url('document_scanned/'.$affiliate->id)}}" data-animation="flip" class="btn btn-primary" ><i class="fa fa-file" ></i> Crear </a>
                </div>
            </div>
            <div class="row">
                {{-- {{$scanned_documents}} --}}
                @foreach($scanned_documents as $scanned_document)
                    <div class="file-box">
                        <div class="file">
                        <a href="{{url('scanned_documents/'.$scanned_document->id)}}"  target="_blank" >
                                <span class="corner"></span>

                                <div class="icon">
                                    <i class="fa fa-file"></i>
                                </div>
                                <div class="file-name">
                                    {{$scanned_document->procedure_document->name}}
                                    {{-- {{$scanned_document->url_file}} --}}
                                    <br/>
                                <small>vence: {{$scanned_document->due_date}}</small>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
         
    </div>
</div>