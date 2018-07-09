<template>
  <div class="col-md-12" style="padding-top: 5px;padding-left: 0px">
        <div class="ibox float-e-margins ibox-primary">
          
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-2">
                        <h3>
                        <button class="btn btn-sm btn-info"  @click="orderList" ><i :class="order_aportes?'fa fa-sort-amount-desc':'fa fa-sort-amount-asc'"></i></button>
                            Aportes

                        </h3> 
                        
                    </div>
                    <div class="pull-right" style="padding-right:10px">
                        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalcyk" @click="clear"><i class="fa fa-table"></i></button>
                    </div>
                    
                    
                </div>
                <div class="row">
                    <div class="col-md-1">  
                            <label style="font-size:80%;" v-if="list_aportes.length>0" > {{ getFormatDate(list_aportes[0].month_year) }}</label>                           
                            <div style="border-style: solid; border-width: 1px; ">
                                <div v-for="(contribution,index) in list_aportes" :key="index" >
                                        <div :style="getStyle(contribution.breakdown_id,false)" @click="selectRow(index)" data-toggle="tooltip" data-placement="left" :title="getFormatDate(contribution.month_year)"></div> 
                                </div>
                            </div>
                            <label style="font-size:80%;" v-if="list_aportes.length>0"> {{ getFormatDate(list_aportes[list_aportes.length-1].month_year) }}</label>                           

                    </div>
                    <div class="col-md-9">
                        <table class="table " id="contribution_table">
                            <thead>
                            <tr> 
                                <th class="col-md-2">Fecha </th>
                                <th class="col-md-2">Sueldo</th>
                                <th class="col-md-2">Categoria</th>
                                <th class="col-md-2">Total</th>
                                <th class="col-md-4">Tipo</th>
                            </tr>
                            </thead>
                            <tbody id="contenedor">
                                <tr v-for="(contribution,index) in list_aportes" :key="index" :class="getColor(contribution.breakdown_id)" >
                                    <td class="col-md-2">{{getFormatDate(contribution.month_year)}}</td>
                                    <td class="col-md-2">{{contribution.base_wage}}</td>
                                    <td class="col-md-2">{{contribution.category_name}}</td>
                                    <td class="col-md-2">{{ contribution.total }}</td>
                                    <td class="col-md-4">
                                        <select class="form-control" v-model="contribution.breakdown_id" >
                                        <option v-for="item in list_types" :value="item.id" :key="item.id"> {{item.name}}</option>
                                        </select>
                                    </td> 
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-2" style="padding-left:0px;padding-right: 2px">
                        <h3>Tipos de Aportes</h3>
                        <ul class="list-group">
                            <li class="list-group-item comando" v-if="count(this.comando.id)>0">
                                <span class="badge" >{{count(this.comando.id)}}</span>
                                {{this.comando.name}}
                            </li>
                            <li class="list-group-item item0_con_aporte" v-if="count(this.item0_con_aporte.id)>0">
                                <span class="badge ">{{count(this.item0_con_aporte.id)}}</span>
                                {{this.item0_con_aporte.name}}
                            </li>
                            <li class="list-group-item item0_sin_aporte" v-if="count(this.item0_sin_aporte.id)>0">
                                <span class="badge ">{{count(this.item0_sin_aporte.id)}}</span>
                                {{this.item0_sin_aporte.name}}
                            </li>
                            <li class="list-group-item bfs_con_aporte" v-if="count(this.bfs_con_aporte.id)>0">
                                <span class="badge ">{{count(this.bfs_con_aporte.id)}}</span>
                                {{this.bfs_con_aporte.name}}
                            </li>
                            <li class="list-group-item bfs_sin_aporte" v-if="count(this.bfs_sin_aporte.id)>0">
                                <span class="badge ">{{count(this.bfs_sin_aporte.id)}}</span>
                                {{this.bfs_sin_aporte.name}}
                            </li>
                            <li class="list-group-item mayo_1976" v-if="count(this.mayo_1976.id)>0">
                                <span class="badge ">{{count(this.mayo_1976.id)}}</span>
                                {{this.mayo_1976.name}}
                            </li>
                            <li class="list-group-item certificacion_con_aporte" v-if="count(this.certificacion_con_aporte.id)>0">
                                <span class="badge ">{{count(this.certificacion_con_aporte.id)}}</span>
                                {{this.certificacion_con_aporte.name}}
                            </li>
                            <li class="list-group-item certificacion_sin_aporte" v-if="count(this.certificacion_sin_aporte.id)>0">
                                <span class="badge ">{{count(this.certificacion_sin_aporte.id)}}</span>
                                {{this.certificacion_sin_aporte.name}}
                            </li>
                            <li class="list-group-item perdiodo_no_trabajado" v-if="count(this.perdiodo_no_trabajado.id)>0">
                                <span class="badge ">{{count(this.perdiodo_no_trabajado.id)}}</span>
                                {{this.perdiodo_no_trabajado.name}}
                            </li>
                            <li class="list-group-item disponibilidad" v-if="count(this.disponibilidad.id)>0">
                                <span class="badge ">{{count(this.disponibilidad.id)}}</span>
                                {{this.disponibilidad.name}}
                            </li>
                        </ul>
                       
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
     
     <!-- adicionando modal -->
    <div class="modal inmodal" id="modalcyk" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
                
               
                <div class="modal-header">
                    <h4 class="modal-title">Seleccion por rango de Fechas</h4>
                    <small class="font-bold">De &nbsp; <strong> {{ getFormatDate(first_date) }} </strong> &nbsp;  hasta &nbsp; <strong> {{ getFormatDate(last_date) }} </strong></small>
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
                            <input type="tel" v-mask="'##/####'" placeholder="mm/yyyy" class="form-control" v-model="modal.first_date">
                        </div>
                        <div class="col-md-1">
                            <label>Hasta:</label>
                        </div>
                        <div class="col-md-4">
                            <input type="tel" v-mask="'##/####'" placeholder="mm/yyyy" class="form-control" v-model="modal.last_date">
                        </div>
                    </div>
                
                </div>
                <div class="modal-footer">
                    <div class="col-md-5">
                    </div>
                    <div class="col-md-7">
                        <button type="button" class="btn btn-white btn-sm" data-dismiss="modal"><i class="fa fa-close"></i> cancelar</button>
                        <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" @click="applyRangeDate"  > <i class="fa fa-check"></i> aceptar</button>
                    </div>
                </div>
            </div>
        </div>
     </div> <!-- fin del modal -->
  </div>
