<template>
<div class="row">
    <div class="col-md-12">
        <div class="col-lg-12">
            <div class="panel panel-primary"  :class="show_spinner ? 'sk-loading' : ''" >
                <div class="panel-heading">
                    <h3 class="pull-left">Pago de Aportes</h3>
                    <div class="text-right" v-if="contributions.length > 0">
                        <button data-animation="flip" class="btn btn-primary" @click="PrintQuote()" :disabled="! total > 0" ><i class="fa fa-print" ></i> Imprimir TOp </button>
                    </div>
                    <div v-else>
                        <button data-animation="flip" class="btn btn-primary" > </button>
                    </div>
                </div>
                <div class="panel-body" id="print" >
                    <div class="sk-folding-cube" v-show="show_spinner">
                        <div class="sk-cube1 sk-cube"></div>
                        <div class="sk-cube2 sk-cube"></div>
                        <div class="sk-cube4 sk-cube"></div>
                        <div class="sk-cube3 sk-cube"></div>
                    </div>
                    <transition
                     enter-active-class="animated tada"
                     leave-active-class="animated bounceOutRight"
                    >
                        <div class="row col-lg-12" v-if="toggle">
                            <div class="form-inline">
                                <div class="form-group">
                                    <input type="text" v-date v-model="dateEnd" @keypress.enter="refresh()" class="form-control">
                                </div>
                                <div class="form-group">
                                    <button @click="refresh()" class="btn btn-primary"> <i class="fa fa-arrow-right"></i> Continuar</button>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                        </div>
                    </transition>
                    <!-- v-if="showContributions" -->
                    <div 
                     class="row col-lg-12">
                        <div v-if="contributions.length > 0">
                            <div class="row" >
                                <div class="col-md-3" >
                                    <input type="text" class="form-control"  v-money @keyup.enter="repeatSalary()" v-model="general_salary">
                                </div>
                                <div class="col-md-3" >
                                    <button class="btn btn-primary " type="button" @click="repeatSalary()"><i class="fa fa-money"></i>&nbsp;Repetir Sueldo</button>
                                </div>
                            </div>
                            <hr>
                            <table class="table table-striped" data-page-size="15">
                                <thead>
                                    <tr>
                                        <th class="footable-visible footable-first-column footable-sortable">Mes/Año</th>
                                        <th class="footable-visible footable-sortable">Total Ganado Bs.</th>
                                        <!-- <th class="footable-visible footable-sortable">Tipo de cambio.</th> -->
                                        <th class="footable-visible footable-sortable">F.R.P. (4.77 %)</th>
                                        <th class="footable-visible footable-sortable">Cuota Mortuoria (1.09 %)</th>
                                        <th class="footable-visible footable-sortable">Ajuste UFV Bs.</th>
                                        <th class="footable-visible footable-sortable">Subtotal Aporte</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(con, index) in contributions" :key="index" id="form" v-bind:style="getStyleColor(index)" :class="{'danger': error(con.subtotal)}">
                                        <td><input type="text"  v-model="con.monthyear" disabled class="form-control"></td>
                                        <td><input type="text" v-model="con.sueldo" v-money @keyup.enter="CalcularAporte(con, index)"  ref="s1"  class="form-control"></td>
                                        <!-- <td><input type="text"  v-model="con.fr" v-money disabled class="form-control"></td> -->
                                        <td><input type="text"  v-model="con.fr" v-money disabled class="form-control"></td>
                                        <td><input type="text" v-model="con.cm" v-money disabled class="form-control"></td>
                                        <td><input type="text" v-model="con.interes" disabled v-money class="form-control"></td>
                                        <td><input type="text"  v-model="con.subtotal" v-money disabled class="form-control"></td>
                                        <td class="row">
                                            <div class="col-md-6">
                                                <button class="btn btn-warning btn-circle" @click="RemoveRow(index)" type="button"><i class="fa fa-times"></i>  </button>
                                            </div>
                                            <div class="col-md-6" v-if="con.sueldo>0 && con.type!='R'">
                                                <button class="btn btn-warning btn-circle" @click="createReimbursement(con.month)" type="button"><i class=""></i> R </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><label for="total">Total a Pagar por Concepto de Aportes:</label></td>
                                        <td colspan="4"><input type="text" v-model="total" v-money disabled class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><label for="total">Total Pagado:</label></td>
                                        <td colspan="4"><input type="text" v-model="paid"  v-money class="form-control"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="text-center" v-if="!is_regional">
                                <button class="btn btn-primary " type="button" :disabled="!disabledSaved" @click="Guardar()"><i class="fa fa-save"></i>&nbsp;Imprimir</button>                                
                            </div>
                            <div class="col-md-12" v-else>
                                <div class="col-md-4">
                                <input type="text" placeholder="banco" v-model="bank" class="form-control">
                                </div>
                                <div class="col-md-4">
                                <input type="text" placeholder="número de comprobante" v-model="bank_pay_number" class="form-control">
                                </div>
                                <div class="col-md-4">
                                <button class="btn btn-primary " type="button" :disabled="!disabledSaved" @click="Guardar()"><i class="fa fa-save"></i>&nbsp;Imprimir</button>
                                </div>
                            </div>
                            <div>
                                <input type="checkbox" id="switch" v-model="toggle" >
                                <label for="switch" class="label-control">Opciones avanzadas</label>
                            </div>
                        </div>
                        <div v-else class="row">
                            <div class="text-center">
                                <h2>No tiene Pagos Pendientes</h2>
                                <button v-if="reprint" @click="reprintButton()" class="btn btn-primary"> <i class="fa fa-print"></i> Reimprimir </button>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal inmodal" id="reimbursement_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                <h4 class="modal-title">Reintegro</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Monto</label>
                    <input id="reimbursement_amount" v-model="reimbursement_amount" name="reimbursement_amount" type="text" placeholder="Monto" class="form-control numberformat">
                    <label>Mes</label>
                    <select class="form-control" name="month" id="month" v-model="reimbursement_month">
                        <option value="01">Enero</option>
                        <option value="02">Febrero</option>
                        <option value="03">Marzo</option>
                        <option value="04">Abril</option>
                        <option value="05">Mayo</option>
                        <option value="06">Junio</option>
                        <option value="07">Julio</option>
                        <option value="08">Agosto</option>
                        <option value="09">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                     </select>
                </div>
                <button class="btn btn-default" type="button" title="Guardar" @click="calculateReimbursement()">
                    Calcular
                </button>
                <div class="form-group">
                    <label>Monto Cotizable</label>
                    <input id="reimbursement_quotable" v-model="reimbursement_quotable" name="reimbursement_quotable" type="text" placeholder="Aporte Total" class="form-control numberformat">
                    <table class="table table-striped" data-page-size="15">
                        <thead>
                            <tr>
                            <th class="footable-visible footable-first-column footable-sortable">Mes</th>
                            <th class="footable-visible footable-sortable">Monto</th>
                            <th class="footable-visible footable-sortable">F.R.P. (4.77 %)</th>
                            <th class="footable-visible footable-sortable">Cuota Mortuoria (1.09 %)</th>                            
                            <th class="footable-visible footable-sortable">Subtotal Aporte</th>                                                     
                            </tr>
                        </thead>
                        <tr style="" v-for="(reim_pay, index3) in reimbursement_pays" :key="index3" id="reimbursement_pays">
                            <td>
                                <input type="text"  v-model = "reim_pay.month_year" disabled class="form-control">
                            </td>
                            <td>
                                <input type="text" v-model = "reim_pay.amount" v-money disabled class="form-control" >
                            </td>
                            <td>
                                <input type="text"  v-model = "reim_pay.retirement_fund" v-money disabled class="form-control">
                            </td>
                            <td>
                                <input type="text" v-model = "reim_pay.quota" v-money disabled class="form-control">
                            </td>                            
                            <td>
                                <input type="text"  v-model = "reim_pay.subtotal" v-money disabled class="form-control">
                            </td>                            
                        </tr>
                        <tr>
                            <td><label for="total">Total:</label></td>
                            <td><input type="text" v-model="info_amount" v-money disabled class="form-control"></td>
                            <td><input type="text" v-model="info_retirement_fund" v-money disabled class="form-control"></td>
                            <td><input type="text" v-model="info_quota" v-money disabled class="form-control"></td>
                            <td><input type="text" v-model="info_total" v-money disabled class="form-control"></td>
                        </tr>

                    </table>
                    <!-- <input id="reim_salary" name="reim_salary" type="text" placeholder="Sueldo" class="form-control numberformat">
                    <label>Categor&iacute;a</label>
                    <select class="form-control" name="reim_category" id="reim_category">
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->percentage}}</option>
                        @endforeach
                    </select>
                    <label>Total Ganado</label>
                    <input id="reim_gain" name="reim_gain" type="text" placeholder="Total ganado" class="form-control numberformat">
                    <label>Aporte</label>
                    <input id="reim_amount" name="reim_amount" type="text" placeholder="Aporte" class="form-control numberformat"> -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                <!--<button type="submit" class="btn btn-primary">Guardar</button>-->
                <button class="btn btn-default" type="button" title="Guardar" @click="addReimbursement()">
                    Guardar
                </button>
            </div>
        </div>
    </div>
