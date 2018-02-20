<template>
    <div>
        Solicitante
        <ret-fun-beneficiary :beneficiary="retfun.applicant"
                 :cities="cities"
                 :kinships="kinships"
                 :editable="false"
                 ></ret-fun-beneficiary>
        <ret-fun-beneficiary v-for="(beneficiary, index) in beneficiaries"
                             :beneficiary="beneficiary"
                             :key='index'
                             :cities="cities"
                             :kinships="kinships"
                             :editable="true"
                             v-on:remove="removeBeneficiary(index)">
        </ret-fun-beneficiary>
        <div class="row">
            <button class="btn btn-success" @click="addBeneficiary()" type="button" ><i class="fa fa-plus"></i></button>
        </div>
    </div>
</template>

<script>
import RetFunBeneficiary from './Beneficiary.vue'
import {mapGetters} from 'vuex';
export default {
  props:[
      'items',
      'cities',
      'kinships',
  ],
  data(){
      return{
          beneficiaries: [],
        //   beneficiaries: this.items// datos iniciales
      }
  },
  mounted(){
  },
  components:{
      RetFunBeneficiary
  },
  computed: {
      ...mapGetters({
          retfun: 'getData',
      })
  },
  methods: {
      addBeneficiary(){
          let beneficiary = {
                first_name: null,
                second_name: null,
                last_name: null,
                mothers_last_name: null,
                surname_husband: null,
                identity_card: null,
                city_identity_card: null,
                birth_date: null,
                kinship: null,
        }
        this.beneficiaries.push(beneficiary);
      },
    removeBeneficiary(index){
        this.beneficiaries.splice(index,1);
    }
  }
}
</script>
