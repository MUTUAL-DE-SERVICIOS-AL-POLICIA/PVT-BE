<script>
export default {
  props:[
      'retirement-fund'
  ],
  mounted(){
      console.log("Hola mundo");
  },
  data(){
      return{
          editing: false,
          form:{
              
          }
      }
  },
  methods:{
      toggle_editing: function () {
			this.editing = !this.editing;
		},
  }

}
</script>
