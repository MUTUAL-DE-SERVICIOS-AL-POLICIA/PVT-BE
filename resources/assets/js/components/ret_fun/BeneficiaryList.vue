<template>
    <div>
        <ret-fun-beneficiary v-for="benef in beneficiaries" :beneficiary="benef" :key='benef.id' :cities="cities"></ret-fun-beneficiary>
        <div class="row">
            <button class="btn btn-success" @click="addBeneficiary()" type="button" ><i class="fa fa-plus"></i></button>
        </div>
    </div>
</template>

<script>
import RetFunBeneficiary from './Beneficiary.vue'
export default {
  props:[
      'items',
      'cities'
  ],
  data(){
      return{
          beneficiaries: this.items
      }
  },
  mounted(){
  },
  components:{
      RetFunBeneficiary
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
      }
  }
}
</script>
