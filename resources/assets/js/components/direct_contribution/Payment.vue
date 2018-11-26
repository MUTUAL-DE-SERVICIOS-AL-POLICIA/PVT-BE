<template>    
    <div class="ibox-content">                    
        <div class="row">                
            <div class="col-md-2">
                <strong> Tipo de Pago:</strong>
            </div>
            <div class="col-md-4">
                <select class="form-control" v-model="payment_type">
                    <option value="1">Al contado</option>
                    <option value="2">Deposito Bancario</option>                    
                </select>
            </div>
            <div class="col-md-2">
                <strong> TOTAL a pagar:</strong>&nbsp;
            </div>
            <div class="col-md-4">                        
                <input type="text" v-model="total" class="form-control" :disabled='!editing'>
            </div>                    
        </div>
        <br>
        <div class="row">
            <div class="col-md-2">
                <strong v-if="payment_type==2"> Banco:</strong>&nbsp;
            </div>
            <div class="col-md-4">   
                <input v-if="payment_type==2" type="text" v-model="bank" class="form-control" :disabled='!editing'>
            </div>
            <div class="col-md-2">
                <strong v-if="payment_type==1"> Efectivo:</strong>
            </div>
            <div class="col-md-4">
                <input v-if="payment_type==1" type="text" class="form-control" v-model="paid" :disabled='!editing'>
            </div>
        </div>
        <br>        
        <div class="row">
            <div class="col-md-2">
                <strong v-if="payment_type==2"> N&uacute;mero de comprobante:</strong>
            </div>
            <div class="col-md-4">
                <input v-if="payment_type==2" type="text" class="form-control" v-model="bank_pay_number" :disabled='!editing'>
            </div>
            <div class="col-md-2">
                    <strong v-if="payment_type==1"> Cambio:</strong>&nbsp;
                </div>
            <div class="col-md-4">                
                <input v-if="payment_type==1" type="text" v-model="getExchange" class="form-control" :disabled='true'>
            </div>
        </div>    
    
        <div v-show="editing">
            <div class="text-center">                
                <button class="btn btn-primary" type="button" @click="store"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
            </div>
        </div>
        <br>        
    </div>         
</template>
<script>
    export default {
          props:[
            'direct_contribution',
        ],
        data(){
            return{
                editing: true,                
                paid: 0,
                payment_type: 1,
                bank: 'BANCO UNION',
                bank_pay_number: '',
                paid_amount: 0,
                exchange: 0,
                total: 50.09,    
            }
        },
        created(){
            console.log("FORM");
               console.log(this.form);
        },
        methods:{
            store: function(){
                axios.post('/contribution_save',{aportes,total:this.total,afid:this.afid,paid:parseMoney(this.paid),bank:this.bank,bank_pay_number:this.bank_pay_number})
                .then(response => {                  
                this.enableDC();
                var i;});
            },            

        },
        computed: {
            getExchange: function() {
                console.log('calculatin exchagne');
                this.exchage = this.paid - this.total;
                return this.exchage;
            }
        }
    }
</script>