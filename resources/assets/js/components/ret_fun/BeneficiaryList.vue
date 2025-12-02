<template>
    <div>
        <ret-fun-beneficiary :beneficiary="retFun.applicant"
                 :cities="cities"
                 :kinships="kinships"
                 :kinship_beneficiaries="kinship_beneficiaries"
                 :editable="false"
                 :solicitante="true"
                 >
        </ret-fun-beneficiary>
        <ret-fun-beneficiary v-for="(beneficiary, index) in beneficiaries"
                             :beneficiary="beneficiary"
                             :key='index'
                             :cities="cities"
                             :kinships="kinships"
                             :kinship_beneficiaries="kinship_beneficiaries"
                             :editable="true"
                             :index="index"
                             v-on:remove="removeBeneficiary(index)">
        </ret-fun-beneficiary>
        <div class="row">
            <div class="col-md-5"></div>
            <div class="col-md-1" v-if="possibleAddBeneficiary">
                <button class="btn btn-success add-beneficiary-button" @click="addBeneficiary()" type="button" ><i class="fa fa-plus"></i></button>
            </div>
            <div class="col-md-6"></div>
        </div>
    </div>
</template>

<script>
import { scroller } from 'vue-scrollto/src/scrollTo';
import { mapGetters } from 'vuex';
import RetFunBeneficiary from './Beneficiary.vue';
export default {
  props:[
      'items',
      'cities',
      'kinships',
      'kinship_beneficiaries'
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
      ...mapGetters('retFunForm',{
          retFun: 'getData',
      }),
      possibleAddBeneficiary(){
          return (this.retFun.modality_id == 1 || this.retFun.modality_id == 4 || this.retFun.modality_id == 63)
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
                legal_representative: null,
        }
        if(this.beneficiaries.length > 0){
            let last_beneficiary=this.beneficiaries[this.beneficiaries.length-1];
            if (last_beneficiary.first_name) {
                this.beneficiaries.push(beneficiary);
            }
        }else{
                this.beneficiaries.push(beneficiary);
        }
        setTimeout(() => {
            if (this.$children[this.$children.length-1].$refs.identitycard) {
                this.$children[this.$children.length-1].$refs.identitycard.focus()
            }
            const scrollToFooterCreateBeneficiaries = scroller();
            scrollToFooterCreateBeneficiaries(`#footerCreateBeneficiaries${this.beneficiaries.length-1}`);
        }, 100);
      },
    removeBeneficiary(index){
        this.beneficiaries.splice(index,1);
    }
  }
}
</script>