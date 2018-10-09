<template>
    <span class="badge" v-if="retFun.isValidated"> {{ retFun.correlative }} </span>
</template>

<script>
    import { mapGetters, mapState } from "vuex";
    import { camelCaseToSnakeCase } from "../../helper.js";
    export default {
        props: ['docId', 'wfStateId', 'type'],
        mounted(){
            axios.get(`/${camelCaseToSnakeCase(this.type)}/${this.docId}/correlative/${this.wfStateId}`).then(response =>{
                this.$store.commit(`${this.type}Form/setCorrelative`,response.data.code);
            }).catch(error=>{
                console.log(error);
            })
        },
        computed: {
            retFun () {
                return this.$store.state[`${this.type}Form`];
            }
        }
    };
</script>

<style>
</style>
