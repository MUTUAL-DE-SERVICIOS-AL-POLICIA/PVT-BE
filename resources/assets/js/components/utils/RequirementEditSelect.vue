<template>
    <div>
        <div class="alert alert-danger mx-5" v-if="requirements.serviceStatus === 'error'">
            <h2>No se pudo recuperar los documentos</h2>
        </div>
        <template v-else>
            <div class="row">
                <div class="pull-left">
                    <legend> Documentos Presentados</legend>
                </div>
                <div class="pull-right">
                    <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active' : ''"
                        @click.prevent="toggle_editing"><i class="fa" :class="editing ? 'fa-edit' : 'fa-pencil'"></i>
                        Editar</button>
                </div>
            </div>
            <div class="wrapper wrapper-content">
                <div v-for="(requirement, groupNumber) in requirementList" :key="'requirementList' + groupNumber"
                    class="animated fadeInRight">
                    <div class="vote-item" @click="checked(groupNumber, i)" v-for="(rq, i) in requirement"
                        :class="rq.background" style="cursor:pointer; padding: 10px;" :key="i" v-if="isVisible(rq)">
                        <input type="hidden"
                            :name="'required_requirements[' + rq.number + '][' + rq.procedureDocumentId + '][procedureRequirementId]'"
                            :value="rq.procedureRequirementId">
                        <input type="hidden"
                            :name="'required_requirements[' + rq.number + '][' + rq.procedureDocumentId + '][name]'"
                            :value="rq.name">
                        <input type="hidden"
                            :name="'required_requirements[' + rq.number + '][' + rq.procedureDocumentId + '][number]'"
                            :value="rq.number">
                        <input type="hidden"
                            :name="'required_requirements[' + rq.number + '][' + rq.procedureDocumentId + '][isUploaded]'"
                            :value="rq.isUploaded">
                        <div class="row" style="display: flex; align-items: center;">
                            <div class="col-md-1">
                                <h1 v-if="rq.number > 0">
                                    {{ rq.number }}
                                </h1>
                                <h1 v-else>
                                    A
                                </h1>
                            </div>
                            <div class="col-md-10"
                                style="display: flex; flex-direction: column; align-items: flex-start;">
                                <div>
                                    <strong style="font-size: 17px;">{{ rq.name }}</strong>
                                    <span v-if="rq.isUploaded" class="label label-success">DOCUMENTO DIGITAL</span>
                                </div>
                                <div style="display: flex; align-items: center; margin-top: 2px; width: 50%;">
                                    <i class="fa fa-comments-o"></i> Comentario:
                                    <input type="text" @click.stop
                                        :name="'required_requirements[' + rq.number + '][' + rq.procedureDocumentId + '][comment]'"
                                        class="form-control" :disabled="!editing">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="vote-icon" style="text-align: center;">
                                    <span style="color:#3c3c3c;"><i class="fa"
                                            :class="rq.status ? 'fa-check-square' : 'fa-square-o'"></i></span>
                                    <div style="display: none;">
                                        <input type="checkbox" v-model="rq.status" value="checked"
                                            :name="'required_requirements[' + rq.number + '][' + rq.procedureDocumentId + '][status]'">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr style="border: 1px solid #C0C0C0;">
                </div>
                <br>
                <div v-if="aditionalRequirements.length > 0">
                    <h4>Documentos adicionales</h4>
                    <select data-placeholder="Documentos adicionales..." class="chosen-select"
                        id="aditionalRequirementsSelected" name="aditionalRequirementsSelected[]" multiple
                        style="width: 350px; display: none;" tabindex="-1" v-model="aditionalRequirementsSelected"
                        v-bind:disabled="!editing">
                        <option v-for="(requirement, index) in aditionalRequirements"
                            :value="JSON.stringify(requirement)" :key="index">
                            {{ requirement.name }}
                            <span v-if="requirement.isUploaded" class="label label-success">
                                DOCUMENTO DIGITAL</span>
                        </option>
                    </select>
                </div>

                <transition name="show-requirements-error" enter-active-class="animated bounceInLeft">
                    <div class="alert alert-danger mx-5" v-if="showRequirementsError">
                        <h2>Debe seleccionar los requisitos</h2>
                    </div>
                </transition>
                <div class="text-center" v-if="editing">
                    <button class="btn btn-danger" type="button" @click="toggle_editing"><i
                            class="fa fa-times-circle"></i>&nbsp;&nbsp;<span cla ss="bold">Cancelar</span></button>
                    <button type="button" class="btn btn-primary" @click="save()"><i
                            class="fa fa-check-circle"></i>&nbsp;Guardar</button>
                </div>
            </div>
        </template>
    </div>
