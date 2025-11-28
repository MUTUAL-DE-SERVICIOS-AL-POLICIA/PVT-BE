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

                    <div class="table-responsive" v-if="salaries.length > 0">
                        <hr>
                        <h4>Lista de salarios base {{ selectedYear }}</h4>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Nro</th>
                                    <th>Grado</th>
                                    <th>Nombre</th>
                                    <th>Salario</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(salary, index) in salaries" :key="index">
                                    <td>{{ index + 1 }}</td>
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
                            <button class="btn btn-success" type="button" @click="updateBaseWages" :disabled="!selectedYear || isLoading || updating">
                                <span v-if="updating"><i class="fa fa-spinner fa-spin"></i> Actualizando...</span>
                                <span v-else>Actualizar Salarios</span>
                            </button>
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
        }
    }
}
</script>
