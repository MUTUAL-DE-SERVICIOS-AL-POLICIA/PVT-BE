<template>
<div>
    <button @click="modal(10)" >
        hola
    </button>
    <transition-group
        enter-active-class="animated tada"
        leave-active-class="animated bounceOutUp"
    >
    <!-- :onclick="`printJS(${print(c.path)})`" -->
        <button v-for="(c, index) in retFun.contributionTypes" v-if="retFun.contributionTypes.length" :key="index" 
        @click="modal(c.id, c.path)"
         class="btn btn-primary btn-md m-r-md m-b-md"><i class="fa fa-print"></i> {{ c.name }}</button>

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
            return {
                printable:`/ret_fun/${this.retFun.id}/${path}`,
                type:'pdf',
                modalMessage: 'Generando documentos de impresión, por favor espere un momento.',
                showModal:true
            };
        },
        modal(contributionTypeId, path){

            console.log('retFun.contributionTypes: ', this.retFun.contributionTypes);
            this.$swal({
                title: 'Escribe una nota:',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Imprimir',
                showLoaderOnConfirm: true,
                preConfirm: (message) => {
                    return axios.post(`/ret_fun/${this.retFun.id}/save_message`,{
                        contributionTypeId: contributionTypeId,
                        message: message
                    }).then(response => {
                        if (!response.data) {
                        throw new Error(response.statusText)
                        }
                        return response
                    })
                    .catch(error => {
                        this.$swal.showValidationError(
                        `Request failed: ${error}`
                        )
                    })
                },
                allowOutsideClick: () => !swal.isLoading()
                }).then((result) => {
                if (result.value) {
                    printJS(this.print(path))
                }
            })
        }

    }
}
</script>
