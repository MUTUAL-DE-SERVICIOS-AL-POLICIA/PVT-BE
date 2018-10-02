<template>
    <div>
        <div class="row">
            <div class="col-md-6">
                <label class="label-control ">Ubicacion</label>
                <ReportFormMultipleSelect label="Ubicacion"
                                        :list="wfStates"
                                        v-model="wfState"
                                        :itemsSelected="wfStatesSelected"></ReportFormMultipleSelect>
            </div>
            <div class="col-md-6">
                <label class="label-control ">Ciudad</label>
                <ReportFormMultipleSelect
                v-model="city"
                label="Ciudad"
                                        :list="cities"
                                        :itemsSelected="wfStatesSelected"></ReportFormMultipleSelect>
            </div>
        </div>
        <div class="row text-center">
            <button @click="fetch()" class="btn btn-primary">Guardar</button>
        </div>
    </div>
</template>

<script>
    import ReportFormMultipleSelect from "./ReportFormMultipleSelect.vue";
    export default {
      components: {
        ReportFormMultipleSelect
      },
      props: ["wfStates", "cities"],
      data() {
        return {
            city: null,
            wfState: null,
            wfStatesSelected: [28, 26]
        };
      },
      methods:{
          fetch(){
              console.log("fetch");
          }
      }
    };
</script>

<style>
</style>
