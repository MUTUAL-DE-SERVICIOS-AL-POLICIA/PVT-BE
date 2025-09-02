<template>
    <div class="ibox">
        <div class="ibox-content">
            <div class="row">
                <div class="pull-left">
                    <legend> Documentos Presentados</legend>
                </div>
                <div class="pull-right">
                    <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active' : ''"
                        @click="toggle_editing"><i class="fa" :class="editing ? 'fa-edit' : 'fa-pencil'"></i>
                        Editar</button>
                </div>
            </div>
            <form>
                <div class="row">
                    <div v-for="(requirement, index) in requirementList" :key="index">
                        <div class="vote-item" @click="checked(index, i)" v-for="(rq, i) in requirement"
                            :class="rq.background" style="cursor:pointer" :key="i" v-if="isVisible(rq)">
                            <div class="row">
                                <div :class="editing ? 'col-md-10' : 'col-md-10'">
                                    <div class="vote-actions">
                                        <h1 v-if="rq.number > 0">
                                            {{ rq.number }}
                                        </h1>
                                        <h1 v-else>
                                            A
                                        </h1>
                                    </div>
                                    <span class="vote-title">{{ rq.name }}</span>
                                    <div class="vote-info">
                                        <div class="col-md-2 no-margins no-padding">
                                            <i class="fa fa-comments-o"></i> Comentario:
                                        </div>
                                        <div class="col-md-6 no-margins no-padding">
                                            <input type="text" :name="'comment' + rq.id" class="form-control"
                                                :disabled="!editing" v-model="rq.comment">
                                        </div>
                                        <br>
                                    </div>
                                </div>
                                <div class="col-md-2 ">
                                    <div class="vote-icon">
                                        <span style="color:#3c3c3c"><i class="fa "
                                                :class="rq.status ? 'fa-check-square' : 'fa-square-o'"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div v-if='rol != 11'>
                    <div v-if="aditionalRequirementsUploaded.length > 0">
                        <h4>Documentos adicionales en DBE</h4>
                        <ul>
                            <li v-for="(requirement, index) in aditionalRequirementsUploaded">
                                {{ requirement.name }}
                                <input type="hidden" id="aditionalRequirementsUploaded"
                                    name="aditionalRequirementsUploaded" :value="convertToStringJson(requirement)">
                            </li>
                        </ul>
                    </div>
                    <div v-if="aditionalRequirements.length > 0" style="margin-bottom:180px">
                        <h4>Documentos adicionales</h4>
                        <select data-placeholder="Documentos adicionales..." class="chosen-select"
                            id="aditionalRequirementsSelected" name="aditionalRequirementsSelected[]" multiple
                            style="width: 350px; display: none;" tabindex="-1" v-model="aditionalRequirementsSelected"
                            v-bind:disabled="!editing">
                            <option v-for="(requirement, index) in aditionalRequirements"
                                :value="convertToStringJson(requirement)" :key="index">{{ requirement.name }}</option>
                        </select>
                    </div>
                </div>
                <br>
                <br>
                <div class="text-center" v-if="editing">
                    <button class="btn btn-danger" type="button" @click="toggle_editing"><i
                            class="fa fa-times-circle"></i>&nbsp;&nbsp;<span cla ss="bold">Cancelar</span></button>
                    <button type="button" class="btn btn-primary" @click="store()"><i
                            class="fa fa-check-circle"></i>&nbsp;Guardar</button>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
