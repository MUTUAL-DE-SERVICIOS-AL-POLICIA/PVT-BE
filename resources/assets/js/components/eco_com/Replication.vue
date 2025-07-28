<template>
  <div>
    <!-- Estado de Carga -->
    <div v-if="isLoadingStatus" class="text-center">
      <i class="fa fa-spinner fa-spin fa-3x"></i>
      <p>Verificando estado de la replicación...</p>
    </div>

    <!-- Mensaje de Error/Alerta -->
    <div v-else-if="!canReplicate" class="alert alert-warning">
      <h4><i class="fa fa-warning"></i> Acción no disponible</h4>
      <p>{{ message }}</p>
    </div>

    <!-- Tarjeta de Replicación (si es posible) -->
    <div v-else class="ibox">
      <div class="ibox-title">
        <h5>Replicación de Trámites</h5>
      </div>
      <div class="ibox-content">
        <div class="row">
          <div class="col-md-6">
            <p><strong>Semestre Origen:</strong></p>
            <p>{{ sourceProcedure.semester }} Semestre {{ new Date(sourceProcedure.year).getFullYear() }}</p>
          </div>
          <div class="col-md-6">
            <p><strong>Semestre a Replicar:</strong></p>
            <p>{{ destinationProcedure.semester }} Semestre {{ new Date(destinationProcedure.year).getFullYear() }}</p>
          </div>
        </div>
        <hr>
        <button class="btn btn-primary" @click="showConfirmationModal">
          <i class="fa fa-arrow-right"></i> Preparar Replicación
        </button>
      </div>
    </div>

    <!-- Modal de Confirmación -->
    <modal name="replication-confirm-modal" height="auto" :adaptive="true">
      <div class="modal-content-replication">
        <div class="ibox-title">
          <h1>Confirmar Replicación</h1>
        </div>
        <div class="modal-body">
            <h4>Desglose de Trámites a Replicar (Simulación):</h4>
            <div class="alert alert-danger">
                <i class="fa fa-warning"></i> <strong>Atención:</strong> Esta acción es irreversible y creará 1420 nuevos trámites.
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" @click="closeConfirmationModal">Cancelar</button>
          <button type="button" class="btn btn-primary" @click="executeReplication">
            <i class="fa fa-check"></i> Ejecutar Replicación
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
      canReplicate: false,
      message: '',
      sourceProcedure: null,
      destinationProcedure: null,
    };
  },
  mounted() {
    this.checkReplicationStatus();
  },
  methods: {
    checkReplicationStatus() {
      // Simulación para desarrollo sin backend
      this.isLoadingStatus = false;
      this.canReplicate = true;
      this.sourceProcedure = { semester: 'Primer', year: '2025-01-01' };
      this.destinationProcedure = { semester: 'Segundo', year: '2025-01-01' };
    },
    showConfirmationModal() {
      this.$modal.show('replication-confirm-modal');
    },
    closeConfirmationModal() {
      this.$modal.hide('replication-confirm-modal');
    },
    executeReplication() {
      if (confirm("¿Estás realmente seguro de que deseas ejecutar la replicación? Esta acción no se puede deshacer.")) {
        this.closeConfirmationModal();
        alert("¡Replicación ejecutada exitosamente! (Simulación)");
      }
    },
  },
};
</script>

<style scoped>
  .modal-content-replication {
    padding: 20px;
  }
  .modal-footer {
    margin-top: 20px;
    text-align: right;
  }
  .ibox-title h5 {
    font-size: 15px;
    font-weight: 600;
  }
  .ibox-content p {
    font-size: 14px;
  }
</style>