<div class="ibox">
    <div class="ibox-content">
        <div class="row">
            <div class="pull-left"> <legend >Vejez</legend></div>
            <div class="text-right">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="file">
                    <img src="data:image/png;base64,{{$fotofrentevejez}}" alt="" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="file">
                    <img src="data:image/png;base64,{{$fotoizquierdavejez}}" alt="" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="file">
                    <img src="data:image/png;base64,{{$fotoderechavejez}}" alt="" />
                </div>
            </div>
        </div>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="pull-left"> <legend >Viudedad</legend></div>
            <div class="text-right">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="file">
                    <img src="data:image/png;base64,{{$fotofrenteviudedad}}" alt="" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="file">
                    <img src="data:image/png;base64,{{$fotoizquierdaviudedad}}" alt="" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="file">
                    <img src="data:image/png;base64,{{$fotoderechaviudedad}}" alt="" />
                </div>
            </div>
        </div>
    </div>
    <div class="ibox-content">
    <div class="row">
        <div class="pull-left"> <legend >Boletas</legend></div>
        <div class="text-right">
        </div>
    </div>
    @foreach($fotosBoletas as $foto)
        <div class="row">
            <div class="col-md-6">
                <div class="file">
                    <img src="data:image/png;base64,{{$foto}}" alt="" />
                </div>
            </div>
        </div>
    @endforeach
</div>