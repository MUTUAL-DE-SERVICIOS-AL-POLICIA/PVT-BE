<div class="col-lg-12">
    <div class="ibox">
        <div class="ibox-content">

            <div class="pull-left">
                <legend> Observaciones</legend>
            </div>
             <div class="text-right">

                {{--@can('create',Muserpol\Models\ObservationType::class)--}}
                
                <a href="" data-toggle="modal" data-target="#observationModal">
                <button class="btn btn-primary"  data-toggle="tooltip" data-placement="top" title="Adicionar observación">
                        <i class="fa fa-plus" style="font-size:15px"></i> Adicionar
                        </button>
                        <!-- <i class="fa fa-plus"> </i> Adicionar -->
                </a>
                {{--@else
                <br>
                @endcan--}}
            </div>
            <div class="row">
                @php
                    $number= 1;
                @endphp
                <div class="ibox-content table-responsive">
                    <table class="table table-hover table-sprite">
                        <thead>
                            <tr>
                            <th> Nro. </th>
                            <th> Fecha </th>
                            <th> Tipo de Observación </th>
                            <th> Mensaje </th>
                            <th> Estado </th>
                            <th colspan="2"> Opciones </th>
                            </tr>
                        </thead>
                            <tbody>
                                @foreach($observations as $observation )
                                    <tr>
                                        <td> {{ $number++ }} </td>
                                        <td> {{date("d/m/Y", strtotime($observation->pivot->date))}} </td>
                                        <td> {{ $observation->name }} </td>
                                        <td> {{ $observation->pivot->message }} </td>
                                        <td>
                                            <h3>
                                            @if($observation->pivot->enabled)
                                            <span class="label  label-primary">
                                            Subsanado
                                            </span>
                                            @else
                                            <span class="label label-danger">
                                            No Subsanado
                                            </span>
                                            @endif
                                            </h3>
                                        </td>
                                        <td>

                                        <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#editObservation"
                                            data-modid="{{ $observation->pivot->observable_id }}"
                                            data-id_obs="{{$observation->id}}"
                                            data-name_obs="{{$observation->name}}"
                                            data-enabled = "{{ $observation->pivot->enabled}}"
                                            data-message="{{ $observation->pivot->message }}">
                                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
                                            @can('delete',new Muserpol\Models\ObservationType)
                                        <button type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#eliminarObs" data-elim="{{ $observation->id}}"><i class="fa fa-trash" aria-hidden="true" ></i></button></td>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                    </table>
                </div>
            </div>

            <input type="checkbox" name="check" id="check" value="1" onchange="javascript:showContent()" /><label>Ver Observaciones Eliminadas</label>

            <div class="row" id="content" style="display: none;">
                @php
                    $number= 1;
                @endphp
                <div class="ibox-content table-responsive">
                    <table class="table table-hover table-sprite">
                        <thead>
                            <tr>
                            <th> Nro. </th>
                            <th> Fecha </th>
                            <th> Tipo de Observación </th>
                            <th> Mensaje </th>
                            <th> Estado </th>
                            <th> Fecha de eliminación </th>
                            </tr>
                        </thead>
                            <tbody>
                                @foreach($observations_delete as $observation )
                                    <tr>
                                        <td> {{ $number++ }} </td>
                                        <td> {{date("d/m/Y", strtotime($observation->pivot->date))}}  </td>
                                        <td> {{ $observation->name }} </td>
                                        <td> {{ $observation->pivot->message }} </td>
                                        <td>
                                            <h3>
                                            @if($observation->pivot->enabled)
                                            <span class="label  label-primary">
                                            Subsanado
                                            </span>
                                            @else
                                            <span class="label label-danger">
                                            No Subsanado
                                            </span>
                                            @endif
                                            </h3>
                                        </td>
                                        <td> {{date("d/m/Y", strtotime($observation->pivot->deleted_at))}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::open(['action' => 'RetirementFundObservationController@store']) !!} 
<div class="modal inmodal" id="observationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <i class="fa fa-warning modal-icon"></i>
                <h4 class="modal-title">Observación</h4>
                <small class="font-bold">crear una observación al Trámite</small>
            </div>
            <div class="modal-body">
               <div class="row">
                   <input type="hidden" name="retirement_fund_id" value="{{ $retirement_fund->id}}">
                   <div class="col-md-4 text-right">
                       <label>Tipo de Observación:</label>
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
                            <label> <input type="radio" checked="" value="false"  name="enabled"> No Subsanado </label> &nbsp;&nbsp;
                            <label> <input type="radio" value="true" name="enabled"> Subsanado </label>
                    </div>
               </div>
               <br>
               <div class="row">
                    <div class="col-md-4 text-right" >
                       <label> Mensaje:</label>
                    </div>
                    <div class="col-md-8">
                        <textarea class="form-control $errors->has('message') ? 'error' : '' " name="message"></textarea>
                        @if ($errors->has('message'))
                        <div class="error">
                            $errors->first('message') 
                        </div>
                        @endif
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


<form class="form-horizontal" action="{{route('retFuneditObservation')}}" method="POST">
    <input type="hidden" name="_method" value="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="retirement_fund_id" value="{{ $retirement_fund->id}}">
<div class="modal inmodal" id="editObservation" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                <i class="fa fa-warning modal-icon"></i>
                <h4 class="modal-title" id="exampleModalLabel">Editar Observación</h4>
                <small class="font-bold">Editar la observación al Trámite</small>
            </div>
            <div class="modal-body">
            <input name="observation_type_id" type="hidden" id="id_obs" value="id_obs">
                <div class="form-group"><label>Obsevación: </label>
                <input name="name_obs" id="name_obs" type="text" class="form-control" disabled>
                </div>

                <div class="form-group"><label>Estado: </label>
                    <div class="toggle">
                            <label><input type="radio" name="enable"  id="no_enable" value="no_enable"><span>No Subsanado</span></label>
                    </div>
                    <div class="toggle">
                        <label><input type="radio" name="enable" id="is_enable" value="is_enable"><span>Subsanado</span></label>
                    </div>
                </div>
                <div class="form-group"><label>Mensaje: </label> 
                </div>
                <div class="form-group">
                        <textarea class="form-control" name="message" id="message" type="text" placeholder="Mensaje" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
</form>

<form class="form-horizontal" action="{{route('retFundeleteObservation')}}" method="POST">
    <input type="hidden" name="_method" value="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="retirement_fund_id" value="{{ $retirement_fund->id}}">
    <div id="eliminarObs" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">¿Estás seguro?</h4>
                    <input name="id_observation" type="hidden" class="form-control" id="id_observation">
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</form>
