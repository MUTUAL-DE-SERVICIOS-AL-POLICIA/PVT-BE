<template>
  <div class="row">
        <table class="table small ">
            <tbody>
                <tr>
                    <td>
                        <strong>Modalidad:</strong>{{ retfun.modality }}
                    </td>
                    <td>
                        <strong>Ciudad:</strong> {{ retfun.city }}
                    </td>

                </tr>
                <tr>
                    <td>
                        <strong>Tipo Solicitante:</strong> {{ retfun.applicant.type }}
                    </td>
                    <td>
                        <strong>Nombre del Solicitante</strong> {{ retfun.applicant.first_name }} {{ retfun.applicant.second_name }} {{ retfun.applicant.last_name }} {{ retfun.applicant.mothers_last_name }} {{ retfun.applicant.surname_husband }}

                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>CI</strong> {{ retfun.applicant.identity_card }} {{ retfun.applicant.city_identity_card }}
                    </td>
                    <td>
                        <strong>Parentesco</strong> {{ retfun.applicant.kinship }}
                    </td>
                </tr>
                </tbody>
    </table>
  </div>
</template>
<script>
import {mapState, mapGetters, mapMutations} from 'vuex';
export default {
    computed: {
        ...mapGetters({
            retfun: 'getData'
        }),
    }
}
</script>
