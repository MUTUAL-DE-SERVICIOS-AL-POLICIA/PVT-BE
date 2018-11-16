<script>
import { mapGetters } from 'vuex';
export default {
    props: [
        'today'
    ],
    data(){
        return {
            document_number: null,
            document_date: null,
            commitment_date: this.today,
            start_contribution_date: null,
        }
    },
    computed: {
        ...mapGetters('directContributionForm', {
            directContribution: 'getData',
        }),
    }
}
</script>
