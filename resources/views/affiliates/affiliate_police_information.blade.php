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
                        <dt>Estado:</dt> <dd>{{ $affiliate->affiliate_state->name }}</dd>
                        <dt>Tipo:</dt> <dd>{{ $affiliate->type }}</dd>
                        <dt>Fecha de Ingreso a la Institucional Policial:</dt> <dd> {{ $affiliate->date_entry }}</dd>
                        <dt>Numero de Item:</dt> <dd> {{ $affiliate->item }}</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="dl-">
                        <dt>Categoria:</dt> <dd>{{ $affiliate->category->name }}</dd>
                        <dt>Grado:</dt> <dd data-toggle="tooltip" data-placement="right" title="{{ $affiliate->degree->name ?? '' }}">{{ $affiliate->degree->shortened ?? '' }} </dd>
                        <dt>Ente gestor:</dt> <dd data-toggle="tooltip" data-placement="right" title="{{ $affiliate->pension_entity->type ?? '' }}">{{ $affiliate->pension_entity->name ?? '' }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="panel-body" v-else>
        	<div class="sk-folding-cube" v-show="show_spinner" >
        	    <div class="sk-cube1 sk-cube"></div>
        	    <div class="sk-cube2 sk-cube"></div>
        	    <div class="sk-cube4 sk-cube"></div>
        	    <div class="sk-cube3 sk-cube"></div>
        	</div>
            {{-- <div class="row"> --}}
                <div class="col-md-6">
                    <dl class="dl-">
                        <dt>Estado:</dt> <dd>
                        {!! Form::select('affilate_state_id', $affiliate_states, null, ['placeholder' => 'Seleccione un estado', 'class' => 'form-control', 'v-model' => 'form.affiliate_state' ]) !!}
                        </dd>
                        <dt>Tipo:</dt> <dd><input type="text" class="form-control" v-model="form.affiliate_type"></dd>
                        <dt>Fecha de Ingreso a la Institucional Policial:</dt> <dd> <input type="text" class="form-control" v-model="form.affiliate_date_entry"></dd>
                        <dt>Numero de Item:</dt> <dd> <input type="text" class="form-control" v-model="form.affiliate_item"></dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="dl-">
                        <dt>Categoria:</dt> <dd>{!! Form::select('category_id', $categories, null, ['placeholder' => 'Seleccione una categoria', 'class' => 'form-control', 'v-model' => 'form.affiliate_category']) !!}</dd>
                        <dt>Grado:</dt> <dd>
                        	{!! Form::select('degree_id', $degrees, null, ['placeholder' => 'Seleccione un Grado', 'class' => 'form-control' , 'v-model' => 'form.affiliate_degree' ]) !!}
                         </dd>
                        <dt>Ente gestor:</dt> <dd>
                        	{!! Form::select('pension_entity_id', $pension_entities, null, ['placeholder' => 'Seleccione el Ente Gestor', 'class' => 'form-control', 'v-model' => 'form.affiliate_pension_entity' ]) !!}
                        </dd>
                    </dl>
                </div>
            {{-- </div> --}}
        </div>
        <div v-show="editing" class="panel-footer">
            <div class="text-center">
                <button class="btn btn-danger" type="button" @click="toggle_editing()"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span class="bold">Cancelar</span></button>
                <button class="btn btn-primary" type="button" @click="update"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
            </div>
        </div>
    </div>
</div>