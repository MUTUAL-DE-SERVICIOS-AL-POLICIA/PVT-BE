<template>
    <div class="ibox">
        <div class="ibox-content">
            <form>
                <requirement-edit-select :requirements="requirements" :store="store"
                    :is-legal-review="isLegalReview"></requirement-edit-select>
            </form>
        </div>
    </div>
</template>
<script>
export default {
    props: [
        'affiliate',
        'ret_fun',
        'requirements',
        'rol',
    ],
    computed: {
        isLegalReview() {
            return this.rol === 11;
        }
    },
    methods: {
        store(requirements, aditionalRequirements = null) { // Se pasa la referencia de la funcion al componente hijo
            if (!this.isLegalReview) {
                let uri = `/ret_fun/${this.ret_fun.id}/edit_requirements`;
                axios.post(uri,
                    {
                        requirements,
                        aditional_requirements: aditionalRequirements
                    }
                ).then(response => {
                    if (response.status == 200) {
                        flash("Verificacion Correcta");
                        location.reload();
                    }
                }).catch(error => {
                    console.log(error);
                    flash("Los Datos no Coinciden", "error");
                });
            } else {
                let uri = `/ret_fun/${this.ret_fun.id}/legal_review/create`;
                axios.post(uri,
                    {
                        submit_documents: requirements
                    }
                ).then(response => {
                    flash("Verificacion Correcta");
                    location.reload();
                }).catch(error => {
                    flash("Los Datos no Coinciden", "error");
                });
            }
        }
    },
}
</script>