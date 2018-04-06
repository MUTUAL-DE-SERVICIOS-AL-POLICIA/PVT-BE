<div class="col-lg-12">
    <div class="panel panel-primary" :class="show_spinner ? 'sk-loading' : ''">
        <div class="panel-heading">
            <h3 class="pull-left">Información Esposa</h3>
            {{-- @can('update',$affiliate) --}}
            <div class="text-right">
                <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing"><i class="fa" :class="editing ?'fa-unlock':'fa-lock'" ></i> </button>
            </div>
            {{-- @else
            <br>
            @endcan --}}
        </div>
        <div class="panel-body " v-if="! editing " >
            <div class="col-md-6">
                <dl class="dl-">
                    <dt>Cedula de identidad:</dt> <dd>@{{ form.identity_card }}  @{{ city_identity_card_name }}</dd>
                    <dt>Registro:</dt> <dd>@{{ form.registration}}</dd>
                    <dt>Primer Nombre:</dt> <dd>@{{ form.first_name}}</dd>
                    <input type="text" class="form-control" v-model="first_name.value" v-show="first_name.edit ==  true">
                    <dt>Segundo Nombre:</dt> <dd> @{{ form.second_name }}</dd>
                    <dt>Apellido Paterno:</dt> <dd>@{{ form.last_name }}</dd>
                    <dt>Apellido Materno:</dt> <dd>@{{ form.mothers_last_name }}</dd>
                    <dt>Apellido Casada(o):</dt> <dd>@{{ form.surname_husband }}</dd>
                    {{-- <dt v-show="affiliate.gender === 'F'">Apellido de Casada:</dt> <dd v-show="affiliate.gender === 'F'">@{{ form.surname_husband }}</dd> --}}
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="dl-">
                    
                    <dt>Estado Civil:</dt> <dd>@{{ civil_status_name }}</dd>
                    <dt>Fecha de Nacimiento:</dt> <dd>@{{ form.birth_date }}</dd>
                    <dt>Edad:</dt> <dd> @{{ age  }} </dd>
                    <dt>Lugar de Nacimiento:</dt> <dd> @{{ form.city_birth_id }}</dd>
                </dl>
            </div>
        </div>
        {{-- @can('update',$spouse) --}}
        <div class="panel-body" v-else>
            <div class="sk-folding-cube" v-show="show_spinner" >
                <div class="sk-cube1 sk-cube"></div>
                <div class="sk-cube2 sk-cube"></div>
                <div class="sk-cube4 sk-cube"></div>
                <div class="sk-cube3 sk-cube"></div>
            </div>
            {{-- <div class="sk-fading-circle" v-show="show_spinner" >
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
            <div class="col-md-6">
                <dl class="dl-">
                    <dt>Cedula de identidad:</dt> <dd><input type="text" v-model="form.identity_card" class="form-control">
                        {!! Form::select('city_identity_card_id', $cities, null, ['placeholder' => 'Seleccione la expedicion del ci', 'class' => 'form-control','v-model' => 'form.city_identity_card_id']) !!}
                    </dd>
                    <dt><label>Registro:</label></dt> <dd><input type="text" v-model="form.registration" class="form-control"></dd>
                    <dt><label>Primer Nombre:</label></dt> <dd><input type="text" v-model="form.first_name" class="form-control"></dd>
                    <dt>Segundo Nombre:</dt> <dd><input type="text" v-model="form.second_name" class="form-control"></dd>
                    <dt>Apellido Paterno:</dt> <dd><input type="text" v-model="form.last_name" class="form-control"></dd>
                    <dt>Apellido Materno:</dt> <dd><input type="text" v-model="form.mothers_last_name" class="form-control"></dd>
                    <dt>Apellido Casada(o):</dt> <dd><input type="text" v-model="form.surname_husband" class="form-control"></dd>
                    {{-- <dt v-show="affiliate.gender === 'F'">Apellido de Casada:</dt> <dd v-show="affiliate.gender === 'F'"><input type="text" class="form-control"></dd> --}}
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="dl-">
                    {{-- <dt>Genero:</dt> <dd>
                        {!! Form::select('gender', ['F'=>'Femenino','M'=>'Masculino'], null, ['placeholder' => 'Seleccione genero', 'class' => 'form-control','v-model' => 'form.gender']) !!}
                                    </dd> --}}
                    <dt>Estado Civil:</dt> <dd>
                        {!! Form::select('civil_status', ['C'=>'Casado(a)','S'=>'Soltero(a)','V'=>'Viuido(a)','D'=>'Divorciado(a)'], null, ['placeholder' => 'Seleccione estado civil', 'class' => 'form-control','v-model' => 'form.civil_status']) !!}
                          
                                           </dd>

                    <dt>Fecha de Nacimiento:</dt> <dd>
                        <div class="input-group date" >
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input  v-model="form.birth_date" type="text" class="form-control">
                        </div>
                        </dd>
                    <dt>Edad:</dt> <dd><input v-model="age" type="text" class="form-control" disabled></dd>
                    <dt>Lugar de Nacimiento:</dt> <dd>{!! Form::select('city_birth_id', $birth_cities, null , ['placeholder' => 'Seleccione la expedicion del ci', 'class' => 'form-control','v-model'=>'form.city_birth_id']) !!}</dd>
                    {{-- <dt>Telefono:</dt> <dd><input type="text" v-model="form.phone_number" class="form-control"></dd>
                    <dt>Celular:</dt> <dd><input type="text" v-model="form.cell_phone_number" class="form-control"></dd> --}}
                </dl>
            </div>
        </div>
        <div v-show="editing" class="panel-footer">
            <div class="text-center">
                <button class="btn btn-danger" type="button" @click="toggle_editing()"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span class="bold">Cancelar</span></button>
                <button class="btn btn-primary" type="button" @click="update"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
            </div>
        </div>
        {{-- @endcan --}}
    </div>
</div>