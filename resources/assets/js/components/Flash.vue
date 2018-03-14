<template>
    <div v-show="show" id="toast-container" class="toast-top-right" aria-live="polite" role="alert">
        <!-- <div class="toast" :class="'toast-'+level" style=""> -->
            <!-- <div class="toast-progress" style="width: 28.27%;"></div> -->
            <!-- <button type="button" class="toast-close-button" role="button">×</button> -->
            <!-- <div class="toast-message" v-text="body"></div> -->
        <!-- </div> -->
    </div>

    <!-- <div class="toast toast-flash"
         :class="'toast-'+level"
         role="alert"
         >
    </div> -->
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
                show: false,
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
                }
                this.show = true;
                toastr.options={
                    "closeButton": true,
                      "debug": false,
                      "newestOnTop": false,
                      "progressBar": true,
                      "positionClass": "toast-top-right",
                      "preventDuplicates": false,
                      "onclick": null,
                      "showDuration": "300",
                      "hideDuration": "1000",
                      "timeOut": "5000",
                      "extendedTimeOut": "1000",
                      "showEasing": "swing",
                      "hideEasing": "linear",
                      "showMethod": "fadeIn",
                      "hideMethod": "fadeOut"
                };
                if (this.level == 'success') {toastr.success(this.body, 'Success!!! :)')}
                if (this.level == 'error') {toastr.error(this.body, 'Wrong!!! :(')}
                if (this.level == 'info') {toastr.info(this.body, 'Mmmm')}
                if (this.level == 'warning') {toastr.warning(this.body, 'Alert!!!')}
                // this.hide();
            }/*,
            hide() {
                setTimeout(() => {
                    this.show = false;
                }, 3000);
            }*/
        }
    };
</script>