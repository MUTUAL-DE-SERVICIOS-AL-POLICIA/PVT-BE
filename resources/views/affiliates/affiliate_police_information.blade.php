@php($role = \Muserpol\Helpers\Util::getRol()->id)
@php($module = \Muserpol\Helpers\Util::getRol()->module_id)
<div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-content">
                <div class="pull-left"> <legend > Información Policial</legend></div>
                @can('update',$affiliate)
                <div class="text-right">
                    <button
                        data-animation="flip"
                        class="btn btn-primary"
                        :class="editing ? 'active': ''"
                        @click="toggle_editing"
                    >
                        <i class="fa" :class="editing ?'fa-edit':'fa-pencil'" ></i> Editar
                    </button>
                </div>
                @else
                <br>
                <br>
                @endcan
                <br>
                <div class="row">
                    <div class="col-md-2"><strong>Estado:</strong></div>
                    <div class="col-md-4">
                        {!! Form::select( 'affiliate_state_id', $affiliate_states, null,
                             ['placeholder' => 'Seleccione un estado', 'class' => 'form-control',
                            'v-model' => 'form.affiliate_state_id',
                            ':disabled' => '!editing || !validationRoles('.$module.', '.$role.', '.$wf_current_state.')'
                             ]
                            )
                        !!}
                    </div>
                    <div class="col-md-2"><strong>Tipo:</strong></div>
                    <div class="col-md-4">{!! Form::select('type', ['Comando'=>'Comando','Batallón'=>'Batallón'], null, ['placeholder' => 'Seleccione un tipo', 'class' => 'form-control',
                            'v-model' => 'form.type' ,':disabled' => '!editing' ]) !!}</div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-2"><strong>Fecha de Ingreso a la Institucional Policial:</strong></div>
                    <div class="col-md-4"><input type="text" class="form-control" v-model="form.date_entry" v-month-year :disabled="!editing || !(editing && ({{ intval($module == 3) }} || {{ intval($module == 4) }} || {{ intval($role == 5) }} ) )"></div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-2"><strong>Categor&iacute;a:</strong></div>
                    <div class="col-md-4">
                        {!!
                            Form::select('category_id', $categories, null, [
                                'placeholder' => 'Seleccione una categoria',
                                'class' => 'form-control',
                                'v-model' => 'form.category_id',
                                ':disabled' => 'true'
                            ])
                        !!}
                    </div>
                    <div class="col-md-2"><strong>Grado:</strong></div>
                    <div class="col-md-4">
                        {!!
                            Form::select('degree_id', $degrees, null, [
                                'placeholder' => 'Seleccione un Grado',
                                'class' => 'form-control',
                                'v-model' => 'form.degree_id',
                                ':disabled' => '!editing || !validationRoles('.$module.', '.$role.', '.$wf_current_state.')'
                            ])
                        !!}
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-2"><label class="control-label">Años de servicio</label></div>
                    <div class="col-md-4">
                        <input
                            type="number"
                            v-model="form.service_years"
                            name="service_years"
                            class="form-control"
                            :disabled="!editing || !validationRoles('{{$module}}', '{{$role}}', '{{$wf_current_state}}')"
                            @change="getCalculateCategory()"
                            v-validate="'min_value:0|max_value:100'"
                            max="100"
                            min="0"
                        >
                        <div v-show="errors.has('service_years') && editing" >
                            <i class="fa fa-warning text-danger"></i>
                            <span class="text-danger">@{{ errors.first('service_years') }}</span>
                        </div>
                    </div>
                    <div class="col-md-2"><label class="control-label">Ente gestor:</label></div>
                    <div class="col-md-4">
                        {!! Form::select('pension_entity_id', $pension_entities, null, ['placeholder' => 'Seleccione el ente gestor', 'class' => 'form-control','v-model'=> 'form.pension_entity_id',':disabled'=>'!editing || !validationRoles('.$module.', '.$role.', '.$wf_current_state.')' ]) !!}
                        <div v-show="errors.has('pension_entity_id') && editing" >
                            <i class="fa fa-warning text-danger"></i>
                            <span class="text-danger">@{{ errors.first('pension_entity_id') }}</span>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-2"><label class="control-label">Meses de servicio</label></div>
                    <div class="col-md-4">
                        <input type="number" name="service_months" v-model="form.service_months" class="form-control" :disabled="!editing || !validationRoles('{{$module}}', '{{$role}}', '{{$wf_current_state}}')" @change="getCalculateCategory()" v-validate="'min_value:0|max_value:11'" min="0" max="11">
                        <div v-show="errors.has('service_months') && editing" >
                            <i class="fa fa-warning text-danger"></i>
                            <span class="text-danger">@{{ errors.first('service_months') }}</span>
                        </div>
                    </div>
                    <div class="col-md-2"><strong>Fecha de Desvinculaci&oacute;n:</strong></div>
                    <div class="col-md-4"><input type="text" class="form-control" v-model="form.date_derelict" v-month-year :disabled="!editing"></div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-2"><strong>Último periodo según listas de revista:  </strong></div><!--aqui -->
                    <!-- <div class="col-md-4"><input type="text" class="form-control" v-model="form.date_last_contribution" v-month-year :disabled="!editing" ></div> -->
                    <div class="col-md-4">{!! Form::text('date_last_contribution', null, ['v-month-year','class' => 'form-control' , 'v-model'
                        => 'form.date_last_contribution' ,':disabled' => '!editing || !((editing && '.$role .' == 12) || (editing && '.$role.' == 39))' ]) !!}</div>
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
