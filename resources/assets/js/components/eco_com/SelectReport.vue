<template>
  <div>
    <label for>Seleccione el Reporte</label>
    <select v-model="form.reportTypeId" :disabled="loadingButton">
      <option v-for="r in reportsType" :value="r.id" :key="r.id">{{r.name}}</option>
    </select>
    <label>Gestion</label>
    <select v-model="form.ecoComProcedureId" :disabled="loadingButton">
      <option v-for="r in ecoComProcedures" :value="r.id" :key="r.id">{{r.full_name}}</option>
    </select>
    <br />
    <div v-if="form.reportTypeId == 4">
      <multiselect
        v-model="form.observationTypeIds"
        :options="observationTypes"
        :multiple="true"
        :close-on-select="false"
        :clear-on-select="false"
        :hide-selected="true"
        :disabled="loadingButton"
        placeholder="Seleccione la observacion"
        track-by="id"
        :show-labels="false"
        label="name"
      >
        <!-- :custom-label="customLabel" -->
        <!-- <template slot="tag" slot-scope="props">
          <span class="custom__tag">
            <span>{{ props.option }}</span>
            <span class="custom__remove" @click="props.remove(props.option)">❌</span>
          </span>
        </template> -->
      </multiselect>
    </div>
    <div v-if="form.reportTypeId == 6 || form.reportTypeId == 7">
      <multiselect
        v-model="form.wfCurrentStateIds"
        :options="wfStates"
        :multiple="true"
        :close-on-select="false"
        :clear-on-select="false"
        :hide-selected="true"
        :disabled="loadingButton"
        placeholder="Seleccione la Ubicacion"
        track-by="id"
        :show-labels="false"
        label="name"
      >
        <!-- :custom-label="customLabel" -->
        <!-- <template slot="tag" slot-scope="props">
          <span class="custom__tag">
            <span>{{ props.option }}</span>
            <span class="custom__remove" @click="props.remove(props.option)">❌</span>
          </span>
        </template> -->
      </multiselect>
    </div>
    <div class="col-md-12">
      <div class="text-center m-sm">
        <button class="btn btn-primary" type="button" @click="send()" :disabled="loadingButton">
          <i v-if="loadingButton" class="fa fa-spinner fa-spin fa-fw" style="font-size:16px"></i>
          <i v-else class="fa fa-check-circle"></i>
          &nbsp;
          {{ loadingButton ? 'Generando...' : 'Generar' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";
export default {
  components: {
    Multiselect
  },
  props: ["ecoComProcedures", "observationTypes", 'wfStates'],
  data() {
    return {
      loadingButton: false,
      reportsType: [
        {
          id: 1,
          name: "Todos los Tramites"
        },
        {
          id: 2,
          name: "Tramites con Concurrencia"
        },
        {
          id: 3,
          name: "Tramites con Apoderados"
        },
        {
          id: 4,
          name: "Tramites Observados Por:"
        },
        // {
        //   id: 5,
        //   name: "Tramites con Etiquetas"
        // },
        {
          id: 6,
          name: "Tramites sin Validar de:"
        },
        {
          id: 7,
          name: "Tramites Validados de:"
        },
        {
          id: 8,
          name: "Tramites Eliminados"
        },
        // {
        //   id: 9,
        //   name: "Doble Beneficio"
        // }
      ],
      form: {
        ecoComProcedureId:
          this.ecoComProcedures.length > 0 ? this.ecoComProcedures[0].id : null
      }
    };
  },
  methods: {
    async send() {
      this.loadingButton = true;
      await axios({
        url: "/eco_com_report_excel",
        method: "POST",
        responseType: "blob", // important
        headers: { Accept: "application/vnd.ms-excel" },
        data: this.form
      })
        // .post("eco_com_report_excel", this.form)
        .then(response => {
          console.log(response);
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement("a");
          link.href = url;
          link.setAttribute("download", "file.xlsx");
          document.body.appendChild(link);
          link.click();
        })
        .catch(error => {
          console.log(error);
        });
      this.loadingButton = false;
    }
  }
};
</script>

<style>
</style>
