<template>    
    <div class="ibox-content">                    
        <div class="row">                
            <div class="col-md-2">
                <strong> Tipo de Pago:</strong>
            </div>
            <div class="col-md-4">
                <!-- <select class="form-control" v-model="payment_type_id" :disabled='!editing'>
                    <option value=""></option>                
                </select> -->
                <select class="form-control m-b" name="city_id" v-model="payment_type_id">                    
                    <option v-for="payment_type in payment_types" :value="payment_type.id" :key="payment_type.id">@{{ payment_type.name }}</option>
                </select>                
            </div>
            <div class="col-md-2">
                <strong v-if="payment_type==1"> Efectivo:</strong>
            </div>
            <div class="col-md-4">
                <input v-if="payment_type==1" type="text" class="form-control" v-model="paid" :disabled='!editing' v-money>
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
                <strong> TOTAL cobrado:</strong>&nbsp;
            </div>
            <div class="col-md-4">                        
                <input type="text" v-model="total" class="form-control" :disabled='!editing' v-money>
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
                <input v-if="payment_type==1" v-money='true' type="text" v-model="getExchange" class="form-control" :disabled='true'>
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
    import {
        parseMoney,
        moneyInputMaskAll,
    }
    from "../../helper.js";
    export default {
          props:[
            'contribution_process',
            'voucher',
            'payment_types',
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
                total: this.contribution_process.total,
                payment_type_id: 1,
            }
        },
        created(){
            console.log("FORM");
               console.log(this.payment_types);
            this.completeVoucher();
        },
        methods:{
            store: function(){
                process = this.contribution_process;
                axios.post('/contribution_process/'+this.contribution_process.id+'/contribution_pay',                
                {   
                    process,
                    total:this.total,                    
                    paid:parseMoney(this.paid),
                    bank:this.bank,
                    bank_pay_number:this.bank_pay_number})
                .then(response => {
                    this.editing = false;
                this.enableDC();
                var i;});
            },           
            completeVoucher(){
                if(this.voucher !== null){
                    this.paid = this.voucher.paid_amount;
                    this.bank = this.voucher.back;
                    this.bank_pay_number = this.voucher.bank_pay_number;
                    this.paid_amount = this.voucher.total;
                    this.payment_type_id = this.voucher.payment_type_id;                
                }
            } 
        },
        computed: {
            getExchange: function() {                               
                this.exchage = parseMoney(this.paid)-parseMoney(this.total);                
                this.exchage = Math.round(this.exchage * 100) / 100
                return this.exchage;
            }
        }
    }
</script>