</div>

</div> 
</template>

<script>
import {
        parseMoney,
        // moneyInputMaskAll,
        // dateInputMaskAll
    }
    from "../../helper.js";
export default {
    
    props: [
        // 'contributions1',
        'afid',
        'last_quotable',
        'commitment',
        'is_regional'
    ],
    data() {   

    return {
      contributions: [],
      total: 0,
      paid: 0,
      tipo:null,
      ufv:0,
      estado: true,
      afi_id:null,
      show_spinner:false,
      count:4,
      ufvs: [],
      general_salary: 0,
      month: 0,      
      reimbursement_amount: 0,
      reimbursement_month: '01',
      reimbursement_quotable: 0,
      reimbursements: [],
      reimbursement_pays: [],
      info_amount: 0,
      info_retirement_fund : 0,
      info_quota : 0,
      info_total : 0,
      bank : '',
      bank_pay_number : '',
      reprint: null,
      dateEnd: moment().format('DD/MM/YYYY'),
    //   showContributions:true,
      toggle:false,
      rate:[]
    };
  },
   
  mounted() {
    console.log(this.is_regional);
    // this.contributions = this.contributions1;  
    this.afi_id = this.afid;    
    window.addEventListener("load", function(event) {
        // moneyInputMaskAll();
        // dateInputMaskAll();
    });
    // if(this.commitment.id == 0){
    //       this.tipo=null;
    // }else{
    //     if(this.commitment.commitment_type=="COMISION")
    //         {
    //           this.tipo=2;
    //         }else{
    //             if(this.commitment.commitment_type=="BAJA TEMPORAL")
    //             {
    //             this.tipo=9;
    //             }else{
    //                 if(this.commitment.commitment_type=="AGREGADO POLICIAL")
    //                 {
    //                     this.tipo=10;
    //                 } 
    //             }
    //         }        
    //     }
    // this.refresh();
  },
  created(){    
  },
  methods: {
      error(value){
          return ! value >  0;
      },
      RemoveRow(index) {
        this.contributions.splice(index,1);
        this.SumTotal();
      },
      getStyleColor(index){          
          if(this.contributions[index].type == 'R')
            return "background-color:#ffe6b3";
      },
      refresh() {
          axios.get(`/affiliate/${this.afid}/get_month_contributions/${moment(this.dateEnd, 'DD/MM/YYYY').format('YYYY-MM-DD')}`)
          .then(response =>{
              this.contributions = response.data
          }).catch(error =>{
              console.log(error)
          });
          axios.get(`/get_contribution_rate/${moment(this.dateEnd, 'DD/MM/YYYY').format('YYYY-MM-DD')}`)
          .then(response =>{
              this.rate = response.data
          }).catch(error =>{
              console.log(error)
          });
            setTimeout(() => {
                // moneyInputMaskAll();
                // dateInputMaskAll();
            }, 300);
        // this.showContributions =  true;
        // this.contributions = this.contributions1;
      },
      repeatSalary(){
        var i;           
        for(i=0;i<this.contributions.length;i++){
            this.contributions[i].sueldo = this.general_salary;            
            this.CalcularAporte(this.contributions[i],i);
        }              
      },      
      calculateReimbursement(){           
        axios.get('/calculate_reimbursement/'+this.afi_id+'/'+this.reimbursement_amount+'/'+this.reimbursement_month)
        .then(response => {
            this.reimbursement_quotable = this.reimbursement_amount;// response.data.quotable;   
            var i;
            let contributions_number = parseInt(this.reimbursement_month)-1;            
            this.reimbursement_quotable = this.reimbursement_amount/contributions_number;            
            let subtotal = this.reimbursement_amount/contributions_number;            
            for(i=0;i<response.data.contributions.length;i++)
            {
                let date =moment(response.data.contributions[i],"YYYY-MM-DD");                
                let retirement_fund_amount =  parseFloat(subtotal*this.rate.retirement_fund/100).toFixed(2);
                let quota_amount = parseFloat(subtotal*this.rate.mortuary_quota/100).toFixed(2);
                console.log(subtotal);
                var new_info = {
                    'month_year' : date.format('MM-YYYY'),
                    'amount'    :   parseFloat(subtotal).toFixed(2),
                    'retirement_fund'   :   retirement_fund_amount,
                    'quota' :   quota_amount,
                    'subtotal'  :   parseFloat(retirement_fund_amount+quota_amount).toFixed(2),
                };
                this.reimbursement_pays.push(new_info);                                 
            }
            i=0;
            for(i=0;i<this.contributions.length;i++)
            {                                                
                let retirement_fund_amount =  parseFloat(subtotal*this.rate.retirement_fund/100).toFixed(2);
                let quota_amount = parseFloat(subtotal*this.rate.mortuary_quota/100).toFixed(2);

                if(parseInt(this.reimbursement_month)>this.contributions[i].month && this.contributions[i].type != 'R' ){                    
                    var new_info = {
                        'month_year' : this.contributions[i].monthyear,
                        'amount'    :   parseFloat(subtotal).toFixed(2),
                        'retirement_fund'   :   retirement_fund_amount,
                        'quota' :   quota_amount,
                        'subtotal'  :   parseFloat(retirement_fund_amount+quota_amount).toFixed(2),
                    };                
                    this.reimbursement_pays.push(new_info);                            
                }     
            }
            
            let quotable = subtotal*this.reimbursement_pays.length;
            this.reimbursement_quotable = quotable;
            this.info_amount = parseFloat(quotable).toFixed(2);
            this.info_retirement_fund = parseFloat(quotable*this.rate.retirement_fund/100).toFixed(2);
            this.info_quota = parseFloat(quotable*this.rate.mortuary_quota/100).toFixed(2);
            this.info_total = parseFloat(this.info_retirement_fund+this.info_quota).toFixed(2);
            //moneyInputMaskAll();
        })
        .catch(e => {
            
             console.log(--this.count);
            // console.log("40004");
            
            // this.show_spinner=false;
            // this.CalcularAporte(con, index);
        });
      },
      CalcularAporte(con, index){
        con.sueldo = parseMoney(con.sueldo);
        if(parseFloat(con.sueldo) >0)
        {
        if(this.count > 0)
        {
            this.show_spinner=true
            if(this.ufvs[con.sueldo] && false)
            {
                con.fr = con.sueldo * this.rate.retirement_fund/100;
                con.cm = con.sueldo * this.rate.mortuary_quota/100;
                con.interes = parseFloat(this.ufv);
                con.subtotal =  (con.fr + con.cm + con.interes).toFixed(2);
                this.show_spinner=false;

                this.SumTotal();
            }
            else
            {                
            axios.post('/get-interest',{con, dateEnd:this.dateEnd})
            .then(response => {                
                this.ufv = response.data.replace(',','.');                
                con.interes = parseFloat(this.ufv);
                this.ufvs[con.sueldo] = this.ufv;
                var newfr,newcm;
                newfr = (con.sueldo * this.rate.retirement_fund/100);
                con.fr = newfr.toFixed(2);
                newcm = (con.sueldo * this.rate.mortuary_quota/100);
                con.cm = newcm.toFixed(2);
                con.subtotal =  (newfr + newcm + con.interes).toFixed(2);                
                this.show_spinner=false;                                          
                this.SumTotal();
                this.count = 3;
                if(index +1 < this.contributions.length)
                this.$refs.s1[index +1].focus();    
            })
            .catch(e => {                
                console.log(--this.count);
                console.log("40004");                
                this.show_spinner=false;
                this.CalcularAporte(con, index);
            })}
        }
        else
        {
            this.show_spinner=false;
            this.count = 4;
            return;
        }
        }
          
      },
        createReimbursement:function(month){             
            this.reimbursement_amount = 0;
            this.reimbursement_month = '01';
            this.reimbursement_quotable = 0;
            this.reimbursements = [];
            this.reimbursement_pays = [];
            this.info_amount = 0;
            this.info_retirement_fund = 0;
            this.info_quota = 0;
            this.info_total = 0;  
            this.reimbursement_month = month;
            $('#reimbursement_modal').modal('show');
        },
        addReimbursement:function(){
            let quotable = this.reimbursement_quotable;                           
            let update_contributions = [];
            console.log('inicio');
            var i;
            var newcontribution;
            var index=0;
            for(i=0;i<this.contributions.length;i++){                    
                update_contributions.push(this.contributions[i]);
                if(parseInt(this.reimbursement_month) == this.contributions[i].month && this.reimbursement_quotable > 0 ){
                    index = i;
                    let fr = parseFloat(quotable*this.rate.retirement_fund/100).toFixed(2);
                    let cm = parseFloat(quotable*this.rate.mortuary_quota/100).toFixed(2);
                    newcontribution = 
                    {
                        id : 0,
                        monthyear : this.reimbursement_month+"-2018",
                        sueldo : parseFloat(quotable).toFixed(2),
                        fr : fr,
                        cm : cm,
                        interes : 0,
                        subtotal : parseFloat(fr+cm).toFixed(2),
                        month : this.reimbursement_month,
                        year : '2018',
                        affiliate_id : 1,
                        type: 'R',
                    };                                        
                    update_contributions.push(newcontribution);                                
                }
            }
            this.contributions = update_contributions;
            this.CalcularAporte(newcontribution,index);          
             $('#reimbursement_modal').modal('toggle');            
            //  moneyInputMaskAll();
        },
      changeType:function(e){
          var i;
          if(e.target.value == 9){              
              for(i=0;i<this.contributions.length && this.last_quotable!=0;i++){
                this.contributions[i].sueldo = this.last_quotable;                
                this.CalcularAporte(this.contributions[i],i);
              }
          }    
          else {
              for(i=0;i<this.contributions.length;i++){
                this.contributions[i].sueldo = 0;
                this.contributions[i].fr = 0;
                this.contributions[i].cm = 0;
                this.contributions[i].interes = 0;
                this.contributions[i].subtotal = 0;
              }
          }
      },

      SumTotal(){
         
            let total1 = 0;
            this.contributions.forEach(con => {                            
                total1 += parseFloat(con.subtotal) ;                
           });
        this.paid = this.total = total1.toFixed(2);
        
        // moneyInputMaskAll();

      },
      PrintQuote(){                              
          this.contributions =  this.contributions.filter((item)=> {
            return (item.sueldo != 0 && item.fr != 0 && item.cm !=0 && item.subtotal != 0);
        });
        var contributions = this.contributions;
        var con = JSON.stringify(contributions);
        var affiliate_id = this.afid;
        var total = this.total;        
        printJS({printable:'/print_contributions_quote?contributions='+con+'&affiliate_id='+affiliate_id+'&total='+total, type:'pdf', showModal:true});
      },
      setDataToTable(period,amount){                    
        $('#main'+period).html(amount);
      },
      enableDC(){
          $(".directContribution").removeClass('disableddiv');
      },
      Guardar(){                       
        this.contributions =  this.contributions.filter((item)=> {                
            return (item.sueldo != 0 && item.fr != 0 && item.cm !=0 && item.subtotal != 0);
        });       
    
        if(this.contributions.length > 0)
        {   
            this.$swal({
            title: 'Esta usted seguro de guardar?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar'
            }).then((result) => {    
                if (result.value) {                    
                var aportes = this.contributions;                    
                axios.post('/contribution_save',{aportes,total:this.total,tipo:this.tipo,afid:this.afid,paid:parseMoney(this.paid),bank:this.bank,bank_pay_number:this.bank_pay_number})
                .then(response => {                  
                this.enableDC();
                var i;
                for(i=0;i<response.data.contribution.length;i++){                        
                    this.setDataToTable(response.data.contribution[i].month_year,response.data.contribution[i].total);
                }
                this.$swal({
                title: 'Pago realizado',
                showConfirmButton: false,
                timer: 6000,
                type: 'success'
                })
                var json_contribution= JSON.stringify(response.data.contributions);
                this.reprint = response.data;                
                var total = this.total;        
                printJS({printable:
                            '/print_contributions_quote?contributions='+json_contribution+
                            '&affiliate_id='+response.data.affiliate_id+
                            '&total='+total, 
                            type:'pdf', 
                        showModal:true});

                // printJS({printable:
                //         '/ret_fun/'+
                //         response.data.affiliate_id+
                //         '/print/voucher/'+
                //         response.data.voucher_id + "?contributions="+json_contribution, 
                //         type:'pdf', showModal:true});



                this.contributions = [];
                })                    
                .catch(error => {
                this.show_spinner = false;                                    
                    console.log(error.response.data);
//                        console.log(xhr.responseText);
//                        var resp = jQuery.parseJSON(xhr.responseText);
                    var resp = error.response.data;
                    $.each(resp, function(index, value)
                    {
                        flash(value,'error',6000);
                    });                        
                })
            }
            })
        } 
    },
    reprintButton(){
        var json_contribution= JSON.stringify(this.reprint.contributions);
        printJS({
            printable: '/ret_fun/'+ this.reprint.affiliate_id+ '/print/voucher/'+ this.reprint.voucher_id + "?contributions="+json_contribution,
            type:'pdf',
            showModal:true
        });
    }
  },
  computed: {
      disabledSaved(){
       return this.contributions.some((c)=> c.subtotal > 0 );
      }
  }
}
</script>

