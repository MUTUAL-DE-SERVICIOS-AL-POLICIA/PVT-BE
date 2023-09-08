<template>
  <div>
    <br />
    <div
      class="alert alert-orange block"
      style="margin-bottom: 0px"
      v-if="paid_by_guarantor"
    >
      <strong>El afiliado tiene préstamo(s) que fueron pagados por garante(s)</strong>. Tomar en cuenta que esta información fue consultada solamente dentro la Plataforma Virtual de Trámites PVT - Préstamos.
    </div>
  </div>
</template>
<script>
export default {
  props: ["affiliateId"],
  data() {
    return {
      paid_by_guarantor: false,
    };
  },
  mounted() {
    console.log(this.affiliateId);
    this.loansPaidByGuarantors(this.affiliateId);
  },
  methods: {
    async loansPaidByGuarantors(affiliate_id) {
      try {
        let res = await axios.get(
          `${process.env.MIX_PVT_URL}/loans_paid_by_guarantors/${affiliate_id}`
        );
        this.paid_by_guarantor = res.data == "1" ? true : false;
      } catch (e) {
        console.log(e);
      }
    },
  },
};
</script>

