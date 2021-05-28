<div class="ibox">    
    <div class="ibox-content">
        <div class="row">
            <div class="pull-left"> <legend >Rostro</legend></div>
            <div class="text-right"></div>
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
                    <img src="{{ Storage::url('eco_com/'.$affiliate->id.'/ci_anverso_'.$economic_complement->id.'.jpg') }}" alt="" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="file">
                    <img src="{{ Storage::url('eco_com/'.$affiliate->id.'/ci_reverso_'.$economic_complement->id.'.jpg') }}" alt="" />
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
                {{-- {{$economic_complement}} --}}
                    <img src="{{ Storage::url('eco_com/'.$affiliate->id.'/boleta_de_renta_'.$economic_complement->id.'.jpg') }}" alt="" />
                </div>
            </div>
        </div>
    </div>
</div>