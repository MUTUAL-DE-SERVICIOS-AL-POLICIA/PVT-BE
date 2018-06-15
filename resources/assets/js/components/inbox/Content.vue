<template>
    <table class="table table-hover table-mail">
        <thead>
            <tr>
                <th v-if="inboxState == 'edited'"></th>
                <th><input type="text" v-model="ci" @keyup="search('ci')" class="form-control" placeholder="CI"></th>
                <th><input type="text" class="form-control" placeholder="NOMBRE"></th>
                <th><input type="text" class="form-control" placeholder="# Trámite"></th>
                <th><input type="text" class="form-control" placeholder="FECHA RECEPCION"></th>
            </tr>
        </thead>
        <tfoot style="display:table-header-group">
            <tr>
                <th v-if="inboxState == 'edited'">
                    <input type="checkbox" :checked="false" v-model="checkedAllStatus" @change="checkedAll()">
                </th>
                <th class="text-center" style="width:100px;">CI</th>
                <th class="text-center">NOMBRE</th>
                <th class="text-center" style="width:100px;"># Trámite</th>
                <th class="text-center">FECHA RECEPCION</th>
            </tr>
        </tfoot>
        <tbody>
            <tr class="read" v-if="documents.length > 0" v-for="(row, index)  in documents" :key="`row-${index}`">
                <td class="check-mail" v-if="inboxState == 'edited'">
                    <input class="iCheck-helper" type="checkbox" :checked="false" :id="row.id" v-model="row.status" @change="checkChange(row.id, row.status)">
                </td>
                <td class="mail-contact">
                    <a :href="`${row.path}`">{{ row.ci }}</a>
                    <!-- <span class="label label-danger pull-right">Documents</span> -->
                </td>
                <td class="mail-subject">
                    <a :href="`${row.path}`">{{ row.name }}</a>
                </td>
                <!-- <td class="">
                    <i class="fa fa-paperclip"></i>
                </td> -->
                <td class="text-right mail-date">
                    <a :href="`${row.path}`">
                        {{ row.code }}
                    </a>
                </td>   
                <td class="text-right mail-date">{{ row.reception_date}}</td>
            </tr>
            <tr v-if="! documents.length > 0"><td class="text-center" :colspan="inboxState == 'edited' ? '5' : '4'" >No hay ningun Trámite</td></tr>
        </tbody>
    </table>
</template>
<script>
import { mapState, mapMutations } from 'vuex';
    export default {
        props:['workflowId', 'documents', 'inboxState'],
        data(){
            return{
                checkedAllStatus: false,
                ci: null,

            }
        },
        methods:{
            /* TODO
            correguir al momento de checkall unchecked uno por uno

             */
            checkChange(id,status){
                let object = {
                    workflow_id: this.workflowId,
                    doc:{
                        id: id,
                        status: status
                    }
                }
                this.$store.commit('pushDoc', object);
            },
            checkedAll(){
                if (this.checkedAllStatus) {
                    this.documents.forEach(d => {
                        if (!d.status) {
                            d.status= true;
                            this.checkChange(d.id,d.status);
                        }
                    });
                }else{
                    this.documents.forEach(d => {
                        if (d.status) {
                            d.status= false;
                            this.checkChange(d.id,d.status);
                        }
                    });
                }
            },
            search(field){
                this.$store.commit('search', this.ci);
            }
        },
    };
</script>
