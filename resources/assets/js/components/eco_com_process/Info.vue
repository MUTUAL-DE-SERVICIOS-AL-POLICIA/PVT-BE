<script>
export default {
  props: ["ecoComProcess", "states", "read", 'pensionEntities'],
  data() {
    return {
      editing: false,
      form: this.ecoComProcess,
      values: {
        reception_date: this.ecoComProcess.reception_date
      }
    };
  },
  created() {
    //   console.log(this.read);
  },
  methods: {
    toggle_editing: function() {
      this.editing = !this.editing;
    //   if (this.editing == false) {
    //     this.form.reception_date = this.values.reception_date;
    //     this.form.procedure_modality_id = this.procedure_modality.id;
    //   }
    },

    update: function() {
      let uri = `/eco_com_process_update_information`;
      this.show_spinner = true;
      axios
        .patch(uri, this.form)
        .then(response => {
          this.editing = false;
          this.show_spinner = false;
          this.form = response.data.eco_com_process;
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
