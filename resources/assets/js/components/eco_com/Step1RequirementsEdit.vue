<script>
export default {
  props: [
    "ecoCom",
    "procedureModalities",
    "requirements",
    "user",
    "cities",
    "procedureTypes",
    "submitted",
    "rol"
  ],
  data() {
    return {
      requirementList: [],
      aditionalRequirements: [],
      aditionalRequirementsSelected: [],
      modality: null,
      show_spinner: false,
      city_end_id: this.user.city_id,
      procedure_type_id: 2,
      my_index: 1,
      modalitiesFilter: [],
      editing: false,
      counter_aditional_document: 1000
    };
  },
  mounted() {
    this.modality = this.ecoCom.eco_com_modality.procedure_modality_id;
    this.getRequirements();
  },
  methods: {
    toggle_editing: function() {
      this.editing = !this.editing;
      setTimeout(() => {
        $(".chosen-select")
          .chosen({ width: "100%" })
          .trigger("chosen:updated")
        // Si es un select múltiple, usa '.chosen-choices'
        $(".chosen-select")
          .next('.chosen-container')
          .find('.chosen-choices') // Para selects múltiples
          .css("border", "4px solid #ceebd6");
      }, 500);

    },
    getRequirements() {
      this.requirementList = this.requirements.filter(r => {
        if (r.number == 0 && this.rol == 11) {
          r.number = r.number + this.counter_aditional_document;
          this.counter_aditional_document++;
        }
        if (r.modality_id == this.modality && r.number != 0) {
          let submit_document = this.submitted.find(function(document) {
            return document.procedure_requirement_id === r.id;
          });
          if (this.rol != 11) {
            //revision legal
            if (submit_document) {
              r["status"] = true;
              r["background"] = "bg-success-green";
              r["comment"] = submit_document.comment;
            } else {
              r["status"] = false;
              r["background"] = "";
              r["comment"] = null;
            }
            return r;
          } else {
            if (submit_document) {
              if (submit_document.is_valid) {
                r["status"] = true;
                r["background"] = "bg-success-green";
                r["comment"] = submit_document.comment;
                r["submit_document_id"] = submit_document.id;
              } else {
                r["status"] = false;
                r["background"] = "";
                r["comment"] = submit_document.comment;
                r["submit_document_id"] = submit_document.id;
              }
              return r;
            }
          }
        }
      });

      Array.prototype.groupBy = function(prop) {
        return this.reduce(function(groups, item) {
          const val = item[prop];
          groups[val] = groups[val] || [];
          groups[val].push(item);
          return groups;
        }, {});
      };

      this.requirementList = this.requirementList.groupBy("number");
      this.getAditionalRequirements();
    },
    getAditionalRequirements() {
      if (!this.modality) {
        this.aditionalRequirements = [];
      }
      if (!this.modality) {
        this.aditionalRequirementsSelected = [];
      }
      this.aditionalRequirements = this.requirements.filter(requirement => {
        if (
          requirement.modality_id == this.modality &&
          requirement.number == 0
        ) {
          let submit_document = this.submitted.find(function(document) {
            return document.procedure_requirement_id === requirement.id;
          });
          if (!submit_document) return requirement;
        }
      });
      this.aditionalRequirementsSelected = this.requirements.filter(
        requirement => {
          if (
            requirement.modality_id == this.modality &&
            requirement.number == 0
          ) {
            let submit_document = this.submitted.find(function(document) {
              return document.procedure_requirement_id === requirement.id;
            });
            if (submit_document) return requirement;
          }
        }
      );

      setTimeout(() => {
        $(".chosen-select")
          .chosen({ width: "100%" })
          .trigger("chosen:updated");
      }, 500);
    },
    checked(index, i) {
      if (this.editing) {
        for (var k = 0; k < this.requirementList[index].length; k++) {
          if (k != i) {
            this.requirementList[index][k].status = false;
            this.requirementList[index][k].background = "bg-warning-yellow";
          }
        }
        this.requirementList[index][i].status = !this.requirementList[index][i]
          .status;
        this.requirementList[index][i].background =
          this.requirementList[index][i].background == "bg-success-green"
            ? ""
            : "bg-success-green";
        if (this.requirementList[index].every(r => !r.status)) {
          for (var k = 0; k < this.requirementList[index].length; k++) {
            if (!this.requirementList[index][k].status) {
              this.requirementList[index][k].background = "";
            }
          }
        }
      } else {
      }
    },
    isVisible(requeriment) {
      if (this.rol != 11) {
        if (this.editing) {
          return true;
        } else {
          return requeriment.status;
        }
      } else {
        return true;
      }
    },
    onChooseCity(event) {
      const options = event.target.options;
      const selectedOption = options[options.selectedIndex];
      const selectedText = selectedOption.textContent;
    },
    groupNumbers(number) {
      if (parseInt(number) == parseInt(this.my_index)) {
        this.my_index++;
        return true;
      }
      return false;
    },
    store() {
      let uri = `/eco_com/${this.ecoCom.id}/edit_requirements`;
      let req = $("#aditional_requirements").val();
      axios
        .post(uri, {
          requirements: this.requirementList,
          aditional_requirements: req
        })
        .then(response => {
          flash("Verificacion Correcta");
          this.toggle_editing();
        })
        .catch(error => {
          flash("Los Datos no Coinciden", "error");
        });
    }
  }
};
</script>