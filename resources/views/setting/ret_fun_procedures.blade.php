<div class="row">
    <div class="col-md-12">
        <ret-fun-procedure :ret_fun_procedure="{{ $ret_fun_procedure }}" :original_procedure="{{ $ret_fun_procedure }}" inline-template> 
                <div class="col-lg-12">
                    <div class="panel panel-primary" :class="show_spinner ? 'sk-loading' : ''">
                        <div class="panel-heading">
                            <h3 class="pull-left">Fondo de Retiro</h3>
                            <div class="text-right">
                                <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing"><i class="fa" :class="editing ?'fa-unlock':'fa-lock'" ></i> </button>
                            </div>
                        </div>                                                

                        <div class="panel-body " v-if="! editing " >                            
                            <div class="col-md-12">
                                <dl class="dl-">                                    
                                    <dt>Porcentaje de Ganancia Anual:</dt> <dd>@{{ ret_fun_procedure.annual_yield }}</dd>
                                    <dt>Porcentaje de Gastos Administrativos:</dt> <dd>@{{ ret_fun_procedure.administrative_expenses }}</dd>
                                    <dt>N&uacute;mero de Contribuciones cotizables:</dt> <dd>@{{ ret_fun_procedure.contributions_number }}</dd>
                                </dl>
                            </div>                           
                        </div>
                        <div class="panel-body" v-else>
                            <div class="sk-folding-cube" v-show="show_spinner" >
                                <div class="sk-cube1 sk-cube"></div>
                                <div class="sk-cube2 sk-cube"></div>
                                <div class="sk-cube4 sk-cube"></div>
                                <div class="sk-cube3 sk-cube"></div>
                            </div>
                     
                            <div class="col-md-6">
                                <dl class="dl-">                                   
                                    <dt>Porcentaje de Ganancia Anual:</dt> <dd><input type="text" v-model="procedure.annual_yield" class="form-control"></dd>
                                    <dt>Porcentaje de Gastos Administrativos:</dt> <dd><input type="text" v-model="procedure.administrative_expenses" class="form-control"></dd>
                                    <dt>N&uacute;mero de Contribuciones cotizables:</dt> <dd><input type="text" v-model="procedure.contributions_number" class="form-control"></dd>
                                </dl>
                            </div>                          
                        </div>
                        <hr>
                      
                        <div v-show="editing" class="panel-footer">
                            <div class="text-center">
                                <button class="btn btn-danger" type="button" @click="toggle_editing()"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span class="bold">Cancelar</span></button>
                                <button class="btn btn-primary" type="button" @click="update"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
                            </div>
                        </div>
                    </div>
                </div>
        </ret-fun-procedure> 
    </div>
</div>
