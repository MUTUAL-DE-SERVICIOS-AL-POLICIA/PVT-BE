    <template>
    <div>
        <div class="row" :id="`footerCreateTestimony${index}`">
            <div class="col-md-12">
                <div class="pull-left">
                    <legend>Testimonio </legend>
                </div>
                <div class="text-right"
                     v-if="editable">
                    <button class="btn btn-danger"
                            type="button"
                            v-on:click="remove()"> <i class="fa fa-trash"></i> </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <label class="control-label">Beneficiarios</label>
            </div>
            <div class="col-md-9">
                <!-- <select data-placeholder="Haz click para seleccionar un beneficiario o mas"
                        class="chosen-select"
                        multiple
                        tabindex="4"
                        v-model="testimony.beneficiaries_list"
                        > -->

                        <!-- <select v-model='testimony.beneficiaries_list' multiple>
                            <option v-for="(beneficiary, index) in beneficiaries"
                                :key="index"
                                :value="beneficiary.id"
                             >{{ beneficiary.identity_card }}</option>
                        </select> -->


                        <multiselect
                            v-model="testimony.beneficiaries_list"
                            :options="options"
                            :multiple="true"
                            :close-on-select="false"
                            :clear-on-select="false"
                            :hide-selected="true"
                            placeholder="Pick some"
                            track-by="name"
                            label="name"
                            :preselect-first="true">
                            <template
                                slot="tag"
                                slot-scope="props">
                                <span class="custom__tag">
                                    <span>{{ props.option.name }}</span>
                                    <span
                                        class="custom__remove"
                                        @click="props.remove(props.option)"
                                    >❌</span>
                                </span>
                            </template>
                        </multiselect>

                        <!-- <chosen-select v-model='testimony.beneficiaries_list' multiple>
                            <option v-for="(beneficiary, index) in beneficiaries"
                                :key="index"
                                :value="beneficiary.id"
                             >{{ beneficiary.identity_card }}</option>
                        </chosen-select> -->
                <!-- </select> -->
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Documento</label>
                </div>
                <div class="col-md-8">
                    <input type="text" ref="document_type" v-model="testimony.document_type" class="form-control" :disabled="!editable" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Nro</label>
                </div>
                <div class="col-md-8">
                    <input type="text" v-model="testimony.number" class="form-control" :disabled="!editable" />
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Fecha</label>
                </div>
                <div class="col-md-8">
                    <input type="date" v-model="testimony.date" class="form-control" :disabled="!editable" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Juzgado</label>
                </div>
                <div class="col-md-8">
                    <input type="text" v-model="testimony.court" class="form-control" :disabled="!editable" />
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Lugar</label>
                </div>
                <div class="col-md-8">
                    <input type="text" v-model="testimony.place" class="form-control" :disabled="!editable" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-4">
                    <label class="control-label">Notario</label>
                </div>
                <div class="col-md-8">
                    <input type="text" v-model="testimony.notary" class="form-control" :disabled="!editable" />
                </div>
            </div>
        </div>
        <br>
        <div class="hr-line-dashed"></div>
    </div>
</template>

    <script>
    import Multiselect from 'vue-multiselect'
    export default {
        components: {
            Multiselect
        },
      props: ["testimony", "editable", "index", 'beneficiaries', 'beneficiariesSelected'],
      mounted() {
          this.testimony.beneficiaries_list = ['1031','1035']
      },
      data(){
          return {
              value: [{ name: 'Vue.js', },{ name: 'Sinatra', },],
      options: [
        { name: 'Vue.js', },
        { name: 'Adonis', },
        { name: 'Rails', },
        { name: 'Sinatra', },
        { name: 'Laravel', },
        { name: 'Phoenix', }
      ]
          }
      },
      methods: {
        remove() {
          this.$emit("remove");
        },
        verify(id) {
            if (this.beneficiariesSelected.length > 0) {
                console.log(id);
                return this.beneficiariesSelected.some(i => i.id == id);
            }
            return false;
        }
      }
    };
</script>

    <style>
</style>
