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
                    <div class="row" >
                        
                        <div class="col-md-6" style="margin-bottom:20px">
                            <label>Aportante:</label>
                        </div>
                        
                    </div>
                    <table class="table table-striped" data-page-size="15">
                        <thead>
                        <tr>
                            <th class="footable-visible footable-first-column footable-sortable">Mes/Año<span class="footable-sort-indicator"></span></th>
                            <th data-hide="phone" class="footable-visible footable-sortable">Renta Bs.<span class="footable-sort-indicator"></span></th>
                            <th data-hide="phone" class="footable-visible footable-sortable">Renta Dignidad Bs.<span class="footable-sort-indicator"></span></th>
                            <th data-hide="phone" class="footable-visible footable-sortable">Auxilio Mortuorio (2.03 %)<span class="footable-sort-indicator"></span></th>
                            <th data-hide="phone" class="footable-visible footable-sortable">Ajuste UFV Bs.<span class="footable-sort-indicator"></span></th>
                            <th data-hide="phone,tablet" class="footable-visible footable-sortable">Subtotal Aporte<span class="footable-sort-indicator"></span></th>
                            <th>Opciones</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr style="" v-for="(con, index) in aidContributions" :key="index" id="form">
                                <td>
                                    <input type="text"  v-model="con.monthyear" disabled class="form-control">
                                </td>
                                <td>
                                    <input type="text" v-model = "con.sueldo" @keyup.enter="CalcularAporte(con, index)"  ref="s1" autofocus class="form-control" >
                                </td>
                                <td>
                                    <input type="text"  v-model = "con.dignity_rent" @keyup.enter="CalcularAporte(con, index)"  ref="s1" autofocus class="form-control" >
                                </td>
                                <td>
                                    <input type="text" v-model = "con.auxilio_mortuorio" disabled class="form-control">
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
                                <td colspan="2"><label for="total">Total a Pagar por Concepto de Aportes de Auxilio Mortuorio:</label></td>
                                <td colspan="3"><input type="text" v-model ="total" disabled class="form-control"></td>
                                <td> <button class="btn btn-success btn-circle" onClick="window.location.reload()" type="button"><i class="fa fa-link"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-primary " type="button" @click="Guardar()"><i class="fa fa-save"></i> Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>
</template>
<script>
export default {
  props: ["aidContributions", "afid"],
  mounted() {
    this.contributions = this.aidContributions;    
    this.afi_id = this.afid;
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
      count: 3
    }
  },
  methods: {
    RemoveRow(index) {
      this.contributions.splice(index, 1);
      this.SumTotal();
    },
    Refresh() {
      this.contributions = this.aidContributions;
    },
    CalcularAporte(con, index) {
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
      this.total = total1;
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
    Guardar() {
      this.contributions = this.aidContributions.filter(item => {
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
              .post("/aid_contribution_save", {
                aportes,
                total: this.total,
                afid: this.afid
              })
              .then(response => {
              this.enableDC();
              var i;
                for(i=0;i<response.data.aid_contribution.length;i++){                        
                    this.setDataToTable(response.data.aid_contribution[i].month_year,response.data.aid_contribution[i].total);
                }
              /* for (var i = 0; i < response.data.aid_contribution.length; i++) {
                  this.setDataToTable(
                    response.data.aidcontribution[i].month_year,
                    response.data.aidcontribution[i].total
                  );
              } */
              this.$swal({
              title: "Pago realizado",
              showConfirmButton: false,
              timer: 6000,
              type: "success"
              })
                printJS({
                  printable:
                    "/quota_aid/" +
                    response.data.affiliate_id +
                    "/print/quota_aid_voucher/" +
                    response.data.voucher_id,
                  type: "pdf",
                  showModal: true
                });
            }).catch(error => {              
              this.show_spinner = false;
              console.log(error.response.data);
              var resp = error.response.data;
              $.each(resp, function(index, value) {
                flash(value, "error", 6000);
              });
            });
          }
        });
      }
    }
  },
  computed: {
    disabledSaved() {
      return this.contributions.some(c => c.subtotal > 0);
    }
  }
};
</script>

