<template>
<div>
    <transition-group
        enter-active-class="animated tada"
        leave-active-class="animated bounceOutUp"
    >
        <button v-for="(c, index) in retFun.contributionTypes" v-if="retFun.contributionTypes.length" :key="index" :onclick="`printJS(${print(c.path)})`" class="btn btn-primary btn-md m-r-md m-b-md"><i class="fa fa-print"></i> {{ c.name }}</button>
    </transition-group>
</div>
</template>
<script>
import { mapGetters} from 'vuex';
export default {
    computed:{
        ...mapGetters('retFunForm', {
            retFun: 'getData'
        }),
    },
    methods:{
        print(path){
            return JSON.stringify({
                printable:`/ret_fun/${this.retFun.id}/${path}`,
                type:'pdf',
                modalMessage: 'Generando documentos de impresión, por favor espere un momento.',
                showModal:true
            });
        }

    }
}
</script>
