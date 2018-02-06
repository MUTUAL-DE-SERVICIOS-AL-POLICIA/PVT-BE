<template>
  <div>

      <select name="" id="" v-model="nuevo">
          <option value="1">Uno</option>
          <option value="2">Uno</option>
          <option value="3">Uno</option>
      </select>

      {{nuevoL}}
  </div>
</template>
<script>
export default {
  props: ["modalities", "requirements", "modality"],
  data() {
    return {
      modality_id: 3,
      actual_target: 1,
      nuevo: "1"

    };
  },
  methods: {
    actualTarget: function(data) {
      var tar = this.actual_target;
      this.actual_target = data;
      return tar;
    }
  },

  computed: {
      nuevoL(){
          return parseInt(this.nuevo) + 1;
      },
    requirementsList() {
      var list = [];
      for (var i = 0; i < this.requirements.length; i++) {
        if (this.modality == this.requirements[i].modality_id)
          list.push(this.requirements[i]);
      }
      return list;
    }
  }
};
</script>
