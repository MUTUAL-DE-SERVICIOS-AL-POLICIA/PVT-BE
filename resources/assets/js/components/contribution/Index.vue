<template>
  <div>
    <p><code>query: {{ query }}</code></p>
    <datatable v-bind="$data">
      <button class="btn btn-default" @click="alertSelectedUids" :disabled="!selection.length">
        <i class="fa fa-commenting-o"></i>
        Alert selected uid(s)
      </button>
    </datatable>
  </div>
</template>
<script>
import Vue from 'vue'
import axios from 'axios'
import Datatable from 'vue2-datatable-component'

Vue.use(Datatable) // done!
import mockData from '../_mockData'
import components from './comps/'
export default {
  components,
  name: 'ContributionTable', // `name` is required as a recursive component
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
          { title: 'Gestion', field: 'month_year', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },
          { title: 'Grado', field: 'degree_id', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },
          { title: 'Unidad', field: 'unit_id', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },
          { title: 'Item', field: 'item', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },
          { title: 'Sueldo', field: 'base_wage', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },
          { title: 'Antiguedad', field: 'seniority_bonus', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },
          { title: 'Estudio', field: 'study_bonus', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },
          { title: 'Cargo', field: 'position_bonus', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },
          { title: 'Frontera', field: 'border_bonus', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },
          { title: 'Oriente', field: 'east_bonus', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },
          { title: 'Seguridad', field: 'public_security_bonus', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },
          { title: 'Ganado', field: 'gain', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },
          { title: 'Cotizable', field: 'quotable', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },
          { title: 'F.R.', field: 'retirement_fund', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },
          { title: 'C.A.M.', field: 'mortuary_quota', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },
          { title: 'Aporte', field: 'total', thComp: 'FilterTh', tdStyle: { fontStyle: 'italic' } },
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
      contri: [],
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
      axios.get('/get_all_contribution',{
        params: this.query
      }).then((response)=> {
        this.data = response.data.contributions, 
        this.total = response.data.total,
        this.contri = this.data
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
