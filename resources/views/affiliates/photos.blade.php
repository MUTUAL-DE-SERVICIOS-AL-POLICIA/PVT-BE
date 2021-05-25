<div class="ibox">    
    <div class="ibox-content">
            <div class="row">
                <div class="pull-left"> <legend > Fotos</legend></div>
                <div class="text-right">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="file">
                        <img src="{{ Storage::url('liveness/faces/'.$affiliate->id.'/Frente.jpg') }}" alt="" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="file">
                        <img src="{{ Storage::url('liveness/faces/'.$affiliate->id.'/Sonriente.jpg') }}" alt="" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="file">
                        <img src="{{ Storage::url('liveness/faces/'.$affiliate->id.'/Derecha.jpg') }}" alt="" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="file">
                        <img src="{{ Storage::url('liveness/faces/'.$affiliate->id.'/Izquierda.jpg') }}" alt="" />
                    </div>
                </div>
            </div>

            <!--
            <div class="row">
                <div class="pull-left"> <legend > CI</legend></div>
                <div class="text-right">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="file">
                        <img src="{{ Storage::url('liveness/CI/'.$affiliate->id.'/'.$affiliate->id.'anverso.jpeg') }}" alt="" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="file">
                        <img src="{{ Storage::url('liveness/CI/'.$affiliate->id.'/'.$affiliate->id.'reverso.jpeg') }}" alt="" />
                    </div>
                </div>
            </div>
            -->
    </div>
</div>