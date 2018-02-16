<template>
<div>
  <div class="row">
    <div class="col-md-6">
        <div class="form-group"><label class="col-sm-4 control-label">Primer Nombre</label>
            <div class="col-sm-8"><input type="text" v-model="beneficiary_first_name" name="beneficiary_first_name[]"  class="form-control"></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group"><label class="col-sm-4 control-label">Segundo Nombre</label>
            <div class="col-sm-8"><input type="text" v-model="beneficiary_second_name" name="beneficiary_second_name[]" class="form-control"></div>
        </div>
    </div>
  </div>
  <div class="row">
      <div class="col-md-4">
          <div class="form-group"><label class="col-sm-6 control-label">Apellido Paterno</label>
              <div class="col-sm-6"><input type="text" v-model="beneficiary_last_name" name="beneficiary_last_name[]" class="form-control"></div>
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group"><label class="col-sm-6 control-label">Apellido Materno</label>
              <div class="col-sm-6"><input type="text" v-model="beneficiary_mothers_last_name" name="beneficiary_mothers_last_name[]" class="form-control"></div>
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group"><label class="col-sm-6 control-label">Apellido de Casada</label>
              <div class="col-sm-6"><input type="text" v-model="beneficiary_surname_husband" name="surname_husband[]" class="form-control"></div>
          </div>
      </div>
  </div>
  <div class="row">
      <div class="col-md-4">
          <div class="form-group"><label class="col-sm-6 control-label">Carnet de Identidad</label>
              <div class="col-sm-6"><input type="text" v-model="beneficiary_identity_card" name="beneficiary_identity_card[]" class="form-control">
                <button @click="searchBeneficiary" type="button" role="button"><i class="fa fa-search"></i></button>
              </div>
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group"><label class="col-sm-4 control-label">Ciudad de Expedicion</label>
              <div class="col-sm-8">
                  <select class="form-control m-b" v-model="beneficiary_city_identity_card" name="beneficiary_city_identity_card[]">
                      <option v-for="city in cities" :key="city.id" :value="city.id" >{{ city.name }}</option>
                  </select>
              </div>
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group"><label class="col-sm-6 control-label">Fecha de Nacimiento</label>
              <div class="col-sm-6"><input type="date" v-model="beneficiary_birth_date" name="beneficiary_birth_date[]" class="form-control"></div>
          </div>
      </div>
  </div>
  <div class="row">
      <div class="col-md-4">
          <div class="form-group"><label class="col-sm-4 control-label">Parentesco</label>
              <div class="col-sm-8">
                  <select class="form-control m-b" v-model="beneficiary_kinship" name="beneficiary_kinship[]">
                      <option v-for="kinship in kinships" :key="beneficiary.id + ''+kinship.id " :value="kinship.id">{{kinship.name}}</option>
                  </select>
              </div>
          </div>
      </div>
  </div>
  <div class="hr-line-dashed"></div>
</div>
</template>
<script>
export default {
  props:[
      'kinships',
      "cities",
      "beneficiary"
  ],
  data(){
      return{
        beneficiary_kinship:'',
        beneficiary_first_name:'',
        beneficiary_second_name:'',
        beneficiary_last_name:'',
        beneficiary_mothers_last_name:'',
        beneficiary_surname_husband:'',
        beneficiary_identity_card:'',
        beneficiary_city_identity_card:'',
        beneficiary_birth_date:'',
      }
  },
  methods:{
    searchBeneficiary: function(){
      let ci= this.beneficiary_identity_card;
      axios.get('/search_ajax', {
        params: {
          ci
        }
      })
      .then( (response) => {
        let data = response.data;
        console.log(data);
        
        this.setDataBeneficiary(data);
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    setDataBeneficiary(data){
      this.beneficiary_first_name = data.first_name;
      this.beneficiary_second_name = data.second_name;
      this.beneficiary_last_name = data.last_name;
      this.beneficiary_mothers_last_name = data.mothers_last_name;
      this.beneficiary_surname_husband = data.surname_husband;
      this.beneficiary_identity_card = data.identity_card;
      this.beneficiary_city_identity_card = data.city_identity_card_id;
      this.beneficiary_birth_date = data.birth_date;
      this.beneficiary_kinship = data.kinship_id;
    },

  }

}
</script>

