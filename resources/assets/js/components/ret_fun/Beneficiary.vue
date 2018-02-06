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
                      <option v-for="city in cities" :value="city.id" >{{ city.name }}</option>
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
                  <select class="form-control m-b" name="beneficiary_kinship[]">
                      <option value="1">option 1</option>
                      <option value="2">option 2</option>
                      <option value="3">option 3</option>
                      <option value="4">option 4</option>
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
      "cities",
      "beneficiary"
  ],
  mounted(){

  }
}
</script>

