<template></template>

<script>
export default {
  props: ["ecoComProcess", "ecoComs"],
  mounted() {
    console.log(this.ecoComs);
  },
  data() {
    return {
      search: null,
      headers: [
        {
          text: "# Trámite",
          value: "code"
        },
        // {'text' : "CI Titular", 'align' : "left", 'value' : "ci"},
        { text: "Modalidad", value: "eco_com_state.name" },
        { text: "Regional", value: "city.name" },
        { text: "Fecha de Recepción", value: "date_reception" },
        { text: "Ubicación", value: "wf_state.name" },
        { text: "Estado", value: "procedure_state.name" },
        { text: "Total", value: "total" }
      ]
    };
  },
  methods: {
    rowClick(id) {
        window.location=`/economic_complement/${id}`;
        
    }
  }
};
</script>

<style>
</style>
