<template>
<div>
    <div v-if="editable">
        <div class="row"> 
            <div class="col-md-11"></div>
            <div class="col-md-1">
                <button class="btn btn-danger" type="button" v-on:click= "remove"> <i class="fa fa-trash"></i> </button>
            </div>
        </div>
        <br>
         <div class="row">
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Carnet de Identidad</label>
                </div>
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" v-model.trim="beneficiary.identity_card" ref="identity_card" name="beneficiary_identity_card[]" class="form-control">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="button" @click="searchBeneficiary" role="button"><i class="fa fa-search"></i></button>
                        </span>
                    </div><!-- /input-group -->
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Ciudad de Expedicion xd</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control" v-model.trim="beneficiary.city_identity_card_id" name="beneficiary_city_identity_card[]">
                        <option value="0">0option</option>
                        <option v-for="city in cities" :key="city.id" :value="city.id" >{{ city.name }}</option>
                    </select>
                </div>
            </div>    
        </div>
        <br>
        <div class="row" >
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Primer Nombre</label>
                </div>
                <div class="col-md-8">
                    <input type="text" v-model.trim="beneficiary.first_name" name="beneficiary_first_name[]"  class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Segundo Nombre</label>
                </div>
                <div class="col-md-8">
                    <input type="text" v-model.trim="beneficiary.second_name" name="beneficiary_second_name[]" class="form-control">
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Apellido Paterno</label>
                </div>
                <div class="col-md-8">
                    <input type="text" v-model.trim="beneficiary.last_name" name="beneficiary_last_name[]" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Apellido Materno</label>
                </div>
                <div class="col-md-8">
                    <input type="text" v-model.trim="beneficiary.mothers_last_name" name="beneficiary_mothers_last_name[]" class="form-control">
                </div>
            </div>
        </div>
        <br>
        <div class="row">
             <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Apellido de Casada</label>
                </div>
                <div class="col-md-8">
                    <input type="text" v-model.trim="beneficiary.surname_husband" name="surname_husband[]" class="form-control">
                </div>
            </div>
        </div>
       <br>
        <div class="row">
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Fecha de Nacimiento</label>
                </div>
                <div class="col-md-8">
                    <input type="date" v-model.trim="beneficiary.birth_date" name="beneficiary_birth_date[]" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Parentesco</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control " v-model.trim="beneficiary.kinship_id" name="beneficiary_kinship[]">
                        <option v-for="kinship in kinships" :key="beneficiary.id + ''+kinship.id " :value="kinship.id">{{kinship.name}}</option>
                    </select>
                </div>
            </div>
            
        </div>
        <div class="hr-line-dashed"></div>
    </div>
    <div v-else>
        <!-- <div class="row"> 
            <div class="col-md-11"></div>
            <div class="col-md-1">
                <button class="btn btn-danger" type="button" v-on:click= "remove"> <i class="fa fa-times"></i> </button>
            </div>
        </div> -->
        <div class="row" >
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Primer Nombre</label>
                </div>
                <div class="col-md-8">
                   <input type="text" v-model.trim="beneficiary.first_name" disabled   class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Segundo Nombre</label>
                </div>
                <div class="col-md-8">
                    <input type="text" v-model.trim="beneficiary.second_name" disabled  class="form-control">
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Apellido Paterno</label>
                </div>
                <div class="col-md-8">
                    <input type="text" v-model.trim="beneficiary.last_name" disabled  class="form-control">
            </div>
                </div>
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Apellido Materno</label>
                </div>
                <div class="col-md-8">
                    <input type="text" v-model.trim="beneficiary.mothers_last_name" disabled  class="form-control">
                </div>
            </div>
            
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Apellido de Casada</label>
                </div>
                <div class="col-md-8">
                    <input type="text" v-model.trim="beneficiary.surname_husband" disabled  class="form-control">
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Carnet de Identidad</label>
                </div>
                <div class="col-md-8">
                    <input type="text" v-model.trim="beneficiary.identity_card" ref="identity_card" disabled  class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Ciudad de Expedicion</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control" v-model.trim="beneficiary.city_identity_card_id" disabled >
                        <option v-for="city in cities" :key="city.id" :value="city.id" >{{ city.name }}</option>
                    </select>
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Fecha de Nacimiento</label>
                </div>
                <div class="col-md-8">
                    <input type="date" v-model.trim="beneficiary.birth_date" disabled  class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Parentesco</label>
                </div>
                <div class="col-md-8">
                    <select class="form-control m-b" v-model.trim="beneficiary.kinship_id" disabled >
                        <option v-for="kinship in kinships" :key="beneficiary.id + ''+kinship.id " :value="kinship.id">{{kinship.name}}</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="hr-line-dashed"></div>
        </div>
    </div>
</template>
<script>
export default {
  props: ["kinships", "cities", "beneficiary", "editable"],
  data() {
    return {};
  },
  mounted() {
    //this.$refs.identity_card.focus();
  },
  methods: {
    remove() {
      this.$emit("remove");
    },
    searchBeneficiary: function() {
      let ci = this.beneficiary.identity_card;
      axios
        .get("/search_ajax", {
          params: {
            ci
          }
        })
        .then(response => {
          let data = response.data;
          console.log(data);
          this.setDataBeneficiary(data);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    setDataBeneficiary(data) {
      this.beneficiary.first_name = data.first_name;
      this.beneficiary.second_name = data.second_name;
      this.beneficiary.last_name = data.last_name;
      this.beneficiary.mothers_last_name = data.mothers_last_name;
      this.beneficiary.surname_husband = data.surname_husband;
      this.beneficiary.identity_card = data.identity_card;
    //   if(data.city_identity_card_id!=null){
    //     this.beneficiary.city_identity_card_id = data.city_identity_card_id;
    //   }
    //   else 
      this.beneficiary.city_identity_card_id = 0;
      this.beneficiary.birth_date = data.birth_date;
      this.beneficiary.kinship_id = data.kinship_id;
    }
  }
};
</script>

