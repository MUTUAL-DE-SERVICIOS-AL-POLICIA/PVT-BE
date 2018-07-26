<template>
    <div class="widget-text-box">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left">
                    <h5 class="tag-title"> Etiquetas</h5>
                </div>
                <button class="pull-right btn btn-primary btn-xs"
                        @click="toggleEditing()">
                    <i class="fa fa-pencil"></i> Editar
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <transition name="tag"
                            enter-active-class="animated bounceInLeft"
                            leave-active-class="animated bounceOutRight"
                            :duration="{ enter: 400, leave: 400 }"
                            mode="out-in">
                    <ul class="tag-list"
                        style="padding: 0"
                        v-if="!editing"
                        key="saved">
                        <li v-for="(tag, index) in tagsRetFun"
                            :key="index">
                            <a href="#"
                               :style="colorClass()"
                               style="">
                                <i class="fa fa-tag"></i> {{tag.name}}</a>
                        </li>
                    </ul>
                    <div v-else
                         key="edit">
                        <div class="form-group">
                            <label class="font-normal">Seleccione las etiquetas</label>
                            <div>
                                <select data-placeholder="Escoge una etiqueta..."
                                        class="chosen-select"
                                        multiple
                                        style="width:350px;"
                                        tabindex="4"
                                        >
                                    <option v-for="(tag, index) in tagsWfState" :key="index" :value="tag.id" :selected="verify(tag.id)">{{ tag.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-danger btn-sm"
                                    @click="cancel()">
                                <i class="fa fa-times"></i> Cancelar
                            </button>
                            <button class="btn btn-primary btn-sm"
                                    @click="save()">
                                <i class="fa fa-save"></i> Guardar
                            </button>
                        </div>
                    </div>
                </transition>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props:['retFunId'],
      data() {
        return {
          editing: false,
          tagsRetFun: [],
          tagsWfState: [],
        };
      },
      mounted() {
        this.getCurrentTags();
      },
      methods: {
        getCurrentTags(){
            axios.get(`/tag_ret_fun/${this.retFunId}`)
            .then(response=>{
                this.tagsRetFun = response.data
            }).catch(error=>{
                console.error(error);
            })
        },
        toggleEditing() {
          this.editing = !this.editing;
          axios.get(`/tag_wf_state`)
            .then(response=>{
                this.tagsWfState = response.data
            }).catch(error=>{
                console.error(error);
            })
          setTimeout(() => {
            $(".chosen-select").chosen({ width: "100%" });
          }, 500);
        },
        verify(tagId){
            if(this.tagsRetFun.length){
                let sw = false;
                this.tagsRetFun.forEach(element => {
                    if (element.id == tagId){
                        sw = true;
                    }
                });
                return sw;
            }
            return false;
        },
        cancel() {
          this.getCurrentTags();
          this.editing = false;
        },
        save() {
          $(".chosen-select").val()
          axios.post(`/update_tag_ret_fun/${this.retFunId}`,{
              ids: $(".chosen-select").val()
          }).then(response=>{
              flash('Actualizacion correcta.');
          }).catch(error=>{
              flash('Error al actualizar las etiquetas.', 'error');
          })
          this.getCurrentTags()
          this.editing = false;
        },
        colorClass() {
          return {
            color: "#a94442",
            backgroundColor: "#f2dede",
            borderColor: "#ebccd1"
          };
        }
      }
    };
</script>