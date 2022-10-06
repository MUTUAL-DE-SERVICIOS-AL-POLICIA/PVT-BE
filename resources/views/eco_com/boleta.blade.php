<div class="ibox">    
    <div class="ibox-content">
        <div class="row">
   

            <div class="pull-left"><legend >Rostro</legend>
            </div>
            <div class="text-right" v-if="btnVerified">
                    <div class="text-center" v-if="editable">
                        <button data-animation="flip" class="btn btn-danger"  @click="updateDesvalidar"><i class="fa" ></i>Desvalidar CI </button> 
                    </div>
                    <div class="text-center" v-if="!editable">
                        <button data-animation="flip" class="btn btn-primary"  @click="updateValidar"><i class="fa" ></i>Validar CI </button>
                    </div>
            </div>
        </div>
        <div class="row">
            <div class="alert alert-warning" v-if="btnVerified">
              <ul>
                <li class="alert-success" v-if="editable">El Rostro esta Validado</li>
                <li class="alert-danger" v-if="!editable">El Rostro aún no se encuentra validado</li>
                <li class="alert-success" v-if = "notification">Puede Recibir Notificación</li>
                <li class="alert-danger" v-if = "!notification">No Puede Recibir Notificación</li>
              </ul>
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