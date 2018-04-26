<script>
export default {
  props:[
      'retirement_fund',
      'rf_city_start',
      'rf_city_end',
      'rf_procedure_modality'
  ],
  data(){
      return{
          editing: false,
          form:this.retirement_fund,
          city_end: this.rf_city_end,
          city_start: this.rf_city_start,
          procedure_modality: this.rf_procedure_modality,
          values:{
              reception_date: this.retirement_fund.reception_date
          }
      }
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
      update: function () {  
        let uri = `/update_information_rf`;
        this.show_spinner=true;
        axios.patch(uri,this.form)
          .then(response=>{
            this.editing = false;
            this.show_spinner = false;
            this.procedure_modality = response.data.procedure_modality;
            this.city_end = response.data.city_end;
            this.city_start = response.data.city_start;
            this.form = response.data.retirement_fund;
            this.values.reception_date = response.data.retirement_fund.reception_date;
            flash('Informacion Policial Actualizada');
          }).catch((response)=>{
            flash('Error al actualizar Informacion Policial: '+response.message,'error');
            this.show_spinner = false;
          });
      }
  }

}
</script>
