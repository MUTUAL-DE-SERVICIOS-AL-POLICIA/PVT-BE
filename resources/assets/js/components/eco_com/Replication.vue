<template>
  <div>
    <!-- Estado de Carga Inicial -->
    <div v-if="isLoadingStatus" class="text-center">
      <i class="fa fa-spinner fa-spin fa-3x"></i>
      <p>Verificando estado de la replicación...</p>
    </div>

    <!-- Mensaje de Error/Alerta -->
    <div v-else-if="!canReplicate" class="alert alert-warning">
      <h4><i class="fa fa-warning"></i> Acción no disponible</h4>
      <p>{{ message }}</p>
    </div>

    <!-- Tarjeta de Replicación -->
    <div v-else class="ibox">
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
        
        <!-- Botón para Iniciar el Cálculo -->
        <button class="btn btn-primary" @click="calculateEligible" :disabled="isCalculating">
          <i v-if="isCalculating" class="fa fa-spinner fa-spin"></i>
          <i v-else class="fa fa-calculator"></i>
          Calcular Trámites a Replicar
        </button>

        <!-- Sección de Resultados (se muestra después del cálculo) -->
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
  </div>
</template>

<script>
export default {
  props: ["permissions"],
  data() {
    return {
      isLoadingStatus: true,
      isCalculating: false,
      canReplicate: false,
      message: '',
      sourceProcedure: null,
      destinationProcedure: null,
      calculationResult: null, // Almacena el resultado del cálculo
    };
  },
  mounted() {
    this.checkReplicationStatus();
  },
  methods: {
    checkReplicationStatus() {
      this.isLoadingStatus = true;
      axios.get("/eco_com_replicate/status")
        .then(response => {
          this.canReplicate = response.data.can_replicate;
          if (this.canReplicate) {
            this.sourceProcedure = response.data.source_procedure;
            this.destinationProcedure = response.data.destination_procedure;
          } else {
            this.message = response.data.message;
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
      this.calculationResult = null; // Limpiar resultados previos

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
      // Por ahora, esto sigue siendo una simulación.
      // En el futuro, llamará al endpoint de ejecución final.
      if (confirm(`¿Estás realmente seguro de que deseas replicar ${this.calculationResult.total_to_replicate} trámites? Esta acción no se puede deshacer.`)) {
        alert("¡Replicación ejecutada exitosamente! (Simulación)");
      }
    },
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