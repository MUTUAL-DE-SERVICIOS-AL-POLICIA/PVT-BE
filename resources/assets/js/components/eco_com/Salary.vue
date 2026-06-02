<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <h2>Sueldos Base por Jerarquias de Grados</h2>
                    </div>
                </div>
                <div class="ibox-content">
                    <div v-if="loading" class="text-center">
                        <i class="fa fa-spinner fa-spin fa-3x"></i>
                        <p class="mt-2">Cargando</p>
                    </div>
                    <div v-else>
                        <div v-for="hierarchy in hierarchies" :key="hierarchy.id" class="mb-5">
                            <h3>{{ hierarchy.name }}</h3>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="success">
                                            <th>Año</th>
                                            <th v-for="degree in hierarchy.degrees" :key="degree.id" class="text-center">
                                                <div data-toggle="tooltip" data-placement="top" :title="degree.name">
                                                    {{ degree.shortened }}
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="year in years" :key="year">
                                            <td class="text-center"><strong>{{ year }}</strong></td>
                                            <td v-for="degree in hierarchy.degrees" :key="degree.id" class="text-right">
                                                {{ getWageAmount(degree, year) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Salary",
    data() {
        return {
            hierarchies: [],
            years: [],
            loading: true,
        };
    },
    mounted() {
        this.fetchGroupedWages();
        window.events.$on('salary-updated', () => {
            this.fetchGroupedWages();
        });
    },
    beforeDestroy() {
        window.events.$off('salary-updated');
    },
    methods: {
        fetchGroupedWages() {
            this.loading = true;
            axios.get('/get_grouped_base_wages')
                .then(response => {
                    this.hierarchies = response.data;
                    this.calculateYears(response.data);
                })
                .catch(error => {
                    console.error('Error fetching grouped base wages:', error);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        calculateYears(hierarchies) {
            const allYears = new Set();
            if (Array.isArray(hierarchies)) {
                hierarchies.forEach(h => {
                    if (Array.isArray(h.degrees)) {
                        h.degrees.forEach(d => {
                            if (d.wages) {
                                Object.keys(d.wages).forEach(year => {
                                    allYears.add(year);
                                });
                            }
                        });
                    }
                });
            }
            this.years = Array.from(allYears).sort((a, b) => b - a);
        },
        getWageAmount(degree, year) {
            return degree.wages[year] ? degree.wages[year].amount : '-';
        }
    }
}
</script>

<style scoped>
.mt-2 {
    margin-top: 1rem;
}
.mb-5 {
    margin-bottom: 3rem;
}
.fa-3x {
    font-size: 3em;
}
</style>