<template>
    <div class="row">
        <div class="form-group">
            <label class="col-lg-2 control-label">Modulos </label>
            <div class="col-lg-10">
                        <!-- @change="updateTagList()" -->
                <select v-model="moduleId"
                        class="form-control"
                        >
                    <option v-for="(md, index) in modules"
                            :value="md.id"
                            :key="index">{{ md.name }}</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-2 control-label">Worklflow State (Area)</label>
            <div class="col-lg-10">
                <select v-model="wfStateId"
                        class="form-control"
                        @change="updateTagList()"
                        >
                    <option v-for="(wf, index) in wfStatesList"
                            :value="wf.id"
                            :key="index">{{ wf.name }}</option>
                </select>
            </div>
        </div>
        <select class="form-control dual_select" multiple v-if="wfStateId">
            <option v-for="(tag, index) in tagsFilter" :key="`unselected-${index}`" :value="tag.id">{{ tag.name }}</option>
            <option v-for="(tag, index) in list" :key="`selected-${index}`" :value="tag.id" selected>{{ tag.name }}</option>
        </select>
        <div class="row text-center" v-if="wfStateId">
            <button class="btn btn-danger"><i class="fa fa-times"></i> Cancelar</button>
            <button class="btn btn-primary" @click="save()"><i class="fa fa-save"></i> Guardar</button>
        </div>
    </div>
</template>
<script>
export default {
    props:{
        modules:Array,
        wfStates:Array,
        tags:Array,
    },
    data(){
        return{
            moduleId:null,
            wfStateId:null,
            list:[],
        }
    },
    methods:{
        updateTagList(){
            if (this.wfStateId) {
                axios.get(`/get_tag_wf_state/`,{params:{
                    id:this.wfStateId
                }})
                .then(response=>{
                    this.list = response.data;
                }).catch(error=>{
                    console.log(error);
                })
            }
            setTimeout(() => {
                $('.dual_select').bootstrapDualListbox({
                    selectorMinimalHeight: 200,
                    nonSelectedListLabel: 'Etiquetas disponibles',
                    selectedListLabel: 'Etiquetas asignadas',
                    infoText: 'Total {0}',
                    filterPlaceHolder:'Buscar ...',
                    infoTextFiltered: '<span class="label label-warning">Filtrado</span> {0} de {1}',
                    infoTextEmpty:'Lista vacia'
                });
                $('.dual_select').bootstrapDualListbox('refresh', true);
            }, 100);
        },
        save(){
            axios.post('/update_tag_wf_state',{ids:$('.dual_select').val(), wf_state_id:this.wfStateId})
            .then(response=>{
                flash('Asignacion correcta'+response.data);
            }).catch(error=>{
                flash('Ocurrio un error al asignar las etiquetas: '+(error.response.data.message|| ''), 'error')
            })
            setTimeout(() => {
                $('.dual_select').bootstrapDualListbox('refresh', true);
            }, 100);
        }
    },
    computed:{
        tagsFilter(){
            return this.tags.filter(t=>{
                return !this.list.map(l=>l.id).includes(t.id)
            })
        },
        wfStatesList() {
            return this.wfStates.filter(w => w.module_id == this.moduleId)
        }
    }
}
</script>
