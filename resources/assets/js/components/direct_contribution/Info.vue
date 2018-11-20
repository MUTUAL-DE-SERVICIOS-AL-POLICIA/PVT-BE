<script>
export default {
  props:[
      'direct_contribution',
      'city_id',
      'city_end',
      'procedure_modality',
      'states',
      'read'
  ],
  data(){
      return{
          editing: false,
          form: this.direct_contribution,
          values:{
              date: this.direct_contribution.date
          }
      }
  },
  created(){
    //   console.log(this.read);
  },
  computed:{
     city_name: function(){
          return !!this.city_id? this.city_id.name:'';
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
            this.form.date = this.values.date;
            this.form.city_id = this.city.id;
            //this.form.procedure_modality_id = this.procedure_modality.id; 
            this.form.document_number = this.document_number;            
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
            //this.procedure_modality = response.data.procedure_modality;
            this.city_id = response.data.city_id;
            this.form = response.data.direct_contribution;            
            flash('Informacion actualizada');
          }).catch((response)=>{
            flash('Error al actualizar Información: '+response.message,'error');
            this.show_spinner = false;
          });
      }
  }

}
</script>