</template>
<script>
export default {
    props: {
        requirements: {
            type: Object,
            required: true
        },
        store: {
            type: Function,
            required: true,
        },
        isLegalReview: {
            type: Boolean,
            required: false,
            default: false,
        }
    },
    data() {
        return {
            requirementList: {},
            aditionalRequirements: [],
            aditionalRequirementsSelected: [],
            additionalCounter: 100, // Este numero se usa como indice en los requisitos con numero 0 para que se grafiquen al final de la lista
            showRequirementsError: false,
            editing: false,
        }
    },
    mounted() {
        if (this.requirements.serviceStatus === 'error') {
            return;
        }
        if (this.isLegalReview) {
            const filter = Object.fromEntries(
                Object.entries(this.requirements.requiredDocuments)
                    .map(([key, group]) => {
                        const docs = group
                            .filter(req => req.status)
                            .map(req => ({
                                ...req,
                                status: req.isValid,
                                background: req.isValid ? 'bg-success-green' : ''
                            }));

                        return [key, docs];
                    })
            );
            filter[this.additionalCounter] = this.requirements.additionallyDocuments
                .filter(aditional => aditional.status)
                .map(aditional => {
                    return {
                        ...aditional,
                        status: aditional.isValid,
                        background: aditional.isValid ? 'bg-success-green' : ''
                    }
                });
            this.requirementList = filter;
        } else {
            this.requirementList = Object.fromEntries(Object.entries(this.requirements.requiredDocuments).map(([groupNumber, v]) => {
                return [groupNumber, v.map(req => {
                    return {
                        ...req,
                        background: req.status ? 'bg-success-green' : '',
                    }
                })]
            }));
            this.aditionalRequirements = this.requirements.additionallyDocuments;
            this.aditionalRequirements.forEach(element => {
                if (element.status) {
                    this.aditionalRequirementsSelected.push(JSON.stringify(element));
                }
            });
            this.reloadStyles();
        }
    },
    methods: {
        checked(groupNumber, i) {
            if (!this.editing) return;
            const list = this.requirementList[groupNumber];
            if (this.isLegalReview) {
                let item = list[i];
                item.status = !item.status;
                item.background = item.status ? 'bg-success-green' : '';
                return;
            }

            // Desactiva todos los ítems excepto el actual
            list.forEach((item, k) => {
                if (k !== i) {
                    item.status = false;
                    item.background = 'bg-warning-yellow';
                } else {
                    item.status = !item.status;
                    item.background = item.status ? 'bg-success-green' : '';
                }
            });

            // Si todos los ítems están inactivos, limpia los backgrounds
            if (list.every(item => !item.status)) {
                list.forEach(item => item.background = '');
            }
        },
        isVisible(requeriment) {
            return this.isLegalReview || this.editing || requeriment.status;
        },
        toggle_editing: function () {
            this.editing = !this.editing;
            this.reloadStyles();

        },
        reloadStyles() {
            this.$nextTick(() => {
                const select = $(".chosen-select");
                select.chosen({ width: "100%" }).trigger("chosen:updated");

                select.next('.chosen-container')
                    .find('.chosen-choices')
                    .css("border", "4px solid #ceebd6");
            });
        },
        save() {
            if (!this.isLegalReview) {
                let reqSelected = $("#aditionalRequirementsSelected").val();
                this.store(this.requirementList, [...reqSelected.map(e => JSON.parse(e))]);
            } else {
                this.store(this.requirementList);
            }
        },
        // Función para verificar que todos los requisitos tengan al menos uno seleccionado, se lo llama desde el padre
        validate() {
            const isRequirementsValid = Object.values(this.requirementList).every(requirementGroup =>
                requirementGroup.some(rq => rq.status)
            );
            if (!isRequirementsValid) {
                this.showRequirementsError = true;
            }
            return isRequirementsValid;
        }
    },
}
</script>