<template>
  <div>
    <datatable v-bind="$data">
      <!-- <button class="btn btn-default" @click="alertSelectedUids" :disabled="!selection.length">
        <i class="fa fa-commenting-o"></i>
        Alert selected uid(s)
      </button> -->
    </datatable>
  </div>
</template>
<script>
import Vue from 'vue'
import axios from 'axios'
import Datatable from 'vue2-datatable-component'

Vue.use(Datatable) // done!
import mockData from '../_mockData'
import components from './comps'
export default {
  components,
  name: 'RetFunTable', // `name` is required as a recursive component
  props: ['row'], // from the parent FriendsTable (if exists)
  data () {
    const amINestedComp = !!this.row
    return {
      supportBackup: true,
      supportNested: true,
      tblClass: 'table-bordered',
      tblStyle: 'color: #666',
      pageSizeOptions: [5, 10, 15, 20],
      columns: (() => {
        const cols = [
           { title: 'Apellido', field: 'last_name', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },
          { title: 'Nombre', field: 'first_name', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },
          { title: 'Modalidad', field: 'modality', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },
          { title: 'Flujo', field: 'workflow', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },
          { title: 'Código', field: 'code', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },
          { title: 'Recepción', field: 'reception_date', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },
          { title: 'Total', field: 'total', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },     
          { title: 'Operation', tdComp: 'Opt', visible: 'true' }
        ]
        const groupsDef = {
          Normal: ['Primer Nombre', 'Segundo Nombre', 'Apellido Paterno'],
          Sortable: ['ID'],
          Extra: []
        }
        return cols.map(col => {
          Object.keys(groupsDef).forEach(groupName => {
            if (groupsDef[groupName].includes(col.title)) {
              col.group = groupName
            }            
          })
          return col
        })
      })(),
      data: [],
      total: 0,
      selection: [],
      summary: {},
      // `query` will be initialized to `{ limit: 10, offset: 0, sort: '', order: '' }` by default
      // other query conditions should be either declared explicitly in the following or set with `Vue.set / $vm.$set` manually later
      // otherwise, the new added properties would not be reactive
      query: amINestedComp ? { uid: this.row.friends } : {},
      // any other staff that you want to pass to dynamic components (thComp / tdComp / nested components)
      xprops: {
        eventbus: new Vue()
      }
    }
  },
  watch: {
    query: {
      handler () {
        this.handleQueryChange()
      },
      deep: true
    }
  },
  methods: {
    handleQueryChange () {
      axios.get('/get_all_ret_fun',{
        params: this.query
      }).then((response)=> {
        this.data = response.data.ret_funds, 
        this.total = response.data.total
        // this.summary = summary
      }).catch(function (error) {
        console.log(error)
      });/*
      mockData(this.query).then(({ rows, total, summary }) => {
        console.log(rows)
        // this.data = rows
      })*/
    },
    alertSelectedUids () {
      console.log(this.selection);
      alert(this.selection.map(({ id }) => id))
    }
  }
}
</script>
<style>
.w-240 {
  width: 240px;
}
</style>
