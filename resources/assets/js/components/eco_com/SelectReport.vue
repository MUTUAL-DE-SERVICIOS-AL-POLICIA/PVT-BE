<template>
  <div>
    <label for>Seleccione el Reporte</label>
    <select v-model="form.reportTypeId" :disabled="loadingButton">
      <option v-for="r in reportsType" :value="r.id" :key="r.id">{{r.name}}</option>
    </select>
    <div v-if="form.reportTypeId != 9 && form.reportTypeId != 21 && form.reportTypeId != 22 && form.reportTypeId != 29 && form.reportTypeId != 30">
      <label>Gestion</label>
      <select v-model="form.ecoComProcedureId" :disabled="loadingButton" @change="cargarplanilla()">
        <option v-for="r in ecoComProcedures" :value="r.id" :key="r.id">{{r.full_name}}</option>
      </select>
    </div>

    <div v-if="form.reportTypeId == 17">
      <label for="change-state">Tipo de Reporte: </label>
      <select id="change-state" v-model="form.changeState">
        <option value="0" selected>Todos los Trámites</option>
        <option value="1">Pago Directo</option>
        <option value="2">Pago Indirecto</option>
      </select>
    </div>

   <!-- <div v-if="form.reportTypeId == 18 && rol.id == 5">
      <label for="change-state">Actualizar Estados</label>
      <i class="fa fa-question-circle" title="Si marca esta opcion actualizara todos los tramites listados en el reporte a Enviado a Banco"></i>
      <input type="checkbox" id="change-state" v-model="form.changeState"/>
    </div>-->
    <div
      v-if="form.reportTypeId == 10 || form.reportTypeId == 11 || form.reportTypeId == 12 || form.reportTypeId == 13 || form.reportTypeId == 19 || form.reportTypeId == 20"
    >
      <label>Comparar Con:</label>
      <select v-model="form.secondEcoComProcedureId" :disabled="loadingButton">
        <option v-for="r in ecoComProceduresFilter" :value="r.id" :key="r.id">{{r.full_name}}</option>
      </select>
    </div>
    <br />
    <div
      v-if="form.reportTypeId == 5 || form.reportTypeId == 6 || form.reportTypeId == 7 || form.reportTypeId == 2 || form.reportTypeId == 3 || form.reportTypeId == 4 || form.reportTypeId == 15"
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
          </span>Seleccione el Reporte
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
    <div v-if="form.reportTypeId == 23 || form.reportTypeId == 24">
      <label for="change-date">Fecha (yyyy-mm-dd)</label>
      <input type="text" id="change-date" v-model="form.changeDate"/>
    </div>
    <div v-if="form.reportTypeId == 18" >
        <label for="banco">Lote diario</label><!--copiar-->
          <select id="banco" v-model="form.changeState" :disabled="loadingButton">
            <option v-for="b in banco" :value="b.procedure_date" :key="b.procedure_date">{{b.procedure_date}}</option>
          </select>
    </div>
    <div class="col-md-12">
      <div class="text-left m-sm" v-if="(form.reportTypeId == 26 || form.reportTypeId == 27) && rol.id == 5">
        <button class="btn btn-primary" type="button" @click="CambiarEstado()">
          <i v-if="loadingButton" class="fa fa-spinner fa-spin fa-fw" style="font-size:16px"></i>
          <i v-else class="fa fa-check-circle"></i>
          &nbsp;
          {{ loadingButton ? 'Cambiando estado a hatilitado...' : 'Cambiar a estado habilitado' }}
        </button>
      </div>
    </div>
    <div class="col-md-12">
      <div class="text-left m-sm" v-if="form.reportTypeId == 28 && rol.id == 5">
        <button class="btn btn-primary" type="button" @click="update()">
          <i v-if="loadingButton" class="fa fa-spinner fa-spin fa-fw" style="font-size:16px"></i>
          <i v-else class="fa fa-check-circle"></i>
          &nbsp;
          {{ loadingButton ? 'Actualizando Pagos en demasía...' : 'Actualizar Pagos en demasía' }}
        </button>
      </div>
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
  props: [
    "ecoComProcedures",
    "observationTypes",
    "affiliateObservations",
    "wfStates",
    "rol"
  ],
  data() {
    return {
      sigep: [],
      banco: [],
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
          id: 5,
          name: "Tramites con Pago por Unica Vez"
        },
        
       /* {
          id: 17,
          name: "Planilla General"
        },*/
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
          id: 20,
          name: "Comparacion de Concurrencias"
        },
        {
          id: 9,
          name: "Afiliados Observados"
        },
        {
          id: 14,
          name: "Afiliados por Etiquetas"
        },
        // {
        //   id: 9,
        //   name: "Doble Beneficio"
        // }
        {
          id: 21,
          name: "Afiliados por conyugue"
        },
        {
          id: 22,
          name: "Reporte de fallecidos"
        },
        {
          id: 23,
          name: "Calculo de promedios vejez"
        },
        {
          id: 24,
          name: "Calculo de promedios viudedad"
        },
        {
          id: 25,
          name: "Planilla general"
        },
        {
          id: 26,
          name: "Planilla de Pago Sigep"
        },
        {
          id: 27,
          name: "Planilla de Pago Banco Union"
        },
        {
          id: 18,
          name: "Para envío BANCO UNION"
        },
        //{
          // id: 28,
          // name: "Pagos en demasía"
        //},
        {
          id: 29,
          name: "Afiliados con documentos no escaneados"
        },
        {
          id: 30,
          name: "Afiliados con doble percepción del ultimo periodo"
        },
        {
          id: 32,
          name: "Afiliados fallecidos por semestre"
        }
      ],
      form: {
        ecoComProcedureId:
          this.ecoComProcedures.length > 0 ? this.ecoComProcedures[0].id : null
      }
    };
  },
   mounted() {
    this.cargarplanilla();
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
    },
    async CambiarEstado() {
      this.loadingButton = true;
      await axios({
        url: "/eco_com_estado",
        method: "POST",
        data: this.form
      })
        .then(response => {
          console.log(response);
        })
        .catch(error => {
          console.log(error);
       });
       this.loadingButton = false;
    },
    async update() {
      this.loadingButton = true;
      await axios({
        url: "/update_overpayments",
        method: "GET",
        data: this.form
      })
        .then(response => {
          console.log(response);
        })
        .catch(error => {
          console.log(error);
       });
       this.loadingButton = false;
    },
    async cargarplanilla() {
        console.log('Entro a cargar planilla');
        const formData = new FormData();
        formData.append("ecoComProcedureId", this.form.ecoComProcedureId);
        await axios
            .post("eco_com_import_planilla", formData)
            .then(response => {
                this.sigep = response.data['eco_com_sigep'];
                this.banco = response.data['eco_com_banco'];
            })
            .catch(error => {
               console.log(error);
        });
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
