<template>
  <div class="ibox">
    <div class="ibox-content">
      <vue-tabs active-tab-color="#59b66d" active-text-color="white" v-on:getUpdateRecords="getRecord()">
        <v-tab title="Modificaciones" icon="fa fa-edit">
          <div class="ibox-content inspinia-timeline">
            <div class="timeline-item" v-for="dr in documentRecords" :key="dr.id">
              <div class="row">
                <div class="col-md-3 date">
                  <h3>{{ dr.date | recordDate | uppercase }}</h3>
                  <i
                    class="fa"
                    :class="getRecordIcon(dr.record_type_id)"
                    :title="dr.record_type.name"
                  ></i>
                  {{ dr.date | recordHour }}
                  <br>
                  <small class="text-navy">{{ dr.user.username }}</small>
                </div>
                <div class="col-md-9 content">
                  <p style="font-size:medium" class="p-sm">{{ dr.message }}</p>
                  <span
                    style="position:absolute; top: 5px; right:10px; font-style:italic"
                  >Area de Calificacion</span>
                </div>
              </div>
            </div>
          </div>
        </v-tab>

        <v-tab title="Flujo del Tramite" icon="fa fa-random">
          <div class="ibox-content inspinia-timeline">
            <div class="timeline-item" v-for="wr in workflowRecords" :key="wr.id">
              <div class="row">
                <div class="col-md-3 date">
                  <h3>{{ wr.date | recordDate | uppercase }}</h3>
                  <i
                    class="fa"
                    :class="getRecordIcon(wr.record_type_id)"
                    :title="wr.record_type.name"
                  ></i>
                  {{ wr.date | recordHour }}
                  <br>
                  <small class="text-navy">{{ wr.user.username }}</small>
                </div>
                <div class="col-md-9 content">
                  <p style="font-size:medium" class="p-sm">{{ wr.message }}</p>
                  <span
                    style="position:absolute; top: 5px; right:10px; font-style:italic"
                  >{{ wr.wf_state.name }}</span>
                </div>
              </div>
            </div>
          </div>
        </v-tab>

        <v-tab title="Anotaciones" icon="fa fa-file" >
          <eco-com-notes :eco-com="ecoCom" v-on:getUpdateRecords="getRecord()" :permissions="permissions" :note-records="noteRecords"></eco-com-notes>
        </v-tab>
      </vue-tabs>
    </div>
  </div>
</template>

<script>
export default {
  props: ["ecoCom", "permissions"],
  data() {
    return {
      documentRecords: [],
      workflowRecords: [],
      noteRecords: [],
      recordTypeIcons: [
        { id: 1, icon: "fa-check", active: true },
        { id: 2, icon: "fa-times", active: true },
        { id: 3, icon: "fa-arrow-right", active: true },
        { id: 4, icon: "fa-arrow-left", active: true },
        { id: 5, icon: "fa-user", active: true },
        { id: 6, icon: "fa-address-card-o", active: true },
        { id: 7, icon: "fa-file-text", active: true },
        { id: 8, icon: "", active: true },
        { id: 9, icon: "fa-eye", active: true },
        { id: 10, icon: "fa-balance-scale", active: true },
        { id: 11, icon: "fa-users", active: true },
        { id: 12, icon: "fa-shield", active: true }
      ],
      form: {
        message: null
      },
      method: "post"
    };
  },
  mounted() {
    this.getRecord();
  },
  methods: {
    getRecordIcon(id) {
      let r = this.recordTypeIcons.find(r => r.id == id);
      if (r) {
        return r.icon;
      }
      return "fa-hand-spock-o";
    },
    async getRecord() {
      await axios
        .get(`/eco_com_record/${this.ecoCom.id}`)
        .then(response => {
          console.log(response.data);
          this.documentRecords = response.data.document_records;
          this.workflowRecords = response.data.workflow_records;
          this.noteRecords = response.data.note_records;
        })
        .catch(error => {
          console.log(error);
        });
    },

  }
};
</script>

<style>
</style>
