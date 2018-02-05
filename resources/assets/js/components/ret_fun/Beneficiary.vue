<template>
  <input type="text" :value="beneficiary.id">
</template>
<script>
export default {
  props:[
      "beneficiary"
  ],
  mounted(){
    console.log(`beneficiary ${this.beneficiary.name}`);
  }
}
</script>

