<div class="col-lg-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="pull-left">Información del Policia</h3>
            <div class="text-right">
                <button data-animation="flip" class="btn btn-primary"><i class="fa" ></i> </button>
            </div>
        </div>
        @if ($retirement_fund->modality_id == 1)
        <div class="panel-body ">
            <div class="col-md-6">
                <dl class="dl-">
                    <dt>Cédula de identidad:</dt>
                    <dd>{{ $affiliate->identity_card }}</dd>
                    <dt>Primer Nombre:</dt>
                    <dd>{{ $affiliate->first_name }}</dd>
                    <dt>Segundo Nombre:</dt>
                    <dd>{{ $affiliate->second_name }}</dd>
                    <dt>Apellido Paterno:</dt>
                    <dd>{{ $affiliate->last_name }}</dd>
                    <dt>Apellido Materno:</dt>
                    <dd>{{ $affiliate->mothers_last_name }}</dd>
                    <dt>Apellido de Casada:</dt>
                    <dd>{{ $affiliate->surname_husband }}</dd>
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="dl-">
                    <dt>Genero:</dt>
                    <dd>{{ $affiliate->gender }}</dd>
                    <dt>Estado Civil:</dt>
                    <dd>{{ $affiliate->civil_status }}</dd>
                    <dt>Fecha de Nacimiento:</dt>
                    <dd>{{ $affiliate->birth_date }}</dd>
                    <dt>Edad:</dt>
                    <dd>{{ $affiliate->birth_date }}</dd>
                    <dt>Lugar de Nacimiento:</dt>
                    <dd>{{ !!$affiliate->city_birth ? $affiliate->city_birth->name : '' }}</dd>
                    <dt>Teléfono:</dt>
                    <dd>{{ $affiliate->phone_number }}</dd>
                    <dt>Celular:</dt>
                    <dd>{{ $affiliate->cell_phone_number }}</dd>
                </dl>
            </div>
        </div>
        @else
        <div class="panel-body ">
            <div class="col-md-6">
                <dl class="dl-">
                    <dt>Cédula de identidad:</dt>
                    <dd>{{ $applicant->identity_card }}</dd>
                    <dt>Primer Nombre:</dt>
                    <dd>{{ $applicant->first_name }}</dd>
                    <dt>Segundo Nombre:</dt>
                    <dd>{{ $applicant->second_name }}</dd>
                    <dt>Apellido Paterno:</dt>
                    <dd>{{ $applicant->last_name }}</dd>
                    <dt>Apellido Materno:</dt>
                    <dd>{{ $applicant->mothers_last_name }}</dd>
                    <dt>Apellido de Casada:</dt>
                    <dd>{{ $applicant->surname_husband }}</dd>
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="dl-">
                    <dt>Genero:</dt>
                    <dd>{{ $applicant->gender }}</dd>
                    <dt>Estado Civil:</dt>
                    <dd>{{ $applicant->civil_status }}</dd>
                    <dt>Fecha de Nacimiento:</dt>
                    <dd>{{ $applicant->birth_date }}</dd>
                    <dt>Edad:</dt>
                    <dd>{{ $applicant->birth_date }}</dd>
                    <dt>Lugar de Nacimiento:</dt>
                    <dd>{{ !!$applicant->city_birth ? $applicant->city_birth->name : '' }}</dd>
                    <dt>Teléfono:</dt>
                    <dd>{{ $applicant->phone_number }}</dd>
                    <dt>Celular:</dt>
                    <dd>{{ $applicant->cell_phone_number }}</dd>
                </dl>
            </div>
        </div>
        @endif {{--
        <div class="panel-body" v-else>
            <div class="sk-folding-cube" v-show="show_spinner">
                <div class="sk-cube1 sk-cube"></div>
                <div class="sk-cube2 sk-cube"></div>
                <div class="sk-cube4 sk-cube"></div>
                <div class="sk-cube3 sk-cube"></div>
            </div>
            <div class="col-md-6">
                <dl class="dl-">
                    <dt>Cédula de identidad:</dt>
                    <dd><input type="text" v-model="form.identity_card" class="form-control"> {!! Form::select('city_identity_card_id',
                        $cities, null, ['placeholder' => 'Seleccione la expedicion del ci', 'class' => 'form-control']) !!}
                    </dd>
                    <dt>Primer Nombre:</dt>
                    <dd><input type="text" v-model="form.first_name" class="form-control"></dd>
                    <dt>Segundo Nombre:</dt>
                    <dd><input type="text" v-model="form.second_name" class="form-control"></dd>
                    <dt>Apellido Paterno:</dt>
                    <dd><input type="text" v-model="form.last_name" class="form-control"></dd>
                    <dt>Apellido Materno:</dt>
                    <dd><input type="text" class="form-control"></dd>
                    <dt v-show="affiliate.gender === 'F'">Apellido de Casada:</dt>
                    <dd v-show="affiliate.gender === 'F'"><input type="text" class="form-control"></dd>
                </dl>
            </div>
            <div class="col-md-6">
                <dl class="dl-">
                    <dt>Genero:</dt>
                    <dd><input type="text" v-model="form.gender" class="form-control"></dd>
                    <dt>Estado Civil:</dt>
                    <dd><input type="text" class="form-control"></dd>
                    <dt>Fecha de Nacimiento:</dt>
                    <dd><input type="text" class="form-control"></dd>
                    <dt>Edad:</dt>
                    <dd><input type="text" class="form-control"></dd>
                    <dt>Lugar de Nacimiento:</dt>
                    <dd><input type="text" class="form-control"></dd>
                    <dt>Teléfono:</dt>
                    <dd><input type="text" class="form-control"></dd>
                    <dt>Celular:</dt>
                    <dd><input type="text" class="form-control"></dd>
                </dl>
            </div>
        </div>
        <div v-show="editing" class="panel-footer">
            <div class="text-center">
                <button class="btn btn-danger" type="button" @click="toggle_editing()"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span class="bold">Cancelar</span></button>
                <button class="btn btn-primary" type="button" @click="update"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
            </div>
        </div> --}}
    </div>
</div>
