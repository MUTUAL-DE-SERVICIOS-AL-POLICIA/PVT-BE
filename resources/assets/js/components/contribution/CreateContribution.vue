<template>
<div class="row">
    <div class="col-md-12">
        <div class="col-lg-12">
            <div class="panel panel-primary"  :class="show_spinner ? 'sk-loading' : ''" >
                <div class="panel-heading">
                    <h3 class="pull-left">Pago de Aportes</h3>
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
                    <div class="row" >
                        
                        <div class="col-md-6" style="margin-bottom:20px">
                            <label>Tipo de Aporte:</label>
                            <select v-model="tipo" class="form-control" v-on:change="changeType">
                                <option value="2">Comisión</option>
                                <option value="10">Agregado Policial</option>
                                <option value="9">Baja Temporal</option>
                            </select>
                            <span v-show="errors.has('tipo')" class="text-danger">{{ errors.first('tipo') }}</span>
                        </div>
                        
                    </div>
                    <table class="table table-striped" data-page-size="15">
                        <thead>
                        <tr>
                            <th class="footable-visible footable-first-column footable-sortable">Mes/Año<span class="footable-sort-indicator"></span></th>
                            <th data-hide="phone" class="footable-visible footable-sortable">Total Ganado<span class="footable-sort-indicator"></span></th>
                            <th data-hide="phone" class="footable-visible footable-sortable">F.R.P. (4.77 %)<span class="footable-sort-indicator"></span></th>
                            <th data-hide="phone" class="footable-visible footable-sortable">Cuota Mortuoria (1.09 %)<span class="footable-sort-indicator"></span></th>
                            <th data-hide="phone" class="footable-visible footable-sortable">Ajuste UFV<span class="footable-sort-indicator"></span></th>
                            <th data-hide="phone,tablet" class="footable-visible footable-sortable">Subtotal Aporte<span class="footable-sort-indicator"></span></th>
                            <th>Opciones</th>                                    
                        </tr>
                        </thead>
                        <tbody>
                            <tr style="" v-for="(con, index) in contributions" :key="index" id="form">
                                <td>                                    
                                    <input type="text"  v-model="con.monthyear" disabled class="form-control">
                                </td>
                                <td>
                                    <input type="text" v-model = "con.sueldo" @keyup.enter="CalcularAporte(con, index)"  ref="s1" autofocus class="form-control" >
                                </td>
                                <td>
                                    <input type="text"  v-model = "con.fr" disabled class="form-control">
                                </td>
                                <td>
                                    <input type="text" v-model = "con.cm" disabled class="form-control">
                                </td>
                                <td>
                                    <input type="text" v-model = "con.interes" disabled class="form-control">
                                </td>
                                <td>
                                    <input type="text"  v-model = "con.subtotal" disabled class="form-control">
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-circle" @click="RemoveRow(index)" type="button"><i class="fa fa-times"></i>  </button>
                                </td>
                                
                            </tr>
                            <tr>
                                <td colspan="2"><label for="total">Total a Pagar por Concepto de Aportes:</label></td>
                                <td colspan="3"><input type="text" v-model ="total" disabled class="form-control"></td>
                                <td> <button class="btn btn-success btn-circle" onClick="window.location.reload()" type="button"><i class="fa fa-link"></i></button></td>
                            </tr>                            
                        </tbody>
                    </table>
                    <button class="btn btn-primary " type="button" :disabled="!disabledSaved" @click="Guardar()"><i class="fa fa-save"></i>&nbsp;Guardar</button>

                </div>
               
            </div>
        </div>
    </div>
</div> 
</template>
<script>

export default {
  
    props: [
        'contributions1',
        'afid',
        'last_quotable',
    ],
    data() {   

    return {
      contributions: [],
      total:0,
      tipo:null,
      ufv:0,
      estado: true,
      afi_id:null,
      show_spinner:false,
      count:3
    };
  },
   
  mounted() {
    this.contributions = this.contributions1;  
    this.afi_id = this.afid;    
  },
  created(){
      
  },
  methods: {
      RemoveRow(index) {         
        this.contributions.splice(index,1);
        this.SumTotal();
      },
      Refresh() {
        this.contributions = this.contributions1;       
      
      },
      CalcularAporte(con, index){
        if(parseFloat(con.sueldo) >0)
        {          
        if(this.count > 0)
        {
            this.show_spinner=true
            axios.post('/get-interest',{con})
            .then(response => {
                
                this.ufv = response.data
                con.fr = con.sueldo * 0.0477;
                con.cm = con.sueldo * 0.0109;
                con.interes = parseFloat(this.ufv);
                con.subtotal =  con.fr + con.cm + con.interes;
            
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
            })
        }
        else
        {
            this.show_spinner=false;
            this.count = 3;
            return;
        }                
        
                
        }           
          
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
        this.total = total1;

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
        if(this.tipo !== null) 
        {
            this.contributions =  this.contributions.filter((item)=> {
                return (item.sueldo != 0 && item.fr != 0 && item.cm !=0 && item.subtotal != 0);
            });       
      
            if(this.contributions.length > 0)
            {   
                this.$swal({
                title: 'Esta usted seguro de guardar?',
                text: "whatever",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar'
                }).then((result) => {    
                    if (result.value) {                    
                    var aportes = this.contributions;                    
                    axios.post('/contribution_save',{aportes,total:this.total,tipo:this.tipo,afid:this.afid})
                    .then(response => {
                  //      console.log('entrando a succes');
                //console.log(response.data);
                    this.enableDC();
                    var i;
                    for(i=0;i<response.data.contribution.length;i++){                        
                        this.setDataToTable(response.data.contribution[i].month_year,response.data.contribution[i].total);
                    }
                    printJS({printable:'/ret_fun/'+response.data.affiliate_id+'/print/voucher/'+response.data.voucher_id, type:'pdf', showModal:true});
                    })
                    .catch(error => {
                    this.show_spinner = false;            
                        //alert(e);
                        console.log(error.response.data);
//                        console.log(xhr.responseText);
//                        var resp = jQuery.parseJSON(xhr.responseText);
                        var resp = error.response.data;
                        $.each(resp, function(index, value)
                        {
                            flash(value,'error',5000);
                        });
                    })

                    this.$swal({
                    title: 'Pago realizado',
                    showConfirmButton: false,
                    timer: 1500,
                    type: 'success'
                    })
                }
                })            
            }
        }      
        
        
    
    },


    

  },
  computed: {
      disabledSaved(){
       return this.contributions.some((c)=> c.subtotal > 0 );
      }
  }

 
}
</script>

