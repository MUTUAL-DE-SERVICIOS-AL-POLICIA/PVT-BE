<template>
    <!-- <table class="table table-hover table-mail">
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
                    <span class="label label-danger pull-right">Documents</span>
                </td>
                <td class="mail-subject">
                    <a :href="`${row.path}`">{{ row.name }}</a>
                </td>
                <td class="">
                    <i class="fa fa-paperclip"></i>
                </td>
                <td class="text-right mail-date">
                    <a :href="`${row.path}`">
                        {{ row.code }}
                    </a>
                </td>   
                <td class="text-right mail-date">{{ row.reception_date}}</td>
            </tr>
            <tr v-if="! documents.length > 0"><td class="text-center" :colspan="inboxState == 'edited' ? '5' : '4'" >No hay ningun Trámite</td></tr>
        </tbody>
    </table> -->
    <v-card>
    <v-card-title>
        <div class="input-group">
            <input type="text" v-model="search" class="form-control input-sm" name="search" placeholder="Search...">
        </div>
    </v-card-title>
    <v-data-table
    :headers="headers"
    :items="documents"
    :search="search"
    hide-actions
    select-all
    item-key="ci"
    class="elevation-1"
    >
    <template slot="headers" slot-scope="props">
      <tr>
        <th v-if="inboxState == 'edited'">
            <input type="checkbox" :checked="false" v-model="checkedAllStatus" @change="checkedAll()">
        </th>
        <th
          v-for="header in props.headers"
          :key="header.text"
        >
          <!-- <v-icon small>arrow_upward</v-icon> -->
          {{ header.text }}
        </th>
      </tr>
    </template>
    <template slot="items" slot-scope="props">
      <tr :active="props.selected" @click="props.selected = !props.selected">
        <td v-if="inboxState == 'edited'">
            <input class="iCheck-helper" type="checkbox" :checked="false" :id="props.item.id" v-model="props.item.status" @change="checkChange(props.item.id, props.item.status)">
        </td>
        <td>
            <a :href="`${props.item.path}`">
                {{ props.item.code }}
            </a>
        </td>
        <td>
            <a :href="`${props.item.path}`">
                {{ props.item.ci }}
            </a>
        </td>
        <td>
            <a :href="`${props.item.path}`">
                {{ props.item.name }}
            </a>
        </td>
        <td>
            <a :href="`${props.item.path}`">
                {{ props.item.city }}
            </a>
        </td>
        <td>
            {{ props.item.reception_date}}
        </td>
      </tr>
    </template>
    <template slot="no-data" >
        <div class="alert alert-warning">
            No hay registros
        </div>
    </template>
    <template slot="no-results">
        <div class="alert alert-danger">
            Su búsqueda de <strong>{{search}}</strong> no encontró ningún resultado.
        </div>
    </template>
  </v-data-table>
  </v-card>
</template>
<script>
import { mapState, mapMutations } from 'vuex';
    export default {
        props:['workflowId', 'documents', 'inboxState'],
        data(){
            return{
                checkedAllStatus: false,
                pagination: {
                    sortBy: 'code'
                },
                search: '',
                selected: [],
                headers: [
                    { text: '# Tramite', value: 'code' },
                    { text: 'CI', align: 'left', value: 'ci' },
                    { text: 'Nombre', value: 'name' },
                    { text: 'Regional', value: 'city' },
                    { text: 'Fecha de Recepcion', value: 'date_reception' },
                ],
            }
        },
        mounted(){
            // remove class of vuetify and added class of inspinia
            $('.datatable').each(function(i, obj) {
                $(obj).removeClass('datatable table datatable--select-all');
                $(obj).addClass('table table-hover table-mail');
                $('.datatable__progress').remove();
            });
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
            // changeSort (column) {
            //     if (this.pagination.sortBy === column) {
            //     this.pagination.descending = !this.pagination.descending
            //     } else {
            //     this.pagination.sortBy = column
            //     this.pagination.descending = false
            //     }
            // }
        },
    };
</script>
