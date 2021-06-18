<div class="ibox">    
    <div class="ibox-content">
        <div class="row">
            <div class="pull-left"><legend >Rostro</legend></div>
            <div class="text-right">
                @if(!$affiliatedevice->isEmpty())
                    <div class="text-center" v-if="editable">
                        <button data-animation="flip" class="btn btn-danger"  @click="updateDesvalidar"><i class="fa" ></i>Desvalidar CI </button> 
                    </div>
                    <div class="text-center" v-if="!editable">
                        <button data-animation="flip" class="btn btn-primary"  @click="updateValidar"><i class="fa" ></i>Validar CI </button>
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="file">
                    <img src="data:image/png;base64,{{$fotofrente}}" alt="" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="file">
                    <img src="data:image/png;base64,{{$fotosonriente}}" alt="" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="file">
                    <img src="data:image/png;base64,{{$fotoizquierda}}" alt="" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="file">
                    <img src="data:image/png;base64,{{$fotoderecha}}" alt="" />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="pull-left"> <legend > CI</legend></div>
            <div class="text-right">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="file">
                    <img src="data:image/png;base64,{{$fotocianverso}}" alt="" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="file">
                    <img src="data:image/png;base64,{{$fotocireverso}}" alt="" />
                </div>
            </div>
        </div>

        <div class="row">
            <div class="pull-left"> <legend > Boleta</legend></div>
            <div class="text-right">
            </div>
        </div>
        <div class="row">
            <div class="file-box">
                <div class="file">
                    <img src="data:image/png;base64,{{$fotoboleta}}" alt="" />
                </div>
            </div>
        </div>
    </div>
</div>