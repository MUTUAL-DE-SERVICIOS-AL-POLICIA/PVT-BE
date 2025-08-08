<template>
  <div>
    <div v-if="isLoadingStatus" class="text-center">
      <i class="fa fa-spinner fa-spin fa-3x"></i>
      <p>Verificando estado de la replicación...</p>
    </div>

    <div v-else>
      <div v-if="!canReplicate" class="alert alert-warning">
        <h4><i class="fa fa-warning"></i> Estado de la Replicación</h4>
        <p>{{ message }}</p>
      </div>
      <div v-if="canReplicate" class="ibox">
        <div class="ibox-title">
          <h5>Replicación de Trámites</h5>
        </div>
        <div class="ibox-content">
          <div class="row">
            <div class="col-md-6">
              <p><strong>Semestre Origen:</strong></p>
              <p>{{ sourceProcedure.semester }} Semestre {{ sourceProcedure.year.substring(0, 4)}}</p>
            </div>
            <div class="col-md-6">
              <p><strong>Semestre a Replicar:</strong></p>
              <p>{{ destinationProcedure.semester }} Semestre {{ sourceProcedure.year.substring(0, 4)}}</p>
            </div>
          </div>
          <hr>

          <button class="btn btn-primary" @click="calculateEligible" :disabled="isCalculating">
            <i v-if="isCalculating" class="fa fa-spinner fa-spin"></i>
            <i v-else class="fa fa-calculator"></i>
            Calcular Trámites a Replicar
          </button>

          <div v-if="calculationResult" class="m-t-lg">
            <h4>Desglose de Trámites a Replicar:</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Modalidad</th>
                        <th>Total Semestre Origen</th>
                        <th>Total a Replicar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in calculationResult.breakdown" :key="item.modality_name">
                        <td>{{ item.modality_name }}</td>
                        <td>{{ item.origin_count }}</td>
                        <td>{{ item.replicate_count }}</td>
                    </tr>
                    <tr class="font-bold">
                        <td>Total</td>
                        <td>{{ calculationResult.total_origin }}</td>
                        <td>{{ calculationResult.total_to_replicate }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="alert alert-danger">
                <i class="fa fa-warning"></i> <strong>Atención:</strong> Esta acción es irreversible y creará <strong>{{ calculationResult.total_to_replicate }}</strong> nuevos trámites.
            </div>

            <button class="btn btn-primary" @click="executeReplication">
            <i class="fa fa-check"></i> Ejecutar Replicación
          </button>
        </div>
      </div>
      </div>

      <div class="ibox m-t-lg">
        <div class="ibox-title">
          <h5>Historial de Replicaciones</h5>
        </div>
        <div class="ibox-content">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Semestre Replicado</th>
                <th>Trámites Creados</th>
                <th>Fecha de Replicación</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="replicationHistory.length === 0">
                <td colspan="3" class="text-center">No se encontraron replicaciones anteriores.</td>
              </tr>
              <tr v-else v-for="item in replicationHistory" :key="item.procedure_name">
                <td>{{ item.procedure_name }}</td>
                <td>{{ item.replicated_count }}</td>
                <td>{{ item.replication_date }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal de Confirmación Final -->
     <modal name="final-confirm-modal" height="auto" :adaptive="true">
      <div class="modal-content-replication">
        <div class="ibox-title">
          <h1>Confirmación Final</h1>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <i class="fa fa-warning"></i> <strong>¡ACCIÓN IRREVERSIBLE!</strong>
            </div>
            <p v-if="calculationResult">
              Estás a punto de replicar <strong>{{ calculationResult.total_to_replicate }}</strong> trámites.
            </p>
            <p>¿Estás absolutamente seguro de que quieres continuar?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" @click="closeFinalConfirmModal" :disabled="isExecuting">Cancelar</button>
          <button type="button" class="btn btn-danger" @click="confirmAndExecute" :disabled="isExecuting">
            <i v-if="isExecuting" class="fa fa-spinner fa-spin"></i>
            <i v-else class="fa fa-check"></i>
            Sí, Ejecutar Replicación
          </button>
        </div>
      </div>
    </modal>

  </div>
</template>

<script>
export default {
  props: ["permissions"],
  data() {
    return {
      isLoadingStatus: true,
      isCalculating: false,
      isExecuting: false,
      canReplicate: false,
      message: '',
      sourceProcedure: null,
      destinationProcedure: null,
      calculationResult: null,
      replicationHistory: [],
    };
  },
  mounted() {
    this.checkReplicationStatus();
    this.fetchHistory();
  },
  methods: {
    fetchHistory() {
      axios.get("/eco_com_replicate/history")
        .then(response => {
          console.log('Datos del historial recibidos:', response.data);
          this.replicationHistory = response.data;
        })
        .catch(error => {
          console.error("Error al cargar el historial de replicaciones:", error);
          window.flash('No se pudo cargar el historial de replicaciones.', 'error');
        });
    },
    checkReplicationStatus() {
      this.isLoadingStatus = true;
      axios.get("/eco_com_replicate/status")
        .then(response => {
          this.canReplicate = response.data.can_replicate;
          this.message = response.data.message; // Siempre guardar el mensaje
          if (this.canReplicate) {
            this.sourceProcedure = response.data.source_procedure;
            this.destinationProcedure = response.data.destination_procedure;
          }
        })
        .catch(error => {
          this.canReplicate = false;
          this.message = error.response ? error.response.data.message : 'Ocurrió un error de red.';
        })
        .finally(() => {
          this.isLoadingStatus = false;
        });
    },
    calculateEligible() {
      this.isCalculating = true;
      this.calculationResult = null;

      axios.post("/eco_com_replicate", {
        source_procedure_id: this.sourceProcedure.id,
      }).then(response => {
        this.calculationResult = response.data;
      }).catch(error => {
        window.flash(error.response.data.message || 'Hubo un error al procesar el cálculo.', 'error');
      }).finally(() => {
        this.isCalculating = false;
      });
    },
    executeReplication() {
      this.$modal.show('final-confirm-modal');
    },
    closeFinalConfirmModal() {
      this.$modal.hide('final-confirm-modal');
    },
    confirmAndExecute() {
      this.isExecuting = true;
      
      axios.post(`/eco_com_replicate/execute`, {
          source_procedure_id: this.sourceProcedure.id,
          destination_procedure_id: this.destinationProcedure.id,
      }).then(response => {
          this.closeFinalConfirmModal();
          window.flash(response.data.message);
          this.calculationResult = null;
          this.checkReplicationStatus(); 
          this.fetchHistory(); // Refresh history
      }).catch(error => {
          window.flash(error.response.data.message || 'Hubo un error al ejecutar la replicación.', 'error');
      }).finally(() => {
          this.isExecuting = false;
      });
    }
  },
};
</script>

<style scoped>
  .ibox-title h5 {
    font-size: 15px;
    font-weight: 600;
  }
  .ibox-content p {
    font-size: 14px;
  }
  .m-t-lg {
    margin-top: 30px;
  }
</style>