export default {
    props: [
        'affiliate',
        'ret_fun',
        'submitted',
        'requirements',
        'rol',
    ],
    data() {
        return {
            requirementList: {},
            aditionalRequirements: [],
            aditionalRequirementsUploaded: [],
            aditionalRequirementsSelected: [],
            modality: null,
            editing: false,
            additionalCounter: 100, // Este numero se usa como indice en los requisitos con numero 0 para que se grafiquen al final de la lista
        }
    },
    mounted() {
        this.modality = this.ret_fun.procedure_modality_id;
        this.getRequirements();
    },
    methods: {
        toggle_editing: function () {
            this.editing = !this.editing;
            setTimeout(() => {
                $(".chosen-select")
                    .chosen({ width: "100%" })
                    .trigger("chosen:updated")
                // Si es un select múltiple, usa '.chosen-choices'
                $(".chosen-select")
                    .next('.chosen-container')
                    .find('.chosen-choices') // Para selects múltiples
                    .css("border", "4px solid #ceebd6");
            }, 500);
        },
        async getRequirements() {
            const isLegalReview = this.rol === 11;

            // Reiniciamos siempre para evitar basura
            this.aditionalRequirementsUploaded = [];
            this.aditionalRequirements = [];

            if (isLegalReview) {
                // Caso: Revisión Legal → agrupar `submitted` por número
                this.requirementList = this.submitted.reduce((acc, sub) => {
                    const index = sub.number > 0 ? sub.number : this.additionalCounter;

                    if (!acc[index]) acc[index] = [];

                    acc[index].push({
                        id: sub.id,
                        name: sub.name,
                        status: sub.is_valid,
                        background: sub.is_valid ? 'bg-success-green' : '',
                        comment: sub.comment,
                        isUploaded: sub.is_uploaded,
                        procedureRequirementId: sub.procedure_requirement_id,
                        number: sub.number
                    });

                    return acc;
                }, {});
            } else {
                // Preparamos mapa rápido de submitted por requirement_id
                const submittedMap = new Map(
                    this.submitted.map(s => [s.procedure_requirement_id, s])
                );

                const acc = {};

                this.requirements.forEach(req => {
                    const sub = submittedMap.get(req.id);

                    if (req.number === 0) {
                        // Requisitos adicionales
                        const opReq = {
                            name: req.document,
                            number: req.number,
                            procedureRequirementId: req.id,
                            isUploaded: sub ? sub.is_uploaded : false
                        };

                        if (opReq.isUploaded) {
                            this.aditionalRequirementsUploaded.push(opReq);
                        } else {
                            this.aditionalRequirements.push(opReq);
                        }
                    } else {
                        if (!acc[req.number]) acc[req.number] = [];
                        if (acc[req.number].some(item => item.isUploaded)) return;

                        const baseReq = {
                            id: req.id,
                            name: req.document,
                            status: false,
                            background: '',
                            comment: null,
                            isUploaded: false,
                            procedureRequirementId: req.id,
                            number: req.number
                        };

                        if (sub) {
                            baseReq.status = true;
                            baseReq.background = sub.is_uploaded ? 'bg-success-blue' : 'bg-success-green';
                            baseReq.comment = sub.comment;
                            baseReq.isUploaded = sub.is_uploaded;

                            // Si está escaneado, reemplazamos todas las opciones
                            if (baseReq.isUploaded) {
                                acc[req.number] = [baseReq];
                                return;
                            }
                        }

                        acc[req.number].push(baseReq);
                    }
                });

                this.requirementList = acc;
            }

            this.getAditionalRequirements();
        },
        getAditionalRequirements() {
            if (!this.modality) {
                this.aditionalRequirements = [];
            }
            if (!this.modality) {
                this.aditionalRequirementsSelected = [];
            }
            this.aditionalRequirements.forEach(element => {
                let submit_document = this.submitted.find(function (document) {
                    return document.procedure_requirement_id === element.procedureRequirementId;
                });
                if (submit_document) {
                    this.aditionalRequirementsSelected.push(this.convertToStringJson(element));
                }
            });

            setTimeout(() => {
                $(".chosen-select")
                    .chosen({ width: "100%" })
                    .trigger("chosen:updated");
            }, 500);
        },
        checked(index, i) {
            if (!this.editing) return;
            if (this.rol == 11) {
                let item = this.requirementList[index][i];
                item.status = !item.status;
                item.background = item.status ? 'bg-success-green' : '';
                return;
            }

            if (this.requirementList[index][i].isUploaded) return;
            const list = this.requirementList[index];

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
            if (this.rol != 11) {
                if (this.editing) {
                    return true;
                } else {
                    return requeriment.status;
                }
            } else {
                return true;
            }
        },
        convertToStringJson(objeto) {
            return JSON.stringify(objeto);
        },
        store() {
            if (this.rol != 11) {
                let uri = `/ret_fun/${this.ret_fun.id}/edit_requirements`;
                let reqSelected = $("#aditionalRequirementsSelected").val();
                axios.post(uri,
                    {
                        requirements: this.requirementList,
                        aditional_requirements: [...reqSelected.map(e => JSON.parse(e)), ...this.aditionalRequirementsUploaded]
                    }
                ).then(response => {
                    flash("Verificacion Correcta");
                    this.toggle_editing();
                    location.reload();
                }).catch(error => {
                    console.log(error);
                    flash("Los Datos no Coinciden", "error");
                });
            } else {
                let uri = `/ret_fun/${this.ret_fun.id}/legal_review/create`;
                axios.post(uri,
                    {
                        submit_documents: this.requirementList
                    }
                ).then(response => {
                    flash("Verificacion Correcta");
                    this.toggle_editing();
                    location.reload();
                }).catch(error => {
                    flash("Los Datos no Coinciden", "error");
                });
            }
        }
    },
}
</script>