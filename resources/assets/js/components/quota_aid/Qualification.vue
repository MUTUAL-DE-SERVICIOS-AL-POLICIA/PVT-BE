<script>
  import { scroller } from "vue-scrollto/src/scrollTo";
  import { parseMoney, flashErrors } from "../../helper.js";
  export default {
    props: ["contributions", "affiliate", "quotaAidId"],
    data() {
      return {
        showEconomicData: true,
        showEconomicDataTotal: true,
        showPercentagesQuotaAid: true,
        totalPercentageQuotaAid: 100,
        maxPercentage: 100.0,
        beneficiaries: [],
        subTotalQuotaAid: 0,
        totalQuotaAid: 0,
        finishQuotaAid: false,
        guarantors: [],
        advancePayment: 0,
        advancePaymentNoteCodeDate: null,
        advancePaymentNoteCode: null,
        advancePaymentCode: null,
        advancePaymentDate: null,
        retentionLoanPayment: 0,
        retentionLoanPaymentNoteCodeDate: null,
        retentionLoanPaymentNoteCode: null,
        retentionLoanPaymentCode: null,
        retentionLoanPaymentDate: null,
        retentionGuarantor: 0,
        retentionGuarantorNoteCodeDate: null,
        retentionGuarantorNoteCode: null,
        retentionGuarantorCode: null,
        retentionGuarantorDate: null
      };
    },
    methods: {
      max(a, b) {
        return true;
        return parseFloat(a.toFixed(2)) > parseFloat(b.toFixed(2)) || isNaN(a);
      },
      colorClass(a, b) {
        return "text";
        if (isNaN(a)) {
          return;
        }
        if (parseFloat(a.toFixed(2)) === parseFloat(b.toFixed(2))) {
          return {
            "text-info": true
          };
        }
        if (parseFloat(a.toFixed(2)) > parseFloat(b.toFixed(2))) {
          return {
            "text-danger": true
          };
        }
        if (parseFloat(a.toFixed(2)) < parseFloat(b.toFixed(2))) {
          return {
            "text-warning": true
          };
        }
      },
      // updateTotalGuarantor() {
      //   this.retentionGuarantor = this.guarantors.reduce((acc, g) => {
      //     return acc + parseFloat(parseMoney(g.amount));
      //   }, 0);
      //   if (this.retentionGuarantor == 0) {
      //     this.retentionGuarantorCode = null;
      //     this.retentionGuarantorNoteCode = null;
      //     this.retentionGuarantorNoteCodeDate = null;
      //     this.retentionGuarantorDate = null;
      //   }
      // },
      // addGuarantor() {
      //   let guarantor = {
      //     amount: 0,
      //     identity_card: null,
      //     full_name: null,
      //     id: null
      //   };
      //   if (this.guarantors.length > 0) {
      //     if (
      //       !this.guarantors.some(g => {
      //         return !g.full_name || !g.identity_card;
      //       })
      //     ) {
      //       this.guarantors.push(guarantor);
      //     }
      //   } else {
      //     this.guarantors.push(guarantor);
      //   }
      //   setTimeout(() => {
      //     // moneyInputMaskAll();
      //     if (this.$refs.guarantoridentitycard) {
      //       let lastIndex = this.$refs.guarantoridentitycard.length - 1;
      //       this.$refs.guarantoridentitycard[lastIndex].focus();
      //     }
      //   }, 500);
      //   this.updateTotalGuarantor();
      // },
      // deleteGuarantor(index) {
      //   this.guarantors.splice(index, 1);
      //   if (this.guarantors.length < 1)
      //     // this.addGuarantor();
      //     this.updateTotalGuarantor();
      // },
      // searchGuarantor(index) {
      //   let ci = this.guarantors[index].identity_card;
      //   axios
      //     .get("/search_ajax", {
      //       params: {
      //         ci
      //       }
      //     })
      //     .then(response => {
      //       let data = response.data;
      //       if (data.type == "afiliado") {
      //         this.guarantors[index].full_name = `${data.first_name} ${
      //           data.second_name
      //         } ${data.last_name} ${data.mothers_last_name} ${
      //           data.surname_husband
      //         }`;
      //         this.guarantors[index].id = data.id;
      //         // if(data.first_name){
      //         //   let lastIndex = this.$refs.guarantoramount.length-1;
      //         //   this.$refs.guarantoramount[lastIndex].focus();

      //         // }
      //       }
      //     })
      //     .catch(function(error) {
      //       console.log("error al buscar garante: ", error);
      //     });
      // },
      // saveAverageQuotable() {
      //   let uri = `/quota_aid/${this.quotaAidId}/save_subtotal`;
      //   axios
      //     .get(uri)
      //     .then(response => {
      //       flash("Subtotal Actualizado");
      //       this.showEconomicDataTotal = true;

      //       if (response.data.discounts.length) {
      //         let advancePaymentResponse = response.data.discounts.filter(
      //           d => d.id == 1
      //         );
      //         if (advancePaymentResponse.length) {
      //           this.advancePayment = advancePaymentResponse[0].pivot.amount;
      //           this.advancePaymentNoteCodeDate =
      //             advancePaymentResponse[0].pivot.note_code_date;
      //           this.advancePaymentNoteCode =
      //             advancePaymentResponse[0].pivot.note_code;
      //           this.advancePaymentCode = advancePaymentResponse[0].pivot.code;
      //           this.advancePaymentDate = advancePaymentResponse[0].pivot.date;
      //         }

      //         let retentionLoanPaymentResponse = response.data.discounts.filter(
      //           d => d.id == 2
      //         );
      //         if (retentionLoanPaymentResponse.length) {
      //           this.retentionLoanPayment =
      //             retentionLoanPaymentResponse[0].pivot.amount;
      //           this.retentionLoanPaymentNoteCodeDate =
      //             retentionLoanPaymentResponse[0].pivot.note_code_date;
      //           this.retentionLoanPaymentNoteCode =
      //             retentionLoanPaymentResponse[0].pivot.note_code;
      //           this.retentionLoanPaymentCode =
      //             retentionLoanPaymentResponse[0].pivot.code;
      //           this.retentionLoanPaymentDate =
      //             retentionLoanPaymentResponse[0].pivot.date;
      //         }

      //         let retentionGuarantorResponse = response.data.discounts.filter(
      //           d => d.id == 3
      //         );
      //         if (retentionGuarantorResponse.length) {
      //           this.retentionGuarantor =
      //             retentionGuarantorResponse[0].pivot.amount;
      //           this.retentionGuarantorNoteCodeDate =
      //             retentionGuarantorResponse[0].pivot.note_code_date;
      //           this.retentionGuarantorNoteCode =
      //             retentionGuarantorResponse[0].pivot.note_code;
      //           this.retentionGuarantorCode =
      //             retentionGuarantorResponse[0].pivot.code;
      //           this.retentionGuarantorDate =
      //             retentionGuarantorResponse[0].pivot.date;
      //         }
      //       }

      //       if (response.data.guarantors.length) {
      //         this.guarantors = [];
      //         response.data.guarantors.forEach(g => {
      //           let guarantor = {
      //             amount: g.amount,
      //             identity_card: g.identity_card,
      //             full_name: g.full_name,
      //             id: g.affiliate_guarantor_id
      //           };
      //           this.guarantors.push(guarantor);
      //         });
      //       }
      //       //   TweenLite.to(this.$data, 0.5, {
      //       this.subTotalQuotaAid = response.data.sub_total_quota_aid;
      //       this.totalQuotaAid = response.data.total_quota_aid;
      //       //   });
      //       //   moneyInputMaskAll();
      //       //   setTimeout(() => {
      //       //     this.$scrollTo("#showEconomicDataTotal");
      //       //   }, 800);
      //     })
      //     .catch(error => {
      //       flash("Error: " + error.response.data.message, "error");
      //       this.showEconomicDataTotal = false;
      //     });
      // }
      firstContinue() {
        let uri = `/quota_aid/${this.quotaAidId}/save_total`;
        axios
          .patch(uri)
          .then(response => {
            console.log(response);

            // if (reload) {
            //   flash("Montos recalculados.");
            // } else {
            //   flash("Calculo Total guardado correctamente.");
            // }
            this.beneficiaries = response.data.beneficiaries;
            this.totalquotaAid = response.data.total_quota_aid;
            this.showPercentagesquotaAid = true;
            // setTimeout(() => {
            //   this.$scrollTo("#showPercentagesquotaAid");
            // }, 800);
          })
          .catch(error => {
            flash(
              "Error al guardar los Datos, Verifique que los beneficiarios tengan parentesco",
              "error"
            );
            this.showPercentagesquotaAid = false;
          });
      },
      savePercentages(reload) {
        let uri = `/quota_aid/${this.quotaAidId}/save_percentages`;
        axios
          .patch(uri, {
            beneficiaries: this.beneficiaries,
            reload
          })
          .then(response => {
            // if (reload) {
            //   flash("Montos recalculados.");
            // } else {
            //   flash("Porcentages actualizados a los derechohabientes.");
            // }
            console.log("giad");

            // this.hasAvailability = response.data.has_availability;
            // this.subTotalAvailability = response.data.subtotal_availability;
            // this.totalAnnualYield = response.data.total_annual_yield;
            // this.totalAvailability = response.data.total_availability;
            // this.total = response.data.total;
            // this.beneficiariesAvailability = response.data.beneficiaries;
            // (this.finishRetFun = true),
            //   (this.arrayDiscounts = response.data.array_discounts);
            // console.log("Has Availability: " + response.data.has_availability);
            // setTimeout(() => {
            //   if (this.hasAvailability) {
            //     this.$scrollTo("#hasAvailabilityScroll");
            //   } else {
            //     this.$scrollTo("#wrapper");
            //   }
            // }, 800);
          })
          .catch(error => {
            console.log(error);
            (this.finishRetFun = false),
              flash("Error al guardar los porcentages", "error");
          });
      }
    },
    computed: {
      totalAmountQuotaAid() {
        const sum = this.beneficiaries.reduce((accumulator, current) => {
          return accumulator + parseFloat(current.temp_amount);
        }, 0.0);
        return sum;
      },
      totalAnimated() {
        return (
          this.subTotalquotaAid -
          parseMoney(this.advancePayment) -
          parseMoney(this.retentionLoanPayment) -
          parseMoney(this.retentionGuarantor)
        );
      },
      percentageAdvancePayment() {
        return this.subTotalquotaAid > 0 && this.subTotalquotaAid != ""
          ? (100 * parseMoney(this.advancePayment)) / this.subTotalquotaAid
          : 0;
      },
      percentageRetentionLoanPayment() {
        return this.subTotalquotaAid > 0 && this.subTotalquotaAid != ""
          ? (100 * parseMoney(this.retentionLoanPayment)) / this.subTotalquotaAid
          : 0;
      },
      percentageRetentionGuarantor() {
        return this.subTotalquotaAid > 0 && this.subTotalquotaAid != ""
          ? (100 * parseMoney(this.retentionGuarantor)) / this.subTotalquotaAid
          : 0;
      }
    }
  };
</script>
