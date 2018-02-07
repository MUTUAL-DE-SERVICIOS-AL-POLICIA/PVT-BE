<template>
<div>
  <div class="row">
    <div class="col-md-6">
        <div class="form-group"><label class="col-sm-4 control-label">Primer Nombre</label>
            <div class="col-sm-8"><input type="text" name="beneficiary_first_name[]" :value="beneficiary.first_shortened" class="form-control"></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group"><label class="col-sm-4 control-label">Segundo Nombre</label>
            <div class="col-sm-8"><input type="text" name="beneficiary_second_name[]" class="form-control"></div>
        </div>
    </div>
  </div>
  <div class="row">
      <div class="col-md-4">
          <div class="form-group"><label class="col-sm-6 control-label">Apellido Paterno</label>
              <div class="col-sm-6"><input type="text" name="beneficiary_last_name[]" class="form-control"></div>
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group"><label class="col-sm-6 control-label">Apellido Materno</label>
              <div class="col-sm-6"><input type="text" name="beneficiary_mothers_last_name[]" class="form-control"></div>
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group"><label class="col-sm-6 control-label">Apellido de Casada</label>
              <div class="col-sm-6"><input type="text" name="surname_husband[]" class="form-control"></div>
          </div>
      </div>
  </div>
  <div class="row">
      <div class="col-md-4">
          <div class="form-group"><label class="col-sm-6 control-label">Carnet de Identidad</label>
              <div class="col-sm-6"><input type="text" name="beneficiary_identity_card[]" class="form-control"></div>
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group"><label class="col-sm-4 control-label">Ciudad de Expedicion</label>
              <div class="col-sm-8">
                  <select class="form-control m-b" name="beneficiary_city_identity_card[]">
                      <option v-for="city in cities" :key="city.id" :value="beneficiary.city_identity_card_id" >{{ city.name }}</option>
                  </select>
              </div>
          </div>
      </div>
      <div class="col-md-4">
          <div class="form-group"><label class="col-sm-6 control-label">Fecha de Nacimiento</label>
              <div class="col-sm-6"><input type="date" name="beneficiary_birth_date[]" class="form-control"></div>
          </div>
      </div>
  </div>
  <div class="row">
      <div class="col-md-4">
          <div class="form-group"><label class="col-sm-4 control-label">Parentesco</label>
              <div class="col-sm-8">
                  <select class="form-control m-b" v-model="beneficiary_kinship" name="beneficiary_kinship[]">
                      <option v-for="kinship in kinships" :key="beneficiary.id + ''+kinship.id " :value="beneficiary.kinship_id">{{kinship.name}}</option>
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
          beneficiary_kinship:''
      }
  },
  mounted(){

  }
}
</script>

