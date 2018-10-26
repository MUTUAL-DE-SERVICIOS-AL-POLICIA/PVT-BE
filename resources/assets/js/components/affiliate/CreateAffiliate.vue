<script>
import { flashErrors } from "../../helper.js";
	export default{
		props:[
            'cities'
		],
        data(){
            return{
                affiliate:{}
            }
        },
        created:function(){
            // if(!this.city_birth){
            //
            // let city_id =this.affiliate.city_birth_id;
            // this.city_birth=this.cities.filter(function(city) {
            //     return city.id==city_id;
            // })[0];
            // city_id =this.affiliate.city_identity_card_id;
            // this.city_identity_card=this.cities.filter(function(city) {
            //     return city.id==city_id;
            // })[0];
            // }
        },
        computed:{
            
        },
        methods:{
            create(){
                //console.log(this.affiliate);
                let url = '/affiliate';
                axios.post(url, this.affiliate)
                .then(response => {
                    flash('Afiliado registrado');
                });
            },
        },
	}
</script>