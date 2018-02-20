<script>
	export default{
		props:[
			'affiliate'
		],
        data(){
            return{
                editing: false,
                show_spinner: false,
                form:this.affiliate,
                city_birth: this.affiliate.city_birth,
                city_identity_card: this.affiliate.city_identity_card,
                first_name:{
                    value: this.affiliate.first_name,
                    edit: false,
                }
            }
        },
        computed:{
            age: function(){
                if(this.form.birth_date){
                    var birthday = +new Date(this.form.birth_date); 
                    return~~ ((Date.now() - birthday) / (31557600000));
                }else
                {
                    return '';
                }
            },
            city_birth_name: function(){
                console.log("reactividad hdp 0");
                return !!this.city_birth?this.city_birth.name:'';
            },
            city_identity_card_name: function(){
                console.log('reactividad hdp 1');
                return !!this.city_identity_card?this.city_identity_card.first_shortened:'';
            },
            gender_name: function(){
                    var g = '';
                    if(this.form.gender=="F")
                    {
                        g ='Femenino';
                    }
                    if(this.form.gender=="M") 
                    {
                       g = 'Masculino';
                    }
                    return g;
                // return !!this.form.gender==='F'?'Femenino':'Masculino'; 
            },
            civil_status_name:function(){
                var st = '';
                switch(this.form.civil_status)
                {
                    case "S":
                        st= 'Soltero(a)';
                        break;
                    
                    case "D":
                        st= 'Divorciado(a)';
                        break;
                    
                    case "V":
                        st= 'Viudo(a)';
                        break;

                    case "C":
                        st= 'Casado(a)';
                        break;

                }   
                return st;
            }

        },
        methods:{
            edit_first_name: function(){
            },
            toggle_editing:function () {
                this.editing = !this.editing;
                console.log(this.form);
            },
            update () {
                let uri = `/update_affiliate/${this.affiliate.id}`;
                this.show_spinner=true;
                axios.patch(uri,this.form)
                    .then(response=>{
                        this.editing = false;
                        this.show_spinner=false;
                        this.form = response.data.affiliate;
                        this.city_birth = response.data.city_birth;
                        this.city_identity_card = response.data.city_identity_card;
                        // this.first_name = response.data.affiliate.first_name;                          
                        flash('Informacion del Afiliado Actualizada');
                    }).catch((response)=>{
                        this.show_spinner=false;
                        flash('Error al actualizar el afiliado: '+response.message,'error');
                    })
            }
        }
	}
</script>