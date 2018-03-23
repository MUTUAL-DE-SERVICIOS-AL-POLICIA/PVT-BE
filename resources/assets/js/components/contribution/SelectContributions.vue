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
        <div class="panel panel-info">
            <div class="panel-heading">
               Aportes 
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
      </div>
      <div class="row"> <!-- Panele item 0 -->
          <div class="panel panel-success">
              <div class="panel-heading">
                Aportes Item 0 
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
                <draggable v-model="list_item0" :element="'tbody'" :options="dragOptions" :move="onMove"  class="dragArea">
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
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt est vitae ultrices accumsan. Aliquam ornare lacus adipiscing, posuere lectus et, fringilla augue.</p>
                </div>
            </div>
    </div>
  </div>

    <!-- <div class="panel panel-info">
      <div class="panel-body">
        
        <div class="dragArea">
        <div class="col-md-6">
          <legend>Aportes</legend>
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
          <draggable v-model="list" :element="'tbody'" :options="dragOptions" :move="onMove"  class="dragArea">
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
          <div class="list">
          <draggable v-model="list" :element="'tbody'" :options="dragOptions" :move="onMove" class="dragArea" >
            <tr v-for="contribution in list2" :key="contribution.id" class="drag">
              <td class="dragArea">{{contribution.month_year}}</td>
              <td class="dragArea">{{contribution.base_wage}}</td>
              <td class="dragArea">{{contribution.category_name}}</td>
              <td class="dragArea">{{contribution.breakdown_name}}</td>
              <td class="dragArea">{{contribution.total}}</td>
            </tr>
          </draggable>
          </div>
          </table>
        </div>

        </div>
      </div>
    </div> -->

     
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

    <div  class="list-group col-md-3">
      <pre>{{listString}}</pre>
    </div>
     <div  class="list-group col-md-3">
      <pre>{{list2String}}</pre>
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
        'citem0'
    ],
  data () {
    return {
      list: this.cnormal,
      list_normal: this.cnormal,
      list_disponibilidad: this.cdisponibilidad,
      list_item0: this.citem0,
      list2:[],
      contribution_list: this.cnormal,
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
      return JSON.stringify(this.cnormal, null, 2);  
    },
    list2String(cNormal){
      return JSON.stringify(this.list_disponibilidad, null, 2);  
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
.normal {
  background-color: grey;
}

.drag {
  background-color: green;
  display: flex;
  flex-direction: row;
}

.list {
  flex-grow: 1;
}

.name {
  width: 50%;
  display: inline-block;
  height: 50px;
  background: pink;
  border: 5px green solid;
  box-sizing: border-box;
  padding: 5px;
}
.name.large {
  width: 100%;
}

.dragArea {
  min-height: 10px;
}

</style>
