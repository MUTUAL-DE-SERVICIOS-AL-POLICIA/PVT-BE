<template>
    <div>
        <beneficiary v-for="benef in beneficiaries" :beneficiary="benef" :key='benef.id'></beneficiary>
        <div class="row">
            <button class="btn btn-success" @click="addBeneficiary()" type="button" ><i class="fa fa-plus"></i></button>
        </div>
    </div>
</template>

<script>
import beneficiary from './Beneficiary.vue'
export default {
  props:[
      'items'
  ],
  data(){
      return{
          beneficiaries: this.items
      }
  },
  mounted(){
      console.log(this.items)
  },
  components:{
      beneficiary
  },
  methods: {
      addBeneficiary(){
          let beneficiary = {
              'id':100,
            'name': "",
            'first_shortened': "",
            'second_shortened': "",
            'third_shortened': ""
        }
        this.beneficiaries.push(beneficiary);
        console.log(this.beneficiaries.length);
      }
  }
}
</script>
