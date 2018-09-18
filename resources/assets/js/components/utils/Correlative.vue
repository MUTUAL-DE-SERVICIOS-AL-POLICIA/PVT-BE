<template>
    <span class="badge" v-if="retFun.isValidated"> {{ retFun.correlative }} </span>
</template>

<script>
    import { mapGetters } from "vuex";
    export default {
        props: ['retFunId', 'wfStateId'],
        mounted(){
            axios.get(`/ret_fun/${this.retFunId}/correlative/${this.wfStateId}`).then(response =>{
                this.$store.commit('retFunForm/setCorrelative',response.data.code);
            }).catch(error=>{
                console.log(error);
            })
        },
        computed: {
            ...mapGetters("retFunForm", {
                retFun: "getData"
            })
        }
    };
</script>

<style>
</style>
