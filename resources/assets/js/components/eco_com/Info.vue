<template></template>

<script>
export default {
  props: [
    "affiliate",
    "ecoCom",
    "ecoComProcedure",
    "degrees",
    "categories",
    "pensionEntities",
    "states",
    "cities"
  ],
  data() {
    return {
      read: true,
      form: {
        id: this.ecoCom.id,
        reception_date: this.ecoCom.reception_date,
        pension_entity_id: this.affiliate.pension_entity_id,
        procedure_state_id: this.ecoCom.procedure_state_id,
        city_id: this.ecoCom.city_id,
        degree_id: this.ecoCom.degree_id,
        category_id: this.ecoCom.category_id
      },
      editing: false,
      show_spinner: false,
      clone: {}
    };
  },
  mounted() {
    this.clone = JSON.parse(JSON.stringify(this.form));
  },
  methods: {
    toggleEditing() {
      this.editing = !this.editing;
      this.clone = JSON.parse(JSON.stringify(this.form));
    },
    cancel() {
      this.form = this.clone;
      this.editing = false;
    },
    update() {
      let uri = `/economic_complement_update_information`;
      this.show_spinner = true;
      axios
        .patch(uri, this.form)
        .then(response => {
          console.log(response)
          if(response.status == 204 ){
            window.location = `/eco_com_process/${this.ecoCom.eco_com_process_id}`
          }
          this.editing = false;
          this.show_spinner = false;
          this.form = response.data;
          flash("Informacion Actualizada");
        })
        .catch(response => {
          flash(
            "Error al actualizar Informacion: " + response.message,
            "error"
          );
          this.show_spinner = false;
        });
    }
  }
};
</script>

<style>
</style>
