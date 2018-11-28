<template>
<div class="row">
    <div class="col-md-12">
        <div class="col-lg-12">
            <div class="panel panel-primary"  :class="show_spinner ? 'sk-loading' : ''" >
                <div class="panel-heading">
                    <h3 class="pull-left">Pago de Aportes de Auxilio Mortuorio</h3>
                    <div class="text-right">
                        <button data-animation="flip" class="btn btn-primary" @click="PrintQuote()"><i class="fa fa-print" ></i> </button>
                    </div>
                </div>

                <div class="panel-body" id ="print">
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
                    <div v-if="showContributions" class="row col-lg-12">
                        <div v-if="contributions.length > 0">
                            <div class="row" >                                            
                                <div class="col-md-3" >                            
                                    <input type="text" class="form-control"  v-money v-model="general_rent">
                                </div>
                                <div class="col-md-3" >                            
                                    <input type="text" class="form-control"  v-money @keyup.enter="repeatSalary" v-model="general_dignity_rent">
                                </div>               
                                <div class="col-md-3" >
                                    <button class="btn btn-primary " type="button" @click="repeatSalary()"><i class="fa fa-money"></i>&nbsp;Repetir Renta</button>
                                </div>        
                            </div>
                            <hr>
                            <table class="table table-striped" data-page-size="15">
                                <thead>
                                <tr>
                                    <th class="footable-visible footable-first-column footable-sortable">Mes/Año</th>
                                    <th class="footable-visible footable-sortable">Renta Bs.</th>
                                    <th class="footable-visible footable-sortable">Renta Dignidad Bs.</th>
                                    <th class="footable-visible footable-sortable">Auxilio Mortuorio (2.03 %)</th>
                                    <th class="footable-visible footable-sortable">Ajuste UFV Bs.</th>
                                    <th data-hide="phone,tablet" class="footable-visible footable-sortable">Subtotal Aporte</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(con, index) in contributions" :key="index" id="form" v-bind:style="getStyleColor(index)" :class="{'danger': error(con.subtotal)}">
                                        <td>
                                            <input type="text"  v-model="con.monthyear" disabled class="form-control">
                                        </td>
                                        <td>
                                            <input type="text" v-model = "con.sueldo" v-money ref="s1" autofocus class="form-control" >
                                        </td>
                                        <td>
                                            <input type="text"  v-model = "con.dignity_rent" v-money @keyup.enter="CalcularAporte(con, index)"  ref="s1" autofocus class="form-control" >
                                        </td>
                                        <td>
                                            <input type="text" v-model = "con.auxilio_mortuorio" disabled v-money class="form-control">
                                        </td>
                                        <td>
                                            <input type="text" v-model = "con.interes" disabled v-money class="form-control">
                                        </td>
                                        <td>
                                            <input type="text"  v-model = "con.subtotal" disabled v-money class="form-control">
                                        </td>
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
                                        <td colspan="2"><label for="total">Total a Pagar por Concepto de Aportes de Auxilio Mortuorio:</label></td>
                                        <td colspan="3"><input type="text" v-money v-model ="total" disabled class="form-control"></td>
                                        <td> <button class="btn btn-success btn-circle" onClick="window.location.reload()" type="button"><i class="fa fa-link"></i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="text-center">
                                <button class="btn btn-primary " type="button" :disabled="!disabledSaved" @click="Guardar()"><i class="fa fa-save"></i>&nbsp;Guardar</button>                    
                            </div>
                            <div>
                                <input type="checkbox" id="switch" v-model="toggle" >
                                <label for="switch" class="label-control">Opciones avanzadas</label>
                            </div>
                        </div>
                        <div v-else class="row">
                            <div class="text-center">
                                <h2>No tiene Pagos Pendientes</h2>
                                <table class="table table-striped" data-page-size="15">
                                    <thead>
                                    <tr>
                                        <th class="footable-visible footable-first-column footable-sortable">Mes/Año</th>
                                        <th class="footable-visible footable-sortable">Renta Bs.</th>
                                        <th class="footable-visible footable-sortable">Renta Dignidad Bs.</th>
                                        <th class="footable-visible footable-sortable">Auxilio Mortuorio (2.03 %)</th>
                                        <th class="footable-visible footable-sortable">Ajuste UFV Bs.</th>
                                        <th class="footable-visible footable-sortable">Subtotal Aporte</th>
                                        <th>Opciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(con, index) in contributions" :key="index" id="form" v-bind:style="getStyleColor(index)" :class="{'danger': error(con.subtotal)}">
                                            <td>
                                                <input type="text"  v-model="con.monthyear" disabled class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" v-model = "con.sueldo" v-money ref="s1" autofocus class="form-control" >
                                            </td>
                                            <td>
                                                <input type="text"  v-model = "con.dignity_rent" v-money @keyup.enter="CalcularAporte(con, index)"  ref="s1" autofocus class="form-control" >
                                            </td>
                                            <td>
                                                <input type="text" v-model = "con.auxilio_mortuorio" disabled v-money class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" v-model = "con.interes" disabled v-money class="form-control">
                                            </td>
                                            <td>
                                                <input type="text"  v-model = "con.subtotal" disabled v-money class="form-control">
                                            </td>
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
                                            <td colspan="2"><label for="total">Total a Pagar por Concepto de Aportes de Auxilio Mortuorio:</label></td>
                                            <td colspan="3"><input type="text" v-money v-model ="total" disabled class="form-control"></td>
                                            <td> <button class="btn btn-success btn-circle" onClick="window.location.reload()" type="button"><i class="fa fa-link"></i></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <!-- <button v-if="reprint" @click="reprintButton()" class="btn btn-primary"> <i class="fa fa-print"></i> Reimprimir </button> -->
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
                            <th class="footable-visible footable-sortable">Auxilio Mortuorio (1.5 %)</th>
                            <th data-hide="phone,tablet" class="footable-visible footable-sortable">Subtotal Aporte</th>
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
                                <input type="text"  v-model = "reim_pay.auxilio_mortuorio" v-money disabled class="form-control">
                            </td>                            
                            <td>
                                <input type="text"  v-model = "reim_pay.subtotal" v-money disabled class="form-control">
                            </td>                            
                        </tr>
                        <tr>
                            <td><label for="total">Total:</label></td>
                            <td><input type="text" v-model="info_amount" v-money disabled class="form-control"></td>
                            <td><input type="text" v-model="info_aid" v-money disabled class="form-control"></td>                            
                            <td><input type="text" v-model="info_total" v-money disabled class="form-control"></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>                
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
}
from "../../helper.js";
export default {
  props: [
    // "aidContributions", 
    "afid",
    "directContributionId",
    // "rate"
      ],
  mounted() {
    this.refresh();
  },
  data() {
    return {
      contributions: [],
      total: 0,
      tipo: null,
      ufv: 0,
      estado: true,
      afi_id: null,
      show_spinner: false,
      count: 3,
      general_rent: 0,
      general_dignity_rent: 0,
      reimbursement_amount: 0,
      reimbursement_month: '01',
      reimbursement_quotable: 0,
      reimbursements: [],
      reimbursement_pays: [],
      info_amount: 0,
      info_aid : 0,
      info_quota : 0,
      info_total : 0,
      reprint: null,
      dateEnd: moment().format('DD/MM/YYYY'),
      showContributions:true,
      toggle:false,
      rate:[],
      number: 4,
    }
  },
  methods: {
    error(value){
        return ! value >  0;
    },
    RemoveRow(index) {
      this.contributions.splice(index, 1);
      this.SumTotal();
    },
    getStyleColor(index){          
        if(this.contributions[index].type == 'R') {_
            return "background-color:#ffe6b3";
        }
    },
    refresh() {
      axios.get(`/affiliate/${this.afid}/get_contribution_debt/${this.number}/${moment(this.dateEnd, 'DD/MM/YYYY').format('YYYY-MM-DD')}`)
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
        this.showContributions =  true;
    },
    repeatSalary(){
      var i;           
      for(i=0;i<this.contributions.length;i++){
          this.contributions[i].sueldo = this.general_rent;
          this.contributions[i].dignity_rent = this.general_dignity_rent;
          this.CalcularAporte(this.contributions[i],i);
      }              
    },
      calculateReimbursement(){                       
        axios.get('/calculate_aid_reimbursement/'+this.afi_id+'/'+this.reimbursement_amount+'/'+this.reimbursement_month)
        .then(response => {            
            this.reimbursement_quotable = this.reimbursement_amount;// response.data.quotable;   
            var i;
            let contributions_number = parseInt(this.reimbursement_month)-1;            
            this.reimbursement_quotable = this.reimbursement_amount/contributions_number;
            let subtotal = this.reimbursement_amount/contributions_number;     
                        
            for(i=0;i<response.data.contributions.length;i++)
            {
                let date =moment(response.data.contributions[i],"YYYY-MM-DD");                
                let aid_amount =  parseFloat(subtotal*this.rate.mortuary_aid/100).toFixed(2);                                
                console.log(response.data.contributions[i]);
                var new_info = {
                    'month_year' : date.format('MM-YYYY'),
                    'amount'    :   parseFloat(subtotal).toFixed(2),
                    'auxilio_mortuorio'   :   aid_amount,                    
                    'subtotal'  :   parseFloat(aid_amount).toFixed(2),
                };
                this.reimbursement_pays.push(new_info);                                 
            }
            i=0;            
            for(i=0;i<this.contributions.length;i++)
            {                                                                
                let aid_amount =  parseFloat(subtotal*this.rate.mortuary_aid/100).toFixed(2);
                if(parseInt(this.reimbursement_month)>this.contributions[i].month && this.contributions[i].type != 'R' ){                    
                    var new_info = {
                        'month_year' : this.contributions[i].monthyear,
                        'amount'    :   parseFloat(subtotal).toFixed(2),
                        'auxilio_mortuorio'   :   aid_amount,
                        'subtotal'  :   parseFloat(aid_amount).toFixed(2),
                    };                
                    this.reimbursement_pays.push(new_info);                            
                }     
            }
            
            let quotable = subtotal*this.reimbursement_pays.length;
            this.reimbursement_quotable = quotable;
            this.info_amount = parseFloat(quotable).toFixed(2);
            this.info_aid = parseFloat(quotable*this.rate.mortuary_aid/100).toFixed(2);
            this.info_total = parseFloat(this.info_aid).toFixed(2);            
        })
        .catch(e => {            
             console.log("error "+this.count);
        });
      },
    CalcularAporte(con, index) {
     con.sueldo = parseMoney(con.sueldo);      
     con.dignity_rent = parseMoney(con.dignity_rent);
     if (parseFloat(con.sueldo) > 0) {
        if (this.count > 0) {
         this.show_spinner = true;
          axios
            .post("/get-interest-aid", { con })
            .then(response => {
             this.ufv = parseFloat(response.data[0].replace(",", "."));
                if(this.ufv < 0)
                    this.ufv = 0;
              con.auxilio_mortuorio = ((con.sueldo - con.dignity_rent) * response.data[1].mortuary_aid/100).toFixed(2);
              con.interes = parseFloat(this.ufv).toFixed(2);
              con.subtotal = (parseFloat(con.auxilio_mortuorio) + parseFloat(con.interes)).toFixed(2);
              console.log(con.subtotal);
              this.show_spinner = false;              
              this.SumTotal();                                            
              this.count = 3;
              if (index + 1 < this.contributions.length)
                this.$refs.s1[index + 1].focus();
            })
            .catch(e => {
              console.log(--this.count);
              console.log("40004");
              this.show_spinner = false;
              this.CalcularAporte(con, index); 
            });
        } else {
          this.show_spinner = false;
          this.count = 3;
          return;
        }
      }
    },
    SumTotal() {
      let total1 = 0;      
      this.contributions.forEach(con => {          
        total1 += parseFloat(con.subtotal);
      });      
      this.total = parseFloat(total1).toFixed(2);       
    },
    PrintQuote() {
      this.contributions = this.contributions.filter(item => {
        return (
          item.sueldo != 0 &&
          item.auxilio_mortuorio != 0 &&
          item.subtotal != 0
        );
      });
      var contributions = this.contributions;
      var con = JSON.stringify(contributions);
      var affiliate_id = this.afid;
      var total = this.total;
      printJS({
        printable:
          "/print_contributions_quote_aid?contributions=" +
          con +
          "&affiliate_id=" +
          affiliate_id +
          "&total=" +
          total,
        type: "pdf",
        showModal: true
      });
    },
    setDataToTable(period, amount) {
      $("#aid_main" + period).html(amount);
    },
    enableDC() {
      $(".directContribution").removeClass("disableddiv");
    },
      createReimbursement:function(month){             
      this.reimbursement_amount = 0;
      this.reimbursement_month = '01';
      this.reimbursement_quotable = 0;
      this.reimbursements = [];
      this.reimbursement_pays = [];
      this.info_amount = 0;
      this.info_aid = 0;    
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
            let aid = parseFloat(quotable*this.rate.mortuary_aid/100).toFixed(2);
            newcontribution = 
            {
                id : 0,
                monthyear : this.reimbursement_month+"-2018",
                sueldo : parseFloat(quotable).toFixed(2),
                aid: aid,
                interes : 0,
                subtotal : parseFloat(aid).toFixed(2),
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
    },
    Guardar() {        
      this.contributions = this.contributions.filter(item => {
        item.sueldo = parseMoney(item.sueldo);
        item.dignity_rent = parseMoney(item.dignity_rent);
        return (          
          item.sueldo != 0 && item.auxilio_mortuorio != 0 && item.subtotal != 0
        );
      });      
       if (this.contributions.length > 0) {
        this.$swal({
          title: "Esta usted seguro de guardar?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Confirmar",
          cancelButtonText: "Cancelar"
        }).then(result => {
          if (result.value) {
            var aportes = this.contributions;            
            axios
              .post("/contribution_process/aid_contribution_save", {
                aportes,
                total: this.total,
                direct_contribution_id: this.directContributionId
              })
              .then(response => {                
              //this.enableDC();
            //   var i;
            //     for(i=0;i<response.data.aid_contribution.length;i++){                        
            //         this.setDataToTable(response.data.aid_contribution[i].month_year,response.data.aid_contribution[i].total);
            //     }
            //   this.$swal({
            //   title: "Pago realizado",
            //   showConfirmButton: false,
            //   timer: 6000,
            //   type: "success"
            //   });
            //   var json_aid_contribution = JSON.stringify(response.data.aid_contributions);              
            //   this.reprint = response.data;
            //   console.log(json_aid_contribution);
            //   printJS({
            //       printable:
            //         "/quota_aid/" +
            //         response.data.affiliate_id +
            //         "/print/quota_aid_voucher/" +
            //         response.data.voucher_id + "?aid_contributions="+json_aid_contribution,
            //       type: "pdf",
            //       showModal: true
            //     });
            //     this.contributions = [];
            }).catch(error => {              
              console.log('with error message');
              
              this.show_spinner = false;
              console.log(error);
              console.log(error.response.data);
              var resp = error.response.data;
               $.each(resp, function(index, value) {
                 flash(value, "error", 6000);
              });
            });
          }
        });
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
    disabledSaved() {
      return this.contributions.some(c => c.subtotal > 0);
    }
  }
};
</script>

