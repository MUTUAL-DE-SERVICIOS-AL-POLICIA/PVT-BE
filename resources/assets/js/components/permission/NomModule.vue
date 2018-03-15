<script>
export default {
 
  props:[
			'roles',
      'permissions',
      'operations',
      'role_permissions',
      'actions'
		],
  data() {
    return {
      module_id: null,
      roles_list: this.roles,
      permissions_list: this.permissions,
      operations_list: this.operations,
      actions_list: this.actions,
      role_permissions_list: this.role_permissions,
      opertaion_list_role: null,
      role: null,
      list_to_send: [],
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
     },
     getOperationPermissionList: function()
     {
        this.list_to_send =[];
        if(this.role)
        {
          console.log('refrescando this.list_to_send CyA');
          for (var i = this.operations_list.length - 1; i >= 0; i--) {
          
            if(this.operations_list[i].module_id == this.module_id)
            {
              var obj = {operation_id: this.operations_list[i].id , name: this.operations_list[i].name};
              for (var j = 0; j < this.actions_list.length; j++) {
        
                obj[this.actions_list[j].name] = this.CheckPermission(this.operations_list[i],this.actions_list[j].id); 
              }
              this.list_to_send.push(obj);
            }
          }
        }

        return this.list_to_send;
     }

  },
  methods: {
      SelectRol: function(role)
      { 
         this.role = role;
         // var obj={operation_id: 1 , name: 'cechus y anita'};
         // console.log(obj);
         // for (var i = this.actions.length - 1; i >= 0; i--) {
           
         //   obj[this.actions[i].name] = this.CheckPermissionCreate(this.roles_list[1]);
         // }
         // console.log(obj);
         // console.log(role);
      },
      CheckPermission: function(operation,action_id)
      {
        console.log('CheckPermission');
        var permissions=[];
        var result = false;
        if(this.role)
        {
          for (var i = this.permissions_list.length - 1; i >= 0; i--) {
            if(this.permissions_list[i].operation_id ==operation.id && this.permissions_list[i].action_id == action_id)
            {
              permissions.push(this.permissions_list[i]);
            }
          }
          console.log("cechus y karen size: "+permissions.length)
          console.log(permissions);
          // for (var i = permissions.length - 1; i >= 0; i--) {
          //   console.log(permissions[i]);
          // }
          for (var i = 0; i < this.role_permissions_list.length; i++) {
            for (var j = 0; j < permissions.length; j++) {
              if(permissions[j].id == this.role_permissions_list[i].permission_id && this.role.id == this.role_permissions_list[i].role_id)
              {
                result= true;
              }
            }
          }

        }
        return result;
      },
      update: function () { 

               var lista = this.list_to_send;
               var data = {module_id:this.module_id ,role_id: this.role.id, permissions_list: lista };
               axios.post('/permission', data )
                    .then(function (resp) {
                        // this.$router.push({path: '/'});
                        console.log(resp);
                        flash('Informacion CYK Actualizada');
                    })
                    .catch(function (resp) {
                        console.log(resp);
                        flash('Error lechuza: '+resp.message,'error');
                    });
      }


  }
};
</script>