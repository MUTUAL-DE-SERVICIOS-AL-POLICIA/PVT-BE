<template>
<div>
  <div class="row">
      <div class="row"> <button class="btn btn-danger" type="button" v-on:click= "remove"> <i class="fa fa-times"></i> </button></div>
    <div class="col-md-6">
        <div class="form-group"><label class="col-sm-4 control-label">Primer Nombre</label>
            <div class="col-sm-8"><input type="text" v-model="beneficiary.first_name" name="beneficiary_first_name[]"  class="form-control"></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group"><label class="col-sm-4 control-label">Segundo Nombre</label>
            <div class="col-sm-8"><input type="text" v-model="beneficiary.second_name" name="beneficiary_second_name[]" class="form-control"></div>
        </div>
    </div>
  </div>
  <div class="row">
      <div class="col-md-4">
          <div class="form-group"><label class="col-sm-6 control-label">Apellido Paterno</label>
              <div class="col-sm-6"><input type="text" v-model="beneficiary.last_name" name="beneficiary_last_name[]" class="form-control"></div>
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group"><label class="col-sm-6 control-label">Apellido Materno</label>
              <div class="col-sm-6"><input type="text" v-model="beneficiary.mothers_last_name" name="beneficiary_mothers_last_name[]" class="form-control"></div>
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group"><label class="col-sm-6 control-label">Apellido de Casada</label>
              <div class="col-sm-6"><input type="text" v-model="beneficiary.surname_husband" name="surname_husband[]" class="form-control"></div>
          </div>
      </div>
  </div>
  <div class="row">
      <div class="col-md-4">
          <div class="form-group"><label class="col-sm-6 control-label">Carnet de Identidad</label>
              <div class="col-sm-6"><input type="text" v-model="beneficiary.identity_card" ref="identity_card" name="beneficiary_identity_card[]" class="form-control">
                <button @click="searchBeneficiary" type="button" role="button"><i class="fa fa-search"></i></button>
              </div>
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group"><label class="col-sm-4 control-label">Ciudad de Expedicion</label>
              <div class="col-sm-8">
                  <select class="form-control m-b" v-model="beneficiary.city_identity_card" name="beneficiary_city_identity_card[]">
                      <option v-for="city in cities" :key="city.id" :value="city.id" >{{ city.name }}</option>
                  </select>
              </div>
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group"><label class="col-sm-6 control-label">Fecha de Nacimiento</label>
              <div class="col-sm-6"><input type="date" v-model="beneficiary.birth_date" name="beneficiary_birth_date[]" class="form-control"></div>
          </div>
      </div>
  </div>
  <div class="row">
      <div class="col-md-4">
          <div class="form-group"><label class="col-sm-4 control-label">Parentesco</label>
              <div class="col-sm-8">
                  <select class="form-control m-b" v-model="beneficiary.kinship" name="beneficiary_kinship[]">
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
      return{}
  },
  mounted(){
      this.$refs.identity_card.focus()
  },
  methods:{
      remove(){
        this.$emit('remove');
      },
    searchBeneficiary: function(){
      let ci= this.beneficiary.identity_card;
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
      this.beneficiary.first_name = data.first_name;
      this.beneficiary.second_name = data.second_name;
      this.beneficiary.last_name = data.last_name;
      this.beneficiary.mothers_last_name = data.mothers_last_name;
      this.beneficiary.surname_husband = data.surname_husband;
      this.beneficiary.identity_card = data.identity_card;
      this.beneficiary.city_identity_card = data.city_identity_card_id;
      this.beneficiary.birth_date = data.birth_date;
      this.beneficiary.kinship = data.kinship_id;
    },

  }

}
</script>

