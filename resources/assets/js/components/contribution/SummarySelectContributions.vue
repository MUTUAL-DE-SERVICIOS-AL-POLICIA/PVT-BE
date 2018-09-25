<script>
    export default {
      props: ["contributions", "types", "retfunid", "startDate", "endDate"],
      created(){
        this.row_higth = 350 / this.contributions.length;
      },
      data() {
        return {
          list_aportes: this.contributions,
          order_aportes: true,
          show_certification: false,
          modal: { first_date: null, last_date: null, contribution_type_id: null },
          row_higth: 0,
          loadingButton: false,
          showLoading: true,
          contribution_types: []
        };
      },
      methods: {
        selectRow(index) {
          $("#contenedor").scrollTop(index * 34);
        },
        getColor1(contribution_type_id) {
          let color;
          switch (contribution_type_id) {
            case 10:
              color = "#aeda8a";
              break;
            case 2:
              color = "#f7f097fd";
              break;
            case 3:
              color = "#f7a197fd";
              break;
            case 1:
              color = "#ffffff";
              break;
            case 4:
              color = "#a1a7fffd";
              break;
            case 5:
              color = "#b1e7faca";
              break;
            case 6:
              color = "#e0ad7dfd";
              break;
            case 7:
              color = "#80e9bdfd";
              break;
            case 8:
              color = "#50c1bdfa";
              break;
            case 9:
              color = "#30c1edfb";
              break;
            case 18:
              color = "#545050";
              break;
            case null:
              color = "#bbbaadfd";
              break;
            default:
              console.log("no se encontro");
              break;
          }
          return color;
        },
        count1(id) {
          return this.contributions.filter(item => item.contribution_type_id == id)
            .length;
        },
        countTotal(id) {
          return this.contributions.filter(item => item.contribution_type_id)
            .length;
        }
      }
    };
</script>

<style>
.table-fixed {
    width:100%;
    table-layout:fixed;
    margin:auto;
}
.table-fixed th, .table-fixed td {
    padding:5px 10px;

}
.table-fixed thead {
    background:#f9f9f9;
    display:table;
    width:100%;
}
.table-fixed tbody {
    height:400px;
    overflow:auto;
    overflow-x:hidden;
    display:block;
    width:100%;
}
.table-fixed tbody tr {
    display:table;
    width:100%;
    table-layout:fixed;
}
</style>|