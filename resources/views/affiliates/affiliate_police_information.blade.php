<div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-content">
                <div class="pull-left"> <legend > Información Policial</legend></div>
                @can('update',$affiliate)
                <div class="text-right">
                    <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing"><i class="fa" :class="editing ?'fa-edit':'fa-pencil'" ></i> Editar </button>
                </div>
                @else
                <br>
                @endcan
                
                <br>
                <div class="row">
                        
                    <div class="col-md-2"><strong>Estado:</strong></div>
                    <div class="col-md-4">{!! Form::select('affiliate_state_id', $affiliate_states, null, ['placeholder' => 'Seleccione un estado', 'class' => 'form-control',
                            'v-model' => 'form.affiliate_state_id' ,':disabled' => '!editing' ]) !!}</div>
                    <div class="col-md-2"><strong>Tipo:</strong></div>
                    <div class="col-md-4">{!! Form::select('type', ['Comando'=>'Comando','Batallón'=>'Batallón'], null, ['placeholder' => 'Seleccione un tipo', 'class' => 'form-control',
                            'v-model' => 'form.type' ,':disabled' => '!editing' ]) !!}</div>
                    
                </div>
                <br>
                <div class="row">
                    <div class="col-md-2"><strong>Fecha de Ingreso a la Institucional Policial:</strong></div>
                    <div class="col-md-4"><input type="text" class="form-control" v-model="form.date_entry" :disabled="!editing"></div>
                    <div class="col-md-2"><strong>Numero de Item:</strong></div>
                    <div class="col-md-4"><input type="text" class="form-control" v-model="form.item" :disabled="!editing"></div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-2"><strong>Categoria:</strong></div>
                    <div class="col-md-4">{!! Form::select('category_id', $categories, null, ['placeholder' => 'Seleccione una categoria',
                            'class' => 'form-control', 'v-model' => 'form.category_id' ,':disabled' => '!editing']) !!}</div>
                    <div class="col-md-2"><strong>Grado:</strong></div>
                    <div class="col-md-4">{!! Form::select('degree_id', $degrees, null, ['placeholder' => 'Seleccione un Grado', 'class' => 'form-control' , 'v-model'
                            => 'form.degree_id' ,':disabled' => '!editing' ]) !!}</div>
                </div>
                <br>
                <div class="row">
                    <div class="text-center" v-if="editing">
                        <button class="btn btn-danger" type="button" @click="toggle_editing()"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span class="bold">Cancelar</span></button>
                        <button class="btn btn-primary" type="button" @click="update"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
                    </div>
                </div>
                
        </div>
    </div>
   
</div>
