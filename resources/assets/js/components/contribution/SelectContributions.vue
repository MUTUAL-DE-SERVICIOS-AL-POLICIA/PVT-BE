<template>
  <div class="fluid container">
    <!-- <div class="form-group form-group-lg panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Sortbale control</h3>
      </div>
      <div class="panel-body">
        <div class = "checkbox">
          <label><input type = "checkbox" v-model="editable">Enable drag and drop</label>      
        </div>
        <button type="button" class="btn btn-default" @click="orderList">Sort by original order</button>
      </div>
    </div> -->

  <div class="col-md-12">
     <div class="col-md-12"> <!--60 aportes -->
        <div class="ibox float-e-margins ibox-primary">
          <div class="ibox-title">
              <h5>Aportes <small class="m-l-sm"></small></h5> <i :class="order_aportes?'fa fa-sort-amount-desc':'fa fa-sort-amount-asc'" @click="orderList"></i>
              <div class="ibox-tools">
                  <a class="collapse-link">
                      <i class="fa fa-chevron-up"></i>
                  </a>
              </div>
          </div>
          <div class="ibox-content">
            
              <table class="table ">
                <thead>
                <tr> 
                    <th class="col-xs-1">Fecha </th>
                    <th class="col-xs-3">Sueldo</th>
                    <th class="col-xs-3">Categoria</th>
                    <th class="col-xs-2">Total</th>
                    <th class="col-xs-3">Tipo</th>
                </tr>
                </thead>
              <tbody>
                <tr v-for="contribution in list_aportes" :key="contribution.id" :class="getColor(contribution.breakdown_id)" >
                    <td class="col-xs-1">{{getFormatDate(contribution.month_year)}}</td>
                    <td class="col-xs-3">{{contribution.base_wage}}</td>
                    <td class="col-xs-3">{{contribution.category_name}}</td>
                    <td class="col-xs-2">{{contribution.total}}</td>
                    <td class="col-xs-3">
                        <select class="form-control" v-model="contribution.breakdown_id" >
                        <option v-for="item in list_types" :value="item.id" :key="item.id"> {{item.name}}</option>
                        </select>
                    </td>
                </tr>
              </tbody>
              </table>

          </div>
          <div class="ibox-footer">
              <span class="pull-right">
                  Cantidad: {{list_aportes.length-1}}
          </span>
              <br>
          </div>
      </div>
      <button class="btn btn-primary" @click="classify" ><i class="fa fa-arrow-right"></i> Clasificar</button>
   
    </div>
   
  </div>

  </div>
</template>

<script>
import draggable from 'vuedraggable'

