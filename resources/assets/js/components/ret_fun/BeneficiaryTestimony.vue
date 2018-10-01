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
                        <multiselect
                            v-model="testimony.ret_fun_beneficiaries"
                            :options="beneficiaries"
                            :multiple="true"
                            :close-on-select="false"
                            :clear-on-select="false"
                            :hide-selected="true"
                            :disabled="!editable"
                            placeholder="Seleccione la cedula de identidad del beneficiario"
                            track-by="identity_card"
                            :custom-label="customLabel"
                            :show-labels="false"
                            >
                            <template
                                slot="tag"
                                slot-scope="props">
                                <span class="custom__tag">
                                    <span>{{ fullName(props.option) }}</span>
                                    <span
                                        class="custom__remove"
                                        @click="props.remove(props.option)"
                                    >❌</span>
                                </span>
                            </template>
                        </multiselect>
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
      props: ["testimony", "editable", "index", 'beneficiaries'],
      data(){
          return {}
      },
      methods: {
        remove() {
          this.$emit("remove");
        },
        fullName(obj){
            let name = obj.identity_card + ' - ' +obj.first_name+ ' '+obj.second_name+' '+obj.last_name+' '+obj.mothers_last_name+' '+obj.surname_husband;
            return name.replace(/\s+/g, ' ').trim();
        },
        customLabel (obj) {
            return this.fullName(obj);
        }

      }
    };
</script>

    <style>
</style>
