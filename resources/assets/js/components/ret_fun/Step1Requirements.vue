
<script>
import { mapState, mapMutations } from 'vuex';
	export default{
		props:[
			'modalities',
            'requirements',
            'user',
            'cities',
            'procedureTypes'
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
                procedure_type_id:2,
                my_index: 1,
                modalitiesFilter: []
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
            },
            onChooseModality(event){
                const options = event.target.options;
                const selectedOption = options[options.selectedIndex];
                const selectedText = selectedOption.textContent;
                var object={
                    name:selectedText,
                    id: this.modality
                }
                this.getRequirements();
              this.$store.commit('setModality',object);//solo se puede enviar un(1) argumento 
            },
            getRequirements(){
                this.requirementList = this.requirements.filter((r) => {
                    if (r.modality_id == this.modality) {
                        r['status'] = false;
                        return r;
                    }
                });
                // this.requirementList = this.requirementList.reduce(function(r, v) {
                //     r[v.number] = r[v.number] || [];
                //     r[v.number].push(v);
                //     return r;
                // }, Object.create(null));
            },
            checked(index){
                this.requirementList[index].status =  ! this.requirementList[index].status;
            },
            onChooseCity(event){
                const options = event.target.options;
                const selectedOption = options[options.selectedIndex];
                const selectedText = selectedOption.textContent;
                this.$store.commit('setCity',selectedText)
            },
            groupNumbers(number){
                // return (parseInt(number) % 2) == 0;
                console.log(`number: ${number}, index: ${this.my_index}, bool: ${number == this.my_index}`);
                if (parseInt(number) == parseInt(this.my_index)) {
                    this.my_index++;
                    return true;
                }
                return false;
            },
        //   actualTarget:function(data){
        //         var tar = this.actual_target;
        //         this.actual_target = data;
        //         return tar;
        //     }
        },
        // computed:{
        //     requirementsList(){
        //         var list = [];
        //         for(var i=0;i<this.requirements.length;i++){
        //             if(this.modality == this.requirements[i].modality_id)
        //                 list.push(this.requirements[i]);
        //         }
        //         return list;
        //     },
            
        // },
	}
</script>