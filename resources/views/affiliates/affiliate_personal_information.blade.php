<div class="col-lg-12">
    <div class="panel panel-primary" :class="show_spinner ? 'sk-loading' : ''">
        <div class="panel-heading">
            <h3 data-toggle="tooltip" data-placement="top" title="Ver Información del Affiliado - {{ $affiliate->fullName('capitalize') }}"
                class="pull-left"><a href="{{route('affiliate.show', $affiliate->id)}}" style="color: #fff">Información Personal</a></h3>
            @can('update',$affiliate)
            <div class="text-right">
                <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing"><i class="fa" :class="editing ?'fa-unlock':'fa-lock'" ></i> </button>
            </div>
            @else
            <br> @endcan
        </div>
        <div v-if="! editing ">
            <br>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-5    ">
                    <strong>Cedula de identidad: </strong> @{{ form.identity_card }} @{{ city_identity_card_name }}
                </div>
                <div class="col-md-5">
                    <strong>Fecha de Nacimiento: </strong>@{{ form.birth_date }}</div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <strong>Primer Nombre: </strong>@{{ form.first_name}}</div>
                <input type="text" class="form-control" v-model="first_name.value" v-show="first_name.edit ==  true">
                <div class="col-md-5">
                    <strong>Segundo Nombre: </strong> @{{ form.second_name }}</div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <strong>Apellido Paterno: </strong>@{{ form.last_name }}</div>
                <div class="col-md-5">
                    <strong>Apellido Materno: </strong>@{{ form.mothers_last_name }}</div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <strong>Apellido de Casada: </strong>@{{ form.surname_husband }}</div>
                <!-- <dt v-show="affiliate.gender === 'F'">Apellido de Casada:</dt> <dd v-show="affiliate.gender === 'F'">@{{ form.surname_husband }}</dd> -->
                <div class="col-md-5">
                    <strong>Genero: </strong>@{{ gender_name }}</div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <strong>Estado Civil: </strong>@{{ civil_status_name }}</div>
                <div class="col-md-5">
                    <strong>Fecha de Nacimiento: </strong>@{{ form.birth_date }}</div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <strong>Edad: </strong> @{{ age }}</div>
                <div class="col-md-5">
                    <strong>Lugar de Nacimiento: </strong> @{{ city_birth_name}}</div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                    <strong>Telefono: </strong>@{{ form.phone_number }}</div>
                <div class="col-md-5">
                    <strong>Celular: </strong>@{{ form.cell_phone_number }}</div>
            </div>
            <br>
        </div>
        @can('update',$affiliate)
        <div class="panel-body" v-else>
            <div class="sk-folding-cube" v-show="show_spinner">
                <div class="sk-cube1 sk-cube"></div>
                <div class="sk-cube2 sk-cube"></div>
                <div class="sk-cube4 sk-cube"></div>
                <div class="sk-cube3 sk-cube"></div>
            </div>
            {{--
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
            <div class="col-md-6">
                <dl class="dl-">
                    <dt>Cedula de identidad:</dt>
                    <dd><input type="text" v-model="form.identity_card" class="form-control"> {!! Form::select('city_identity_card_id',
                        $cities, null, ['placeholder' => 'Seleccione la expedición del ci', 'class' => 'form-control','v-model'
                        => 'form.city_identity_card_id']) !!}
                    </dd>
                    <dt><label>Primer Nombre:</label></dt>
                    <dd><input type="text" v-model="form.first_name" class="form-control"></dd>
                    <dt>Segundo Nombre:</dt>
                    <dd><input type="text" v-model="form.second_name" class="form-control"></dd>
                    <dt>Apellido Paterno:</dt>
                    <dd><input type="text" v-model="form.last_name" class="form-control"></dd>
                    <dt>Apellido Materno:</dt>
                    <dd><input type="text" v-model="form.mothers_last_name" class="form-control"></dd>
                    <dt v-show="affiliate.gender === 'F'">Apellido de Casada:</dt>
                    <dd v-show="affiliate.gender === 'F'"><input type="text" class="form-control"></dd>
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="dl-">
                    <dt>Genero:</dt>
                    <dd>
                        {!! Form::select('gender', ['F'=>'Femenino','M'=>'Masculino'], null, ['placeholder' => 'Seleccione genero', 'class' => 'form-control','v-model'
                        => 'form.gender']) !!}
                    </dd>
                    <dt>Estado Civil:</dt>
                    <dd>
                        {!! Form::select('civil_status', ['C'=>'Casado(a)','S'=>'Soltero(a)','V'=>'Viuido(a)','D'=>'Divorciado(a)'], null, ['placeholder'
                        => 'Seleccione estado civil', 'class' => 'form-control','v-model' => 'form.civil_status']) !!}

                    </dd>

                    <dt>Fecha de Nacimiento:</dt>
                    <dd>
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input v-model="form.birth_date"
                                type="text" class="form-control">
                        </div>
                    </dd>
                    <dt>Edad:</dt>
                    <dd><input v-model="age" type="text" class="form-control" disabled></dd>
                    <dt>Lugar de Nacimiento:</dt>
                    <dd>{!! Form::select('city_birth_id', $birth_cities, null , ['placeholder' => 'Seleccione la expedición del
                        ci', 'class' => 'form-control','v-model'=>'form.city_birth_id']) !!}</dd>
                    <dt>Telefono:</dt>
                    <dd><input type="text" v-model="form.phone_number" class="form-control"></dd>
                    <dt>Celular:</dt>
                    <dd><input type="text" v-model="form.cell_phone_number" class="form-control"></dd>
                </dl>
            </div>
        </div>
        <div v-show="editing" class="panel-footer">
            <div class="text-center">
                <button class="btn btn-danger" type="button" @click="toggle_editing()"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span class="bold">Cancelar</span></button>
                <button class="btn btn-primary" type="button" @click="update"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
            </div>
        </div>
        @endcan
    </div>
</div>