<template>
<div>
    <button v-for="(c, index) in retFun.contributionTypes" :key="index" class="btn btn-primary btn-sm m-r-md m-b-md"><i class="fa fa-file-pdf-o"></i> {{ c.name }}</button>

</div>
</template>
<script>
import { mapGetters} from 'vuex';
export default {
    computed:{
        ...mapGetters('retFunForm', {
            retFun: 'getData'
        }),
    }
}
</script>
