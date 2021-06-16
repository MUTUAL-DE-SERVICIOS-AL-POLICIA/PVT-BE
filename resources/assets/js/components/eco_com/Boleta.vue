<script>
	export default{
        props:[
            'affiliate',
			'affiliatedevice'
		],
		data(){
			return { editable: true }
		},
		mounted() {
			this.editable=this.affiliatedevice.verified;
    	},
		methods: {
            updateValidar: function() {
                let uri = `/CIDevice/${this.affiliate.id}/true`;
                axios.get(uri)
					.then(response=>{
						this.editable=true;
						flash('CI validado');
					}).catch((response)=>{
						flash('Error al validar, verifique que esta enrolado','error');
					});
            },
            updateDesvalidar: function() {
                let uri = `/CIDevice/${this.affiliate.id}/false`;
                axios.get(uri)
					.then(response=>{
						this.editable=false;
						flash('CI desvalidado');
					}).catch((response)=>{
						flash('Error al validar, verifique que esta enrolado','error');
					});
            }
        }
	}
</script>