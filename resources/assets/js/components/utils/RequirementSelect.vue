<template>
    <div>
        <h2>Lista de Requisitos</h2>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div v-for="(requirement, index) in localRequirementList" :key="index">
                <div class="vote-item" @click="checked(index, i)" v-for="(rq, i) in requirement" :class="rq.background"
                    style="cursor:pointer" :key="i">
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
                    <div class="row">
                        <div class="col-md-10">
                            <div class="vote-actions">
                                <h1>
                                    {{ rq.number }}
                                </h1>
                            </div>
                            <span class="vote-title">{{ rq.name }} <span class="label label-success">{{ rq.isUploaded
                                ?
                                "DOCUMENTO DIGITAL" : "" }}</span></span>
                            <div class="vote-info">
                                <div class="col-md-2 no-margins no-padding">
                                    <i class="fa fa-comments-o"></i> Comentario:
                                </div>
                                <div class="col-md-6 no-margins no-padding">
                                    <input type="text"
                                        :name="'required_requirements[' + rq.number + '][' + rq.procedureDocumentId + '][comment]'"
                                        class="form-control">
                                </div>
                                <br>
                            </div>
                        </div>
                        <div class="col-md-2 ">
                            <div class="vote-icon">
                                <span style="color:#3c3c3c"><i class="fa "
                                        :class="rq.status ? 'fa-check-square' : 'fa-square-o'"></i></span>
                                <div style="display: none;">
                                    <input type="checkbox" v-model="rq.status" value="checked"
                                        :name="'required_requirements[' + rq.number + '][' + rq.procedureDocumentId + '][status]'">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div v-if="localAditionalRequirementsUploaded.length > 0">
                <h4>Documentos adicionales en DBE</h4>
                <ul>
                    <li v-for="(requirement, index) in localAditionalRequirementsUploaded">
                        {{ requirement.name }}
                        <input type="hidden" name="aditional_requirements[]" :value="JSON.stringify(requirement)">
                    </li>
                </ul>
            </div>
            <div v-if="localAditionalRequirements.length > 0">
                <h4>Documentos adicionales</h4>
                <select data-placeholder="Documentos adicionales..." class="chosen-select"
                    name="aditional_requirements[]" multiple="" style="width: 350px; display: none;" tabindex="-1">
                    <option v-for="(requirement, index) in localAditionalRequirements"
                        :value="JSON.stringify(requirement)" :key="index">{{ requirement.name }} </option>
                </select>
            </div>

            <transition name="show-requirements-error" enter-active-class="animated bounceInLeft">
                <div class="alert alert-danger mx-5" v-if="showRequirementsError">
                    <h2>Debe seleccionar los requisitos</h2>
                </div>
            </transition>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        requirementList: {
            type: Object,
            required: true
        },
        aditionalRequirementsUploaded: {
            type: Array,
            required: true
        },
        aditionalRequirements: {
            type: Array,
            required: true
        },
    },
    data() {
        return {
            localRequirementList: {},
            localAditionalRequirements: [],
            localAditionalRequirementsUploaded: [],
            showRequirementsError: false,
        }
    },
    methods: {
        checked(index, i) {
            for (var k = 0; k < this.localRequirementList[index].length; k++) {
                if (k != i) {
                    this.localRequirementList[index][k].status = false;
                    this.localRequirementList[index][k].background = 'bg-warning-yellow';
                }
            }
            this.localRequirementList[index][i].status = !this.localRequirementList[index][i].status;
            this.localRequirementList[index][i].background = this.localRequirementList[index][i].background == 'bg-success-green' ? '' : 'bg-success-green';
            if (this.localRequirementList[index].every(r => !r.status)) {
                for (var k = 0; k < this.localRequirementList[index].length; k++) {
                    if (!this.localRequirementList[index][k].status) {
                        this.localRequirementList[index][k].background = '';
                    }
                }
            }

            this.$emit('update:requirementList', this.localRequirementList);
        },
        validate() {
            const isRequirementsValid = Object.values(this.requirementList).every(requirementGroup =>
                requirementGroup.some(rq => rq.status)
            );
            if(!isRequirementsValid){
                this.showRequirementsError = true;
            }
            return isRequirementsValid;
        }
    },
    watch: {
        requirementList(newVal) {
            // Si el padre cambia, sincronizamos de nuevo
            // Se clona el objeto con JSON
            this.localRequirementList = JSON.parse(JSON.stringify(newVal));
        },
        aditionalRequirements(newVal) {
            // Si el padre cambia, sincronizamos de nuevo
            this.localAditionalRequirements = [...newVal]
        },
        aditionalRequirementsUploaded(newVal) {
            // Si el padre cambia, sincronizamos de nuevo
            this.localAditionalRequirementsUploaded = [...newVal]
        }
    },
}
</script>