<template>
    <div v-show="show" id="toast-container" class="toast-top-right" aria-live="polite" role="alert">
    </div>
</template>
<script>
    
</script>

<script>
    export default {
        props: ['message'],
        data() {
            return {
                body: this.message,
                level: 'success',
                timeOut: 5000,
                show: false
            }
        },
        created() {                        
            if (this.message) {
                this.flash();
            }
            window.events.$on(
                'flash', data => this.flash(data)
            );
        },
        methods: {
            flash(data) {
                if (data) {
                    this.body = data.message;
                    this.level = data.level;
                    this.timeOut = data.timeOut;
                }
                this.show = true;
                toastr.options={
                    "closeButton": true,
                      "debug": false,
                      "newestOnTop": false,
                      "progressBar": true,
                      "preventDuplicates": false,
                      "onclick": null,
                      "showDuration": "600",
                      "hideDuration": "1000",
                      "timeOut": this.timeOut,
                      "extendedTimeOut": "1000",
                      "showEasing": "swing",
                      "hideEasing": "linear",
                      "showMethod": "fadeIn",
                      "hideMethod": "fadeOut"
                };
                if (this.level == 'success') {toastr.success(this.body, 'Éxito')}
                if (this.level == 'error') {toastr.error(this.body, 'Error')}
                if (this.level == 'info') {toastr.info(this.body, '!')}
                if (this.level == 'warning') {toastr.warning(this.body, 'Alerta')}
            }
        }
    };
</script>