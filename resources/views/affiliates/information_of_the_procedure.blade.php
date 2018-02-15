<div class="col-lg-6">
    <div class="panel panel-primary">       
        <div class="panel-body"> 
            <div class="box box-success box-solid">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li><a href="#tab_1" data-toggle="tab" title="Fondo de Retiro">&nbsp;<i class="glyphicon glyphicon-piggy-bank" aria-hidden="true"></i>&nbsp;</a></li>
                        <li><a href="#tab_2" data-toggle="tab" title="Cuota Mortuorio">&nbsp;<i class="fa fa-fw fa-heartbeat fa-lg" aria-hidden="true"></i>&nbsp;</a></li>
                        <li><a href="#tab_3" data-toggle="tab" title="Auxilio Mortuorio">&nbsp;<i class="fa fa-fw fa-heartbeat fa-lg" aria-hidden="true"></i>&nbsp;</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <h3 class="box-title">Fondo de Retiro</h3>    
                            @if($retirement_fund!=null)
                                <dl class="dl-horizontal">
                                    <dt>Codigo:</dt><dd>{{ $retirement_fund->code }}</dd>
                                    <dt>Fecha de recepcion:</dt><dd>{{ $retirement_fund->reception_date }}</dd>
                                    <dt>Ciudad inicio Tramite:</dt><dd>{{ $retirement_fund->city_start->name }}</dd>
                                    <dt>Modalidad:</dt><dd>{{ $retirement_fund->procedure_modality->name }}</dd>
                                    <dt>Tipo:</dt><dd>{{ $retirement_fund->type }}</dd>
                                    <dt>Subtotal:</dt><dd>{{ $retirement_fund->subtotal }}</dd>
                                    <dt>Total:</dt><dd>{{ $retirement_fund->total }}</dd>
                                    <dt>Ciudad de Pago:</dt><dd>{{ $retirement_fund->city_end->name }}</dd>
                                </dl>
                                    
                                <center><p><a class="btn btn-primary" href="/ret_fun/{{ $retirement_fund->affiliate_id }}" role="button">Ver</a></p></center>
                            @else        
                                <div class="row text-center">
                                    <i class="fa  fa-list-alt fa-3x  fa-border" aria-hidden="true"></i>
                                    <h4 class="box-title">No hay registros de Fondo de Retiro</h4>
                                </div> 
                            @endif                      
                        </div>
                        <div class="tab-pane" id="tab_2">
                            <h3 class="box-title">Cuota Mortuoria</h3>
                            
                            @if($cuota!=null)
                                <dl class="dl-horizontal">   
                                    <dt>Codigo:</dt> <dd>{{ $cuota->code }}</dd>
                                    <dt>Fecha de recepcion:</dt><dd>{{ $cuota->reception_date }}</dd>
                                    <dt>Ciudad inicio Tramite:</dt><dd>{{ $cuota->city_start->name }}</dd>
                                    <dt>Modalidad:</dt><dd>{{ $cuota->procedure_modality->name }}</dd>
                                    <dt>Tipo:</dt><dd>{{ $cuota->type }}</dd>
                                    <dt>Subtotal:</dt><dd>{{ $cuota->subtotal }}</dd>
                                    <dt>Total:</dt><dd>{{ $cuota->total }}</dd>
                                    <dt>Ciudad de Pago:</dt><dd>{{ $cuota->city_end->name }}</dd>                                  
                                </dl>
                                <center><p><a class="btn btn-primary" href="/quota_aid/{{ $cuota->affiliate_id }}" role="button">Ver</a></p></center>
                            @else
                                <div class="row text-center">
                                    <i class="fa  fa-list-alt fa-3x  fa-border" aria-hidden="true"></i>
                                    <h4 class="box-title">No hay registros de Cuota Mortuorio</h4>
                                </div>
                            @endif           
                        </div>
                        <div class="tab-pane" id="tab_3">
                            <h3 class="box-title">Auxilio Mortuorio</h3>
                            @if($auxilio!=null)
                           
                                <dl class="dl-horizontal">     
                                    <dt>Codigo:</dt> <dd>{{ $auxilio->code }}</dd>
                                    <dt>Fecha de recepcion:</dt><dd>{{ $auxilio->reception_date }}</dd>
                                    <dt>Ciudad inicio Tramite:</dt><dd>{{ $auxilio->city_start->name }}</dd>
                                    <dt>Modalidad:</dt><dd>{{ $auxilio->procedure_modality->name }}</dd>
                                    <dt>Tipo:</dt><dd>{{ $auxilio->type }}</dd>
                                    <dt>Subtotal:</dt><dd>{{ $auxilio->subtotal }}</dd>
                                    <dt>Total:</dt><dd>{{ $auxilio->total }}</dd>
                                    <dt>Ciudad de Pago:</dt><dd>{{ $auxilio->city_end->name }}</dd>
                               </dl>
                               <center><p><a class="btn btn-primary" href="/quota_aid/{{ $auxilio->affiliate_id }}"role="button">Ver</a></p></center>
                            @else   
                                <div class="row text-center">
                                    <i class="fa  fa-list-alt fa-3x  fa-border" aria-hidden="true"></i>
                                    <h4 class="box-title">No hay registros de Auxilio Mortuorio</h4>
                                </div>
                            @endif          
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</div> 