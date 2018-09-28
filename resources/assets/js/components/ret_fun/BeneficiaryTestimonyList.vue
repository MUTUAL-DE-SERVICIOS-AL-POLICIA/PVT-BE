<template>
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
              <h5>
                Testimonios
              </h5>
              <div class="ibox-tools">
                <button>Editar</button>
              </div>
            </div>
            <div class="ibox-content">
                <div class="text-right">
                </div>
                <BeneficiaryTestimony v-for="(testimony, index) in testimonies"
                                      :key="index"
                                      :index="index"
                                      :testimony="testimony"
                                      :editable="editing"
                                      :beneficiaries="beneficiaries"
                                      :beneficiaries-selected="testimony.ret_fun_beneficiaries"
                                      @remove="removeTestimony(index)"></BeneficiaryTestimony>
            </div>
                <div class="row"
                     v-if="editing">
                    <div class="col-md-5"></div>
                    <div class="col-md-1">
                        <button class="btn btn-success add-beneficiary-button"
                                @click="addTestimony()"
                                type="button"><i class="fa fa-plus"></i></button>
                    </div>
                    <div class="col-md-6"></div>
                </div>
            <div class="ibox-footer">
                <div class="text-center" v-show="editing">
                    <button class="btn btn-danger" type="button" @click="cancel()"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span class="bold">Cancelar</span></button>
                    <button class="btn btn-primary" type="button" @click="update()"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { scroller } from "vue-scrollto/src/scrollTo";
    import BeneficiaryTestimony from "./BeneficiaryTestimony.vue";
    export default {
      components: {
        BeneficiaryTestimony
      },
      props: ["beneficiaries", 'retFunId', 'testimoniesBackend'],
      data() {
        return {
          testimonies: this.testimoniesBackend,
          editing: true
        };
      },
      mounted(){
        console.log(this.testimoniesBackend);

      },
      methods: {
        addTestimony() {
          let testimony = {
            id: null,
            document_type: null,
            number: null,
            date: null,
            court: null,
            place: null,
            notary: null,
            beneficiaries_list:[]
          };
          if (this.testimonies.length > 0) {
            let lastTestimony = this.testimonies[this.testimonies.length - 1];
            if (lastTestimony.document_type) {
              this.testimonies.push(testimony);
            }
          } else {
            this.testimonies.push(testimony);
          }
          setTimeout(() => {
            if (this.$children[this.$children.length - 1].$refs.document_type) {
              this.$children[this.$children.length - 1].$refs.document_type.focus();
              const scrollToFooterCreateTestimony = scroller();
              scrollToFooterCreateTestimony(
                `#footerCreateTestimony${this.testimonies.length - 1}`
              );
            }
          }, 100);
        },
        removeTestimony(index) {
          this.testimonies.splice(index, 1);
        },
        update(){
            axios.patch(`/update_beneficiary_testimony/${this.retFunId}`, this.testimonies)
            .then(response => {
                console.log(response);
                
            }).catch(error=>{
                console.log(error);
                
            })
        }
      }
    };
</script>

<style>
</style>
