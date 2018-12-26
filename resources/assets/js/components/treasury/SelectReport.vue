<template>
  <div>
    <select v-model="typeReport" class="form-control" v-validate="'required'">
      <option :value="null"></option>
      <option v-for="type in types" :key="type.id" :value="type.id">{{ type.name }}</option>
    </select>
    <div class="form-group row m-b-md" :class="{ 'has-error': errors.has('from')}">
      <div class="col-sm-3 col-form-label"><label class="control-label">Desde:</label></div>
      <div class="col-md-5"><input name="from" v-model="from" v-date type="text" class="form-control" v-validate="'required|date_format:DD/MM/YYYY|max_current_date'">
          <div v-show="errors.has('from')">
              <i class="fa fa-warning text-danger"></i>
              <span class="text-danger">{{ errors.first('from') }}</span>
          </div>
      </div>
    </div>
    <div class="form-group row m-b-md" :class="{ 'has-error': errors.has('to')}">
      <div class="col-sm-3 col-form-label"><label class="control-label">Hasta:</label></div>
      <div class="col-md-5"><input name="to" v-model="to" v-date type="text" class="form-control" v-validate="'required|date_format:DD/MM/YYYY|max_current_date'">
          <div v-show="errors.has('to')">
              <i class="fa fa-warning text-danger"></i>
              <span class="text-danger">{{ errors.first('to') }}</span>
          </div>
      </div>
    </div>
    <button class="btn btn-primary" :disabled="!typeReport" @click="generateReport">
      <i class="fa fa-check"></i> Generar Impresion
    </button>
  </div>
</template>
<script>
export default {
  props: {
    types: {
      type: Array,
      required: true
    },
    fromDate: {
      type: String,
      required: false
    },
    toDate: {
      type: String,
      required: false
    }
  },
  data() {
    return {
      from: this.fromDate,
      to:this.toDate,
      typeReport: null
    };
  },
  methods: {
    generateReport() {
      axios({
        url: `/treasury/report`,
        method: 'GET',
        responseType: 'blob', // important
        params: {
          type: this.typeReport,
          from: this.from,
          to: this.to,
        }
      }).then((response) => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'file.pdf');
        document.body.appendChild(link);
        link.click();
      });
      // axios.get(`/treasury/report`, {params: {
      //   type: this.typeReport,
      //   from: this.from,
      //   to: this.to,
      // }})
      // .then(response => {
      //   console.table(response.data.headers.map(i=>i.text));
      //   console.table(response.data.footer.map(i=>i.text));
      //   console.log(response.data.rows);
      //   console.log(response.data.title);
      // })
    }
  }
};
</script>

<style>
</style>
