<script>
	export default{
        props:[
            'affiliate'
		],
		methods: {
            updateValidar: function() {
                let uri = `/CIDevice/${this.affiliate.id}/true`;
                axios.get(uri)
					.then(response=>{
						flash('CI validado');
					}).catch((response)=>{
						flash('Error al validar, verifique que esta enrolado','error');
					});
            },
            updateDesvalidar: function() {
                let uri = `/CIDevice/${this.affiliate.id}/false`;
                axios.get(uri)
					.then(response=>{
						flash('CI desvalidado');
					}).catch((response)=>{
						flash('Error al validar, verifique que esta enrolado','error');
					});
            }
        }
	}
</script>