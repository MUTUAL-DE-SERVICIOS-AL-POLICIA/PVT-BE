<template>
    <div>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="form-group" :class="{ 'has-error': errors.has('procedure_type_id') }">
                    <label class="col-sm-3 control-label">Tipo de Pago</label>
                    <div class="col-sm-8">
                        <select class="form-control m-b" ref="procedure_type_id" name="procedure_type_id"
                            @change="onChooseProcedureType" v-model="procedure_type_id" v-validate.initial="'required'">
                            <option :value="null"></option>
                            <option v-for="type in procedureTypes" :value="type.id" :key="type.id">{{ type.name }}
                            </option>
                        </select>
                        <i v-show="errors.has('procedure_type_id')" class="fa fa-warning text-danger"></i>
                        <span v-show="errors.has('procedure_type_id')" class="text-danger">{{
                            errors.first('procedure_type_id') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group" :class="{ 'has-error': errors.has('city_end_id') }">
                    <label class="col-sm-4 control-label">Regional</label>
                    <div class="col-sm-8">
                        <select class="form-control m-b" ref="city_end" name="city_end_id" @change="onChooseCity"
                            v-model="city_end_id" v-validate.initial="'required'">
                            <option :value="null"></option>
                            <option v-for="city in cities" :value="city.id" :key="city.id">{{ city.name }}</option>
                        </select>
                        <i v-show="errors.has('city_end_id')" class="fa fa-warning text-danger"></i>
                        <span v-show="errors.has('city_end_id')" class="text-danger">{{ errors.first('city_end_id')
                            }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" :class="{ 'has-error': errors.has('ret_fun_modality') }">
                    <label class="col-sm-4 control-label">Modalidad</label>
                    <div class="col-sm-8">
                        <select class="form-control" v-model="modality" v-on:change="onChooseModality" ref="modality"
                            name="ret_fun_modality" id="ret_fun_modality" v-validate.initial="'required'">
                            <option :value="null"></option>
                            <option v-for="(modality, index) in modalitiesFilter" :value="modality.id" :key="index">
                                {{ modality.name }}</option>
                        </select>
                        <i v-show="errors.has('ret_fun_modality')" class="fa fa-warning text-danger"></i>
                        <span v-show="errors.has('ret_fun_modality')" class="text-danger">{{
                            errors.first('ret_fun_modality') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <requirement-select ref="requirements" :requirement-list="requirementList"
                :aditional-requirements="aditionalRequirements"></requirement-select>
        </div>
    </div>
</template>
<script>
export default {
    props: [
        'affiliate',
        'modalities',
        'user',
        'cities',
        'procedureTypes',
    ],
    data() {
        return {
            requirementList: {},
            aditionalRequirements: [],
            modality: null,
            city_end_id: this.user.city_id,
            procedure_type_id: 2,
            modalitiesFilter: [],
        }
    },
    mounted() {
        this.$store.commit('retFunForm/setCity', this.cities.filter(city => city.id == this.city_end_id)[0].name);
        this.onChooseProcedureType();
    },
    methods: {
        // Valida el primer paso del formulario, llamado desde el padre
        async validateStep() {            
            const isStepValid = await this.$validator.validateAll();
            const isRequirementsValid = this.$refs.requirements.validate();
            return isStepValid && isRequirementsValid;
        },
        onChooseProcedureType() {
            this.modalitiesFilter = this.modalities.filter((m) => {
                return m.procedure_type_id == this.procedure_type_id;
            })
            this.modality = null;
        },
        onChooseModality() {
            const mod = this.modalities.filter(e => e.id == this.modality)[0];
            if (mod) {
                let object = {
                    name: mod.name,
                    id: mod.id,
                    shortened: mod.shortened
                }
                this.$store.commit('retFunForm/setModality', object);
            }
            this.getRequirements();
        },
        async getRequirements() {
            if (!this.modality) { this.requirementList = {} }
            else {
                let uri = `/gateway/api/affiliates/${this.affiliate.id}/modality/${this.modality}/collate`;
                const data = (await axios.get(uri)).data;
                this.requirementList = data.requiredDocuments;
                this.aditionalRequirements = data.additionallyDocuments;
            }
            setTimeout(() => {
                $(".chosen-select").chosen({ width: "100%" }).trigger("chosen:updated");
            }, 500);
        },
        onChooseCity(event) {
            const options = event.target.options;
            const selectedOption = options[options.selectedIndex];
            const selectedText = selectedOption.textContent;
            this.$store.commit('retFunForm/setCity', selectedText)
        },
    },
}
</script>