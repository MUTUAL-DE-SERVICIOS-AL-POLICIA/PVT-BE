<script>
//import { mapState, mapMutations } from 'vuex';
	export default{
		props:[
            'quota_aid',
			'modalities',
            'requirements',
            'user',
            'cities',
            'procedureTypes',
            'submitted',
            'rol'
            //'showRequirementsError',            
		],
        data(){
            return{
                requirementList: [],
                aditionalRequirements: [],
                aditionalRequirementsSelected: [],
                modality: null,
                show_spinner: false,
                modality_id: 14,
                actual_target: 1,
                city_end_id:this.user.city_id,
                procedure_type_id:2,
                my_index: 1,
                modalitiesFilter: [],
                quota_aid_id: 428,
                editing:false,
                counter_aditional_document: 1000
            }
        },
        created(){
            // this.submitted.forEach(item => {
            //     console.log(item.procedure_requirement_id);
            // });
        },
        mounted(){
            // console.log(this.submitted);
            //this.$store.commit('setCity',this.cities.filter(city => city.id == this.city_end_id)[0].name);
            this.onChooseProcedureType();
            this.modality = this.quota_aid.procedure_modality_id;            
            this.getRequirements();
            //console.log('dataaa');
            //console.log(this.requirementList[0]);
        },
        methods:{
            onChooseProcedureType(){
                this.modalitiesFilter = this.modalities.filter((m) => {
                    return m.procedure_type_id == this.procedure_type_id;
                })
                this.modality = null;
            },
            onChooseModality(event){
                // const options = event.target.options;
                // const selectedOption = options[options.selectedIndex];
                // if (selectedOption) {
                //     const selectedText = selectedOption.textContent;
                //     var object={
                //         name:selectedText,
                //         id: this.modality
                //     }
                    //this.$store.commit('setModality',object);//solo se puede enviar un(1) argumento 
                //}
                //this.getRequirements();
            },
            toggle_editing:function () {
                this.editing = !this.editing;
                setTimeout(() => {
                    $(".chosen-select").chosen({ width: "100%" }).trigger("chosen:updated");
                }, 500);
            },
            getRequirements(){                               
                this.requirementList = this.requirements.filter((r) => {
                    if(r.number == 0 && this.rol == 38) {
                        r.number = r.number+this.counter_aditional_document;
                        this.counter_aditional_document++;
                    }

                    if (r.modality_id == this.modality && r.number != 0) {
                        //if(this.submitted[r.number] == r.id){
                            // console.log('match 1'+this.modality_id);
                       let submit_document = this.submitted.find(function(document){ return document.procedure_requirement_id === r.id });                       
                    //    console.log(this.rol);
                        if(this.rol!=38){ //revision legal
                        
                            if(submit_document){
                                r['status'] = true;
                                r['background'] = 'bg-success-green';
                                r['comment'] = submit_document.comment;
                                
                            }
                            else{
                                r['status'] = false;
                                r['background'] = '';
                                r['comment'] = null;
                            }                            
                            return r;
                        }else{                            
                            if(submit_document)
                            {                                
                                if(submit_document.is_valid){                                    
                                    r['status'] = true;
                                    r['background'] = 'bg-success-green';
                                    r['comment'] = submit_document.comment;
                                    r['submit_document_id'] = submit_document.id;
                                }
                                else{                                    
                                     r['status'] = false;
                                    r['background'] = '';
                                    r['comment'] = submit_document.comment;
                                    r['submit_document_id'] = submit_document.id;
                                }                                
                                return r;
                            }
                        }                        
                    }
                });
                // this.requirementList = this.requirementList.filter((r) => {
                //     console.log("asdf");
                // });
                

                //console.log("datos up");
                //console.log(this.requirements);
                Array.prototype.groupBy = function(prop) {
                    return this.reduce(function(groups, item) {
                        const val = item[prop]
                        groups[val] = groups[val] || []
                        groups[val].push(item)                        
                        return groups
                    }, {})
                }
                

                this.requirementList =  this.requirementList.groupBy('number');
                this.getAditionalRequirements();
                // var size = 0, key;
                // for (key in this.requirementList) {
                // if (this.requirementList.hasOwnProperty(key)) size++;
            // }
            // console.log(size);         
                // this.requirementList = this.requirementList.reduce(function(r, v) {
                //     r[v.number] = r[v.number] || [];
                //     r[v.number].push(v);
                //     return r;
                // }, Object.create(null));
                
            },
            getAditionalRequirements(){
                if(!this.modality){this.aditionalRequirements = []}
                if(!this.modality){this.aditionalRequirementsSelected = []}
                this.aditionalRequirements = this.requirements.filter((requirement) => {                    
                    if (requirement.modality_id == this.modality && requirement.number == 0) {
                        let submit_document = this.submitted.find(function(document){ return document.procedure_requirement_id === requirement.id });
                        if(!submit_document)
                            return requirement;
                    }
                });
                this.aditionalRequirementsSelected = this.requirements.filter((requirement) => {                    
                    if (requirement.modality_id == this.modality && requirement.number == 0) {
                        let submit_document = this.submitted.find(function(document){ return document.procedure_requirement_id === requirement.id });
                        if(submit_document)
                            return requirement;
                    }
                });
                
                setTimeout(() => {
                    $(".chosen-select").chosen({ width: "100%" }).trigger("chosen:updated");
                }, 500);                
            }, 
            checked(index, i){
                if(this.editing){
                    for(var k = 0; k < this.requirementList[index].length; k++ ){
                        if (k != i ) {
                        this.requirementList[index][k].status = false;
                        this.requirementList[index][k].background = 'bg-warning-yellow';

                        }
                    }
                    this.requirementList[index][i].status =  ! this.requirementList[index][i].status;
                    this.requirementList[index][i].background = this.requirementList[index][i].background == 'bg-success-green' ? '' : 'bg-success-green';
                    // this.requirementList[index][i].status = true;
                    if (this.requirementList[index].every(r => !r.status )) {
                        for(var k = 0; k < this.requirementList[index].length; k++ ){
                            if (!this.requirementList[index][k].status) {
                                this.requirementList[index][k].background = '';
                            }
                        }
                    }
                }else{
                    
                }

            },
            isVisible(requeriment){
                // console.log(requeriment)
                if(this.rol!=38)
                {
                    if(this.editing){
                    return true; 
                    }else{
                        return requeriment.status;
                    }
                }
                else{
                    return true;
                }
            },
            onChooseCity(event){
                const options = event.target.options;
                const selectedOption = options[options.selectedIndex];
                const selectedText = selectedOption.textContent;                
            },
            groupNumbers(number){
                // return (parseInt(number) % 2) == 0;
                // console.log(`number: ${number}, index: ${this.my_index}, bool: ${number == this.my_index}`);
                if (parseInt(number) == parseInt(this.my_index)) {
                    this.my_index++;
                    return true;
                }
                return false;
            },
            store(quota_aid){
                if(this.rol!=38){
                    let uri = `/quota_aid/${this.quota_aid.id}/edit_requirements`;                
                    let req = $('#aditional_requirements').val();
                    console.log(uri);
                    axios.post(uri,
                        {
                        requirements: this.requirementList,
                        aditional_requirements: req
                        }
                    ).then(response =>{
                        flash("Verificacion Correcta");
                        this.toggle_editing();
                    
                        //this.showEconomicData = true
                        //TweenLite.to(this.$data, 0.5, { totalAverageSalaryQuotable: response.data.total_average_salary_quotable,totalQuotes: response.data.total_quotes });
                    }).catch(error =>{
                        flash("Los Datos no Coinciden", "error");
                        //this.showEconomicData = false;
                    });                
                }else{
                    let uri = `/quota_aid/${this.quota_aid.id}/legal_review/create`;                
                        axios.post(uri,
                            {
                            submit_documents: this.requirementList
                            }
                        ).then(response =>{
                            flash("Documentos actualizados correctamente.");
                            this.toggle_editing();
                        
                            //this.showEconomicData = true
                            //TweenLite.to(this.$data, 0.5, { totalAverageSalaryQuotable: response.data.total_average_salary_quotable,totalQuotes: response.data.total_quotes });
                        }).catch(error =>{
                            flash("Los Datos no Coinciden", "error");
                            //this.showEconomicData = false;
                        }); 
                }

                //console.log(this.requirementList);
            }
   
        },

	}
</script>