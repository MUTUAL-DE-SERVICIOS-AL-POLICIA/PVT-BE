<script>
import {scroller} from 'vue-scrollto/src/scrollTo'
	export default{
		props:[
            'beneficiaries2',
			'beneficiariesBackend',
			'originalBeneficiariesBackend',
            'cities',
            'kinships',
            'quotaAid',
            'procedureModalityId',
		],
        data(){
            return{
                editing: false,
                show_spinner: false,
                iterator:-1,
                beneficiaries: this.beneficiariesBackend,
            }
        },
        computed:{
            // age: function(){
            //     var birthday = +new Date(this.beneficiary.birth_date); 
            //     if(this.beneficiary.birth_date!=null){

            //         return~~ ((Date.now() - birthday) / (31557600000));
            //     }else
            //     {
            //         return '';
            //     }
            // },
        },
        methods:{
            toggle_editing () {
			this.editing = !this.editing;
                if(this.editing==false)
                {
                    this.beneficiaries =  this.beneficiaries2;
                }
            },
            cancel(){
                this.beneficiaries =  this.originalBeneficiariesBackend;
                this.originalBeneficiariesBackend = this.beneficiaries;
                this.toggle_editing();
            },
            getCity (id){
                var i=0;
                for(i=0;i<this.cities.length;i++)
                    if(this.cities[i].id == id)
                        return this.cities[i].first_shortened;
                return "S/N";
            },
            getKinship (id){
                var i=0;
                for(i=0;i<this.kinships.length;i++)
                    if(this.kinships[i].id == id)
                        return this.kinships[i].name;
                return "S/N";
            },
            update () {
                let uri = `/update_beneficiaries/${this.retFunId}`;
                this.show_spinner=true;
                console.log(this.beneficiaries);
                
                axios.patch(uri,this.beneficiaries)
                .then((response)=>{
                    this.editing = false;
                    this.show_spinner=false;
                    this.beneficiaries = response.data.beneficiaries;
                    flash('Informacion del Afiliado Actualizada  '+response.data);
                }).catch((response)=>{
                    this.show_spinner=false;
                    this.beneficiaries = this.ben;
                    flash('Error al actualizar el afiliado: '+response.message,'error');
                })
            },
            addBeneficiary(){
                let beneficiary = {
                        first_name: null,
                        second_name: null,
                        last_name: null,
                        mothers_last_name: null,
                        surname_husband: null,
                        identity_card: null,
                        city_identity_card_id: null,
                        birth_date: null,
                        kinship: null,
                        state: false,
                }
                if(this.beneficiaries.length >= 0){
                    let last_beneficiary=this.beneficiaries[this.beneficiaries.length-1];
                    if (last_beneficiary.first_name) {
                        this.beneficiaries.push(beneficiary);
                    }
                }else{
                        this.beneficiaries.push(beneficiary);
                }
                setTimeout(() => {
                    if (this.$children[this.$children.length-1].$refs.identitycard) {
                        this.$children[this.$children.length-1].$refs.identitycard.focus();
                        const scrollToFooterCreateBeneficiaries = scroller();
                        scrollToFooterCreateBeneficiaries(`#footerCreateBeneficiaries${this.beneficiaries.length-1}`);
                    }
                }, 100);
            },
            canAddBeneficiary(){
                return this.procedureModalityId == 1 || this.procedureModalityId == 4;
            },
            removeBeneficiary(index){
                this.beneficiaries.splice(index,1);
            },
        }
	}
</script>