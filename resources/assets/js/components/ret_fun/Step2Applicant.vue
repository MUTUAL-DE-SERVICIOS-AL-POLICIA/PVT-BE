<script>
export default {
  props:[
    'modality',
    'cities',
  ],
  data(){
    return{
      applicant_type: '',
      show_advisor_form: false,
      show_apoderado_form: false,
    }
  },
  methods: {
    change_applicant: function() {
      if(this.applicant_type  === 'Tutor'){
        this.show_advisor_form = !this.show_advisor_form;
        this.show_apoderado_form = false;
        // bus.$on('modality_on_f', function(id){
        //   console.log('*************'+id)
        // })
        console.log()
        return;
      }
      if(this.applicant_type  === 'Apoderado'){
        this.show_apoderado_form = !this.show_advisor_form;
        this.show_advisor_form = false;
        return;
      }
      if(this.applicant_type  === 'Beneficiario'){
        this.show_apoderado_form = false;
        this.show_advisor_form = false;
        return;
      }
      
    }
  }
}
</script>
