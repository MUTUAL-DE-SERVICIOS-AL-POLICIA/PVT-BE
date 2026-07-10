<template>
  <div class="ibox">
    <div class="ibox-content">
      <vue-tabs
        active-tab-color="#59b66d"
        active-text-color="white"
        v-on:getUpdateRecords="getRecord()"
      >
        <v-tab title="Modificaciones" icon="fa fa-edit">
          <h2>Historial de Modificaciones Afiliado</h2>
          <div class="ibox-content inspinia-timeline">
            <div class="timeline-item" v-for="dr in affiliateRecords" :key="dr.id">
              <div class="row">
                <div class="col-md-3 date">
                  <h3>{{ dr.created_at | recordDate | uppercase }}</h3>
                  <!-- <i
                    class="fa"
                    :class="getRecordIcon(dr.record_type_id)"
                    :title="dr.record_type.name"
                  ></i> -->
                  {{ dr.created_at | recordHour }}
                  <br>
                  <small class="text-navy">{{ }}</small>
                </div>
                <div class="col-md-9 content">
                  <p style="font-size:medium" class="p-sm">{{ dr.message }}</p>
                  <!-- <span
                    style="position:absolute; top: 5px; right:10px; font-style:italic"
                  >{{ dr.wf_state.name }}</span> -->
                </div>
              </div>
            </div>
          </div>
          <div>
            <h2>Historial de Modificaciones Cónyuge</h2>
            <div class="ibox-content inspinia-timeline">
            <div class="timeline-item" v-for="dr in spouseRecords" :key="dr.id">
              <div class="row">
                <div class="col-md-3 date">
                  <h3>{{ dr.created_at | recordDate | uppercase }}</h3>
                  {{ dr.created_at | recordHour }}
                  <br>
                  <small class="text-navy">{{ }}</small>
                </div>
                <div class="col-md-9 content">
                  <p style="font-size:medium" class="p-sm">{{ dr.action }}</p>
                </div>
              </div>
            </div>
          </div>
          </div>
        </v-tab>
        <v-tab title="Anotaciones" icon="fa fa-file">
          <affiliate-notes
            :affiliate="affiliate"
            v-on:getUpdateRecords="getRecord()"
            :permissions="permissions"
            :note-records="records"
          ></affiliate-notes>
        </v-tab>

        <v-tab title="Historial (Gestiones Pasadas)" icon="fa fa-random">
          <div class="ibox-content inspinia-timeline">
            <div class="timeline-item" v-for="wr in affiliateActivities" :key="wr.id">
              <div class="row">
                <div class="col-md-3 date">
                  <h3>{{ wr.created_at | recordDate | uppercase }}</h3>
                  
                  <br>
                  <small v-if="wr.user != null" class="text-navy">{{ wr.user.username }}</small>
                  <small v-else class="text-navy"></small>
                </div>
                <div class="col-md-9 content">
                  <p style="font-size:medium" class="p-sm">{{ wr.message }}</p>
                  <span
                    style="position:absolute; top: 5px; right:10px; font-style:italic"
                  ></span>
                </div>
              </div>
            </div>
          </div>
        </v-tab>
      </vue-tabs>
      
    </div>
  </div>
</template>

<script>
export default {
  props: ["affiliate", "permissions"],
  data() {
    return {
      affiliateRecords: [],
      records: [],
      affiliateActivities:[],
      spouseRecords: [],

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
        { id: 12, icon: "fa-shield", active: true },
        { id: 13, icon: "fa-sticky-note-o", active: true },
        { id: 14, icon: "fa-map-marker", active: true },
        { id: 15, icon: "fa-tags", active: true }
      ]
    };
  },
  mounted() {
    document.querySelectorAll(".tab-affiliate-records")[0].addEventListener(
      "click",
      () => {
        this.getRecord();
        this.getRecordSpouse();
      },
      { passive: true }
    );
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
      this.$scrollTo("#wrapper");
      await axios
        .get(`/affiliate_record/${this.affiliate.id}`)
        .then(response => {
          // console.log(response.data);
          this.records = response.data.records;
          this.affiliateRecords = response.data.affiliate_records;
          this.affiliateActivities = response.data.affiliate_activities;
        })
        .catch(error => {
          console.log(error);
        });
    },
    async getRecordSpouse() {
      this.$scrollTo("#wrapper");
      await axios
        .get(`/spouse_record/${this.affiliate.id}`)
        .then(response => {
          this.spouseRecords = response.data.spouse_records;
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>

<style>
</style>
