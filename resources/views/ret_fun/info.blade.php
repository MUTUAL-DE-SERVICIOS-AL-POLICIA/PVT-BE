<div class="col-lg-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="pull-left">Información del Tramite</h3>
            <div class="text-right">
                <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing"><i class="fa" :class="editing ?'fa-unlock':'fa-lock'" ></i> </button>
            </div>
        </div>

        <div class="panel-body " v-if="! editing">
            <div class="col-md-6">
                <dl class="dl-">
                    <dt>Modalidad:</dt>
                    <dd>{{ $retirement_fund->procedure_modality->name ?? '' }} </dd>
                    <dt>Ciudad de Recepcion:</dt>
                    <dd>{{ $retirement_fund->city_start->name ?? '' }} </dd>
                    <dt>Fecha de Recepcion:</dt>
                    <dd>{{ $retirement_fund->city_start->name ?? '' }} </dd>
                    <dt>Regional:</dt>
                    <dd>{{ $retirement_fund->city_end->name ?? '' }} </dd>
                </dl>
            </div>
        </div>
    </div>
</div>