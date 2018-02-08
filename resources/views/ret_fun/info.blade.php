<div class="col-lg-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="pull-left">Información del Tramite</h3>
            <div class="text-right">
                <button data-animation="flip" class="btn btn-primary"><i class="fa" ></i> </button>
            </div>
        </div>

        <div class="panel-body ">
            <div class="col-md-6">
                <dl class="dl-">
                    <dt>Modalidad:</dt>
                    <dd>{{ $retirement_fund->procedure_modality->name ?? '' }} </dd>
                </dl>
            </div>
        </div>
    </div>
</div>