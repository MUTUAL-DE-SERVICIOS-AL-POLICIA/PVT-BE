<script>
export default {
  props: ["roles", "permissions", "operations", "role_permissions", "actions"],
  data() {
    return {
      module_id: null,
      roles_list: this.roles,
      permissions_list: [],
      operations_list: [],
      role_permissions_list: [],
      opertaion_list_role: null,
      role: null,
      list_to_send: [],
      sw: false,
      loadingButton: false
    };
  },
  computed: {
    roles_module: function() {
      return this.roles_list.filter(r => r.module_id == this.module_id);
    },
    getOperationPermissionList: function() {
      this.list_to_send = [];
      if (this.role) {
        this.operations_list.forEach(operation => {
          var obj = {
            operation_id: operation.id,
            name: operation.name
          };
          this.actions.forEach(action => {
            obj[action.name] = this.CheckPermission(operation, action.id);
          });
          this.list_to_send.push(obj);
        });
      }

      return this.list_to_send;
    }
  },
  methods: {
    SelectRol: function(role) {
      this.role = role;
      this.operations_list = this.operations.filter(
        o => o.module_id == this.module_id
      );
      this.permissions_list = this.permissions.filter(
        p => p.module_id == this.module_id
      );
      this.role_permissions_list = this.role_permissions.filter(
        r => r.role_id == this.role.id
      );
    },
    selectAll: function() {
      this.sw = !this.sw;
      for (var i = 0; i < this.list_to_send.length; i++) {
        if (this.sw) {
          this.list_to_send[i].create = true;
          this.list_to_send[i].read = true;
          this.list_to_send[i].update = true;
          this.list_to_send[i].delete = true;
          this.list_to_send[i].print = true;
        } else {
          this.list_to_send[i].create = false;
          this.list_to_send[i].read = false;
          this.list_to_send[i].update = false;
          this.list_to_send[i].delete = false;
          this.list_to_send[i].print = false;
        }
      }
    },
    CheckPermission: function(operation, action_id) {
      var permissions = [];
      var result = false;
      if (this.role) {
        this.permissions_list.forEach(p => {
          if (p.operation_id == operation.id && p.action_id == action_id) {
            permissions.push(p.id);
          }
        });
        this.role_permissions_list.forEach(r => {
          if (permissions.includes(r.permission_id)) {
            result = true;
          }
        });
      }
      return result;
    },
    async update() {
      this.loadingButton = true;
      var lista = this.list_to_send;
      var data = {
        module_id: this.module_id,
        role_id: this.role.id,
        permissions_list: lista
      };
      await axios
        .post("/permission", data)
        .then(function(resp) {
          flash("Informacion Actualizada");
        })
        .catch(function(resp) {
          flash("Error: " + resp.message, "error");
        });
      this.loadingButton = false;
    }
  }
};
</script>