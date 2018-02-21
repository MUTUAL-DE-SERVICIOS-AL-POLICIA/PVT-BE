<script>
export default {
  props:[
      'retirement_fund',
      'rf_city_start',
      'rf_city_end',
      'rf_procedure_modality'
  ],
  mounted(){
      console.log("cechuz y karem ");
  },
  data(){
      return{
          editing: false,
          form:this.retirement_fund,
          city_end: this.rf_city_end,
          city_start: this.rf_city_start,
          procedure_modality: this.rf_procedure_modality
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
      // console.log(this.form);
      // console.log(this.city_s);
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
            console.log('Lechuza y Karem');
            // console.log(response.data);

            flash('Informacion Policial Actualizada');
          }).catch((response)=>{
            flash('Error al actualizar Informacion Policial: '+response.message,'error');
            this.show_spinner = false;
          });
      }
  }

}
</script>
