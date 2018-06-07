<template>
    <table class="table table-hover table-mail">
        <tbody>
            <tr class="read" v-if="documents.length > 0" v-for="(row, index)  in documents" :key="`row-${index}`">
                <td class="check-mail">
                    <input v-if="inboxState == 'edited'" class="iCheck-helper" type="checkbox" :checked="false" :id="row.id" v-model="row.status" @change="checkChange(row.id, row.status)">
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
            <tr v-if="! documents.length > 0"><td class="text-center">No hay ningun tramite</td></tr>
        </tbody>
    </table>
</template>
<script>
import { mapState, mapMutations } from 'vuex';
    export default {
        props:['workflowId', 'documents', 'inboxState'],
        methods:{
            checkChange(id,status){
                let object = {
                    workflow_id: this.workflowId,
                    doc:{
                        id: id,
                        status: status
                    }
                }
                this.$store.commit('pushDoc', object);
            }
        }
    };
</script>
