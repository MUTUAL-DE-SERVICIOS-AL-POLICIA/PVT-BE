<template>
  <div class="row">
      <div class="alert alert-info">
          <div class="row">
            <div class="col-md-4">
                <strong>Modalidad:</strong> {{ quotaAid.modality }}
            </div>
            <div class="col-md-4">
                <strong>Parentesco:</strong> {{ quotaAid.applicant.kinship }}
            </div>
            <div class="col-md-4">
                <strong>Grado:</strong> {{ hierarchy.name+' - '+affiliate.degree }}
            </div>
          </div>
      </div>
  </div>
</template>
<script>
import {mapState, mapGetters, mapMutations} from 'vuex';
export default {
    props:[
        'affiliate',
        'hierarchy',
	],
    computed: {
        ...mapGetters('quotaAidForm',{
            quotaAid: 'getData'
        }),
    },
    created()
    {

    }

}
</script>
