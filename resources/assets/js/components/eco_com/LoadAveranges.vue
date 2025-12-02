<template>
  <div class="row">
    <div class="col-md-12">

      <div class="ibox ibox-e-margins">
        <div class="ibox-title">
          <h2 class="text-primary">Promedios de Rentas - Pensiones y Límite Referencial</h2>
        </div>

        <div class="ibox-content">

          <!-- BOTÓN DESCARGAR FORMATO -->
          <div class="mb-4 text-right">
            <button @click="exportDegreesWithPrestations()" class="btn btn-primary btn-sm">
              <i class="fa fa-download"></i> Descargar Formato planilla CSV
            </button>
          </div>

          <!-- BLOQUE PARA SUBIR ARCHIVO -->
          <div class="card p-3 mb-4">
            <h4 class="mb-3">
              <i class="fa fa-upload"></i> Importar datos de un período
            </h4>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label>Seleccione el período</label>
                <select class="form-control" v-model="upload.ecoComProcedureId">
                  <option disabled value="">Seleccione...</option>
                  <option v-for="p in ecoComProcedures" :key="p.id" :value="p.id">
                    {{ p.full_name }}
                  </option>
                </select>
              </div>
              <div class="form-group col-md-5">
                <label>Archivo CSV</label>
                <input class="form-control" type="file" id="file-upload" :disabled="loadingButton" />
              </div>
              <div class="form-group col-md-3 d-flex align-items-end">
                <label></label>
                <button class="btn btn-success btn-block mb-0" @click="importAverage()"
                  :disabled="loadingButton || !upload.ecoComProcedureId">
                  <i v-if="loadingButton" class="fa fa-spinner fa-spin"></i>
                  <i v-else class="fa fa-save"></i>
                  {{ loadingButton ? ' Procesando...' : ' Importar Período' }}
                </button>
              </div>
            </div>
          </div>

          <!-- SELECT DE VISUALIZACIÓN -->
          <hr class="hr" />
          <div class="card p-3 mb-4">
            <div class="form-row">
              <div class="form-group col-md-4">
                <label>Seleccione el período para visualizar</label>
                <select class="form-control" v-model="form.ecoComProcedureId">
                  <option disabled value="">Seleccione...</option>
                  <option v-for="r in ecoComProcedures" :key="r.id" :value="r.id">
                    {{ r.full_name }}
                  </option>
                </select>
              </div>
            </div>
          </div>


          <!-- TABLA -->
          <table class="table table-bordered table-hover mt-3">
            <thead class="thead-light">
              <tr>
                <th class="text-center">Id. grado</th>
                <th class="text-center">Grado</th>
                <th class="text-left">Prestación</th>
                <th class="text-left">Límite Referencial</th>
                <th class="text-left">Promedio</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="row in averageData" :key="row.id">
                <td class="text-center">{{ row.degree.correlative }}</td>
                <td class="text-center">{{ row.degree.name }}</td>
                <td>{{ row.procedure_modality.name }}</td>
                <td>{{ row.referential_limit }}</td>
                <td>{{ row.average }}</td>
              </tr>
            </tbody>
          </table>

        </div>
      </div>

    </div>
  </div>
</template>


<script>
export default {
  props: ["permissions", "ecoComProcedures"],

  data() {
    return {
      loadingButton: false,

      form: {
        ecoComProcedureId: null,
        year: "",
        semester: ""
      },
      upload: {
        ecoComProcedureId: null,
        year: "",
        semester: ""
      },

      averageData: [],
      ecoComProcedureId: null
    };
  },

  mounted() {
    // Cargar el primer elemento apenas se abra la página
    if (this.ecoComProcedures.length > 0) {
      this.form.ecoComProcedureId = this.ecoComProcedures[0].id;
    }
  },

  watch: {
    "form.ecoComProcedureId"(newVal) {
      const record = this.ecoComProcedures.find(r => r.id === newVal);

      if (record) {
        this.form.semester = record.semester;
        this.form.year = record.year.substring(0, 4);
        this.generateAverage();
      }
    },
    "upload.ecoComProcedureId"(newVal) {
      const record = this.ecoComProcedures.find(r => r.id === newVal);

      if (record) {
        this.upload.ecoComProcedureId = record.id;
        this.upload.semester = record.semester;
        this.upload.year = record.year.substring(0, 4);
      }
    },
  },

  methods: {
    async generateAverage() {
      if (!this.form.year || !this.form.semester) return;
      this.loadingButton = true;
      await axios.get("ecocomrents", {
        params: {
          year: this.form.year,
          semester: this.form.semester
        }
      })
        .then(res => {
          this.averageData = res.data;
        })
        .catch(err => {
          console.error(err);
          flash("Error al generar promedios", "error");
        })
        .finally(() => {
          this.loadingButton = false;
        });
    },

    async importAverage() {
      this.loadingButton = true;
      const fileInput = document.getElementById("file-upload");
      if (!fileInput.files.length) {
        flash("Seleccione un archivo CSV", "error");
        this.loadingButton = false;
        return;
      }
      let formData = new FormData();
      formData.append("file", fileInput.files[0]);
      formData.append("ecoComProcedureId", this.upload.ecoComProcedureId);
      formData.append("semester", this.upload.semester);
      formData.append("year", this.upload.year);
      try {
        const res = await axios.post("import_averages", formData, {
          headers: { "Content-Type": "multipart/form-data" }
        });

        flash("Importado correctamente");
        this.generateAverage();

      } catch (err) {
        console.error(err.response.data);
        flash(err.response.data.message, "error");
      } finally {
        this.loadingButton = false;
      }
    },

    async exportDegreesWithPrestations() {
      try {
        const res = await axios.get("exportDegreesWithPrestations", {
          responseType: "blob"
        });

        const fileURL = window.URL.createObjectURL(new Blob([res.data]));
        const fileLink = document.createElement("a");
        fileLink.href = fileURL;
        fileLink.setAttribute("download", "plantilla_grados.csv");
        document.body.appendChild(fileLink);
        fileLink.click();

      } catch (error) {
        console.error(error);
        flash("No se pudo generar la plantilla.", "error");
      }
    }

  }
};
</script>