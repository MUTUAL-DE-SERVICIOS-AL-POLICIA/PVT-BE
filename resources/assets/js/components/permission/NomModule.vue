<script>
export default {
 
  props:[
			'roles'
		],
  data() {
    return {
      nombre: "",
      module_id: null,
      roles_list: this.roles,
    }
  },
  computed: {
     
  },
  methods: {
      tab_role: function(role)
      { 
         // console.log(role);
          return '#Tab_'+role.id;
      },
      tab_role_action: function (role)
      { 
          console.log(role.id);
          return 'Tab_'+role.id;
      }
  }
};
</script>