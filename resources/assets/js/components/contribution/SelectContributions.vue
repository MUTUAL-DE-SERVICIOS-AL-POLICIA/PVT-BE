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
     <div class="col-md-6"> <!--60 aportes -->
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
              <div style="width:500px; height:515px; overflow:auto;">
              <table class="table">
              <thead>
              <tr> 
                  <th>Nro</th>
                  <th>Fecha </th>
                  <th>Sueldo</th>
                  <th>Categoria</th>
                  <th>Tipo</th>
                  <th>Total</th>
              </tr>
              </thead>
              <draggable v-model="list_aportes" :element="'tbody'" :options="dragOptions" :move="onMove"  class="dragArea">
              <tr v-for="contribution in list_aportes" :key="contribution.id" :class="getColor(contribution.breakdown_id)" >

                  <td> {{contribution.contador}} </td>
                  <td>{{contribution.month_year}}</td>
                  <td>{{contribution.base_wage}}</td>
                  <td>{{contribution.category_name}}</td>
                  <td>
                      <select class="form-control" v-model="contribution.breakdown_id" >
                        <option v-for="item in list_types" :value="item.id" :key="item.id"> {{item.name}}</option>
                      </select>
                  </td>
                  <td>{{contribution.total}}</td>
              </tr>
              </draggable>
              </table>

              </div> 
          </div>
          <div class="ibox-footer">
              <span class="pull-right">
                  Cantidad: {{list_aportes.length-1}}
          </span>
              <br>
          </div>
      </div>
      <button class="btn btn-primary" @click="classify" ><i class="fa fa-arrow-right"></i> Clasificar</button>
      <button class="btn btn-primary" @click="selectContributions" ><i class="fa fa-check"></i> 60 aportes</button>
      <button class="btn btn-primary" @click="save" ><i class="fa fa-list-alt"></i> Registrar</button>
    </div>
    <div class="col-md-6"> <!-- paneles hdps -->
     
      <div class="row"> <!-- Panele item 0 -->
        <div class="ibox float-e-margins ibox-primary">
          <div class="ibox-title">
              <h5>Aportes Item 0  <small class="m-l-sm"></small></h5>
              <div class="ibox-tools">
                  <a class="collapse-link">
                      <i class="fa fa-chevron-up"></i>
                  </a>
              </div>
          </div>
          <div class="ibox-content">
              <div style="width:530px; height:300px; overflow:auto;">
              
                <table class="table">
                    <thead>
                        <tr>
                        <th>Fecha</th>
                        <th>Sueldo</th>
                        <th>Categoria</th>
                        <th>Tipo</th>
                        <th>Total</th>
                        
                        </tr>
                    </thead>
                    <draggable v-model="list_item0" :element="'tbody'" :options="dragOptions" :move="onMove" >
                        <tr v-for="contribution in list_item0" :key="contribution.id" >
                        <td>{{contribution.month_year}}</td>
                        <td>{{contribution.base_wage}}</td>
                        <td>{{contribution.category_name}}</td>
                        <td>
                            <select class="form-control" v-model="contribution.breakdown_id" >
                                <option v-for="item in list_types" :value="item.id" :key="item.id"> {{item.name}}</option>
                            </select>
                        </td>
                        <td>{{contribution.total}}</td>
                        </tr>
                    </draggable>
                </table>

                </div> 
            </div>
            <div class="ibox-footer">
                <span class="pull-right">
                    Cantidad: {{list_item0.length}}
            </span>
                <br>
            </div>
        </div>

      </div>
      <div class="row"> <!-- Panel en disponibilidad-->
            <div class="ibox float-e-margins ibox-primary">
              <div class="ibox-title">
                  <h5>Aportes en Disponibilidad <small class="m-l-sm"></small></h5><i :class="order_disponibilidad?'fa fa-sort-amount-desc':'fa fa-sort-amount-asc'" @click="orderListDisp"></i>
                  <div class="ibox-tools">
                      <a class="collapse-link">
                          <i class="fa fa-chevron-up"></i>
                      </a>
                  </div>
              </div>
              <div class="ibox-content">
                  <div style=" height:300px; overflow:auto;">
                  <table class="table">
                  <thead>
                  <tr>
                      <th>Fecha</th>
                      <th>Sueldo</th>
                      <th>Categoria</th>
                      <th>Tipo</th>
                      <th>Total</th>
                  </tr>
                  </thead>
                  <draggable v-model="list_disponibilidad" :element="'tbody'" :options="dragOptions" :move="onMove"  class="dragArea">
                  <tr v-for="contribution in list_disponibilidad" :key="contribution.id" >
                      <td>{{contribution.month_year}}</td>
                      <td>{{contribution.base_wage}}</td>
                      <td>{{contribution.category_name}}</td>
                      <td>
                            <select class="form-control" v-model="contribution.breakdown_id" >
                                <option v-for="item in list_types" :value="item.id" :key="item.id" > {{item.name}}</option>
                            </select>
                      </td>
                      <td>{{contribution.total}}</td>
                  </tr>
                  </draggable>
                  </table>

                  </div> 
              </div>
              <div class="ibox-footer">
                  <span class="pull-right">
                      Cantidad: {{list_disponibilidad.length}}
              </span>
                  <br>
              </div>
          </div>


      </div>
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
        'cnormal',
        'cdisponibilidad',
        'citem0',
        'retfunid',
        'types'
    ],
  data () {
    return {
      
      list_disponibilidad: this.cdisponibilidad,
      list_item0: [],
      list_aportes:this.cnormal,
      list_types: this.types,
      list_sixty:[],
      order_aportes: true,
      order_disponibilidad: true,
      editable:true,
      isDragging: false,
      delayedDragging:false
    }
  },
  created: function () {
    // `this` points to the vm instance
    
    console.log('Revisando lista_disponibilidad: ' + this.list_disponibilidad.length)
    console.log('Revisando lista_item0: ' + this.list_item0.length)
    console.log('Revisando lista_aportes: ' + this.list_aportes.length)
    
    //corregir con estilos hdps
   
    if(this.list_disponibilidad.length == 0)
    {
      this.list_disponibilidad.push({"id":0,"base_wage":"","total":"","gain":"","retirement_fund":"","breakdown_id":"","breakdown_name":"","category_id":"","category_name":"","month_year":"","contador":null})
    }
    if(this.list_item0.length == 0)
    {
      this.list_item0.push({"id":0,"base_wage":"","total":"","gain":"","retirement_fund":"","breakdown_id":"","breakdown_name":"","category_id":"","category_name":"","month_year":"","contador":null})
    }
    if(this.list_aportes.length == 0)
    {
      this.list_aportes.push({"id":0,"base_wage":"","total":"","gain":"","retirement_fund":"","breakdown_id":"","breakdown_name":"","category_id":"","category_name":"","month_year":"","contador":null})
    }
     
    var disponibilidad = this.types.filter(function (type) {
        return type.name  == 'Disponibilidad';   
    })[0];
    var item0 =  this.types.filter(function (type) {
        return type.name  == 'Item 0';   
    })[0];
    var servicio = this.types.filter(function (type) {
        return type.name  == 'Servicio';   
    })[0];
    console.log(servicio);
    console.log(servicio.id);    

    for (let i = 0; i < this.list_aportes.length; i++) {
      var obj =   this.list_aportes[i]; 
      obj['contador'] = null;
      this.list_aportes[i] = obj;
    //   console.log(obj);  
    }

    for (let i = 0; i < this.list_disponibilidad.length; i++) {
      var obj =   this.list_disponibilidad[i]; 
      obj['contador'] = null;
      this.list_disponibilidad[i] = obj;
    //   console.log(obj);  
    }

   for (let i = 0; i < this.list_aportes.length; i++) {
       
       switch (this.list_aportes[i].breakdown_id) {
            case 1:
                this.list_aportes[i].breakdown_id = disponibilidad.id;
                this.list_aportes[i].breakdown_name = disponibilidad.name;
                break
            case 3:
                this.list_aportes[i].breakdown_id = item0.id;
                this.list_aportes[i].breakdown_name = item0.name;
                // console.log('cambiando a items 0');
                // console.log(this.list_aportes[i]);
                
               break;
       
           default:
                this.list_aportes[i].breakdown_id = servicio.id;
                this.list_aportes[i].breakdown_name = servicio.name;
               break;
       }       
   } 
   for (let i = 0; i < this.list_disponibilidad.length; i++) {
       this.list_disponibilidad[i].breakdown_id = disponibilidad.id;
       this.list_disponibilidad[i].breakdown_name = disponibilidad.name;
   }
   console.log(this.list_aportes);
  },
  methods:{
    orderList () {
        console.log('ordenando la hueva esta');
      if(this.order_aportes){
          this.list_aportes = this.list_aportes.sort((one,two) =>{return new Date(one.month_year)- new Date(two.month_year); });
      }else{
          this.list_aportes = this.list_aportes.sort((one,two) =>{return new Date(two.month_year)- new Date(one.month_year); })
      }
      this.order_aportes = !this.order_aportes;
    },
    orderListDisp(){
        
      if(this.order_disponibilidad){
          this.list_disponibilidad = this.list_disponibilidad.sort((one,two) =>{return new Date(one.month_year)- new Date(two.month_year); });
      }else{
          this.list_disponibilidad = this.list_disponibilidad.sort((one,two) =>{return new Date(two.month_year)- new Date(one.month_year); })
      }
      this.order_disponibilidad = !this.order_disponibilidad;
    },
    onMove ({relatedContext, draggedContext}) {
      const relatedElement = relatedContext.element;
      const draggedElement = draggedContext.element;
      return (!relatedElement || !relatedElement.fixed) && !draggedElement.fixed
    },
    save(){
      console.log('guardando lista '+ this.affiliateid);
    //   var sixty_ids = [];
    //   list_sixty.forEach(aporte => {
    //       sixty_ids.push(aporte.id);
    //   });

      var data = {'ret_fun_id':this.retfunid,'list_disponibilidad':this.list_disponibilidad,'list_aportes':this.list_aportes,'list_sixty':this.list_sixty};
      axios.post('/ret_fun/savecontributions', data )
                  .then(function (resp) {
                      // this.$router.push({path: '/'});
                      console.log(resp);
                         
                      flash('Informacion CYK Actualizada');
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
        this.list_item0 =[];
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
            case 3:
                color="cyk";
                break;
            case 2:
                color="cyv";
                break;
        }
        return color;
    }
  },
  computed: {
    dragOptions () {
      return  {
        animation: 0,
        group: 'description',
        disabled: !this.editable,
        ghostClass: 'ghost'
      };
    },
    listString(){
      return JSON.stringify(this.cnormal, null, 2);  
    },
    list2String(cNormal){
      return JSON.stringify(this.list_aportes, null, 2);  
    },
    getAportes(){
        return this.list_aportes;
    }
  },
  watch: {
    isDragging (newValue) {
      if (newValue){
        this.delayedDragging= true
        return
      }
      this.$nextTick( () =>{
           this.delayedDragging =false
      })
    }
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
    background: #ff7a69fd;
    
}
</style>