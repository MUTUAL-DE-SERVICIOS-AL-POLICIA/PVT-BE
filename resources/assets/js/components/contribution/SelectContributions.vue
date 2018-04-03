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
    let i= 0;
    while (new Date(ff).getTime() > new Date(fi).getTime() ) {
        let ad = ff.split('-');
        year = ad[0];
        month = ad[1];
        console.log(ad);  
        month--;
        if(month==0)
        {
            year--;
            month = 12;
        }
        ff= year+'-'+month+'-01';
        console.log(ff);
        i++;
    }
    console.log('cyk '+i);
    // this.list_aportes.forEach(aporte => {
    //     let fecha = aporte.month_year.split('-');
    //     console.log(fecha);
    // });

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
    selectContributions(){
        console.log(' fire');
        this.order_aportes=false;
        this.orderList();
        this.list_sixty = [];
        for (var j = 0; j < this.list_aportes.length; j++) {
            var obj = this.list_aportes[j];
            if(j<60) 
            {
                obj.contador = j+1;
                this.list_sixty.push(obj);
            }else
            {
                obj.contador = null;
            }
            Vue.set (this.list_aportes, j, obj)
            
        }
  
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
        }
        return color;
    },
    getFormatDate(fecha)
    {
        let str = fecha.split('-');
        return str[0]+' - '+parseInt(str[1]);
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