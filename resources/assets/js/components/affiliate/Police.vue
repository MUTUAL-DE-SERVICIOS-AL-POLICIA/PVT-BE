<script>
	export default{
		props:[
			'affiliate'
		],
		mounted(){
			console.log("police information");
		},
		data(){
			return{
				show_spinner:false,
				editing:false,
				form:{
					affiliate_state: !! this.affiliate.affiliate_state ? this.affiliate.affiliate_state.id : null,
					affiliate_type: this.affiliate.type,
					affiliate_date_entry: this.affiliate.date_entry,
					affiliate_item: this.affiliate.item,
					affiliate_category: !! this.affiliate.category ? this.affiliate.category.id : null,
					affiliate_degree: !! this.affiliate.degree ? this.affiliate.degree.id : null,
					affiliate_pension_entity: !! this.affiliate.pension_entity ? this.affiliate.pension_entity.id : null,
				}
			}
		},
		methods: {
			toggle_editing: function () {
				this.editing = !this.editing;
			},
			update: function () {
				
				let uri = `/update_affiliate_police/${this.affiliate.id}`;
				this.show_spinner=true;
				axios.patch(uri,this.form)
					.then(()=>{
						this.editing = false;
						this.show_spinner = false;
						flash('Informacion Policial Actualizada');
					}).catch((response)=>{
						flash('Error al actualizar Informacion Policial: '+response.message,'error');
						this.show_spinner = false;
					});
			}
		}
	}
</script>