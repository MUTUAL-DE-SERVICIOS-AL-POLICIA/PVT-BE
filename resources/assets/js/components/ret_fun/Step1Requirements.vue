
<script>
import { mapState, mapMutations } from 'vuex';
	export default{
		props:[
			'modalities',
            'requirements',
            'user',
            'cities'
		],
        data(){
            return{
                editing: false,
                modality: null,
                show_spinner: false,
                modality_id: 3,
                actual_target: 1,
                city_end_id: this.user.city_id,
            }
        },
        mounted(){
            this.$store.commit('setCity',this.cities.filter(city => city.id == this.city_end_id)[0].name);
        },
        methods:{
            onChooseModality(event){
                const options = event.target.options;
                const selectedOption = options[options.selectedIndex];
                const selectedText = selectedOption.textContent;
                var object={
                    name:selectedText,
                    id: this.modality
                }
              this.$store.commit('setModality',object);//solo se puede enviar un(1) argumento 
            },
            onChooseCity(event){
                const options = event.target.options;
                const selectedOption = options[options.selectedIndex];
                const selectedText = selectedOption.textContent;
                this.$store.commit('setCity',selectedText)
            },
          actualTarget:function(data){
                var tar = this.actual_target;
                this.actual_target = data;
                return tar;
            }
        },
        computed:{
            requirementsList(){
                var list = [];
                for(var i=0;i<this.requirements.length;i++){
                    if(this.modality == this.requirements[i].modality_id)
                        list.push(this.requirements[i]);
                }
                return list;
            },
            
        },
	}
</script>