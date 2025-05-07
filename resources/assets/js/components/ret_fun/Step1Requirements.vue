<script>
export default {
    props: [
        'affiliate',
        'modalities',
        'user',
        'cities',
        'procedureTypes',
        'showRequirementsError'
    ],
    data() {
        return {
            editing: false,
            requirementList: [],
            aditionalRequirements: [],
            aditionalRequirementsUploaded: [],
            modality: null,
            show_spinner: false,
            modality_id: 3,
            actual_target: 1,
            city_end_id: this.user.city_id,
            procedure_type_id: 2,
            my_index: 1,
            modalitiesFilter: [],
        }
    },
    mounted() {
        this.$store.commit('retFunForm/setCity', this.cities.filter(city => city.id == this.city_end_id)[0].name);
        this.onChooseProcedureType();
    },
    methods: {
        onChooseProcedureType() {
            this.modalitiesFilter = this.modalities.filter((m) => {
                return m.procedure_type_id == this.procedure_type_id;
            })
            this.modality = null;
            this.getRequirements();
        },
        onChooseModality(event) {
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
            this.getAditionalRequirements();
        },
        async getRequirements() {
            if (!this.modality) { this.requirementList = [] }
            else {
                let uri = `/gateway/api/affiliates/${this.affiliate.id}/modality/${this.modality}/collate`;
                const data = (await axios.get(uri)).data;
                const requiredDocuments = data.requiredDocuments;
                Object.values(requiredDocuments).forEach(value => {
                    value.forEach(r => {
                        r['status'] = r['isUploaded'];
                        r['background'] = r['isUploaded'] ? 'bg-success-blue' : '';
                    });
                });
                this.requirementList = requiredDocuments;
                this.aditionalRequirements = data.additionallyDocuments;
                this.aditionalRequirementsUploaded = data.additionallyDocumentsUpload;
            }
        },
        convertToStringJson(objeto) {
            return JSON.stringify(objeto);
        },
        getAditionalRequirements() {
            if (!this.modality) { this.aditionalRequirements = [] }
            setTimeout(() => {
                $(".chosen-select").chosen({ width: "100%" }).trigger("chosen:updated");
            }, 500);
        },

        checked(index, i) {
            if (this.requirementList[index][i].isUploaded) return;
            for (var k = 0; k < this.requirementList[index].length; k++) {
                if (k != i) {
                    this.requirementList[index][k].status = false;
                    this.requirementList[index][k].background = 'bg-warning-yellow';

                }
            }
            this.requirementList[index][i].status = !this.requirementList[index][i].status;
            this.requirementList[index][i].background = this.requirementList[index][i].background == 'bg-success-green' ? '' : 'bg-success-green';
            if (this.requirementList[index].every(r => !r.status)) {
                for (var k = 0; k < this.requirementList[index].length; k++) {
                    if (!this.requirementList[index][k].status) {
                        this.requirementList[index][k].background = '';
                    }
                }
            }

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