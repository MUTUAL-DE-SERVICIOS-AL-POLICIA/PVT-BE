<template>
  <div class="ibox">
    <div class="ibox-content">
      <form>
        <requirement-edit-select :requirements="requirements" :store="store"></requirement-edit-select>
      </form>
    </div>
  </div>
</template>
<script>
export default {
  props: [
    "affiliate",
    "ecoCom",
    "submitted",
    "requirements",
  ],
  methods: {
    store(requirements, additionalRequirements = null) { // Se pasa la referencia de la funcion al componente hijo
      let uri = `/eco_com/${this.ecoCom.id}/edit_requirements`;
      axios
        .post(uri, {
          requirements,
          additional_requirements: additionalRequirements
        })
        .then(response => {
          if (response.status == 200) {
            flash("Verificacion Correcta");
            location.reload();
          }
        })
        .catch(error => {
          console.log(error);
          flash("Los Datos no Coinciden", "error");
        });
    }
  }
};
</script>