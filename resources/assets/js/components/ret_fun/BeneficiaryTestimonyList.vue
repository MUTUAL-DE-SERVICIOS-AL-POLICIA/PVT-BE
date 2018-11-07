<template>
    <div class="col-lg-12">
        <div class="ibox">
            <div class="ibox-title">
              <h5>
                Testimonios
              </h5>
              <div class="ibox-tools">
                <button @click="editing = !editing" class="btn btn-primary"><i class="fa" :class="{'fa-edit':editing, 'fa-pencil':!editing}"></i> Editar</button>
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
                                      :type="type"
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
    import { camelCaseToSnakeCase } from '../../helper.js'
    import { scroller } from "vue-scrollto/src/scrollTo";
    import BeneficiaryTestimony from "./BeneficiaryTestimony.vue";
    export default {
      components: {
        BeneficiaryTestimony
      },
      props: ["beneficiaries", 'docId' , 'type'],
      data() {
        return {
          testimonies: [],
          editing: false
        };
      },
      mounted(){
        this.getTestimonies();
      },
      methods: {
        getTestimonies(){
          axios.get(`/${camelCaseToSnakeCase(this.type)}_beneficiaries_testimonies/${this.docId}`)
          .then(response => {
            this.testimonies = response.data
          }).catch(error => {
            console.log(error);
          })
        },
        addTestimony() {
          let testimony = {
            id: 'new',
            document_type: null,
            number: null,
            date: null,
            court: null,
            place: null,
            notary: null,
            ret_fun_beneficiaries:[],
            quota_aid_beneficiaries:[],
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
            axios.patch(`/update_beneficiary_testimony_${camelCaseToSnakeCase(this.type)}/${this.docId}`, this.testimonies)
            .then(response => {
                flash('Testimonios Actualizados');
                this.getTestimonies();
                this.editing = false;
            }).catch(error=>{
                flash('Ocurrio un error al actualizar los testimonios', 'error');
                this.editing = false;
                console.log(error);
            })
        },
        cancel(){
          this.editing = false;
          this.getTestimonies();
        }
      }
    };
</script>

<style>
</style>
