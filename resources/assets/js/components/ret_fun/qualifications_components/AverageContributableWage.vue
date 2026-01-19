<template>
    <div class="ibox" v-if="isLoaded">
        <div class="ibox-title">
            <h2 class="pull-left">Parámetros de la gestión</h2>
            <div class="ibox-tools">
                <button class="btn btn-primary" @click="show()" data-toggle="tooltip" title="Adicionar">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="ibox-content">
            <table class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Fecha de inicio</th>
                        <th>Número de Aportes Máximo</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in procedures" :key="index">
                        <td>{{ item.start_date }}</td>
                        <td>{{ item.contributions_limit }}</td>
                        <td style="width: 1%; white-space: nowrap;">
                            <button class="btn btn-warning" @click="show(item)" data-toggle="tooltip" title="Editar">
                                <i class="fa fa-pencil"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <modal name="procedure-modal" height="auto" width="60%" :clickToClose="false" :focusTrap="true"
            :scrollable="true" style="z-index: 1000;">
            <div class="row w-full m-xxs">
                <div class="ibox-title">
                    <h1>{{ modal.title }}</h1>
                </div>

                <div v-for="input in modal.inputs" class="col-md-12" :class="{ 'has-error': errors.has(input.name) }">
                    <div class="col-md-3">
                        <label class="control-label">{{ input.label }}</label>
                    </div>
                    <div class="col-md-9">
                        <input :type="input.type" class="form-control m-b" :name="input.name" v-model="form[input.name]"
                            v-validate.defer="input.validation">
                        <i v-show="errors.has(input.name)" class="fa fa-warning text-danger"></i>
                        <span v-show="errors.has(input.name)" class="text-danger">{{
                            errors.first(input.name) }}</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="footable table table-stripped toggle-arrow-tiny footable-loaded tablet breakpoint">
                        <thead>
                            <tr>
                                <th class="footable-visible">Grados</th>
                                <th class="footable-visible">Aplicar Limite de Aportes</th>
                                <th class="footable-visible">Salario Promedio Máximo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="hierarchy in modal.hierarchies">
                                <tr class="footable-even" :class="hierarchy.show ? 'footable-detail-show' : ''">
                                    <td class="footable-visible footable-first-column"
                                        @click="hierarchy.show = !hierarchy.show"><span
                                            class="footable-toggle"></span>{{
                                                hierarchy.name }}</td>
                                    <td>
                                        <input type="checkbox" class="icheckbox_square-green"
                                            :name="'hierarchy_cb_' + hierarchy.id"
                                            v-model="form.hierarchies[hierarchy.id].apply_contributions_limit">
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" :name="'hierarchy_input_' + hierarchy.id"
                                                v-validate.defer="'required|numeric_locale|min_value:1'"
                                                class="form-control"
                                                v-model.number="form.hierarchies[hierarchy.id].average_salary_limit">
                                            <span class="input-group-addon">Bs.</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="footable-row-detail" v-if="hierarchy.show">
                                    <td class="footable-row-detail-cell" colspan="4">
                                        <div class="footable-row-detail-inner">
                                            <div class="footable-row-detail-row" v-for="d in hierarchy.degrees">
                                                <div class="footable-row-detail-name">{{ d.name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12">
                    <table class="footable table table-stripped toggle-arrow-tiny footable-loaded tablet breakpoint">
                        <thead>
                            <tr>
                                <th class="footable-visible">Modalidad</th>
                                <th class="footable-visible">Porcentaje de Rendimiento</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="procedureType in modal.procedureType">
                                <tr class="footable-even" :class="procedureType.show ? 'footable-detail-show' : ''">
                                    <td class="footable-visible footable-first-column"
                                        @click="procedureType.show = !procedureType.show"><span
                                            class="footable-toggle"></span>{{
                                                procedureType.name }}</td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" :name="'procedureType_input_' + procedureType.id"
                                                v-validate.defer="'required|numeric_locale|min_value:0'" class="form-control"
                                                v-model.number="form.procedureType[procedureType.id].percentageYield">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="footable-row-detail" v-if="procedureType.show">
                                    <td class="footable-row-detail-cell" colspan="4">
                                        <div class="footable-row-detail-inner">
                                            <div class="footable-row-detail-row" v-for="m in procedureType.modalities">
                                                <div class="footable-row-detail-name">{{ m.name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12">
                    <div class="text-center m-sm">
                        <button class="btn btn-danger" type="button" @click="hide()">
                            <i class="fa fa-times-circle"></i>&nbsp;&nbsp;
                            <span class="bold">Cancelar</span>
                        </button>
                        <button class="btn btn-primary" type="button" @click="save(form.method)">
                            <i class="fa fa-check-circle"></i>&nbsp;Guardar
                        </button>
                    </div>
                </div>
            </div>
        </modal>
    </div>
</template>
<script>
export default {
    name: 'AverageContributableWage',
    props: {
        procedures: { type: Array, required: true },
        hierarchies: { type: Array, required: true },
        procedureTypes: { type: Array, required: true }
    },
    data() {
        return {
            isLoaded: false,
            form: {}, // Objeto que se enviara al backend
            modal: { // Sirve para renderizar los inputs del modal
                title: '',
                inputs: [{
                    label: 'Fecha de Inicio',
                    name: 'start_date',
                    type: 'date',
                    validation: 'required'
                }, {
                    label: 'Número de Aportes Máximo',
                    name: 'contributions_limit',
                    type: 'number',
                    validation: 'required|numeric_locale|min_value:0'
                },],
                hierarchies: this.hierarchies.map(hierarchy => ({
                    id: hierarchy.id,
                    name: hierarchy.name,
                    degrees: hierarchy.degrees,
                    show: false,
                })),
                procedureType: this.procedureTypes.map(procedureType => ({
                    id: procedureType.id,
                    moduleId: procedureType.module_id,
                    name: procedureType.name,
                    show: false,
                    modalities: procedureType.procedure_modalities.map(modalities => ({
                        id: modalities.id,
                        name: modalities.name,
                    }))
                }))
            }
        }
    },
    mounted() {
        // Se registra una validación personalizada para que acepte números con coma o punto como separador decimal
        this.$validator.extend('numeric_locale', {
            getMessage: field => `El campo ${field} debe ser un número válido.`,
            validate: value => /^-?\d+([.,]\d+)?$/.test(value)
        });

        // Inicializa el formulario
        this.clearForm();
        this.isLoaded = true;
    },
    methods: {
        show(procedure) {
            this.clearForm();
            if (procedure) {
                this.modal.title = 'Editar Gestión';
                this.form.id = procedure.id;
                this.form.start_date = procedure.start_date;
                this.form.limit_average = procedure.limit_average;
                this.form.contributions_limit = procedure.contributions_limit;
                for (const hierarchy of procedure.hierarchies) {
                    this.form.hierarchies[hierarchy.id] = {
                        id: hierarchy.id,
                        name: hierarchy.name,
                        average_salary_limit: hierarchy.pivot.average_salary_limit || null,
                        apply_contributions_limit: hierarchy.pivot.apply_contributions_limit || false
                    };
                }
                this.form.procedureType = {};
                for (const modalities of procedure.procedure_modalities) {
                    this.form.procedureType[modalities.procedure_type_id] = {
                        id: modalities.procedure_type_id,
                        percentageYield: modalities.pivot.annual_percentage_yield,
                        modalitiesIds: []
                    }
                    this.form.procedureType[modalities.procedure_type_id].modalitiesIds.push(modalities.pivot.procedure_modality_id);
                }
                this.form.method = 'patch';
            } else {
                this.modal.title = 'Adicionar Nueva Gestión';
                this.form.method = 'post';
            }
            this.$modal.show('procedure-modal');
        },
        hide() {
            this.clearForm();
            this.$modal.hide('procedure-modal');
        },
        async save(method) {
            switch (method) {
                case 'post':
                    this.$validator.validateAll().then(async valid => {
                        if (valid) {
                            await axios.post('/ret_fun_procedure', this.form)
                                .then(response => {
                                    console.log('Data saved successfully:');
                                    window.location.reload();
                                })
                                .catch(error => {
                                    console.log("Error al procesar: ", error.response.data.errors);
                                    const serverErrors = error.response.data.errors;
                                    Object.keys(serverErrors).forEach(field => {
                                        this.$validator.errors.add({
                                            field: field,
                                            msg: serverErrors[field][0]
                                        });
                                    });
                                });
                        }
                    });
                    break;
                case 'patch':
                    console.log('Editing RetFunProcedure with ID:', this.form.id);
                    await this.$validator.validateAll();
                    if (this.$validator.errors.items.length) {
                        console.log('Errores');
                        console.log(this.$validator.errors);
                        return;
                    }
                    await axios.patch(`/ret_fun_procedure/${this.form.id}`, this.form)
                        .then(response => {
                            console.log('Data edited successfully:');
                            console.log(response.data.message);
                            window.location.reload();
                        })
                        .catch(error => {
                            console.log("Error al procesar: ", error.response.data);
                            const serverErrors = error.response.data.errors;
                            if (serverErrors) {
                                Object.keys(serverErrors).forEach(field => {
                                    this.$validator.errors.add({
                                        field: field,
                                        msg: serverErrors[field][0]
                                    });
                                });
                            }
                        });
                    break;
                default:
                    console.error('Método no soportado:', method);
                    return;
            }

        },
        clearForm() {
            this.form = {
                id: null,
                start_date: null,
                contributions_limit: null,
                hierarchies: this.hierarchies.reduce((acc, item) => {
                    acc[item.id] = {
                        id: item.id,
                        name: item.name,
                        average_salary_limit: null,
                        apply_contributions_limit: false
                    };
                    return acc;
                }, {}),
                procedureType: this.procedureTypes.reduce((acc, type) => {
                    acc[type.id] = {
                        id: type.id,
                        name: type.name,
                        percentageYield: 0,
                        modalitiesIds: type.procedure_modalities.map(e => e.id)
                    };
                    return acc;
                }, {}),
            };
            this.$validator.reset();
        }
    }
}
</script>