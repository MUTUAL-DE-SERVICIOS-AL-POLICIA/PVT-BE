<template>
  <div class="row">
      <div class="alert alert-info">
          <div class="row">
            <div class="col-md-4">
                <strong>Modalidad:</strong> {{ retfun.modality }}
            </div>
            <div class="col-md-4">
                <strong>Parentesco:</strong> {{ retfun.applicant.kinship }}
            </div>
            <div class="col-md-4">
                <!-- reservado para el grado hdp -->
            </div>
          </div>
      </div>
  </div>
</template>
<script>
import {mapState, mapGetters, mapMutations} from 'vuex';
export default {
    computed: {
        ...mapGetters({
            retfun: 'getData'
        }),
    },
    created()
    {
        console.log(this.retfun);
    }

}
</script>