export default {
  name: 'CechuzyKaren',
  components: {
    draggable,
  },
   props: [
        'contributions',
        'contype',
        'types'
    ],
  data () {
    return {
      
  
      list_aportes:this.contributions,
      list_types: this.types,
      con_type: this.contype,
      last_date: this.contributions[0],
      first_date: this.contributions[this.contributions.length-1],
        item0 : null,
        disponibilidad: null,
        servicio: null,
        bfs:null,
        nh: null,
        cas: null,
      order_aportes: true,

    }
  },
  created: function () {
    
    console.log('Revisando lista_aportes: ' + this.list_aportes.length)
    
    this.disponibilidad = this.types.filter(function (type) {
        return type.name  == 'Disponibilidad';   
    })[0];
    this.item0 =  this.types.filter(function (type) {
        return type.name  == 'Item 0';   
    })[0];
    this.servicio = this.types.filter(function (type) {
        return type.name  == 'Servicio';   
    })[0];

    this.nh = this.types.filter(function (type) {
        return type.name  == 'No Hay Regristro';   
    })[0];    

    this.cas = this.types.filter(function (type) {
        return type.name  == 'Registro Segun CAS';   
    })[0];

    this.bfs = this.types.filter(function (type) {
        return type.name  == 'Batallon de Seguridad Fisica';   
    })[0];

    console.log(this.servicio);
 
   if(this.con_type){
        for (let i = 0; i < this.list_aportes.length; i++) {
       
            switch (this.list_aportes[i].breakdown_id) {

                    case 1:
                        this.list_aportes[i].breakdown_id = this.disponibilidad.id;
                        this.list_aportes[i].breakdown_name = this.disponibilidad.name;
                        break;

                    case 3:
                        this.list_aportes[i].breakdown_id = this.item0.id;
                        this.list_aportes[i].breakdown_name = this.item0.name;              
                    break;
            
                default:
                        this.list_aportes[i].breakdown_id = this.servicio.id;
                        this.list_aportes[i].breakdown_name = this.servicio.name;
                    break;
            }       
        } 
        console.log('con_type cechuz y anita')
   }
    console.log(this.con_type);
    console.log(this.first_date);
    console.log(this.last_date);
    let fi = this.first_date.month_year;
    let ff = this.last_date.month_year;
    let year = null;
    let month = null;
    var list = [];
    let contribution = null;

    while (new Date(ff).getTime() > new Date(fi).getTime() ) {
        let ad = ff.split('-');
        year = ad[0];
        month = ad[1];
        // console.log(ad);
        contribution = this.searchContribution(ff);
        if(!contribution)
        {
            contribution = {"base_wage":"","breakdown_id":0,"breakdown_name":"","category_id":0,"category_name":"","gain":"","id":0,"month_year":ff,"retirement_fund":"","total":""};
        }     
        list.push(contribution);
        month--;
        if(month==0)
        {
            year--;
            month = 12;
        }
        ff= year+'-'+month+'-01';
    }
    console.log('lechuz se comio a la Karen XD ');
    console.log(list);
    this.list_aportes = list;
  },
  methods:{
    orderList () {
      console.log('ordenando aportes cyk');
      if(this.order_aportes){
          this.list_aportes = this.list_aportes.sort((one,two) =>{return new Date(one.month_year)- new Date(two.month_year); });
      }else{
          this.list_aportes = this.list_aportes.sort((one,two) =>{return new Date(two.month_year)- new Date(one.month_year); })
      }
      this.order_aportes = !this.order_aportes;
    },
    save(){
      console.log('guardando lista '+ this.affiliateid);
      var data = {'ret_fun_id':this.retfunid,'list_disponibilidad':this.list_disponibilidad,'list_aportes':this.list_aportes,'list_sixty':this.list_sixty};
      axios.post('/ret_fun/savecontributions', data )
                  .then(function (resp) {
                      // this.$router.push({path: '/'});
                      console.log(resp);
                         
                      flash('Informacion Clasificada');
                  })
                  .catch(function (resp) {
                      console.log(resp);
                      flash('Error lechuza: '+resp.message,'error');
                  });
    },
    
    classify(){
        console.log('cechuz y karem');

        this.list_aportes.forEach(aporte => {
            // console.log(aporte);
            if(aporte.breakdown_id==3)
                this.list_item0.push(aporte);
        });
    },
    getColor(breakdown_id)
    {
        var color="cya";
        switch (breakdown_id) {
            case this.disponibilidad.id:
                color="cyk";
                break;
            case this.item0.id:
                color="cyv";
                break;
            case this.servicio.id:
                color="cya";
                break;
            case this.bfs.id:
                color="bsf";
                break;
            case this.nh.id:
                color="nh";
                break;
            case this.cas.id:
                color="cas";
                break;
            case 0:
                color="cym";
                break;
            
        }
        return color;
    },
    getFormatDate(fecha)
    {
        let str = fecha.split('-');
        return str[0]+' - '+parseInt(str[1]);
    },
    searchContribution(date)
    {   
        // console.log(date);
        let newDate = date.split('-');
        if(newDate[1].length==1)
        {
            newDate[1] = '0'+newDate[1];
        }
        let strDate = newDate[0]+'-'+newDate[1]+'-'+newDate[2];
        let contribution = null;

        for (let i = 0; i < this.list_aportes.length; i++) {
            if(new Date(this.list_aportes[i].month_year).getTime() == new Date(strDate).getTime() )
            {
                contribution = this.list_aportes[i];
                i = this.list_aportes.length;
                // console.log('Encotrado el hdp');
            } 
        }
        return contribution;
    }
  },
  computed: {
    list2String(cNormal){
      return JSON.stringify(this.list_aportes, null, 2);  
    }
   
  },
  watch: {
 
  }
}
</script>

<style>
.flip-list-move {
  transition: transform 0.5s;
}

.no-move {
  transition: transform 0s;
}

.ghost {
  opacity: .5;
  background: #C8EBFB;
}
.cyk {
 background: #aeda8a;   
}
.cya {
    background: #ffffff;
}
.cyv {
    background: #f7f097fd;
}
.cym {
    background: #bbbaadfd;
}
.nh {
    background: #80e9bdfd;
}
.cas{
    background: #e0ad7dfd;
}
.bsf{
    background: #a1a7fffd;
}
tr {
width: 100%;
display: inline-table;
}

table{
 height:300px; 
}
thead{
  width: 100%;
}
tbody{
  overflow-y: scroll;
  height: 300px;
  width: 95%;
  position: absolute;
}
</style>