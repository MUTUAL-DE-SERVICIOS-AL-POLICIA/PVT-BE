<template>
<div class="row">
    <div class="col-md-12">
        <div class="col-lg-12">
            <div class="panel panel-primary" >
                <div class="panel-heading">
                    <h3 class="pull-left">Pago de Aportes</h3>
                    <div class="text-right">
                        <button data-animation="flip" class="btn btn-primary" onclick="printJS('print', 'html')"><i class="fa fa-print" ></i> </button>
                      
                    </div>
                </div>

                <div class="panel-body" id ="print">  
                    <div class="row" >
                        
                        <div class="col-md-12" style="margin-bottom:20px">
                            <label>Tipo de Aporte:</label>
                            <select class="form-control">
                                <option value="1">Auxilio mortuorio</option>
                                <option value="2">Item 0</option>
                                <option value="3">Item 02</option>
                            </select>
                        </div>
                    </div>
                    <table class="table table-striped" data-page-size="15">
                        <thead>
                        <tr>
                            <th class="footable-visible footable-first-column footable-sortable">Mes/Año<span class="footable-sort-indicator"></span></th>
                            <th data-hide="phone" class="footable-visible footable-sortable">Sueldo<span class="footable-sort-indicator"></span></th>
                            <th data-hide="phone" class="footable-visible footable-sortable">Aporte<span class="footable-sort-indicator"></span></th>
                            <th data-hide="phone" class="footable-visible footable-sortable">Interes<span class="footable-sort-indicator"></span></th>
                            <th data-hide="phone,tablet" class="footable-visible footable-sortable">Subtotal<span class="footable-sort-indicator"></span></th>
                            <th>Opciones</th>                                    
                        </tr>
                        </thead>
                        <tbody>
                            <tr style="" v-for="(con, index) in contributions" :key="index">
                                <td>                                    
                                    <input type="text"  v-model="con.monthyear" disabled class="form-control">
                                </td>
                                <td>
                                    <input type="text" v-model = "con.sueldo" @keyup.enter="CalcularAporte(con, index)" ref="s1"  class="form-control"  name="aportes[]">
                                </td>
                                <td>
                                    <input type="text"  v-model = "con.aporte" class="form-control" name="aportes[]">
                                </td>
                                <td>
                                    <input type="text" v-model = "con.interes" class="form-control" name="aportes[]">
                                </td>
                                <td>
                                    <input type="text"  v-model = "con.subtotal" class="form-control" name="aportes[]">
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-circle" @click="RemoveRow(index)" type="button"><i class="fa fa-times"></i>  </button>
                                </td>
                                
                            </tr>
                            <tr>
                                <td colspan="2"><label for="total">Total a Pagar por Concepto de Aportes:</label></td>
                                <td colspan="3"><input type="text" v-model ="total" disabled class="form-control"></td>
                                <td> <button class="btn btn-success btn-circle" @click="Refresh" type="button"><i class="fa fa-link"></i></button></td>
                            </tr>                            
                        </tbody>
                    </table>
                    <button class="btn btn-primary " type="button"><i class="fa fa-save"></i>&nbsp;Guardar</button>

                </div>
               
            </div>
        </div>
    </div>
</div>

</template>
<script>
export default {
  
   props: ['contributions1','cont1'],
  data() {   
    return {
      contributions: [],
      total:0
      
    };
  },
   
  mounted() {
   this.contributions = this.contributions1;
  },
  methods: {
      RemoveRow(index) {         
        this.contributions.splice(index,1);
        this.SumTotal();
      },
      Refresh() {
        console.log(this.contributions1);
        
        this.contributions = this.cont1;       
      
      },
      CalcularAporte(con, index){
          if(con.sueldo >0)
          {
            
            con.aporte = con.sueldo * 0.5;
            con.interes = con.aporte +10;
            con.subtotal = con.aporte + con.interes;

          }
         
          this.SumTotal();
          if(index +1 < this.contributions.length)
            this.$refs.s1[index +1].focus();            
          
      },
      
      SumTotal(){
             let total1 = 0;
            this.contributions.forEach(con => {                            
                total1 = total1 + con.subtotal ;
            });              
            this.total = total1;

      }
  },
 
};
</script>

