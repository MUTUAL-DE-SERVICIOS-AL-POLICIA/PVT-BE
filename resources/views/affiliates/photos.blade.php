<div class="ibox">    
    <div class="ibox-content">
        <div class="row">
            <div class="pull-left"> <legend >Rostro</legend></div>
            @if (Util::rolIsEcoCom())
            <div class="text-right" v-if="btnVerified">
                    <div class="text-center" v-if="editable">
                        <button data-animation="flip" class="btn btn-danger"  @click="updateDesvalidar"><i class="fa" ></i>Desvalidar CI </button> 
                    </div>
                    <div class="text-center" v-if="!editable">
                        <button data-animation="flip" class="btn btn-primary"  @click="updateValidar"><i class="fa" ></i>Validar CI </button>
                    </div>
            </div>
            @endif
        </div>
        <div class="row">
            <div class="alert alert-warning" v-if="btnVerified">
              <ul>
                <li class="alert-success" v-if="editable">Rostro verificado con carnet.</li>
                <li class="alert-danger" v-if="!editable">El rostro no se encuentra verificado con el carnet.</li>
                <li class="alert-success" v-if = "notification">Puede recibir notificaciones.</li>
                <li class="alert-danger" v-if = "!notification">No puede recibir notificaciones.</li>
              </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="file">
                    <img src="data:image/png;base64,{{$fotofrente}}" alt="" />
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
    </div>
</div>
