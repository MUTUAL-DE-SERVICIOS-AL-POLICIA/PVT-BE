<div class="col-md-12">
<<<<<<< HEAD
    <div class="ibox">
        <div class="ibox-content">
                <div  class="pull-left">
                    <legend>Fondo de Retiro</legend>
=======
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="pull-left">Información del trámite</h3>
            @can('update',new Muserpol\Models\RetirementFund\RetirementFund)
            <div class="text-right">
                <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing"><i class="fa" :class="editing ?'fa-unlock':'fa-lock'" ></i> </button>
            </div>
            @else
            <br> @endcan
        </div>
    
      
            {{--
            <div class="sk-folding-cube" v-show="show_spinner">
                <div class="sk-cube1 sk-cube"></div>
                <div class="sk-cube2 sk-cube"></div>
                <div class="sk-cube4 sk-cube"></div>
                <div class="sk-cube3 sk-cube"></div>
            </div> --}} {{--
            <div class="sk-fading-circle" v-show="show_spinner">
                <div class="sk-circle1 sk-circle"></div>
                <div class="sk-circle2 sk-circle"></div>
                <div class="sk-circle3 sk-circle"></div>
                <div class="sk-circle4 sk-circle"></div>
                <div class="sk-circle5 sk-circle"></div>
                <div class="sk-circle6 sk-circle"></div>
                <div class="sk-circle7 sk-circle"></div>
                <div class="sk-circle8 sk-circle"></div>
                <div class="sk-circle9 sk-circle"></div>
                <div class="sk-circle10 sk-circle"></div>
                <div class="sk-circle11 sk-circle"></div>
                <div class="sk-circle12 sk-circle"></div>
            </div> --}}
            <br>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    <strong> Modalidad:</strong>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" v-model="procedure_modality_name" disabled="">
                </div>
                <div class="col-md-2">
                    <strong>Ciudad de Recepcion:</strong>
                </div>
                <div class="col-md-3">
                    {!! Form::select('city_start_id', $cities, null , ['placeholder' => 'Seleccione cuidad', 'class' => 'form-control','v-model'=>'form.city_start_id',':disabled'=>'!editing'])
                    !!}
                </div>
                <div class="col-md-1"></div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    <strong> Fecha de Recepcion:</strong>&nbsp;
                </div>
                <div class="col-md-3">
                    @if(Session::get('rol_id') == 28)
                        <input type="date" v-model="form.reception_date" class="form-control" > 
                    @else
                        <input type="date" v-model="form.reception_date" class="form-control" disabled> 
                    @endif
>>>>>>> upstream/master
                </div>
                @can('update',new Muserpol\Models\RetirementFund\RetirementFund)
                <div class="text-right">
                    <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing"><i class="fa" :class="editing ?'fa-edit':'fa-pencil'" ></i> Editar </button>
                </div>
                @else
                <br>
                    @endcan
                <br>
                
                   
                <div class="row">
                    {{-- <div class="col-md-1"></div> --}}
                    <div class="col-md-2">
                        <strong> Modalidad:</strong>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" v-model="procedure_modality_name" disabled="">
                    </div>
                    <div class="col-md-2">
                        <strong>Ciudad de Recepcion:</strong>
                    </div>
                    <div class="col-md-4">
                        {!! Form::select('city_start_id', $cities, null , ['placeholder' => 'Seleccione cuidad', 'class' => 'form-control','v-model'=>'form.city_start_id',':disabled'=>'!editing'])
                        !!}
                    </div>
                    {{-- <div class="col-md-1"></div> --}}
                </div>
                <br>
                <div class="row">
                    {{-- <div class="col-md-1"></div> --}}
                    <div class="col-md-2">
                        <strong> Fecha de Recepcion:</strong>&nbsp;
                    </div>
                    <div class="col-md-4">
                        @if(Session::get('rol_id') == 28)
                            <input type="date" v-model="form.reception_date" class="form-control" > 
                        @else
                            <input type="date" v-model="form.reception_date" class="form-control" disabled> 
                        @endif
                    </div>
                    <div class="col-md-2">
                        <strong>Regional:</strong>&nbsp;
                    </div>
                    <div class="col-md-4">
                        {!! Form::select('city_end_id', $cities, null , ['placeholder' => 'Seleccione cuidad', 'class' => 'form-control','v-model'=>'form.city_end_id',':disabled'=>'!editing'])
                        !!}
                    </div>
                    {{-- <div class="col-md-1"></div> --}}
                </div>
                <br>
                <div class="row">
                    {{-- <div class="col-md-1"></div> --}}
                    <div class="col-md-2">
                        <strong>Estado:</strong>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control" v-model="form.ret_fun_state_id" ref="modality" name="ret_fun_state_id" :disabled='!editing' >
                            <option v-for="(state, index) in states" :value="state.id" :key="index">@{{state.name}}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
        
                    </div>
                    {{-- <div class="col-md-1"></div> --}}
                </div>
                <br>
            
                <div v-show="editing">
                    <div class="text-center">
                        <button class="btn btn-danger" type="button" @click="toggle_editing()"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span class="bold">Cancelar</span></button>
                        <button class="btn btn-primary" type="button" @click="update"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
                    </div>
                </div>
                <br>
        </div>
    </div>
</div>