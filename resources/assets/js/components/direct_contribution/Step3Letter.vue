<script>
import { mapGetters } from 'vuex';
export default {
    props: [
        'today'
    ],
    data(){
        return {
            
        }
    },
    computed: {
        ...mapGetters('directContributionForm', {
            directContribution: 'getData',
        }),
    }
}
</script>