</template>
 
<script>
import {TheMask} from 'vue-the-mask'

export default {
  name: 'clasificacion-aportes',
  components: {
    TheMask,
  },
   props: [
        'contributions',
        'contype',
        'types',
        'retfunid',
        'urlcertification',
        'ulrzero',
        'urlavailable',
        'start',
        'end'
    ],
  data () {
    return {
        list_aportes:this.contributions,
        list_types: this.types,
        con_type: this.contype,
        last_date: this.end,
        first_date: this.start,
        comando: null,
        item0_con_aporte : null,
        item0_sin_aporte : null,
        bfs_con_aporte: null, //batallon de seguridad fisica (bsf)
        bfs_sin_aporte: null, //batallon de seguridad fisica (bsf)
        mayo_1976: null, //periodos antes de mayo
        certificacion_con_aporte: null, //periodos antes de mayo
        certificacion_sin_aporte: null, //periodos antes de mayo
        perdiodo_no_trabajado: null, //periodos antes de mayo
        disponibilidad: null,
        order_aportes: true,
        show_certification: false,
        modal: { first_date: null, last_date: null,contribution_type_id: null},
        row_higth: 0
    }
  },
  created: function () {
    
    let array_date = this.first_date.split('-'); 
    this.first_date = array_date[0]+'-'+array_date[1]+'-01';
    array_date = this.last_date.split('-'); 
    this.last_date = array_date[0]+'-'+array_date[1]+'-01';
    console.log(this.last_date);
    console.log('Revisando lista_aportes: ' + this.list_aportes.length)
    // console.log(this.urlcertification);
    console.log(this.types);
    this.comando = this.types.filter(function (type) {
        return type.name  == 'Período reconocido por comando';   
    })[0];
    this.item0_con_aporte =  this.types.filter(function (type) {
        return type.name  == 'Período en item 0 Con Aporte';   
    })[0];
    this.item0_sin_aporte =  this.types.filter(function (type) {
        return type.name  == 'Período en item 0 Sin Aporte';   
    })[0];
    this.bfs_con_aporte =  this.types.filter(function (type) {
        return type.name  == 'Período de Batallón de Seguridad Física Con Aporte';   
    })[0];
    this.bfs_sin_aporte =  this.types.filter(function (type) {
        return type.name  == 'Período de Batallón de Seguridad Física Sin Aporte';   
    })[0];
    this.mayo_1976 =  this.types.filter(function (type) {
        return type.name  == 'Periodos anteriores a Mayo de 1976 Sin Aporte';   
    })[0];
    this.certificacion_con_aporte =  this.types.filter(function (type) {
        return type.name  == 'Período Certificación Con Aporte';   
    })[0];
    this.certificacion_sin_aporte =  this.types.filter(function (type) {
        return type.name  == 'Período Certificación Sin Aporte';   
    })[0];
    this.perdiodo_no_trabajado =  this.types.filter(function (type) {
        return type.name  == 'Período no Trabajado';   
    })[0];
    this.disponibilidad = this.types.filter(function (type) {
        return type.name  == 'Disponibilidad';   
    })[0];
    console.log('revisando ids');
    console.log('comando '+this.comando.id);
    console.log('item0_con_aporte '+this.item0_con_aporte.id);
    console.log('item0_sin_aporte '+this.item0_sin_aporte.id);
    console.log('bfs_con_aporte '+this.bfs_con_aporte.id);
    console.log('bfs_sin_aporte '+this.bfs_sin_aporte.id);
    console.log('mayo_1976 '+this.mayo_1976.id);
    console.log('certificacion_con_aporte '+this.certificacion_con_aporte.id);
    console.log('certificacion_sin_aporte '+this.certificacion_sin_aporte.id);
    console.log('perdiodo_no_trabajado '+this.perdiodo_no_trabajado.id);
    console.log('disponibilidad '+this.disponibilidad.id);

    if(!this.con_type){
        this.show_certification =true;
    }

    // console.log(this.nh);
    let list_hdp= this.list_aportes;
    if(this.con_type){
        for (let i = 0; i < list_hdp.length; i++) {
       
            switch (list_hdp[i].breakdown_id) {

                    case 1:
                        list_hdp[i].breakdown_id = this.disponibilidad.id;
                        list_hdp[i].breakdown_name = this.disponibilidad.name;
                        break;

                    case 3:
                        list_hdp[i].breakdown_id = this.item0_con_aporte.id;
                        list_hdp[i].breakdown_name = this.item0_con_aporte.name;              
                    break;
            
                    default:
                        list_hdp[i].breakdown_id = this.comando.id;
                        list_hdp[i].breakdown_name = this.comando.name;
                    break;
            }   
            // console.log(list_hdp[i].breakdown_id);    
            // console.log(list_hdp[i].breakdown_name);    
        } 
        this.list_aportes = list_hdp;
    }
    console.log(this.con_type);
    console.log('fi');
    console.log(moment(this.first_date).toDate());
    console.log('ff');
    console.log(this.last_date);
    let fi = this.first_date;

    let ff = this.last_date;
    let year = null;
    let month = null;
    var list = [];
    let contribution = null;
    //llenado de datos
    while (moment(ff,'YYYY-MM-DD').toDate().getTime() >= moment(fi,'YYYY-MM-DD').toDate() ) {
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
        let month_str=''+month;
        if(month<10)
        {

            month_str = '0'+month;
        }
        ff= year+'-'+month_str+'-01';
        console.log(ff);
    }

    console.log(list);
    this.list_aportes = list;
    this.row_higth = 386/this.list_aportes.length;
    console.log('termino los procesos');

    // let string_date= "08/02/2013"
    let string_date= "2013-02-08";
    console.log(string_date);
    // let date = moment(string_date,"DD/MM/YYYY").toDate();
    let date = moment(string_date,"YYYY-MM-DD").toDate();
    // let date = new Date(string_date);
    console.log(date);    
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
    getStyle(breakdown_id,only_color){
        let style = 'display: block;width: 100%; height:'+this.row_higth+'px;';
         var color="cya";
        //  console.log(breakdown_id);
        switch (breakdown_id) {
            case this.disponibilidad.id:
                color="#aeda8a";
                break;
            case this.item0_con_aporte.id:
                color="#f7f097fd";
                break;
            case this.item0_sin_aporte.id:
                color="#f7a197fd";
                break;
            case this.comando.id:
                color="#ffffff";
                break;
            case this.bfs_con_aporte.id:
                color="#a1a7fffd";
                break;
            case this.bfs_sin_aporte.id:
                color="#b1e7faca";
                break;
            case this.mayo_1976.id:
                color="#e0ad7dfd";
                break;
            case this.certificacion_con_aporte.id:
                color="#80e9bdfd";
                break;
            case this.certificacion_sin_aporte.id:
                color="#50c1bdfa";
                break;
            case this.perdiodo_no_trabajado.id:
                color="#30c1edfb";
                break;
            case 0:
                color="#bbbaadfd";
                break;
            default: 
                console.log('no se encontro'+breakdown_id);
            break;
            
        }
        // console.log(style);
        if(only_color){
            console.log('color: '+color);
            return 'color:'+color;
        }else{
            return style+'background:'+color+';';
        }
    },
    getColor(breakdown_id)
    {
        var color="cya";
        switch (breakdown_id) {
            case this.disponibilidad.id:
                color="disponibilidad";
                break;
            case this.item0_con_aporte.id:
                color="item0_con_aporte";
                break;
            case this.item0_sin_aporte.id:
                color="item0_sin_aporte";
                break;
            case this.comando.id:
                color="comando";
                break;
            case this.bfs_con_aporte.id:
                color="bfs_con_aporte";
                break;
            case this.bfs_sin_aporte.id:
                color="bfs_sin_aporte";
                break;
            case this.mayo_1976.id:
                color="mayo_1976";
                break;
            case this.certificacion_con_aporte.id:
                color="certificacion_con_aporte";
                break;
            case this.certificacion_sin_aporte.id:
                color="certificacion_sin_aporte";
                break;
            case this.perdiodo_no_trabajado.id:
                color="perdiodo_no_trabajado";
                break;
            case 0:
                color="cym";
                break;
            default: 
                console.log('no se encontro color :'+breakdown_id);
            break;
            
        }
        return color;
    },
    getFormatDate(fecha)
    {
        // console.log(fecha);
        if(fecha){
            let str = fecha.split('-');
            return str[1]+' - '+parseInt(str[0]);
        }
        return fecha;
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
            let fi =moment('01/'+this.modal.first_date,"DD/MM/YYYY").toDate();

            let ff =moment('01/'+this.modal.last_date,"DD/MM/YYYY").toDate();
        
            let c_type_id = this.modal.contribution_type_id;
            let year = null;
            let month = null;
            let aporte_date = null;

            let c_type = this.types.filter(function (type) {
                return type.id  == c_type_id;   
            })[0];

            for (let i = 0; i < this.list_aportes.length; i++)
            {
                let aporte = this.list_aportes[i];
          
                aporte_date = moment(aporte.month_year,"YYYY-MM-DD").toDate();
               
                if(aporte_date.getTime() >= fi.getTime() && aporte_date.getTime() <= ff.getTime()  )
                {   
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
            if(new Date('01/'+this.modal.first_date).getTime() < new Date('01/'+this.first_date.month_year).getTime()  )
            {
                flash('Error: la fecha '+this.modal.first_date+' no debe ser menor a '+this.first_date.month_year ,'warning');
                response = false;
                console.log('Error: la fecha '+this.modal.first_date+' no debe ser menos a '+this.first_date.month_year);
            }
            if(new Date('01/'+this.modal.last_date).getTime() > new Date('01/'+this.last_date.month_year).getTime() )
            {
                flash('Error: la fecha '+this.modal.last_date+' no debe ser mayor a '+this.last_date.month_year ,'warning');
                response = false;
                console.log('Error: la fecha '+this.modal.last_date+' no debe ser mayor a '+this.last_date.month_year);
            }
            if(new Date('01/'+this.modal.first_date).getTime() > new Date('01/'+this.modal.last_date).getTime())
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
.disponibilidad {
 background: #aeda8a;   
}
.item0_con_aporte {
    background: #f7f097fd;
}
.item0_sin_aporte{
    background: #f7a197fd;
}
.comando {
    background: #ffffff;
}
.bfs_con_aporte {
    background: #a1a7fffd;
}
.bfs_sin_aporte {
    background: #b1e7faca;
}
.mayo_1976 {
    background: #e0ad7dfd;
}
.certificacion_con_aporte{
    background: #80e9bdfd;
}
.certificacion_sin_aporte{
    background: #50c1bdfa;
}
.perdiodo_no_trabajado{
    background: #30c1edfb;
}
.cym{
    background: #bbbaadfd;
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

</style>|