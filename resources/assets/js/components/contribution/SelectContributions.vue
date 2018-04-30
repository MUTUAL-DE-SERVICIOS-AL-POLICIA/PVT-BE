<template>
  <div class="fluid container">

    <!-- <div class="panel panel-default">
        <div class="panel-body">
            <label>Servicio </label>
            <label>Disponibilidad </label>
            <label>Item 0 </label>
            <label>CAS </label>
            <label>No has registro</label>
        </div>
    </div> -->

  <div class="col-md-12 col-lg-11 col-sm-12">
        <div class="ibox float-e-margins ibox-primary">
          <!-- <div class="ibox-title"> -->
              <!-- <h5>Aportes <small class="m-l-sm"></small></h5> <i :class="order_aportes?'fa fa-sort-amount-desc':'fa fa-sort-amount-asc'" @click="orderList"></i> -->
              <!-- <div class="ibox-tools">
                  <a class="collapse-link">
                      <i class="fa fa-chevron-up"></i>
                  </a>
              </div> -->
          <!-- </div> -->
          <div class="ibox-content">
                <div class="row">
                    <div class="col-md-7">
                        <h2>Aportes <small class="m-l-sm"></small></h2> 
                        <button class="btn btn-sm btn-info"  @click="orderList" ><i :class="order_aportes?'fa fa-sort-amount-desc':'fa fa-sort-amount-asc'"></i></button>
                        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalcyk" @click="clear"><i class="fa fa-table"></i></button>
                        <div v-if="show_certification">
                            <a :href="this.urlcertification" class="btn btn-sm btn-primary " v-if="count(servicio.id)>0" ><i class="fa fa-file-pdf-o"></i> 60 Aportes </a>
                            <a :href="this.ulrzero" class="btn btn-sm btn-primary " v-if="count(item0.id)>0" ><i class="fa fa-file-pdf-o"></i> Item 0 </a>
                            <a :href="this.urlavailable" class="btn btn-sm btn-primary " v-if="count(disponibilidad.id)>0"><i class="fa fa-file-pdf-o"></i> Disponibilidad </a>
                        </div>  
                    </div>
                    <div class="col-md-5">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <!-- <div class="col-md-1"> <div class="square"></div> </div> -->
                                    <div class="col-md-6">
                                        <label>Servicio:</label> {{count(servicio.id)}} 
                                        
                                    </div>
                                    <!-- <div class="col-md-1"> <div class="square"></div> </div> -->
                                    <div class="col-md-6">
                                        <label>Disponibilidad:</label> {{count(disponibilidad.id)}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Item 0:</label> {{count(item0.id)}}
                                    </div>
                                    <div class="col-md-6">
                                        <label>CAS:</label> {{count(cas.id)}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>No has registro:</label> {{ count(nh.id)}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-md-1">  
                            <label style="font-size:80%;" v-if="list_aportes.length>0" > {{ getFormatDate(list_aportes[0].month_year) }}</label>                           
                            <div style="border-style: solid; border-width: 1px; ">
                                <div v-for="(contribution,index) in list_aportes" :key="contribution.id" >
                                        <div :style="getStyle(contribution.breakdown_id)" @click="selectRow(index)" data-toggle="tooltip" data-placement="left" :title="getFormatDate(contribution.month_year)"></div> 
                                </div>
                            </div>
                            <label style="font-size:80%;" v-if="list_aportes.length>0"> {{ getFormatDate(list_aportes[list_aportes.length-1].month_year) }}</label>                           

                    </div>
                    <div class="col-md-11">
                        <table class="table " id="contribution_table">
                            <thead>
                            <tr> 
                                <th class="col-md-1">Fecha </th>
                                <th class="col-md-3">Sueldo</th>
                                <th class="col-md-3">Categoria</th>
                                <th class="col-md-2">Total</th>
                                <th class="col-md-3">Tipo</th>
                            </tr>
                            </thead>
                            <tbody id="contenedor">
                                <tr v-for="contribution in list_aportes" :key="contribution.id" :class="getColor(contribution.breakdown_id)" >
                                    <td class="col-md-1">{{getFormatDate(contribution.month_year)}}</td>
                                    <td class="col-md-3">{{contribution.base_wage}}</td>
                                    <td class="col-md-3">{{contribution.category_name}}</td>
                                    <td class="col-md-2">{{ contribution.total }}</td>
                                    <td class="col-md-3">
                                        <select class="form-control" v-model="contribution.breakdown_id" >
                                        <option v-for="item in list_types" :value="item.id" :key="item.id"> {{item.name}}</option>
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

          </div>
          <div class="ibox-footer">
                <button class="btn btn-primary btn-sm" @click="save" ><i class="fa fa-arrow-right"></i> Clasificar</button>
                
                <span class="pull-right">
                <strong>  Cantidad: {{list_aportes.length}} </strong>
                </span>
              <br>
          </div>
      </div>
     
   
  </div>
    <!-- adicionando modal -->
    <div class="modal inmodal" id="modalcyk" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
                
               
                <div class="modal-header">
                    <h4 class="modal-title">Seleccion por rango de Fechas</h4>
                    <!-- <div class="alert alert-info">
                           Fecha Inicio: <strong> {{ getFormatDate(first_date.month_year) }} </strong> &nbsp;  Fecha Fin:<strong> {{ getFormatDate(last_date.month_year) }} </strong>
                    </div> -->
                    <small class="font-bold">De &nbsp; <strong> {{ getFormatDate(first_date.month_year) }} </strong> &nbsp;  hasta &nbsp; <strong> {{ getFormatDate(last_date.month_year) }} </strong></small>
                </div>
                <div class="modal-body">
                   
                    <div class="row">
                        <div class="col-md-4"> <label>Tipo de contribucion</label></div>
                        <div class="form-group col-md-8" >
                            <select class="form-control" v-model="modal.contribution_type_id" >
                                <option v-for="item in list_types" :value="item.id" :key="item.id"> {{item.name}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-1">
                            <label>De:</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="date" v-model="modal.first_date">
                        </div>
                        <div class="col-md-1">
                            <label>Hasta:</label>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" type="date" v-model="modal.last_date">
                        </div>
                    </div>
                
                </div>
                <div class="modal-footer">
                    <div class="col-md-5">
                        <!-- <div class="alert alert-info">
                            Rango de 1-1900 hasta 2-2016
                        </div> -->
                    </div>
                    <div class="col-md-7">
                        <button type="button" class="btn btn-white btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" @click="applyRangeDate"  > <i class="fa fa-check"></i> aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
  
</template>table
 
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
        'types',
        'retfunid',
        'urlcertification',
        'ulrzero',
        'urlavailable'
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
        show_certification: false,
        modal: { first_date: null, last_date: null,contribution_type_id: null},
        row_higth: 0
    }
  },
  created: function () {
    
    console.log('Revisando lista_aportes: ' + this.list_aportes.length)
    // console.log(this.urlcertification);
    console.log(this.types);
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
        return type.name  == 'No Hay Registro';   
    })[0];    

    this.cas = this.types.filter(function (type) {
        return type.name  == 'Registro Segun CAS';   
    })[0];

    this.bfs = this.types.filter(function (type) {
        return type.name  == 'Batallon de Seguridad Fisica';   
    })[0];

    if(!this.con_type){
        this.show_certification =true;
    }
    console.log(this.nh);
    let list_hdp= this.list_aportes;
    if(this.con_type){
        for (let i = 0; i < list_hdp.length; i++) {
       
            switch (list_hdp[i].breakdown_id) {

                    case 1:
                        list_hdp[i].breakdown_id = this.disponibilidad.id;
                        list_hdp[i].breakdown_name = this.disponibilidad.name;
                        break;

                    case 3:
                        list_hdp[i].breakdown_id = this.item0.id;
                        list_hdp[i].breakdown_name = this.item0.name;              
                    break;
            
                default:
                        list_hdp[i].breakdown_id = this.servicio.id;
                        list_hdp[i].breakdown_name = this.servicio.name;
                    break;
            }   
            // console.log(list_hdp[i].breakdown_id);    
            // console.log(list_hdp[i].breakdown_name);    
        } 
        this.list_aportes = list_hdp;
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
        if(month.length==1)
        {
            month = '0'+month;
        }
        ff= year+'-'+month+'-01';
    }
    // console.log(list);
    this.list_aportes = list;
    this.row_higth = 386/this.list_aportes.length;
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
    selectRow(index){
        console.log('select row index: '+index);
        // this.position_top = index*51;
        $('#contenedor').scrollTop(index*51);
    },
    save(){
        if(this.checkList())
        {
            console.log('guardando lista '+ this.affiliateid);
            var data = {'ret_fun_id':this.retfunid,'list_aportes':this.list_aportes};
            axios.post('/ret_fun/savecontributions', data )
                        .then(function (resp) {
                            // this.$router.push({path: '/'});
                            console.log(resp);
                                
                            flash('Informacion Clasificada');
                        })
                        .catch(function (resp) {
                            console.log(resp);
                            flash('Error: '+resp.message,'error');
                        });
        }else{
            flash('verifique que no existan aportes sin clasificar','warning');
        }

     
    },
    classify(){
        

        this.list_aportes.forEach(aporte => {
            // console.log(aporte);
            if(aporte.breakdown_id==3)
                this.list_item0.push(aporte);
        });
    },
    getStyle(breakdown_id){
        let style = 'display: block;width: 100%; height:'+this.row_higth+'px;';
         var color="cya";
        switch (breakdown_id) {
            case this.disponibilidad.id:
                color="#aeda8a";
                break;
            case this.item0.id:
                color="#f7f097fd";
                break;
            case this.servicio.id:
                color="#ffffff";
                break;
            case this.bfs.id:
                color="#a1a7fffd";
                break;
            case this.nh.id:
                color="#e0ad7dfd";
                break;
            case this.cas.id:
                color="#80e9bdfd";
                break;
            case 0:
                color="#bbbaadfd";
                break;
            default: 
                console.log(breakdown_id);
            break;
            
        }
        console.log(style);
        return style+'background:'+color+';';
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
            default: 
                console.log(breakdown_id);
            break;
            
        }
        return color;
    },
    getFormatDate(fecha)
    {
        let str = fecha.split('-');
        return str[1]+' - '+parseInt(str[0]);
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
        // if(!contribution) // en caso de buggs XD 
        // {   
        //     console.log('no encontro el buscador hdp '+ strDate);
        //     for (let i = 0; i < this.list_aportes.length; i++) {
        //         console.log('list: '+this.list_aportes[i].month_year);
        //         console.log('strDate:'+strDate);
        //         if(new Date(this.list_aportes[i].month_year).getTime() == new Date(strDate).getTime() )
        //         {
        //             contribution = this.list_aportes[i];
        //             i = this.list_aportes.length;
        //             // console.log('Encotrado el hdp');
        //         } 
        //     }
        // }
        return contribution;
    },
    checkList()
    {
        let response= true;
        for (let i = 0; i < this.list_aportes.length; i++) {
            if(this.list_aportes[i].breakdown_id ==0) 
            {   
                   response=false;
            }
        }
        return response;
    },
    count(contribution_type){
        let count=0;
        for (let i = 0; i < this.list_aportes.length; i++) {
            if(this.list_aportes[i].breakdown_id == contribution_type) 
            {   
                   count++;
            }
        }
        return count;
    },
    clear(){
        this.modal.first_date = null;
        this.modal.last_date = null;
        this.modal.contribution_type_id = null;
    },
    applyRangeDate()
    {
        if(this.isValid())
        {
            let fi = this.modal.first_date;
            let ff = this.modal.last_date;
            let c_type_id = this.modal.contribution_type_id;
            let year = null;
            let month = null;

            let c_type = this.types.filter(function (type) {
                return type.id  == c_type_id;   
            })[0];
            // console.log(c_type);
            for (let i = 0; i < this.list_aportes.length; i++) {
                let aporte = this.list_aportes[i];
                if(new Date(aporte.month_year).getTime() >= new Date(fi).getTime() && new Date(aporte.month_year).getTime() <= new Date(ff).getTime()  )
                {
                    console.log(aporte);
                    aporte.breakdown_id = c_type.id;
                    aporte.breakdown_name = c_type.name;
                    Vue.set(this.list_aportes,i,aporte);
                }
            }
        }
    },
    isValid()
    {
        let response = true;
        // && !this.modal.last_date && !this.modal.contribution_type_id
        if(!this.modal.first_date)
        {
            flash('Error: verifique que la fecha "De:" no este vacia','error');
            response = false;
        }
        if(!this.modal.last_date)
        {
            flash('Error: verifique que la fecha "Hasta:" no este vacia','error');
            response = false;
        }
        if(!this.modal.contribution_type_id)
        {
            flash('Error: debe seleccionar al menos un tipo de contribucion','error');
            response = false;
        }
        if(response)
        {
            if(new Date(this.modal.first_date).getTime() < new Date(this.first_date.month_year).getTime()  )
            {
                flash('Error: la fecha '+this.modal.first_date+' no debe ser menor a '+this.first_date.month_year ,'warning');
                response = false;
                console.log('Error: la fecha '+this.modal.first_date+' no debe ser menos a '+this.first_date.month_year);
            }
            if(new Date(this.modal.last_date).getTime() > new Date(this.last_date.month_year).getTime() )
            {
                flash('Error: la fecha '+this.modal.last_date+' no debe ser mayor a '+this.last_date.month_year ,'warning');
                response = false;
                console.log('Error: la fecha '+this.modal.last_date+' no debe ser mayor a '+this.last_date.month_year);
            }
            if(new Date(this.modal.first_date).getTime() > new Date(this.modal.last_date).getTime())
            {
                flash('Error: la fecha '+this.modal.first_date+' no debe ser mayor a '+this.modal.last_date ,'warning');
                response = false;
                console.log('Error: la fecha '+this.modal.first_date+' no debe ser mayor a '+this.modal.last_date);
            }
        }
       
        return response;
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
    background: #e0ad7dfd;
}
.cas{
    background: #80e9bdfd;
}
.bsf{
    background: #a1a7fffd;
}
tr {
width: 100%;
display: inline-table;
}

table{
 height:400px; 
}
thead{
  width: 100%;
}
tbody{
  overflow-y: scroll;
  height: 400px;
  width: 97%;
  position: absolute;
}
.square {
  display: block;
  width: 15px;
  height: 1px;
  background: #124405fd;
}
</style>|