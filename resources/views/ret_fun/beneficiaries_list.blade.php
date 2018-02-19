<div class="row">
    <div class="col-md-12">
        <ret-fun-beneficiaries-show :beneficiaries="{{ $beneficiaries }}" :original_beneficiaries="{{ $beneficiaries }}" :cities="{{$cities}}" :kinships="{{$kinships}}" inline-template> 
                <div class="col-lg-12">
                    <div class="panel panel-primary" :class="show_spinner ? 'sk-loading' : ''">
                        <div class="panel-heading">
                            <h3 class="pull-left">Beneficiarios</h3>
                            <div class="text-right">
                                <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing"><i class="fa" :class="editing ?'fa-unlock':'fa-lock'" ></i> </button>
                            </div>
                        </div>
                      <template v-for="(beneficiary, iterator) in beneficiaries">
                          

                        <div class="panel-body " v-if="! editing " >                            
                            <div class="col-md-6">
                                <dl class="dl-">
                                    <dt>Cedula de identidad:</dt> <dd>@{{ beneficiary.identity_card }}  @{{ getCity(beneficiary.city_identity_card_id) }}</dd>
                                    <dt>Primer Nombre:</dt> <dd>@{{ beneficiary.first_name }}</dd>                                   
                                    <dt>Segundo Nombre:</dt> <dd>@{{ beneficiary.second_name }}</dd>
                                    <dt>Apellido Paterno:</dt> <dd>@{{ beneficiary.last_name }}</dd>
                                    <dt>Apellido Materno:</dt> <dd>@{{ beneficiary.mothers_last_name }}</dd>
                                    <dt v-show="beneficiary.gender === 'F'">Apellido de Casada:</dt> <dd v-show="beneficiary.gender === 'F'">@{{ beneficiary.surname_husband }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="dl-">
                                    <dt>Parentesco:</dt> <dd>@{{ getKinship(beneficiary.kinship_id) }}</dd>
                                    <dt>Genero:</dt> <dd>@{{ beneficiary.gender }}</dd>
                                    <dt>Estado Civil:</dt> <dd>@{{ beneficiary.civil_status }}</dd>
                                    <dt>Fecha de Nacimiento:</dt> <dd>@{{ beneficiary.birth_date }}</dd>
                                    <dt>Edad:</dt> <dd> @{{ age  }} </dd>                                    
                                    <dt>Telefono:</dt> <dd>@{{ beneficiary.phone_number }}</dd>
                                    <dt>Celular:</dt> <dd>@{{ beneficiary.cell_phone_number }}</dd>
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
                     
                            <div class="col-md-6">
                                <dl class="dl-">
                                    <dt>Cedula de identidad:</dt> <dd><input type="text" v-model="ben[iterator].identity_card" class="form-control">
                                        <select class="form-control m-b" v-model='ben[iterator].city_identity_card_id'>
                                            <option v-for="city in cities" 
                                                    :selected="city.id == beneficiary.city_identity_card_id ? 'selected' : ''"
                                                    :value="city.id">
                                                @{{ city.name }}
                                            </option>
                                        </select>
                                    </dd>
                                    <dt>Primer Nombre:</dt> <dd><input type="text" v-model="ben[iterator].first_name" class="form-control"></dd>
                                    <dt>Segundo Nombre:</dt> <dd><input type="text" v-model="ben[iterator].second_name" class="form-control"></dd>
                                    <dt>Apellido Paterno:</dt> <dd><input type="text" v-model="ben[iterator].last_name" class="form-control"></dd>
                                    <dt>Apellido Materno:</dt> <dd><input type="text" v-model="ben[iterator].mothers_last_name" class="form-control"></dd>
                                    <dt v-show="beneficiary.gender === 'F'">Apellido de Casada:</dt> <dd v-show="beneficiary.gender === 'F'"><input type="text" class="form-control"></dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="dl-">
                                    <dt>Parentesco:</dt> 
                                    <select class="form-control m-b" v-model="ben[iterator].kinship_id">
                                            <option v-for="kinship in kinships" 
                                                    :selected="kinship.id == beneficiary.kinship_id ? 'selected' : ''"
                                                    :value="kinship.id">
                                                @{{ kinship.name }}
                                            </option>
                                    </select>
                                    <dt>Estado Civil:</dt> <dd><input  type="text"  class="form-control" v-model="ben[iterator].civil_status"></dd>

                                    <dt>Fecha de Nacimiento:</dt> <dd>
                                        <div class="input-group date" >
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input  type="text" v-model="ben[iterator].birth_date" class="form-control">
                                        </div>
                                        </dd>
                                    <dt>Edad:</dt> <dd><input  type="text" class="form-control" disabled></dd>
                                    
                                    <dt>Telefono:</dt> <dd><input type="text" v-model="ben[iterator].phone_number" class="form-control"></dd>
                                    <dt>Celular:</dt> <dd><input type="text" v-model="ben[iterator].cell_phone_number" class="form-control"></dd>
                                </dl>
                            </div>
                        </div>
                        <hr>
                      </template>
                        <div v-show="editing" class="panel-footer">
                            <div class="text-center">
                                <button class="btn btn-danger" type="button" @click="toggle_editing()"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span class="bold">Cancelar</span></button>
                                <button class="btn btn-primary" type="button" @click="update"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
        </ret-fun-beneficiaries-show> 
    </div>
</div>