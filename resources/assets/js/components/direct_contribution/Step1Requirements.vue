
<script>
import { mapState, mapMutations } from 'vuex';
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
                show_spinner: false,
                procedure_modality_id: null,
                actual_target: 1,
                city_id:this.user.city_id,
                procedure_type_id:6,
                my_index: 1,
                modalitiesFilter: [],
                contribution_process_type: null,
                contribution_process_types: [
                    {
                        id: 'N',
                        name: 'Nuevo'
                    },
                    {
                        id: 'A',
                        name: 'Antiguo'
                    }
                ]
            }
        },
        mounted(){
            this.$store.commit('directContributionForm/setCity',this.cities.filter(city => city.id == this.city_id)[0].name);
            this.onChooseProcedureType();
        },
        methods:{
            onChooseProcedureType(){
                this.modalitiesFilter = this.modalities.filter((m) => {
                    return m.procedure_type_id == this.procedure_type_id;
                })
                this.$store.commit('directContributionForm/setProcedureType',{id:this.procedure_modality_id });
                this.getRequirements();
            },
            onChooseModality(event){
                const options = event.target.options;
                const selectedOption = options[options.selectedIndex];
                if (selectedOption) {
                    const selectedText = selectedOption.textContent;
                    var object={
                        name:selectedText,
                        id: this.procedure_modality_id
                    }
                    this.$store.commit('directContributionForm/setModality',object);//solo se puede enviar un(1) argumento 
                }
                this.getRequirements();
                this.getAditionalRequirements();
            },
            onChooseContributionProcessType(){
                return;
            },
            getRequirements(){
                if(!this.procedure_modality_id){this.requirementList = []}
                this.requirementList = this.requirements.filter((r) => {
                    if (r.modality_id == this.procedure_modality_id && r.number != 0) {
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

                this.requirementList =  this.requirementList.groupBy('number');
            },
            getAditionalRequirements(){
                if(!this.procedure_modality_id){this.aditionalRequirements = []}                
                this.aditionalRequirements = this.requirements.filter((requirement) => {                    
                    if (requirement.modality_id == this.procedure_modality_id && requirement.number == 0) {
                        return requirement;
                    }
                });                
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
                this.$store.commit('retFunForm/setCity',selectedText)
            },
            groupNumbers(number){
                if (parseInt(number) == parseInt(this.my_index)) {
                    this.my_index++;
                    return true;
                }
                return false;
            },
        },
	}
</script>