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
				pension_entity: this.affiliate.pension_entity,
				values:{
						date_entry: this.affiliate.date_entry,
						item: this.affiliate.item,
						type: this.affiliate.type
					}
		
			}
		},
		created() {

		},
		computed:{
			state_name: function(){
				return !!this.state? this.state.name:'';
			},
			category_name: function(){
				return !!this.category? this.category.name:'';		
			},
			degree_name: function(){
				return !!this.degree? this.degree.name:'';
			},
			pension_entity_name: function(){
				return !!this.pension_entity? this.pension_entity.name:'';
			}

		}
		,
		methods: {
			toggle_editing: function () {
				this.editing = !this.editing;
				if(this.editing==false)
				{
					this.form.affiliate_state_id = this.state.id;
					this.form.date_entry = this.values.date_entry;
					this.form.item = this.values.item;
					this.form.category_id  = this.category.id;
					this.form.degree_id  = this.degree.id;
					this.form.pension_entity_id = this.pension_entity.id;
					this.form.state_id = this.state.id;
					this.form.type = this.values.type;
					this.form.file_code = this.values.file_code;
				}
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
						this.values.date_entry = response.data.affiliate.date_entry;
						this.values.item = response.data.affiliate.item;
						this.values.type = response.data.affiliate.type; 
						this.values.file_code = response.data.affiliate.file_code;
						flash('Informacion Policial Actualizada');
					}).catch((response)=>{
						flash('Error al actualizar Informacion Policial: '+response.message,'error');
						this.show_spinner = false;
					});
			}
		}
	}
</script>