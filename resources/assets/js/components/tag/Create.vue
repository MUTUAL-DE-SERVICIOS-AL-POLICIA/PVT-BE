<template>
    <div class="row">
        <div class="form-group">
            <label class="col-lg-2 control-label">Nombre</label>
            <div class="col-lg-10">
                <input type="text"
                       class="form-control"
                       placeholder="nombre"
                       v-model="tag.name">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Abreviacion</label>
            <div class="col-lg-10">
                <input type="text"
                       class="form-control"
                       placeholder="abreviacion"
                       v-model="tag.shortened">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Slug</label>
            <div class="col-lg-10">
                <input type="text"
                       class="form-control"
                       disabled
                       :value="sluglify">
            </div>
        </div>
        <div class="form-group" v-if="! edit">
            <label class="col-lg-2 control-label">Modulo (Opcional) </label>
            <div class="col-lg-10">
                <select v-model="moduleId"
                        class="form-control">
                    <option :value="null"></option>
                    <option v-for="(md, index) in modules"
                            :value="md.id"
                            :key="index">{{ md.name }}</option>
                </select>
            </div>
        </div>
        <div class="form-group" v-if="! edit">
            <label class="col-lg-2 control-label">Worklflow State "Area" (Opcional)</label>
            <div class="col-lg-10">
                <select v-model="wfStateId"
                        class="form-control">
                    <option :value="null"></option>
                    <option v-for="(wf, index) in wfStatesList"
                            :value="wf.id"
                            :key="index">{{ wf.name }}</option>
                </select>
            </div>
        </div>

        <div class="row text-center">
            <div class="form-group">
                <button class="btn btn-danger">
                    <i class="fa fa-times"></i> Cancelar</button>
                <button class="btn btn-primary"
                        @click="save()">
                    <i class="fa fa-save"></i> Guardar</button>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
      props: {
        edit: Boolean,
        modules: Array,
        wfStates: Array,
        tagId: Number,
      },
      data() {
        return {
          tag: {},
          tagWfState:[],
          moduleId:null,
          wfStateId:null
        }
      },
      created(){
          if (this.edit) {
              axios.get(`/get_tag/${this.tagId}`)
              .then(response=>{
                  this.tag =  response.data;
              }).catch(error=>{
                  console.log(error);
              })
          }
      },
      methods: {
        save() {
          if (this.edit) {
              axios
              .patch(`/tag/${this.tagId}`, this.tag)
              .then(response => {
                flash("Se actualizo Correctamente la etiqueta");
              })
              .catch(error => {
                flash(`Ocurrio un error al editar la etiqueta: ${(error.response.data.message) || ''}`, 'error');
              });
          } else {
              let object = Object.assign({wf_state_id:this.wfStateId}, this.tag);
              axios
              .post("/tag", object)
              .then(response => {
                flash("Se guardo Correctamente la etiqueta");
              })
              .catch(error => {
                flash(`Ocurrio un error al guardar la etiqueta: ${(error.response.data.message) || ''}`, 'error');
              });
          }
        }
      },
      computed: {
        wfStatesList() {
            return this.wfStates.filter(w => w.module_id == this.moduleId);
        },
        sluglify() {
          let str = this.tag.name || "";
          const a =
            "àáäâèéëêìíïîòóöôùúüûñçßÿœæŕśńṕẃǵǹḿǘẍźḧ·/_,:;άαβγδεέζήηθιίϊΐκλμνξοόπρσςτυϋύΰφχψωώ";
          const b =
            "aaaaeeeeiiiioooouuuuncsyoarsnpwgnmuxzh------aavgdeeziitiiiiklmnxooprsstyyyyfhpoo";
          const p = new RegExp(a.split("").join("|"), "g");
          return str
            .toString()
            .trim()
            .toLowerCase()
            .replace(/ου/g, "ou")
            .replace(/ευ/g, "eu")
            .replace(/θ/g, "th")
            .replace(/ψ/g, "ps")
            .replace(/\//g, "-")
            .replace(/\s+/g, "-") // Replace spaces with -
            .replace(p, c => b.charAt(a.indexOf(c))) // Replace special chars
            .replace(/&/g, "-and-") // Replace & with 'and'
            .replace(/[^\w\-]+/g, "") // Remove all non-word chars
            .replace(/\-\-+/g, "-") // Replace multiple - with single -
            .replace(/^-+/, "") // Trim - from start of text
            .replace(/-+$/, ""); // Trim - from end of text
        }
      }
    };
</script>
