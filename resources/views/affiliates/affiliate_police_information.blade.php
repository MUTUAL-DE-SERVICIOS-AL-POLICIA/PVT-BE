<div class="col-lg-12">
    <div class="panel panel-primary" :class="show_spinner ? 'sk-loading' : ''">
        <div class="panel-heading">
            <h3 class="pull-left">Información Policial</h3>
            <div class="text-right">
            @can('update',$affiliate)
                <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing"><i class="fa" :class="editing ?'fa-unlock':'fa-lock'" ></i> </button>
            @else
                <br>
            @endcan
            </div>
        </div>

        <div class="panel-body" v-if="! editing	">
            <div class="row">
                <div class="col-md-6">
                  
                    <dl class="dl-">
                        <dt>Estado:</dt> <dd>@{{ state_name }}</dd>
                        <dt>Tipo:</dt> <dd>@{{ form.type }}</dd>
                        <dt>Fecha de Ingreso a la Institucional Policial:</dt> <dd> @{{ form.date_entry }}</dd>
                        <dt>Numero de Item:</dt> <dd> @{{ form.item }}</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="dl-">
                        <dt>Categoria:</dt> <dd>@{{ category_name }}</dd>
                        <dt>Grado:</dt> <dd > @{{degree_name}} </dd>
                        <dt>Ente gestor:</dt> <dd >@{{pension_entity_name}}</dd>
                    </dl>
                </div>
            </div>
        </div>
        @can('update',$affiliate)
        <div class="panel-body" v-else>
            <div class="sk-folding-cube" v-show="show_spinner">
                <div class="sk-cube1 sk-cube"></div>
                <div class="sk-cube2 sk-cube"></div>
                <div class="sk-cube4 sk-cube"></div>
                <div class="sk-cube3 sk-cube"></div>
            </div>
            <div class="row">
                <div class="col-md-6">  
                    <dl class="dl-">
                        <dt>Estado:</dt>
                        <dd>
                            {!! Form::select('affiliate_state_id', $affiliate_states, null, ['placeholder' => 'Seleccione un estado', 'class' => 'form-control',
                            'v-model' => 'form.affiliate_state_id' ]) !!}
                        </dd>
                        <dt>Tipo:</dt>
                        <dd>{!! Form::select('type', ['Comando'=>'Comando','Batallón'=>'Batallón'], null, ['placeholder' => 'Seleccione un tipo', 'class' => 'form-control',
                            'v-model' => 'form.type' ]) !!}
                        </dd>
                        <dt>Fecha de Ingreso a la Institucional Policial:</dt>  
                        <dd> <input type="text" class="form-control" v-model="form.date_entry"></dd>
                        <dt>Numero de Item:</dt>
                        <dd> <input type="text" class="form-control" v-model="form.item"></dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="dl-">
                        <dt>Categoria:</dt>
                        <dd>{!! Form::select('category_id', $categories, null, ['placeholder' => 'Seleccione una categoria',
                            'class' => 'form-control', 'v-model' => 'form.category_id']) !!}</dd>
                        <dt>Grado:</dt>
                        <dd>
                            {!! Form::select('degree_id', $degrees, null, ['placeholder' => 'Seleccione un Grado', 'class' => 'form-control' , 'v-model'
                            => 'form.degree_id' ]) !!}
                        </dd>
                        <dt>Ente gestor:</dt>
                        <dd>
                            {!! Form::select('pension_entity_id', $pension_entities, null, ['placeholder' => 'Seleccione el Ente Gestor', 'class' =>
                            'form-control', 'v-model' => 'form.pension_entity_id' ]) !!}
                        </dd>
                    </dl>
                </div>
            </div>
            <div class="row">
                <div v-show="editing" class="panel-footer">
                    <div class="text-center">
                        <button class="btn btn-danger" type="button" @click="toggle_editing()"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span class="bold">Cancelar</span></button>
                        <button class="btn btn-primary" type="button" @click="update"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
                    </div>
                </div>
            </div>
            
        </div>
        @endcan
    </div>
</div>
