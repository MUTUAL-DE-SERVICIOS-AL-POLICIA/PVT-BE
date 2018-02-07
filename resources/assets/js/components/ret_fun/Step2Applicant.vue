<script>
export default {
  props:[
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
      if(this.applicant_type  === '2'){
        this.show_advisor_form = !this.show_advisor_form;
        this.show_apoderado_form = false;
        return;
      }
      if(this.applicant_type  === 'Apoderado'){
        this.show_apoderado_form = !this.show_advisor_form;
        this.show_advisor_form = false;
        return;
      }
      if(this.applicant_type  === '1'){
        this.show_apoderado_form = false;
        this.show_advisor_form = false;
        return;
      }
      console.log(this.applicant_type);
    }
  }
}
</script>