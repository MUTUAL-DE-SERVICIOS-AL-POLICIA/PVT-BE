<template>    
    <div class="ibox-content">
        <div  class="pull-left">
            <legend>Pago</legend>
        </div>                
        <div class="text-right">
            <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing"><i class="fa" :class="editing ?'fa-edit':'fa-pencil'" ></i> Editar </button>
        </div>        
        <br>
        <div class="row">                
            <div class="col-md-2">
                <strong> Tipo de Pago:</strong>
            </div>
            <div class="col-md-4">                
                <select class="form-control m-b" name="payment_type_id" @change="switchPayment" v-model="payment_type_id" :disabled='!editing'>
                    <option v-for="payment_type in payment_types" :value="payment_type.id" :key="payment_type.id">{{ payment_type.name }}</option>
                </select>                
            </div>
            <div class="col-md-2">
                <strong v-if="payment_type_id==1"> Efectivo:</strong>
            </div>
            <div class="col-md-4">
                <input v-if="payment_type_id==1" type="text" class="form-control" v-model="paid" :disabled='!editing' v-money>
            </div>               
        </div>
        <br>
        <div class="row">
            <div class="col-md-2">
                <strong v-if="payment_type_id==2"> Banco:</strong>&nbsp;
            </div>
            <div class="col-md-4">   
                <input v-if="payment_type_id==2" type="text" v-model="bank" class="form-control" :disabled='!editing'>
            </div>            
            <div class="col-md-2">
                <strong> TOTAL COBRO:</strong>&nbsp;
            </div>
            <div class="col-md-4">                        
                <input type="text" v-model="total" class="form-control" :disabled='!editing' v-money>
            </div> 
        </div>
        <br>        
        <div class="row">
            <div class="col-md-2">
                <strong v-if="payment_type_id==2"> N&uacute;mero de comprobante:</strong>
            </div>
            <div class="col-md-4">
                <input v-if="payment_type_id==2" type="text" class="form-control" v-model="bank_pay_number" :disabled='!editing'>
            </div>
            <div class="col-md-2">
                    <strong v-if="payment_type_id==1"> Cambio:</strong>&nbsp;
                </div>
            <div class="col-md-4">                
                <input v-if="payment_type_id==1" v-money='true' type="text" v-model="getExchange" class="form-control" :disabled='true'>
            </div>
        </div>    
    
        <div v-show="editing">
            <div class="text-center">                
                <button class="btn btn-primary" type="button" @click="store"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content forum-container">
                        <div class="row">
                            <table class="table table-stripped toggle-arrow-tiny">
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Concepto</th>
                                        <th>Monto</th>
                                        <th>Acci&oacute;n</th>
                                    </tr>
                                </thead>
                                <tbody>                                    
                                    <tr v-for="voucher in vouchers" :key="voucher.id">
                                        <td> {{ voucher.code }} </td>
                                        <td> {{ voucher.type.name }} </td>
                                        <td> {{ voucher.total }} </td>
                                        <td><a href="#"><i class="fa fa-check text-navy"></i></a></td>
                                    </tr>                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>         
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
            'charge',                        
            'payment_types',
            'affiliate_id',
            'vouchers',
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
                total: this.charge.amount,
                payment_type_id: 1,
            }
        },
        created(){            
            //this.completeVoucher();
            //this.switchPayment();
        },
        methods:{
            store: function(){                
                if(parseMoney(this.paid) < parseMoney(this.charge.total) && this.payment_type_id == 1) {
                    flash("El monto pagado es menor al monto total", "error",6000);
                    return;
                }
                if(parseMoney(this.total) < parseMoney(this.charge.total)) {
                    flash("El monto total es menor al monto cotizado", "error",6000);
                    return;
                }                         
                axios.post('/voucher',
                {                       
                    affiliate_id: this.affiliate_id,
                    total: parseMoney(this.total),
                    paid: parseMoney(this.paid),
                    bank: this.bank,
                    payment_type_id: this.payment_type_id,
                    bank_pay_number:this.bank_pay_number})
                .then(response => {
                    console.log('trying to storess');
                    this.editing = false;
                    //this.enableDC();
                    //flash('Cobro realizado exitosamente');
                    let affiliate_id = this.affiliate_id;
                    let voucher_id = response.data.voucher.id;
                    console.log('affiliate/'+affiliate_id+'/voucher/'+voucher_id+'/print');
                    printJS({printable:'/affiliate/'+affiliate_id+'/voucher/'+voucher_id+'/print', type:'pdf', showModal:true});
                    console.log('after print');
                    var i;
                }
                );
            },
            // completeVoucher(){                
            //     if(this.voucher !== null && this.voucher != 0){
            //         this.paid = this.voucher.paid_amount;
            //         this.bank = this.voucher.bank;
            //         this.bank_pay_number = this.voucher.bank_pay_number;
            //         this.paid_amount = this.voucher.total;
            //         this.payment_type_id = this.voucher.payment_type_id;                
            //         this.editing = false;
            //     }
            // },
            toggle_editing() {
                this.editing = !this.editing;
            },            
            switchPayment() {                                
                if(this.payment_type_id == 1) {
                    let rounded = this.voucher.amount;
                    this.total = this.roudOneDecimal(rounded);
                } else {
                    console.log(this.voucher.amount);
                    this.total = this.voucher.amount;
                }
                console.log(this.total);
            },
            roudOneDecimal(number) {
                var result = number*10;
                result = Math.ceil(result);
                result = result/10;
                return result;
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