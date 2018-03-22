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
    <div class="panel panel-info">
      <div class="panel-body">
        <!-- <div  class="col-md-6">
            <draggable class="list-group" element="ul" v-model="contribution_list" :options="dragOptions" :move="onMove" @start="isDragging=true" @end="isDragging=false"> 
              <transition-group type="transition" :name="'flip-list'">
                <li class="list-group-item" v-for="contribution in contribution_list" :key="contribution.id"> 
                  <strong>{{contribution.month_year}}</strong>  Sueldo:{{contribution.base_wage}}  Categoria:{{contribution.category_name}} Total:{{contribution.gain}} 
                                                        <br> Fondo Retiro:{{contribution.retirement_fund}} Desglose:{{contribution.breakdown_name}}
                </li> 
              </transition-group>
            </draggable>
        </div>
        <div  class="col-md-6">
          <draggable element="span" v-model="list2" :options="dragOptions" :move="onMove"> 
              <transition-group name="no" class="list-group" tag="ul">
                <li class="list-group-item" v-for="contribution in list2" :key="contribution.id"> 
                  <strong>{{contribution.month_year}}</strong>  Sueldo:{{contribution.base_wage}}  Categoria:{{contribution.category_name}} Total:{{contribution.gain}} 
                                                        <br> Fondo Retiro:{{contribution.retirement_fund}} Desglose:{{contribution.breakdown_name}}
                </li> 
              </transition-group>
          </draggable>
        </div> -->
        <div class="col-md-6">
          <legend>Aportes</legend>
          <table class="table dragArea">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Sueldo</th>
              <th>Categoria</th>
              <th>Desglose</th>
              <th>Total</th>
              
            </tr>
          </thead>
          <draggable v-model="list" :element="'tbody'" :options="dragOptions" :move="onMove" >
            <tr v-for="contribution in list" :key="contribution.id" >
              <td>{{contribution.month_year}}</td>
              <td>{{contribution.base_wage}}</td>
              <td>{{contribution.category_name}}</td>
              <td>{{contribution.breakdown_name}}</td>
              <td>{{contribution.total}}</td>
            </tr>
          </draggable>
          </table>
        </div>
        <div class="col-md-6">
          <legend>60 Aportes</legend>
          <table class="table dragArea">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Sueldo</th>
              <th>Categoria</th>
              <th>Desglose</th>
              <th>Total</th>
              
            </tr>
          </thead>
          <draggable v-model="list" :element="'tbody'" :options="dragOptions" :move="onMove" >
           
            <tr v-for="contribution in list2" :key="contribution.id" class="dragArea">
              <td class="dragArea">{{contribution.month_year}}</td>
              <td class="dragArea">{{contribution.base_wage}}</td>
              <td class="dragArea">{{contribution.category_name}}</td>
              <td class="dragArea">{{contribution.breakdown_name}}</td>
              <td class="dragArea">{{contribution.total}}</td>
            </tr>
          </draggable>
          </table>
        </div>
      </div>
    </div>

     
    <br>

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
        'contribuciones',
    ],
  data () {
    return {
      list: this.contribuciones,
      list2:[],
      contribution_list: this.contribuciones,
      editable:true,
      isDragging: false,
      delayedDragging:false
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
      return JSON.stringify(this.contribution_list, null, 2);  
    },
    list2String(){
      return JSON.stringify(this.list2, null, 2);  
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

.list-group {
  min-height: 20px;
}

.list-group-item {
  cursor: move;
}

.list-group-item i{
  cursor: pointer;
}
.dragArea {
  min-height: 20px;
  background: rgb(251, 216, 200);
}
</style>
