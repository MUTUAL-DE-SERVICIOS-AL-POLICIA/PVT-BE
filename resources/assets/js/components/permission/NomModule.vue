<script>
export default {
 
  props:[
			'roles',
      'permissions',
      'operations',
      'role_permissions'
		],
  data() {
    return {
      module_id: null,
      roles_list: this.roles,
      permissions_list: this.permissions,
      operations_list: this.operations,
      role_permissions_list: this.role_permissions,
      role: null,
    }
  },
  computed: {
     roles_module: function(){
        console.log('ingresndo funcion hdp');
        var lista =[];
        if(this.module_id)
        {
          for (var i = this.roles_list.length - 1; i >= 0; i--) {
            console.log(this.roles_list[i].module_id);
            if(this.roles_list[i].module_id==this.module_id){
              lista.push(this.roles_list[i]);
            }
          }
          console.log('imprimiendo los seleccionados ');
          console.log(lista);

        }
        return lista;
     },
     permissions_role: function(){
        
        var lista=[];
        if(this.role)
        {
          for (var i = this.permissions_list.length - 1; i >= 0; i--) {
            console.log(this.permissions_list[i]);
            // if(this.role.id = )
          }
        }
     }

  },
  methods: {
      SelectRol: function(role)
      { 
         this.role = role;
         // console.log(role);
      },
      CheckPermissionCreate: function(operation)
      {
        var permissions=[];
        var result = false;
        if(this.role)
        {
          for (var i = this.permissions_list.length - 1; i >= 0; i--) {
            if(this.permissions_list[i].operation_id ==operation.id && this.permissions_list[i].action_id == 1)
            {
              permissions.push(this.permissions_list[i]);
            }
          }
          console.log("cechus y karen size: "+permissions.length)
          console.log(permissions);
          // for (var i = permissions.length - 1; i >= 0; i--) {
          //   console.log(permissions[i]);
          // }
          for (var i = this.role_permissions_list.length - 1; i >= 0; i--) {
            for (var j = permissions.length - 1; i >= 0; i--) {
              if(permissions[j].id == this.role_permissions_list[i].id)
              {
                result= true;
              }
            }
             
          }

        }
        return result;
      }

  }
};
</script>