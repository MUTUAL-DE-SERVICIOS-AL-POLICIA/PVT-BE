<template>
    <div>
        <ret-fun-beneficiary :beneficiary="retfun.applicant"
                 :cities="cities"
                 :kinships="kinships"
                 :editable="false"
                 :solicitante="true"
                 >
        </ret-fun-beneficiary>
        <ret-fun-beneficiary v-for="(beneficiary, index) in beneficiaries"
                             :beneficiary="beneficiary"
                             :key='index'
                             :cities="cities"
                             :kinships="kinships"
                             :editable="true"
                             v-on:remove="removeBeneficiary(index)">
        </ret-fun-beneficiary>
        <div class="row">
            <div class="col-md-5"></div>
            <div class="col-md-1" v-if="possibleAddBeneficiary">
                <button class="btn btn-success" @click="addBeneficiary()" type="button" ><i class="fa fa-plus"></i></button>
            </div>
            <div class="col-md-6"></div>
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
      }),
      possibleAddBeneficiary(){
          return (this.retfun.modality_id == 1 || this.retfun.modality_id == 4)
      }
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
                city_identity_card_id: null,
                birth_date: null,
                kinship: null,
        }
        if(this.beneficiaries.length > 0){
            let last_beneficiary=this.beneficiaries[this.beneficiaries.length-1];
            if (last_beneficiary.first_name) {
                this.beneficiaries.push(beneficiary);
            }
        }else{
                this.beneficiaries.push(beneficiary);
        }
      },
    removeBeneficiary(index){
        this.beneficiaries.splice(index,1);
    }
  }
}
</script>