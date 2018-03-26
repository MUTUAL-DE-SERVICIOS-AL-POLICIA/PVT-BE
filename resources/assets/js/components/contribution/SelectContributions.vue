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
            <div class="ibox float-e-margins ibox-primary">
              <div class="ibox-title">
                  <h5>Aportes en Disponibilidad <small class="m-l-sm"></small></h5>
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
              <div class="ibox-footer">
                  <span class="pull-right">
                      Cantidad: {{list_disponibilidad.length}}
              </span>
                  <br>
              </div>
          </div>


      </div>
    </div>
    <div class="col-md-6"> <!--60 aportes -->
        <div class="ibox float-e-margins ibox-primary">
          <div class="ibox-title">
              <h5>60 Aportes <small class="m-l-sm"></small></h5>
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
          <div class="ibox-footer">
              <span class="pull-right">
                  Cantidad: {{list_aportes.length-1}}
          </span>
              <br>
          </div>
      </div>
      <button class="btn btn-primary" @click="save" ><i class="fa fa-check"></i> Guardar</button>
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
        'retfunid'
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
    },
    save(){
      console.log('guardando lista '+ this.affiliateid);
      var data = {'ret_fun_id':this.retfunid,'lista_disponibilidad':this.list_disponibilidad,'list_aportes':this.list_aportes,'lista_item0':this.list_item0};
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
