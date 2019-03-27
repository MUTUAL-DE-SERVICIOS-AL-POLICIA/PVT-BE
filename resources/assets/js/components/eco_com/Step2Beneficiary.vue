<script>
import { mapGetters } from "vuex";
export default {
  props: ["cities", "affiliate", "ecoComBeneficiary"],
  data() {
    return {
      legal_guardian_first_name: "",
      legal_guardian_second_name: "",
      legal_guardian_first_name: "",
      legal_guardian_second_name: "",
      legal_guardian_last_name: "",
      legal_guardian_mothers_last_name: "",
      legal_guardian_surname_husband: "",
      legal_guardian_identity_card: "",
      legal_guardian_city_identity_card: "",
      legal_guardian_number_authority: "",
      legal_guardian_date_authority: "",
      legal_guardian_gender: null,
      legal_guardian_notary_of_public_faith: "",
      legal_guardian_notary: "",
      beneficiary_phone_numbers: this.parsePhone(
        this.ecoComBeneficiary.phone_number
      ),
      beneficiary_cell_phone_numbers: this.parsePhone(
        this.ecoComBeneficiary.cell_phone_number
      ),
      has_legal_guardian: false,
      legal_guardian_types: [
        {
          id: 1,
          name: "Solicitante y Cobrador"
        },
        {
          id: 2,
          name: "Solicitante"
        }
      ],
      legal_guardian_type: null
    };
  },
  mounted() {
    console.log(this.beneficiary_phone_numbers);
    this.addPhoneNumber();
    this.addCellPhoneNumber();
  },
  computed: {
    getNameLegalGuardianType() {
      if (this.legal_guardian_type) {
        return this.legal_guardian_types.find(
          l => l.id == this.legal_guardian_type
        ).name;
      }
      return null;
    },
    ...mapGetters("retFunForm", {
      retFun: "getData"
    }),
    applicantIsMale() {
      return this.ecoComBeneficiary.gender == "M";
    },
    isDeathMode() {
      return this.retFun.modality_id == 4 || this.retFun.modality_id == 1;
    }
  },
  methods: {
    addPhoneNumber() {
      if (this.beneficiary_phone_numbers.length > 0) {
        let last_phone = this.beneficiary_phone_numbers[
          this.beneficiary_phone_numbers.length - 1
        ];
        if (last_phone.value && !last_phone.value.includes("_")) {
          this.beneficiary_phone_numbers.push({ value: null });
        }
      } else {
        this.beneficiary_phone_numbers.push({ value: null });
      }
    },
    deletePhoneNumber(index) {
      this.beneficiary_phone_numbers.splice(index, 1);
      if (this.beneficiary_phone_numbers.length < 1) this.addPhoneNumber();
    },
    addCellPhoneNumber() {
      if (this.beneficiary_cell_phone_numbers.length > 0) {
        let last_phone = this.beneficiary_cell_phone_numbers[
          this.beneficiary_cell_phone_numbers.length - 1
        ];
        if (last_phone.value && !last_phone.value.includes("_")) {
          this.beneficiary_cell_phone_numbers.push({ value: null });
        }
      } else {
        this.beneficiary_cell_phone_numbers.push({ value: null });
      }
    },
    deleteCellPhoneNumber(index) {
      this.beneficiary_cell_phone_numbers.splice(index, 1);
      if (this.beneficiary_cell_phone_numbers.length < 1)
        this.addCellPhoneNumber();
    },
    searchApplicant: function() {
      let ci = document.getElementsByName("applicant_identity_card")[0].value;
      axios
        .get("/search_ajax", {
          params: {
            ci
          }
        })
        .then(response => {
          let data = response.data;
          this.setDataApplicant(data);
          console.log(data);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    searchLegalGuardian: function() {
      let ci = document.getElementsByName("legal_guardian_identity_card")[0]
        .value;
      axios
        .get("/search_ajax", {
          params: {
            ci
          }
        })
        .then(response => {
          let data = response.data;
          this.setDataLegalGuardian(data);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    setDataLegalGuardian(data) {
      this.legal_guardian_first_name = data.first_name;
      this.legal_guardian_second_name = data.second_name;
      this.legal_guardian_last_name = data.last_name;
      this.legal_guardian_mothers_last_name = data.mothers_last_name;
      this.legal_guardian_surname_husband = data.surname_husband;
      this.legal_guardian_identity_card = data.identity_card;
      this.legal_guardian_city_identity_card = data.city_identity_card_id;
      this.legal_guardian_gender = data.gender;
    },
    parsePhone(phones) {
      return phones.map(phone => {
        return {
          value: phone.trim()
        };
      });
    }
  }
};
</script>