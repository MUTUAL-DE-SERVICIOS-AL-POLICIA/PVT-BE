<template>
    <div class="row">
        <div class="col-md-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Obtención de Sueldos</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="year-select">Seleccione un Año:</label>
                                <div class="input-group">
                                    <select id="year-select" class="form-control" v-model="selectedYear" :disabled="isLoading">
                                        <option v-if="isLoading" value="" disabled>Cargando...</option>
                                        <option v-else value="">-- Seleccione --</option>
                                        <option v-for="year in years" :key="year" :value="year">
                                            {{ year }}
                                        </option>
                                    </select>
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="button" @click="calculate" :disabled="!selectedYear || isLoading">
                                            <span v-if="calculating"><i class="fa fa-spinner fa-spin"></i> Calculando...</span>
                                            <span v-else>Calcular</span>
                                        </button>
                                    </span>
                                </div>
                                <div v-if="updateMessage" class="alert alert-info mt-2">{{ updateMessage }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Importar Sueldos Base</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Plantilla de Importación:</label>
                                        <div class="input-group">
                                            <a href="/salary_calculation/download_template" class="btn btn-primary" type="button" target="_blank">
                                                <i class="fa fa-download"></i> Descargar Plantilla
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Archivo Excel (.xlsx, .csv):</label>
                                        <input type="file" ref="fileInput" @change="handleFileUpload" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <button class="btn btn-success" @click="importSalaries" :disabled="!uploadFile || loadingImport">
                                        <span v-if="loadingImport"><i class="fa fa-spinner fa-spin"></i> Importando...</span>
                                        <span v-else><i class="fa fa-upload"></i> Importar Sueldos</span>
                                    </button>
                                </div>
                            </div>
                            <div v-if="importMessage" class="alert alert-success mt-2">{{ importMessage }}</div>
                            <div v-if="importError" class="alert alert-danger mt-2">{{ importError }}</div>
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>

                    <div class="table-responsive" v-if="salaries.length > 0">
                        <hr>
                        <h4>Lista de salarios base {{ selectedYear }}</h4>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Nro</th>
                                    <!-- <th>Codigo de Grado</th> -->
                                    <th>Grado</th>
                                    <th>Nombre</th>
                                    <th>Salario</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(salary, index) in salaries" :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <!-- <td>{{ salary.degree_id }}</td> -->
                                    <td>{{ salary.degree_shortened }}</td>
                                    <td>{{ salary.degree_name }}</td>
                                    <td>{{ (salary.contribution_salary !== null && salary.contribution_salary != 0) ? (salary.contribution_salary | currency) : '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-right">
                            <a :href="'/salary_calculation/export?year=' + selectedYear" class="btn btn-success" type="button" v-if="salaries.length > 0">
                                <i class="fa fa-file-excel-o"></i> Exportar a Excel
                            </a>
                            <!-- <button class="btn btn-success" type="button" @click="updateBaseWages" :disabled="!selectedYear || isLoading || updating">
                                <span v-if="updating"><i class="fa fa-spinner fa-spin"></i> Actualizando...</span>
                                <span v-else>Actualizar Salarios</span>
                            </button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "SalaryCalculations",
    data() {
        return {
            years: [],
            selectedYear: '',
            salaries: [],
            isLoading: false,
            calculating: false,
            updating: false,
            updateMessage: '',
            uploadFile: null,
            loadingImport: false,
            importMessage: '',
            importError: '',
        };
    },
    mounted() {
        this.fetchYears();
    },
    methods: {
        fetchYears() {
            this.isLoading = true;
            return axios.get('/get_calculation_years')
                .then(response => {
                    this.years = response.data;
                })
                .catch(error => {
                    console.error("Error al obtener los años:", error);
                    this.updateMessage = "Error al cargar la lista de años.";
                })
                .finally(() => {
                    this.isLoading = false;
                });
        },
        calculate() {
            if (!this.selectedYear) return;
            this.calculating = true;
            this.isLoading = true;
            this.salaries = [];
            this.updateMessage = '';
            axios.get('/get_comparative_salaries', {
                params: {
                    year: this.selectedYear
                }
            })
            .then(response => {
                this.salaries = response.data;
            })
            .catch(error => {
                console.error("Error al calcular los salarios:", error);
                this.updateMessage = "Error al calcular los salarios comparativos.";
                this.salaries = [];
            })
            .finally(() => {
                this.calculating = false;
                this.isLoading = false;
            });
        },
        async updateBaseWages() {
            if (!this.selectedYear) return;

            this.updating = true;
            this.isLoading = true;
            this.updateMessage = '';

            await this.$nextTick(); // Asegura que el estado de 'updating' se refleje en la UI antes de la llamada async

            try {
                const response = await axios.post('/execute_update_base_wage', {
                    year: this.selectedYear
                });
                this.updateMessage = response.data.message;
                this.salaries = [];
                this.selectedYear = '';

                await this.fetchYears();
                window.events.$emit('salary-updated');

            } catch (error) {
                console.error("Error al actualizar los salarios:", error);
                if (error.response && error.response.data && error.response.data.message) {
                    this.updateMessage = `Error: ${error.response.data.message}`;
                } else {
                    this.updateMessage = "Ocurrió un error inesperado al actualizar los salarios.";
                }
            } finally {
                this.updating = false;
                this.isLoading = false;
            }
        },
        // funcionalidad para la importacion de sueldos
        handleFileUpload(event) {
            this.uploadFile = event.target.files[0];
            this.importError = ''; // Borrar errores anteriores
            this.importMessage = ''; // Borrar mensajes anteriores
        },
        downloadSalaryTemplate() {
            this.importError = '';
            this.importMessage = '';
            const url = '/salary_calculation/download_template';
            window.open(url, '_blank');
        },
        async importSalaries() {
            if (!this.uploadFile) {
                this.importError = 'Por favor, seleccione un archivo para importar.';
                return;
            }

            this.loadingImport = true;
            this.importError = '';
            this.importMessage = '';

            let formData = new FormData();
            formData.append('file', this.uploadFile);

            try {
                const response = await axios.post('/salary_calculation/import', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                this.importMessage = response.data.message || 'Sueldos importados correctamente.';
                this.uploadFile = null;
                this.$refs.fileInput.value = ''; // borrar el input file
                this.salaries = []; // Borrar los sueldos mostrados actualmente, forzando un recálculo si es necesario
                window.events.$emit('salary-updated'); // Disparar actualización para cualquier otro componente
            } catch (error) {
                console.error("Error al importar sueldos:", error);
                if (error.response && error.response.data && error.response.data.message) {
                    this.importError = `Error: ${error.response.data.message}`;
                } else if (error.response && error.response.data && error.response.data.errors) {
                     // manejar errores de validación de Laravel
                     const errors = error.response.data.errors;
                     let errorMessages = [];
                     for (const key in errors) {
                         if (errors.hasOwnProperty(key)) {
                             errorMessages.push(errors[key].join(', '));
                         }
                     }
                     this.importError = `Errores de validación: ${errorMessages.join('; ')}`;
                }
                else {
                    this.importError = "Ocurrió un error inesperado al importar los sueldos.";
                }
            } finally {
                this.loadingImport = false;
            }
        }
    }
}
</script>
