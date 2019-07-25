<template>
  <div>
    <label for>Seleccione el Reporte</label>
    <select v-model="form.reportTypeId" :disabled="loadingButton">
      <option v-for="r in reportsType" :value="r.id" :key="r.id">{{r.name}}</option>
    </select>
    <div v-if="form.reportTypeId != 9">
      <label>Gestion</label>
      <select v-model="form.ecoComProcedureId" :disabled="loadingButton">
        <option v-for="r in ecoComProcedures" :value="r.id" :key="r.id">{{r.full_name}}</option>
      </select>
    </div>
    <div v-if="form.reportTypeId == 18 && rol.id == 5">
      <label for="change-state">Actualizar Estados</label>
      <i class="fa fa-question-circle" title="Si marca esta opcion actualizara todos los tramites listados en el reporte a Enviado a Banco"></i>
      <input type="checkbox" id="change-state" v-model="form.changeState"/>
    </div>
    <div
      v-if="form.reportTypeId == 10 || form.reportTypeId == 11 || form.reportTypeId == 12 || form.reportTypeId == 13 || form.reportTypeId == 19"
    >
      <label>Comparar Con:</label>
      <select v-model="form.secondEcoComProcedureId" :disabled="loadingButton">
        <option v-for="r in ecoComProceduresFilter" :value="r.id" :key="r.id">{{r.full_name}}</option>
      </select>
    </div>
    <br />
    <div
      v-if="form.reportTypeId == 6 || form.reportTypeId == 7 || form.reportTypeId == 2 || form.reportTypeId == 3 || form.reportTypeId == 4 || form.reportTypeId == 15"
    >
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
        </template>-->
      </multiselect>
    </div>
    <!-- <div v-if="form.reportTypeId == 9 ">
      <multiselect
        v-model="form.affiliateObservationsId"
        :options="affiliateObservations"
        :multiple="true"
        :close-on-select="false"
        :clear-on-select="false"
        :hide-selected="true"
        :disabled="loadingButton"
        placeholder="Seleccione la observacion"
        track-by="id"
        :show-labels="false"
        label="name"
        :max="1"
      ></multiselect>
    </div>-->

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
  props: [
    "ecoComProcedures",
    "observationTypes",
    "affiliateObservations",
    "wfStates",
    "rol"
  ],
  data() {
    return {
      loadingButton: false,
      reportsType: [
        {
          id: 1,
          name: "Todos los Tramites"
        },
        {
          id: 4,
          name: "Tramites Observados"
        },
        {
          id: 15,
          name: "Tramites por Estado"
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
          id: 17,
          name: "Planilla General"
        },
        {
          id: 18,
          name: "Planilla BANCO UNION"
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
          id: 16,
          name: "Tramites por Etiqueta"
        },
        {
          id: 8,
          name: "Tramites Eliminados"
        },
        {
          id: 10,
          name: "Comparacion de Grado"
        },
        {
          id: 11,
          name: "Comparacion de Categoria"
        },
        {
          id: 12,
          name: "Comparacion de Promedio"
        },
        {
          id: 13,
          name: "Comparacion de Componentes SIP"
        },
        {
          id: 19,
          name: "Comparacion de Datos Personales"
        },
        {
          id: 9,
          name: "Afiliados Observados"
        },
        {
          id: 14,
          name: "Afiliados por Etiquetas"
        }
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
  },
  computed: {
    ecoComProceduresFilter() {
      return this.ecoComProcedures.filter(x => {
        return x.id != this.form.ecoComProcedureId;
      });
    }
  }
};
</script>

<style>
</style>
