<div class="row">
    <div class="col-md-12">
        <contribution-commitment :commitment="{{$aid_commitment}}" :affiliate_id="{{$affiliate_id}}" :today_date="{{json_encode($today_date)}}" inline-template> 
                <div class="col-lg-12">
                    <div class="panel panel-primary" :class="show_spinner ? 'sk-loading' : ''">
                        <div class="panel-heading">
                            <h3 class="pull-left">Compromiso de pago</h3>
                            @can('update',new Muserpol\Models\Contribution\Contribution)
                            <div class="text-right">
                                <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing"><i class="fa" :class="editing ?'fa-unlock':'fa-lock'" ></i> </button>
                            </div>
                            @else
                            <br>
                            @endcan
                        </div>
                                                
                        <div class="panel-body " v-if="! editing" >                            
                            <div class="col-md-12" v-if="! create && commitment.id != 0">
                            <div class="col-md-6">
                                <dl class="dl-">                                    
                                    <dt>Tipo:</dt> <dd>@{{ commitment.commitment_type }}</dd>
                                    <dt>Memorandum:</dt> <dd>@{{ commitment.number }}</dd>
                                    <dt>Fecha:</dt> <dd>@{{ commitment.commision_date }}</dd>
                                    <dt>Destino:</dt> <dd>@{{ commitment.destination }}</dd>                                     
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="dl-">
                                    <dt>Fecha de compromiso:</dt> <dd>@{{ commitment.commitment_date }}</dd>
                                    <dt>Estado:</dt> <dd>@{{ commitment.state }}</dd>
                                    <dt>Imprimir:</dt> <dd> 
                                    <button data-animation="flip" class="btn btn-primary" @click="print_commitment"><i class="fa fa-print"></i> </button>
                                    </dd>                                    
                                </dl>
                            </div>
                        </div>
                            <div class="col-md-12" v-else>
                                <button data-animation="flip" class="btn btn-primary" @click="create_new"><i class="fa fa-chevron-down"></i>Crear nuevo</button>
                            </div>
                            
                        </div>
                        
                        <div class="panel-body" v-else>
                        @can('update', new Muserpol\Models\Contribution\Contribution)
                            <div class="sk-folding-cube" v-show="show_spinner" >
                                <div class="sk-cube1 sk-cube"></div>
                                <div class="sk-cube2 sk-cube"></div>
                                <div class="sk-cube4 sk-cube"></div>
                                <div class="sk-cube3 sk-cube"></div>
                            </div>
                     
                            <div class="col-md-12">
                                <!--<div class="col-md-12" v-if="! create" >-->
                                    <div class="col-md-6">
                                        <dl class="dl-">                                    
                                            <dt>Aportante:</dt> 
                                                <dd>
                                                    <select class="form-control m-b" v-model='commitment.commitment_type'>                                            
                                                        <option value="TITULAR">Titular</option>
                                                        <option value="ESPOSA">Esposa</option>
                                                        <option value="CONYUGE">Cónyuge</option>
                                                    </select>                                                                                
                                                </dd>
                                            <dt>Declaración de Pensión:</dt> <dd><input type="text" v-model="commitment.number" class="form-control"></dd>
                                            <dt>Fecha de Declaración (AAAA-MM-DD):</dt> <dd><input type="text" v-model="commitment.commision_date"  class="form-control"></dd>
                                            <dt>Fecha de compromiso:</dt> <dd><input type="text" v-model="commitment.commitment_date"  class="form-control"></dd>
                                        </dl>   
                                    </div>
                                    <div class="col-md-6"  v-if=" commitment.id != 0">                               
                                        <dl class="dl-">                                            
                                            <dt>Estado:</dt> <dd>
                                                <button data-animation="flip" class="btn btn-primary" @click="update(-1)"><i class="fa fa-chevron-down"></i>Dar de baja </button>
                                            </dd>
                                            <dt>Imprimir:</dt> <dd>                                         
                                            </dd>                                    
                                        </dl>                                
                                    </div>
                                    <div v-else></div>
                                <!--</div>-->
<!--                                <div class="col-md-12" v-else>
                                    <button data-animation="flip" class="btn btn-primary" @click="create_new"><i class="fa fa-chevron-down"></i>Crear nuevo2</button>
                                </div>-->
                            </div>                            
                        @endcan
                        </div>
                        
                        <hr>                      
                    <div v-show="editing" class="panel-footer">
                        <div class="text-center">
                            <button class="btn btn-danger" type="button" @click="toggle_editing()"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span class="bold">Cancelar</span></button>
                            <button class="btn btn-primary" type="button" @click="update(affiliate_id)"><i class="fa fa-check-circle" onClick="window.location.reload()"></i>Guardar</button>
                            
                        {{--      <td> <button class="btn btn-success btn-circle" onClick="window.location.reload()" type="button"><i class="fa fa-link"></i></button></td>                            
                          --}}</div>
                    </div>
                </div>
            </div>
        </contribution-commitment> 
    </div>
</div>