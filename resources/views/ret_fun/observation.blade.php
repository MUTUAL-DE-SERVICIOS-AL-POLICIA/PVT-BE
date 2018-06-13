<div class="col-lg-12">
    <div class="panel-group" id="accordion">
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseObservation">Observaciones</a>
                    <div class="pull-right">
                        <button typer="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#observationModal"> <i class="fa fa-plus"></i></button>
                    </div>
                </h3>
            </div>
            <div id="collapseObservation" class="panel-collapse collapse in">
                <table class="table table-hover table-sprite">
                    <thead>
                        <tr>
                            <th> Fecha </th>
                            <th> Tipo de Observación </th>
                            <th> Descripción </th>
                            <th> Estado </th>
                            <th> Opciones </th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($observations as $observation )
                    <tr>
                        <td> {{ $observation->date }} </td>
                        <td> {{ $observation->observation_type->name }} </td>
                        <td> {{ $observation->message }} </td>
                        <td>
                            <h3>
                            @if($observation->is_enabled)
                            <span class="label  label-primary">
                                Subsanado
                            </span>    
                            @else
                            <span class="label label-danger">
                                Vigente
                            </span>    
                            @endif
                            </h3> 
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#eliminar" data-elim="{{ $observation->id }}"><i class="fa fa-trash" aria-hidden="true" ></i></button>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#eliminar" data-elim="{{ $observation->id }}"><i class="fa fa-pencil" aria-hidden="true" ></i></button>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseObservationDeleted">Observaciones Eliminadas</a>
                </h4>
            </div>
            <div id="collapseObservationDeleted" class="panel-collapse collapse">
                <table class="table table-hover table-sprite">
                    <thead>
                        <tr>
                            <th> Fecha </th>
                            <th> Tipo de Observación </th>
                            <th> Descripción </th>
                            <th> Estado </th>
                            <th> Opciones </th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($observations as $observation )
                    <tr>
                        <td> {{ $observation->date }} </td>
                        <td> {{ $observation->observation_type->name }} </td>
                        <td> {{ $observation->message }} </td>
                        <td>
                            <h3>
                            @if($observation->is_enabled)
                            <span class="label  label-primary">
                                Subsanado
                            </span>    
                            @else
                            <span class="label label-danger">
                                Vigente
                            </span>    
                            @endif
                            </h3> 
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#observationModal" data-elim="{{ $observation->id }}"><i class="fa fa-trash" aria-hidden="true" ></i></button>
                            <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#observationModal" data-elim="{{ $observation->id }}"><i class="fa fa-pencil" aria-hidden="true" ></i></button>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div> <!-- fin XD !-->
{!! Form::open(['action' => 'RetirementFundObservationController@store']) !!}
<div class="modal inmodal" id="observationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <i class="fa fa-warning modal-icon"></i>
                <h4 class="modal-title">Observacion</h4>
                <small class="font-bold">crear una observacion al Trámite</small>
            </div>
            <div class="modal-body">
               <div class="row">
                   <input type="hidden" name="retirement_fund_id" value="{{ $retirement_fund->id}}">
                   <div class="col-md-4 text-right">
                       <label>Tipo de Observacion:</label>
                   </div>
                   <div class="col-md-8">
                       <select class="form-control" name="observation_type_id">
                            @foreach($observation_types as $observation)
                            <option value="{{ $observation->id }}"> {{ $observation->name }}</option>
                            @endforeach
                        </select>
                   </div>
               </div>
               <br>
               <div class="row">
                    <div class="col-md-4 text-right">
                        <label>Estado:</label>
                    </div>
                    <div class="col-md-8">
                            <label> <input type="radio" checked="" value="false"  name="is_enabled"> Vigente </label> &nbsp;&nbsp;
                            <label> <input type="radio" value="true" name="is_enabled"> Subsanado </label>
                    </div>
               </div>
               <br>
               <div class="row">
                    <div class="col-md-4 text-right">
                       <label> Descripción:</label>
                    </div>
                    <div class="col-md-8">
                        <textarea class="form-control" name="message"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="text-center">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
