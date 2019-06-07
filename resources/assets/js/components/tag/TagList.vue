<template>

        
    <div class="ibox ">
        <div class="ibox-title">
            <h5>Etiquetas del Trámite</h5>
            <button class="pull-right btn btn-primary btn-xs"
                    @click="toggleEditing()">
                <i class="fa fa-plus"></i> Adicionar
            </button>
        </div>
        <div class="ibox-content" :class="{'sk-loading': showSpinner}">
            <div class="sk-spinner sk-spinner-circle" v-if="showSpinner">
                <div class="sk-circle1 sk-circle"></div>
                <div class="sk-circle2 sk-circle"></div>
                <div class="sk-circle3 sk-circle"></div>
                <div class="sk-circle4 sk-circle"></div>
                <div class="sk-circle5 sk-circle"></div>
                <div class="sk-circle6 sk-circle"></div>
                <div class="sk-circle7 sk-circle"></div>
                <div class="sk-circle8 sk-circle"></div>
                <div class="sk-circle9 sk-circle"></div>
                <div class="sk-circle10 sk-circle"></div>
                <div class="sk-circle11 sk-circle"></div>
                <div class="sk-circle12 sk-circle"></div>
            </div>
            <transition name="tag"
                        enter-active-class="animated bounceInLeft"
                        leave-active-class="animated bounceOutRight"
                        :duration="{ enter: 400, leave: 400 }"
                        mode="out-in">
                <div key="saved" v-if="!editing" class="row">
                        <ul class="tag-list"
                        style="padding: 0"
                        >
                        <li v-for="(tag, index) in tagsList"
                            :key="index">
                            <a href="#"
                                :style="colorClass()"
                                style="">
                                <i class="fa fa-tag"></i> {{tag.name}}</a>
                        </li>
                    </ul>
                </div>
                <div v-else
                        key="edit">
                    <div class="form-group">
                        <select data-placeholder="Haz clic para seleccionar una etiqueta..."
                                class="chosen-select"
                                multiple
                                style="width:350px;"
                                tabindex="4"
                                >
                            <option v-for="(tag, index) in tagsWfState" :key="index" :value="tag.id" :selected="verify(tag.id)">{{ tag.name }}</option>
                        </select>
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
</template>
<script>
import { camelCaseToSnakeCase } from "../../helper.js";
    export default {
        props:['docId', 'type'],
      data() {
        return {
          editing: false,
          showSpinner: false,
          tagsList: [],
          tagsWfState: [],
        };
      },
      mounted() {
        this.getCurrentTags();
      },
      methods: {
        async getCurrentTags(){
            this.showSpinner = true;
            await axios.get(`/tag_${camelCaseToSnakeCase(this.type)}/${this.docId}`)
            .then(response=>{
                this.tagsList = response.data
                this.showSpinner = false;
            }).catch(error=>{
                console.error(error);
                this.showSpinner = false;
            })
        },
        async toggleEditing() {
          this.editing = !this.editing;
          let uri = `/tag_wf_state`;
          if (this.type == 'affiliate') {
              uri = `/tag_module`
          }
          await axios.get(uri)
            .then(response=>{
                this.tagsWfState = response.data
            }).catch(error=>{
                console.error(error);
            })
          setTimeout(() => {
            $(".chosen-select").chosen({ width: "100%" });
          }, 700);
        },
        verify(tagId){
            if(this.tagsList.length){
                return this.tagsList.some(element => element.id == tagId)
            }
            return false;
        },
        cancel() {
          this.getCurrentTags();
          this.editing = false;
        },
        async save() {
          this.showSpinner = true;
          $(".chosen-select").val()
          await axios.post(`/update_tag_${camelCaseToSnakeCase(this.type)}/${this.docId}`,{
              ids: $(".chosen-select").val()
          }).then(response=>{
              flash('Actualizacion correcta.');
          }).catch(error=>{
              flash('Error al actualizar las etiquetas.', 'error');
          })
          setTimeout(() => {
              this.getCurrentTags()
              this.showSpinner = false;
          }, 4000);
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
