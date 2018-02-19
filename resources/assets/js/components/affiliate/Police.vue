<script>
	export default{
		props:[
			'affiliate'
		],
		data(){
			return{
				show_spinner:false,
				editing:false,
				form:this.affiliate
				// form:{
				// 	affiliate_state: !! this.affiliate.affiliate_state ? this.affiliate.affiliate_state.id : null,
				// 	affiliate_type: this.affiliate.type,
				// 	affiliate_date_entry: this.affiliate.date_entry,
				// 	affiliate_item: this.affiliate.item,
				// 	affiliate_category: !! this.affiliate.category ? this.affiliate.category.id : null,
				// 	affiliate_degree: !! this.affiliate.degree ? this.affiliate.degree.id : null,
				// 	affiliate_pension_entity: !! this.affiliate.pension_entity ? this.affiliate.pension_entity.id : null,
				// }
			}
		},
		// computed:{
		// 	state: function(){
					
		// 		var state_name='';
		// 		console.log(this.form);
			
		// 		return state_name;
		// 	}

		// }
		// ,
		methods: {
			toggle_editing: function () {
				this.editing = !this.editing;
				console.log(this.form);
			},
			update: function () {	
				let uri = `/update_affiliate_police/${this.affiliate.id}`;
				this.show_spinner=true;
				axios.patch(uri,this.form)
					.then(response=>{
						this.editing = false;
						this.show_spinner = false;
						console.log(response.data);	
						this.form = response.data;
						console.log(this.form);
						// this.form.affiliate_state_id = response.data.affiliate_state_id;
						// this.form.affiliate_type = response.data.type;
						// this.form.affiliate_date_entry_id = response.data.date_entry;
						// this.form.affiliate_category_id = response.data.category_id;
						// this.form.affiliate_degree_id = response.data.degree_id;
						// this.form.affiliate_pension_entity_id = response.data.pension_entity_id;

						//this.form.affiliate_category = response.data.category.id;

						flash('Informacion Policial Actualizada');
					}).catch((response)=>{
						flash('Error al actualizar Informacion Policial: '+response.message,'error');
						this.show_spinner = false;
					});
			}
		}
	}
</script>