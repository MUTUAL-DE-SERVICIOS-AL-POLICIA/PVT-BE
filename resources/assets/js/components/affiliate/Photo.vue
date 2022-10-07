<script>
	export default{
        props:[
            'affiliate',
			'affiliatedevice',
			'affiliatetoken'
		],
		data(){
			return { editable: true,
		             btnVerified:false,
					 notification: true
				    }
		},
		mounted() {
			if (this.affiliatedevice!=-1){
				this.editable = this.affiliatedevice.verified;
			    this.btnVerified = true;
		    }else{
				this.btnVerified = false;
			}
			if(this.affiliatetoken!=-1){
					if(this.affiliatetoken.api_token!= null && this.affiliatetoken.firebase_token!= null){
					  this.notification = true;
					  }
					  else{
					  this.notification = false;
					  };
			}else{
                this.notification = false;
			}
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