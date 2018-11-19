<script>
export default {
  props:[
      'direct_contribution',
      'city_start',
      'city_end',
      'procedure_modality',
      'states',
      'read'
  ],
  data(){
      return{
          editing: false,
          form:this.direct_contribution,
          city_end: this.city_end,
          city_start: this.city_start,
          procedure_modality: this.procedure_modality,          
          values:{
              reception_date: this.direct_contribution.reception_date
          }
      }
  },
  created(){
    //   console.log(this.read);
  },
  computed:{
     city_start_name: function(){
          return !!this.city_start? this.city_start.name:'';
     },
     city_end_name: function(){
          return !!this.city_end? this.city_end.name:'';
     },
     procedure_modality_name: function(){
          return !!this.procedure_modality? this.procedure_modality.name:'';
     } 

  },
  methods:{
      toggle_editing: function () {
			this.editing = !this.editing;
        if(this.editing==false)
        {
            this.form.reception_date = this.values.reception_date;
            this.form.city_end_id = this.city_end.id;
            this.form.city_start_id = this.city_start.id;
            this.form.procedure_modality_id = this.procedure_modality.id; 
        }
		  },
      getState: function(state_id){
          var i;
          for(i =0; i<this.states.length;i++){
              if(this.states[i].id == state_id)
                return this.states[i].name;
          }
      },
      update: function () {  
        let uri = `/update_information_direct_contribution`;
        this.show_spinner=true;
        axios.patch(uri,this.form)
          .then(response=>{
            this.editing = false;
            this.show_spinner = false;
            this.procedure_modality = response.data.procedure_modality;
            this.city_end = response.data.city_end;
            this.city_start = response.data.city_start;
            this.form = response.data.direct_contribution;
            this.values.reception_date = response.data.direct_contribution.reception_date;
            flash('Informacion Policial Actualizada');
          }).catch((response)=>{
            flash('Error al actualizar Informacion Policial: '+response.message,'error');
            this.show_spinner = false;
          });
      }
  }

}
</script>
