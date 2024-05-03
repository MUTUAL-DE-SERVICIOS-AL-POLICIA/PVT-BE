<script>
	export default{
		props:[
			'affiliate',
			'typeEcoCom',
			'ecoComId',
			'categories',
			'ecoCom',
			'user'
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
						type: this.affiliate.type
					},
				calculateCategoryId: null,
			}
		},
		created() {
			if(this.form.service_years || this.form.service_months){
				this.getCalculateCategory()
			}
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
			},
			isInclusion: function() {
				if(this.ecoCom)
					if(this.ecoCom.eco_com_reception_type.id == 1)
						return false
					else return this.ecoCom.eco_com_reception_type.id == 2
				else return true
			},
			isRehabilitation: function() {
				if(this.ecoCom)
					if(this.ecoCom.eco_com_reception_type.id == 1)
						return false
					else return this.ecoCom.eco_com_reception_type.id == 3
				else return false
			},
			itsUsual: function() {
				if(this.ecoCom)
				return this.ecoCom.eco_com_reception_type.id == 1
			}
		}
		,
		methods: {
			getCalculateCategory(){
				let years = this.form.service_years;
				let months = this.form.service_months;
				if (years < 0 || months < 0 || years >100 || months > 12 ) {
					return "error";
				}
				if (months > 0) {
					years++;
				}
				let category = this.categories.find(c =>{
					return c.from <= years && c.to >= years
				})
				if(!!category){
					this.form.category_id = category.id
				}
			},
			validationRoles(module, role) {
				let rolesPermited = []
				if(parseInt(module) !== 2) { // si no es complemento economico
					rolesPermited = [ 28, 43 ] // solo estos roles pueden editar
				} else {
					if(parseInt(module) == 2 && (this.isInclusion || this.isRehabilitation)) { // si  el tramite es inclusión o rehabilitación
						rolesPermited = [ 2, 4, 5, 22, 23, 24, 25, 26, 27, 52, 68 ] // solo estos roles pueden editar
					}
				}
				return rolesPermited.indexOf(parseInt(role)) !== -1
			},
			canEdit() {
				if(this.ecoCom === undefined) return true
				if(this.isInclusion || this.isRehabilitation) {
					const role_current = this.ecoCom.wf_state.role_id // rol actual
					const roles = this.user.roles // roles del usuario
					if(roles) { // tiene roles el usuario
						const filter = roles.filter(obj => obj.id == role_current)
						const found = roles.find(obj => {
							return obj.id == 4 || obj.id == 5 // jefatura y área técnica
						});
						if(filter.length !== 0 || found ) { // si se ha encontrado un rol con el trámite o los roles 4 o 5
							return true
						}
					}
					return false
				} else if(this.itsUsual) {
					const roles = this.user.roles
					if(roles) {
						const found = roles.find(obj => {
							return obj.id == 4 || obj.id == 5
						})
						if( found ) return true
					}
					return false
				}
			},
			toggle_editing: function () {
				this.editing = !this.editing;
				if(this.editing==false)
				{
					this.form.affiliate_state_id = this.state.id;
					this.form.date_entry = this.values.date_entry;
					this.form.category_id  = this.category.id;
					this.form.degree_id  = this.degree.id;					
					this.form.pension_entity_id = this.pension_entity.id;
					this.form.state_id = this.state.id;
					this.form.type = this.values.type;
				}
			},
			update: function (){
				let uri = `/update_affiliate_police/${this.affiliate.id}`;
				if (this.typeEcoCom == true) {
					uri = `/update_affiliate_police_eco_com`;
				}
				console.log(`updating ${uri}`)
				this.form.eco_com_id= this.ecoComId;
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
						this.values.type = response.data.affiliate.type; 
						flash('Informacion Policial Actualizada');
					}).catch((response)=>{
						flash('Error al actualizar Informacion Policial: '+response.message,'error');
						this.show_spinner = false;
					});
			}
		}
	}
</script>