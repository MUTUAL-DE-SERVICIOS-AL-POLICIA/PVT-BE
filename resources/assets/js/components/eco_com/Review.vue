<template>
  <div>
    <div class="ibox">
      <div class="ibox-content" v-if="reviewList.has_certification">
        <div class="row">
          <div class="pull-left">
            <legend>
              Procedimientos de revisión realizados
            </legend>
          </div>
          <div class="pull-right">
            <button
              v-if="rol==4"
              data-animation="flip"
              class="btn btn-primary"
      
              @click="toggle_editing"
            >
              <i class="fa" :class="editing ? 'fa-edit' : 'fa-pencil'"></i>
              Editar
            </button>
          </div>
        </div>
        <form>
          <div class="row">
            <div
              v-for="(review, index) in reviewList.review_procedures"
              :key="index"
            >
              <div
                class="vote-item"
                @click="checked(review, index)"
                :class="review.is_valid ? 'bg-success-green' : ''"
                style="cursor: pointer"
              >
                <div class="row">
                  <div :class="editing ? 'col-md-10' : 'col-md-10'">
                    <div class="vote-actions">
                      <h1>{{ review.id }}</h1>
                    </div>
                    <span class="vote-title">{{ review.name }}</span>
                  </div>
                  <div class="col-md-2">
                    <div class="vote-icon">
                      <span style="color: #3c3c3c"
                        ><i
                          class="fa"
                          :class="
                            review.is_valid
                              ? 'fa-check-square'
                              : 'fa-square-o'
                          "
                        ></i
                      ></span>
                      <div style="opacity: 0" v-if="rol != 4">
                        <input
                          type="checkbox"
                          v-model="review.is_valid"
                          value="checked"
                          :name="'document' + review.id"
                          class="largerCheckbox"
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <br />

          <br />
          <br />
          <div class="text-center" v-if="editing">
            <button
              class="btn btn-danger"
              type="button"
              @click="toggle_editing"
            >
              <i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span cla ss="bold"
                >Cancelar</span
              >
            </button>
            <button type="button" class="btn btn-primary" @click="storeReview()">
              <i class="fa fa-check-circle"></i>&nbsp;Guardar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: ["ecoCom", "user", "rol"],
  data() {
    return {
      show_spinner: false,
      procedure_type_id: 2,
      editing: false,
      reviewList: [],
      update_reviewList: {
        review_procedures: [],
      },
    };
  },
  mounted() {
    this.getReview();
  },
  methods: {
    toggle_editing: function () {
      this.editing = !this.editing;
      console.log(this.editing);
      setTimeout(() => {
        $(".chosen-select").chosen({ width: "100%" }).trigger("chosen:updated");
      }, 500);
    },

    checked(index, i) {
      if (this.editing) {
        index.is_valid ? (this.reviewList.review_procedures[i].background = ""): (this.reviewList.review_procedures[i].background ="bg-success-green");
        this.reviewList.review_procedures[i].is_valid = !this.reviewList.review_procedures[i].is_valid;

        this.reviewList.review_procedures[i].background == "bg-success-green" ? "": "bg-success-green";
        this.update_reviewList.review_procedures = JSON.parse(JSON.stringify(this.reviewList.review_procedures));

        this.update_reviewList.review_procedures.forEach((item) => {
          delete item.background;
          delete item.active;
          delete item.name;
          delete item.created_at;
          delete item.updated_at; 
          item.user_id = this.user.id; 
        });
      }
    },
    isVisible() {
      if (this.rol == 4) {
        if (this.editing) {
          return true;
        } else {
          return review.is_valid;
        }
      } else {
        return true;
      }
    },

    getReview() {
      let uri = `/review_show/${this.ecoCom}`;
      axios
        .get(uri)
        .then((response) => {
          this.reviewList = response.data.data;
          console.log(response.data.data);
          console.log(this.reviewList);
          //flash("Verificacion Correcta");
          //this.toggle_editing();
        })
        .catch((error) => {
          flash("Ocurrio un error en el cargado de revisión", "error");
        });
    },
    storeReview() {
      let uri = `/eco_com/review_edit`;
      axios
        .post(uri, {
          economic_complement_id: this.ecoCom,
          review_procedures: this.update_reviewList.review_procedures,
        })
        .then((response) => {
          flash("Se guardo de forma correcta");
          this.toggle_editing();
        })
        .catch((error) => {
          flash("Ocurrio un error", "error");
        });
    },
  },
};
</script>