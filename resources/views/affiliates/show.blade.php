@extends('layouts.app')

@section('title', 'Afiliados')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{ Breadcrumbs::render('show_affiliate', $affiliate) }}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row text-center">
        <a href="{{route('create_ret_fun', $affiliate->id)}}" data-toggle="tooltip" data-placement="top" title="Iniciar tr&aacute;mite de FONDO DE RETIRO">
            <button class="btn btn-info btn-sm dim" type="button" ><i class="fa fa-paste"></i> </button>
        </a>
        <a href="{{route('create_quota_aid', $affiliate->id)}}" data-toggle="tooltip" data-placement="top" title="Initar tr&aacute;mite de CUOTA Y AUXILIO MORTUORIO">
            <button class="btn btn-info btn-sm  dim" type="button"><i class="fa fa-paste"></i> </button>
        </a>
    </div>
    <div class="row">
        <div class="col-md-6">
            <affiliate-show  :affiliate="{{ $affiliate }}" inline-template> 
                    <div class="col-lg-12">
                        <div class="panel panel-primary" :class="show_spinner ? 'sk-loading' : ''">
                            <div class="panel-heading">
                                <h3 class="pull-left">Información Personal</h3>
                                <div class="text-right">
                                    <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing"><i class="fa" :class="editing ?'fa-unlock':'fa-lock'" ></i> </button>
                                </div>
                            </div>
                            <div class="panel-body " v-if="! editing " >
                                <div class="col-md-6">
                                    <dl class="dl-">
                                        <dt>Cedula de identidad:</dt> <dd>{{ $affiliate->identity_card }}  {{ !!$affiliate->city_identity_card ? $affiliate->city_identity_card->first_shortened : '' }}</dd>
                                        <dt>Primer Nombre:</dt> <dd>{{ $affiliate->first_name }}</dd>
                                        <input type="text" class="form-control" v-model="first_name.value" v-show="first_name.edit ==  true">
                                        <dt>Segundo Nombre:</dt> <dd>{{ $affiliate->second_name }}</dd>
                                        <dt>Apellido Paterno:</dt> <dd>{{ $affiliate->last_name }}</dd>
                                        <dt>Apellido Materno:</dt> <dd>{{ $affiliate->mothers_last_name }}</dd>
                                        <dt v-show="affiliate.gender === 'F'">Apellido de Casada:</dt> <dd v-show="affiliate.gender === 'F'">{{ $affiliate->surname_husband }}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-6">
                                    <dl class="dl-">
                                        <dt>Genero:</dt> <dd>{{ $affiliate->gender }}</dd>
                                        <dt>Estado Civil:</dt> <dd>{{ $affiliate->civil_status }}</dd>
                                        <dt>Fecha de Nacimiento:</dt> <dd>{{ $affiliate->birth_date }}</dd>
                                        <dt>Edad:</dt> <dd> @{{ age }} </dd>
                                        <dt>Lugar de Nacimiento:</dt> <dd>{{ !!$affiliate->city_birth ? $affiliate->city_birth->name : '' }}</dd>
                                        <dt>Telefono:</dt> <dd>{{ $affiliate->phone_number }}</dd>
                                        <dt>Celular:</dt> <dd>{{ $affiliate->cell_phone_number }}</dd>
                                    </dl>
                                </div>
                            </div>
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
                                            {!! Form::select('city_identity_card_id', $cities, null, ['placeholder' => 'Seleccione la expedicion del ci', 'class' => 'form-control']) !!}
                                        </dd>
                                        <dt>Primer Nombre:</dt> <dd><input type="text" v-model="form.first_name" class="form-control"></dd>
                                        <dt>Segundo Nombre:</dt> <dd><input type="text" v-model="form.second_name" class="form-control"></dd>
                                        <dt>Apellido Paterno:</dt> <dd><input type="text" v-model="form.last_name" class="form-control"></dd>
                                        <dt>Apellido Materno:</dt> <dd><input type="text" v-model="form.mothers_last_name" class="form-control"></dd>
                                        <dt v-show="affiliate.gender === 'F'">Apellido de Casada:</dt> <dd v-show="affiliate.gender === 'F'"><input type="text" class="form-control"></dd>
                                    </dl>
                                </div>
                                <div class="col-md-6">
                                    <dl class="dl-">
                                        <dt>Genero:</dt> <dd><input type="text" v-model="form.gender" class="form-control"></dd>
                                        <dt>Estado Civil:</dt> <dd><input  type="text"  v-model="form.civil_status" class="form-control"></dd>
                                        
                                        <dt>Fecha de Nacimiento:</dt> <dd>
                                            <div class="input-group date" >
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input  v-model="form.birth_date" type="text" class="form-control">
                                            </div>
                                            </dd>
                                        <dt>Edad:</dt> <dd><input v-model="age" type="text" class="form-control" disabled></dd>
                                        <dt>Lugar de Nacimiento:</dt> <dd><input v-model="form.birth_date" type="text" class="form-control"></dd>
                                        <dt>Telefono:</dt> <dd><input type="text" v-model="form.phone_number" class="form-control"></dd>
                                        <dt>Celular:</dt> <dd><input type="text" v-model="form.cell_phone_number" class="form-control"></dd>
                                    </dl>
                                </div>
                            </div>
                            <div v-show="editing" class="panel-footer">
                                <div class="text-center">
                                    <button class="btn btn-danger" type="button" @click="toggle_editing()"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span class="bold">Cancelar</span></button>
                                    <button class="btn btn-primary" type="button" @click="update"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
                                </div>
                            </div>
                        </div>
                    </div>

            </affiliate-show> 

        </div>
        <div class="col-md-6">
            <affiliate-police :affiliate="{{ $affiliate }}" inline-template>
                @include('affiliates.affiliate_police_information', ['affiliate'=>$affiliate])
            </affiliate-police>
        </div>
{{--<div class="col-md-6">
                    <affiliate-police  :affiliate="{{ json_encode($affiliate) }}"></affiliate-police>
                    
                </div> 
        
--}}
        <div class="col-md-6">
            @include('affiliates.information_of_the_procedure')                     
        </div>    
    </div>

</div>

@endsection
