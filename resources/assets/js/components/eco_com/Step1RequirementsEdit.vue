<template>
  <div class="ibox">
    <div class="ibox-content">
      <form>
        <requirement-edit-select :requirements="requirements" :store="store" :is-review="isReview"
    review-field="isArchiveReview"></requirement-edit-select>
      </form>
    </div>
  </div>
</template>
<script>

export default {
  props: [
    "affiliate",
    "ecoCom",
    "requirements",
    'rol'
  ],
  computed: {
    isReview() {
      return this.rol === 108;
    }
  },
  methods: {
    store(requirements, additionalRequirements = null) { // Se pasa la referencia de la funcion al componente hijo
      if (!this.isReview) {
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
      } else {
        let uri = `/eco_com/${this.ecoCom.id}/archive_review`;
        axios
          .post(uri, {
            submit_documents: requirements
          })
          .then(response => {
            flash("Verificacion Correcta");
            location.reload();
          })
          .catch(error => {
            flash("Los Datos no Coincidensss", "error");
          });
      }
    }
  }
};
</script>