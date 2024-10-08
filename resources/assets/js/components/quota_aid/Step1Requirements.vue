<script>
	export default{
		props:[
			'modalities',
            'requirements',
            'user',
            'cities',
            'procedureTypes',
            'showRequirementsError'
		],
        data(){
            return{
                editing: false,
                requirementList: [],
                aditionalRequirements: [],
                modality: null,
                show_spinner: false,
                city_end_id:this.user.city_id,
                procedure_type_id:3,
                modalitiesFilter: [],
            }
        },
        mounted(){
            this.$store.commit('quotaAidForm/setCity',this.cities.filter(city => city.id == this.city_end_id)[0].name);
            this.onChooseProcedureType();
        },
        methods:{
            onChooseProcedureType(){
                this.modalitiesFilter = this.modalities.filter((m) => {
                    return m.procedure_type_id == this.procedure_type_id;
                })
                this.modality = null;
                this.getRequirements();
                //this.getAditionalRequirements();
            },
            onChooseModality(event){
                // const options = event.target.options; 
                // const selectedOption = options[options.selectedIndex];
                let mod = this.modalities.filter(e => e.id == this.modality)[0];
                if (mod) {
                    let object = {
                        name: mod.name,
                        id: mod.id,
                        shortened: mod.shortened
                    }
                    this.$store.commit('quotaAidForm/setModality', object);
                }
                // if (selectedOption) {
                //     const selectedText = selectedOption.textContent;
                //     var object={
                //         name:selectedText,
                //         id: this.modality
                //     }
                //     this.$store.commit('quotaAidForm/setModality',object);//solo se puede enviar un(1) argumento 
                // }
                this.getRequirements();
                this.getAditionalRequirements();
            },
            getRequirements(){
                if(!this.modality){ this.requirementList = [] }
                this.requirementList = this.requirements.filter((r) => {
                    if (r.modality_id == this.modality && r.number != 0) {
                        r['status'] = false;
                        r['background'] = '';
                        return r;
                    }
                });
                Array.prototype.groupBy = function(prop) {
                    return this.reduce(function(groups, item) {
                        const val = item[prop]
                        groups[val] = groups[val] || []
                        groups[val].push(item)
                        return groups
                    }, {})
                }
                this.requirementList =  this.requirementList.groupBy('number')
            },
            getAditionalRequirements(){
                if(!this.modality){this.aditionalRequirements = []}                
                this.aditionalRequirements = this.requirements.filter((requirement) => {
                    if (requirement.modality_id == this.modality && requirement.number == 0) {
                        return requirement;
                    }
                });                
                console.log("requerimientos");
                console.log(this.aditionalRequirements.length  );
                setTimeout(() => {
                    $(".chosen-select").chosen({ width: "100%" }).trigger("chosen:updated");
                }, 500);                
            },
            checked(index, i){
                for(var k = 0; k < this.requirementList[index].length; k++ ){
                    if (k != i ) {
                    this.requirementList[index][k].status = false;
                    this.requirementList[index][k].background = 'bg-warning-yellow';

                    }
                }
                this.requirementList[index][i].status =  ! this.requirementList[index][i].status;
                this.requirementList[index][i].background = this.requirementList[index][i].background == 'bg-success-green' ? '' : 'bg-success-green';
                // this.requirementList[index][i].status = true;
                if (this.requirementList[index].every(r => !r.status )) {
                    for(var k = 0; k < this.requirementList[index].length; k++ ){
                        if (!this.requirementList[index][k].status) {
                            this.requirementList[index][k].background = '';
                        }
                    }
                }

            },
            onChooseCity(event){
                const options = event.target.options;
                const selectedOption = options[options.selectedIndex];
                const selectedText = selectedOption.textContent;
                this.$store.commit('quotaAidForm/setCity',selectedText)
            },
	}
}
</script>