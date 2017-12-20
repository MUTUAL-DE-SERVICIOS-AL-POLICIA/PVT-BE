<template>
    <div class="col-lg-12">
        <code>{{ editing }}</code>
        <div class="panel panel-primary" :class="show_spinner ? 'sk-loading' : ''">
            <div class="panel-heading">
                <h3 class="pull-left">Información Personal</h3>
                <div class="text-right">
                    <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing()"><i class="fa" :class="editing ?'fa-unlock':'fa-lock'" ></i> </button>
                </div>
            </div>
            <div class="panel-body " v-if="! editing " >
                <div class="col-md-6">
                    <dl class="dl-">
                        <dt>Cedula de identidad:</dt> <dd>{{ affiliate.identity_card }}  {{ !!affiliate.city_identity_card ? affiliate.city_identity_card.first_shortened : '' }}</dd>
                        <dt>Primer Nombre:</dt> <dd @dbclick="first_name.edit = true">{{ first_name.value }}</dd>
                        <input type="text" class="form-control" v-model="first_name.value" v-show="first_name.edit ==  true">
                        <dt>Segundo Nombre:</dt> <dd>{{ affiliate.second_name }}</dd>
                        <dt>Apellido Paterno:</dt> <dd>{{ affiliate.last_name }}</dd>
                        <dt>Apellido Materno:</dt> <dd>{{ affiliate.mothers_last_name }}</dd>
                        <dt v-show="affiliate.gender == 'F'">Apellido de Casada:</dt> <dd v-show="affiliate.gender == 'F'">{{ affiliate.surname_husband }}</dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="dl-">
                        <dt>Genero:</dt> <dd>{{ affiliate.gender }}</dd>
                        <dt>Estado Civil:</dt> <dd>{{ affiliate.civil_status }}</dd>
                        <dt>Fecha de Nacimiento:</dt> <dd>{{ affiliate.birth_date }}</dd>
                        <dt>Edad:</dt> <dd>{{ affiliate.birth_date }}</dd>
                        <dt>Lugar de Nacimiento:</dt> <dd>{{ !!affiliate.city_birth ? affiliate.city_birth.name : '' }}</dd>
                        <dt>Telefono:</dt> <dd>{{ affiliate.phone_number }}</dd>
                        <dt>Celular:</dt> <dd>{{ affiliate.cell_phone_number }}</dd>
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
                <!-- <div class="sk-fading-circle" v-show="show_spinner" >
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
                </div> -->
                <div class="col-md-6">
                    <dl class="dl-">
                        <dt>Cedula de identidad:</dt> <dd><input type="text" v-model="form.identity_card" class="form-control">  <select name="" id="" class="form-control">
                            <option value="lp">LP</option>
                        </select></dd>
                        <dt>Primer Nombre:</dt> <dd><input type="text" v-model="form.first_name" class="form-control"></dd>
                        <dt>Segundo Nombre:</dt> <dd><input type="text" v-model="form.second_name" class="form-control"></dd>
                        <dt>Apellido Paterno:</dt> <dd><input type="text" v-model="form.last_name" class="form-control"></dd>
                        <dt>Apellido Materno:</dt> <dd><input type="text" class="form-control"></dd>
                        <dt v-show="affiliate.gender == 'F'">Apellido de Casada:</dt> <dd v-show="affiliate.gender == 'F'"><input type="text" cl ass="form-control"></dd>
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="dl-">
                        <dt>Genero:</dt> <dd><input type="text" v-model="form.gender" class="form-control"></dd>
                        <dt>Estado Civil:</dt> <dd><input type="text" class="form-control"></dd>
                        <dt>Fecha de Nacimiento:</dt> <dd><input type="text" class="form-control"></dd>
                        <dt>Edad:</dt> <dd><input type="text" class="form-control"></dd>
                        <dt>Lugar de Nacimiento:</dt> <dd><input type="text" class="form-control"></dd>
                        <dt>Telefono:</dt> <dd><input type="text" class="form-control"></dd>
                        <dt>Celular:</dt> <dd><input type="text" class="form-control"></dd>
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
</template>
<script>
	export default{
		props:[
			'affiliate'
		],
        data(){
            return{
                editing: false,
                show_spinner: false,
                form:{
                    identity_card: this.affiliate.identity_card,
                    first_name: this.affiliate.first_name,
                    second_name: this.affiliate.second_name,
                    last_name: this.affiliate.last_name,
                    mothers_last_name: this.affiliate.mothers_last_name,
                    gender: this.affiliate.gender,
                },
                first_name:{
                    value: this.affiliate.first_name,
                    edit: false,
                }
            }
        },
        methods:{
            edit_first_name: function(){
                console.log(this.first_name.value)
            },
            toggle_editing:function () {
                this.editing = !this.editing;
            },
            update () {
                let uri = `/update_affiliate/${this.affiliate.id}`;
                this.show_spinner=true;
                axios.patch(uri,this.form)
                    .then(()=>{
                        this.editing = false;
                        this.show_spinner=false;
                        flash('Informacion del Afiliado Actualizada');
                    }).catch((response)=>{
                        this.show_spinner=false;
                        flash('Error al actualizar el afiliado: '+response.message,'error');
                    })
            }
        }
	}
</script>