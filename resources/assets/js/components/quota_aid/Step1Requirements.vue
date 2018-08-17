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
                modality: null,
                show_spinner: false,
                modality_id: 3,
                actual_target: 1,
                city_end_id:this.user.city_id,
                procedure_type_id:3,
                my_index: 1,
                modalitiesFilter: [],
            }
        },
        mounted(){
            this.$store.commit('setCity',this.cities.filter(city => city.id == this.city_end_id)[0].name);
            this.onChooseProcedureType();
        },
        methods:{
            onChooseProcedureType(){
                this.modalitiesFilter = this.modalities.filter((m) => {
                    return m.procedure_type_id == this.procedure_type_id;
                })
                this.modality = null;
            },
            onChooseModality(event){
                const options = event.target.options;
                const selectedOption = options[options.selectedIndex];
                if (selectedOption) {
                    const selectedText = selectedOption.textContent;
                    var object={
                        name:selectedText,
                        id: this.modality
                    }
                    this.$store.commit('setModality',object);//solo se puede enviar un(1) argumento 
                }
                this.getRequirements();
            },
            getRequirements(){
                this.requirementList = this.requirements.filter((r) => {
                    if (r.modality_id == this.modality) {
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
                // this.requirementList = this.requirementList.reduce(function(r, v) {
                //     r[v.number] = r[v.number] || [];
                //     r[v.number].push(v);
                //     return r;
                // }, Object.create(null));
                // console.log(this.requirementList);
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
                this.$store.commit('setCity',selectedText)
            },
            groupNumbers(number){                                
                if (parseInt(number) == parseInt(this.my_index)) {
                    this.my_index++;
                    return true;
                }
                return false;
            },
	}
}
</script>