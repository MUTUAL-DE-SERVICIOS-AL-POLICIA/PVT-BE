<div class="row">
    <div class="col-md-12">
        <contribution-aid-commitment :aid_commitment="{{$aid_commitment}}" :affiliate_id="{{$affiliate_id}}" :today_date="{{json_encode($today_date)}}" inline-template> 
                <div class="col-lg-12">
                    <div class="panel panel-primary" :class="show_spinner ? 'sk-loading' : ''">
                        <div class="panel-heading">
                            <h3 class="pull-left">Compromiso de pago</h3>
                            {{-- @can('update',new Muserpol\Models\Contribution\AidCommitment) --}}
                            <div class="text-right">
                                <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing"><i class="fa" :class="editing ?'fa-unlock':'fa-lock'" ></i> </button>
                            </div>
                            {{-- @else --}}
                            {{-- <br> --}}
                            {{-- @endcan --}}
                        </div>
                                                
                        <div class="panel-body " v-if="! editing" >                            
                            <div class="col-md-12" v-if="! create && aid_commitment.id != 0">
                            <div class="col-md-6">
                                <dl class="dl-">                                    
                                    <dt>Aportante:</dt> <dd>@{{ get_contributor() }}</dd>
                                    <dt>Declaración de Pensión:</dt> <dd>@{{ aid_commitment.pension_declaration }}</dd>
                                    <dt>Fecha de Declaración:</dt> <dd>@{{ aid_commitment.pension_declaration_date | getFormatDate}}</dd>                                    
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl class="dl-">
                                    <dt>Fecha de compromiso:</dt> <dd>@{{ aid_commitment.date_commitment | getFormatDate }}</dd>
                                    <dt>Estado:</dt> <dd>@{{ aid_commitment.state }}</dd>
                                    <dt>Imprimir:</dt> <dd> 
                                    <button data-animation="flip" class="btn btn-primary" @click="print_aid_commitment"><i class="fa fa-print"></i> </button>
                                    </dd>                                    
                                </dl>
                            </div>
                        </div>
                            <div class="col-md-12" v-else>
                                <button data-animation="flip" class="btn btn-primary" @click="create_new"><i class="fa fa-chevron-down"></i>Crear nuevo</button>
                            </div>
                            
                        </div>
                        
                        <div class="panel-body" v-else>
                        {{-- @can('update', new Muserpol\Models\Contribution\AidContribution) --}}
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
                                                    <select class="form-control m-b" v-model='aid_commitment.contributor'>                                            
                                                        <option value="T">Titular</option>                                                        
                                                        <option value="C">Cónyuge</option>
                                                    </select>                                                                                
                                                </dd>
                                            <dt>Declaración de Pensión:</dt> <dd><input type="text" v-model="aid_commitment.pension_declaration" class="form-control"></dd>
                                            <dt>Fecha de Declaración:</dt> <dd><input type="date" v-model="aid_commitment.pension_declaration_date"  class="form-control"></dd>
                                            <dt>Fecha de compromiso:</dt> <dd><input type="date" v-model="aid_commitment.date_commitment"  class="form-control"></dd>
                                        </dl>   
                                    </div>
                                    <div class="col-md-6"  v-if=" aid_commitment.id != 0">                               
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
                        {{-- @endcan --}}
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
        </contribution-aid-commitment> 
    </div>
</div>