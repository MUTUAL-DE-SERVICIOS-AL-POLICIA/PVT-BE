<script>
	export default{
		props:[
			'affiliate'
		],
		data(){
			return{
				show_spinner:false,
				editing:false,
				form:this.affiliate,
				state: this.affiliate.affiliate_state,
				category: this.affiliate.category,
				degree: this.affiliate.degree,
				pension_entity: this.affiliate.pension_entity
		
			}
		},
		computed:{
			state_name: function(){
				// console.log('reactividad hdp ');
				return !!this.state? this.state.name:'';
			},
			category_name: function(){
				// console.log('reactividad hdp 2 ');
				return !!this.category? this.category.name:'';		
			},
			degree_name: function(){
				// console.log('reactividad hdp 3');
				return !!this.degree? this.degree.name:'';
			},
			pension_entity_name: function(){
				// console.log('reactividad hdp 4');
				return !!this.pension_entity? this.pension_entity.name:'';
			}

		}
		,
		methods: {
			toggle_editing: function () {
				this.editing = !this.editing;
			},
			update: function () {	
				let uri = `/update_affiliate_police/${this.affiliate.id}`;
				this.show_spinner=true;
				axios.patch(uri,this.form)
					.then(response=>{
						this.editing = false;
						this.show_spinner = false;
						this.form = response.data.affiliate;
						this.state = response.data.state;
						this.category = response.data.category;
						this.degree = response.data.degree;
						this.pension_entity = response.data.pension_entity;
						console.log('Lechuza y Karem');
						flash('Informacion Policial Actualizada');
					}).catch((response)=>{
						flash('Error al actualizar Informacion Policial: '+response.message,'error');
						this.show_spinner = false;
					});
			}
		}
	}
</script>