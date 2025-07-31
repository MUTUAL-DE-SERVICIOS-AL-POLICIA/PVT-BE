<template>
    <div class="ibox">
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
                        <th>Salario Promedio Máximo</th>
                        <th>Número de Aportes Máximo</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in procedures" :key="index">
                        <td>{{ item.start_date }}</td>
                        <td>{{ item.limit_average }} Bs.</td>
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
        <modal name="procedure-modal" height="auto" :clickToClose="false" :focusTrap="true">
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
                    <h3>Seleccione a que grados se aplicará el límite de aportes</h3>
                    <div class="dd">
                        <ol class="dd-list">
                            <li v-for="hierarchy in modal.hierarchies" class="dd-item"
                                :class="hierarchy.show ? '' : 'dd-collapsed'">
                                <button type="button" :data-action="hierarchy.show ? 'collapse' : 'expand'"
                                    @click="hierarchy.show = !hierarchy.show" style="">{{ hierarchy.show ? 'Collapse' :
                                        'Expand' }}</button>
                                <div class="dd-handle">
                                    <input type="checkbox" v-model="form.hierarchiesIds" :value="hierarchy.id"> {{
                                        hierarchy.name }}
                                </div>
                                <ol class="dd-list">
                                    <li v-for="degree in hierarchy.degrees" class="dd-handle"
                                        style="padding: 2px !important;">
                                        {{ degree.name }}
                                    </li>
                                </ol>
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="text-center m-sm">
                        <button class="btn btn-danger" type="button" @click="$modal.hide('procedure-modal')">
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
        procedures: {
            type: Array,
            required: true
        },
        hierarchies: {
            type: Array,
            required: true
        }
    },
    data() {
        return {
            form: {
                id: null,
                start_date: null,
                limit_average: 0,
                contributions_limit: null,
                hierarchiesIds: [],
                method: 'post'
            },
            modal: {
                title: '',
                inputs: [{
                    label: 'Fecha de Inicio',
                    name: 'start_date',
                    type: 'date',
                    validation: 'required'
                }, {
                    label: 'Salario Promedio Máximo',
                    name: 'limit_average',
                    type: 'number',
                    validation: 'required|numeric_locale|min_value:1'
                }, {
                    label: 'Número de Aportes Máximo',
                    name: 'contributions_limit',
                    type: 'number',
                    validation: 'required|numeric|min_value:1'
                }],
                hierarchies: this.hierarchies.map(hierarchy => ({
                    id: hierarchy.id,
                    name: hierarchy.name,
                    degrees: hierarchy.degrees,
                    show: false,
                    value: false,
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
                this.form.hierarchiesIds = procedure.hierarchies
                    .filter(h => h.pivot.apply_limit)
                    .map(h => h.pivot.hierarchy_id);
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
            this.form.id = null;
            this.form.start_date = null;
            this.form.limit_average = null;
            this.max_contributions_limit = null;
            this.$validator.reset();
        }
    }
}
</script>