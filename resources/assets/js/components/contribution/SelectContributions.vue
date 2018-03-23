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
    <div class="col-md-6"> <!-- paneles hdps -->
      <div class="row"> <!-- Panele antes de disponibilidad-->
        <div class="ibox float-e-margins ibox-primary">
            <div class="ibox-title">
                <h5>Aportes antes de Disponibilidad <small class="m-l-sm"></small></h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content">
              <div style="width:530px; height:300px; overflow:auto;">
                
                  <table class="table ">
                  <thead>
                  <tr>
                      <th>Fecha</th>
                      <th>Sueldo</th>
                      <th>Categoria</th>
                      <th>Desglose</th>
                      <th>Total</th>
                      
                  </tr>
                  </thead>
                  <draggable v-model="list_normal" :element="'tbody'" :options="dragOptions" :move="onMove"  class="dragArea">
                  <tr v-for="contribution in list_normal" :key="contribution.id" >
                      <td>{{contribution.month_year}}</td>
                      <td>{{contribution.base_wage}}</td>
                      <td>{{contribution.category_name}}</td>
                      <td>{{contribution.breakdown_name}}</td>
                      <td>{{contribution.total}}</td>
                  </tr>
                  </draggable>
                  </table>

              </div> 
            </div>
            <div class="ibox-footer">
                <span class="pull-right">
                  Cantidad: {{list_normal.length}}
            </span>
                <br>
            </div>
        </div>

      </div>
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
                        <th>Desglose</th>
                        <th>Total</th>
                        
                        </tr>
                    </thead>
                    <draggable v-model="list_item0" :element="'tbody'" :options="dragOptions" :move="onMove" >
                        <tr v-for="contribution in list_item0" :key="contribution.id" >
                        <td>{{contribution.month_year}}</td>
                        <td>{{contribution.base_wage}}</td>
                        <td>{{contribution.category_name}}</td>
                        <td>{{contribution.breakdown_name}}</td>
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
            <div class="panel panel-warning">
                <div class="panel-heading">
                  Aportes en Disponibilidad
                </div>
                <div class="panel-body">
                  <table class="table">
                  <thead>
                    <tr>
                      <th>Fecha</th>
                      <th>Sueldo</th>
                      <th>Categoria</th>
                      <th>Desglose</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <draggable v-model="list_disponibilidad" :element="'tbody'" :options="dragOptions" :move="onMove"  class="dragArea">
                    <tr v-for="contribution in list_disponibilidad" :key="contribution.id" >
                      <td>{{contribution.month_year}}</td>
                      <td>{{contribution.base_wage}}</td>
                      <td>{{contribution.category_name}}</td>
                      <td>{{contribution.breakdown_name}}</td>
                      <td>{{contribution.total}}</td>
                    </tr>
                  </draggable>
                  </table>
                </div>
            </div>
      </div>
    </div>
    <div class="col-md-6"> <!--60 aportes -->
           <div class="panel panel-primary">
                <div class="panel-heading">
                  60 Aportes hdps
                  
                </div>
                <div class="panel-body">
             
                  <table class="table striped">
                  <thead>
                    <tr>
                      <th>Fecha</th>
                      <th>Sueldo</th>
                      <th>Categoria</th>
                      <th>Desglose</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <draggable v-model="list_aportes" :element="'tbody'" :options="dragOptions" :move="onMove"  class="dragArea">
                    <tr v-for="contribution in list_aportes" :key="contribution.id" >
                      <td>{{contribution.month_year}}</td>
                      <td>{{contribution.base_wage}}</td>
                      <td>{{contribution.category_name}}</td>
                      <td>{{contribution.breakdown_name}}</td>
                      <td>{{contribution.total}}</td>
                    </tr>
                  </draggable>
                  </table>
          
                </div>
            </div>
    </div>
  </div>

     
    <!-- <br>
    <div id="main">
      <h1>Vue Draggable</h1>           
      <div class="drag">
      <div class="list">
          <h2>List 1 Draggable</h2>
        <draggable v-model="list" class="dragArea" :options="{group:'people'}">
          <div class="name" :class="element.class" v-for="element in list">{{element.name}}</div>
        </draggable>
      </div>

    <div class="list">
        <h2>List 2 Draggable</h2>
        <draggable v-model="list2" class="dragArea" :options="{group:'people'}">
          <div class="name" v-for="element in list2">{{element.name}}</div>
        </draggable>
    </div>

      </div>
    </div> -->

    <!-- <div  class="list-group col-md-3">
      <pre>{{listString}}</pre>
    </div>
     <div  class="list-group col-md-3">
      <pre>{{list2String}}</pre>
    </div> -->
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
        'citem0'
    ],
  data () {
    return {
      list: this.cnormal,
      list_normal: this.cnormal,
      list_disponibilidad: this.cdisponibilidad,
      list_item0: this.citem0,
      list_aportes:[],
      editable:true,
      isDragging: false,
      delayedDragging:false
    }
  },
  created: function () {
    // `this` points to the vm instance
    
    console.log('Revisando lista_normal: ' + this.list_normal.length)
    console.log('Revisando lista_disponibilidad: ' + this.list_disponibilidad.length)
    console.log('Revisando lista_item0: ' + this.list_item0.length)
    console.log('Revisando lista_aportes: ' + this.list_aportes.length)
    if(this.list_normal.length == 0)
    {
      this.list_normal.push({"id":0,"base_wage":"","total":"","gain":"","retirement_fund":"","breakdown_id":"","breakdown_name":"","category_id":"","category_name":"","month_year":""})
    }
    if(this.list_disponibilidad.length == 0)
    {
      this.list_disponibilidad.push({"id":0,"base_wage":"","total":"","gain":"","retirement_fund":"","breakdown_id":"","breakdown_name":"","category_id":"","category_name":"","month_year":""})
    }
    if(this.list_item0.length == 0)
    {
      this.list_item0.push({"id":0,"base_wage":"","total":"","gain":"","retirement_fund":"","breakdown_id":"","breakdown_name":"","category_id":"","category_name":"","month_year":""})
    }
    if(this.list_aportes.length == 0)
    {
      this.list_aportes.push({"id":0,"base_wage":"","total":"","gain":"","retirement_fund":"","breakdown_id":"","breakdown_name":"","category_id":"","category_name":"","month_year":""})
    }
  },
  methods:{
    orderList () {
      this.list = this.list.sort((one,two) =>{return one.order-two.order; })
    },
    onMove ({relatedContext, draggedContext}) {
      const relatedElement = relatedContext.element;
      const draggedElement = draggedContext.element;
      return (!relatedElement || !relatedElement.fixed) && !draggedElement.fixed
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



</style>
