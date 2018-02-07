<div class="col-lg-12">
    <div class="panel panel-primary" :class="show_spinner ? 'sk-loading' : ''">
        <div class="panel-heading">
            <h3 class="pull-left">Información Policial</h3>
            <div class="text-right">
                <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing"><i class="fa" :class="editing ?'fa-unlock':'fa-lock'" ></i> </button>
            </div>
        </div>
        <div class="panel-body" v-if="! editing	">
            <div class="row">
                <div class="col-md-6">
                    <dl class="dl-">
                        <dt>Estado:</dt> <dd>{{ $affiliate->affiliate_state->name ?? '' }}</dd>
                        <dt>Tipo:</dt> <dd>{{ $affiliate->type }}</dd>
                        <dt>Fecha de Ingreso a la Institucional Policial:</dt> <dd> {{ $affiliate->date_entry }}</dd>
                        <dt>Numero de Item:</dt> <dd> {{ $affiliate->item }}</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="dl-">
                        <dt>Categoria:</dt> <dd>{{ $affiliate->category->name ?? '' }}</dd>
                        <dt>Grado:</dt> <dd data-toggle="tooltip" data-placement="right" title="{{ $affiliate->degree->name ?? '' }}">{{ $affiliate->degree->shortened ?? '' }} </dd>
                        <dt>Ente gestor:</dt> <dd data-toggle="tooltip" data-placement="right" title="{{ $affiliate->pension_entity->type ?? '' }}">{{ $affiliate->pension_entity->name ?? '' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>