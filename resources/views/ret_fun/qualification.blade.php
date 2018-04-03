@extends('layouts.app') 
@section('title', 'Fondo de Retiro') 
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {!!Breadcrumbs::render('show_qualification_retirement_fund', $retirement_fund)!!}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <ret-fun-qualification inline-template :dates-contributions="{{json_encode($dates_contributions)}}" :dates-availability="{{json_encode($dates_availability)}}"
            :dates-item-zero="{{json_encode($dates_item_zero)}}" :retirement-fund-id="{{$retirement_fund->id}}">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>Aportes y periodos considerados</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                            <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                        </div>
                    </div>
                    <div class="ibox-content" style="">
                        <div class="form-group" id="data_5">
                            <div class="col-md-4">
                                <h4>Aportes fondo de retiro Policial Solidario </h4>
                            </div>
                            <ret-fun-qualification-group :dates-availability-child="datesContributions" @total="calculate">
                            </ret-fun-qualification-group>
                        </div>
                        <div class="form-group" id="data_5">
                            <div class="col-md-4">
                                <h4>Destino en Letras de Disponibilidad</h4>
                            </div>
                            <ret-fun-qualification-group :dates-availability-child="datesAvailability" @total="calculate">
                            </ret-fun-qualification-group>
                        </div>
                        <div class="form-group" id="data_5">
                            <div class="col-md-4">
                                <h4>Periodo de Aportes Item 0</h4>
                            </div>
                            <ret-fun-qualification-group :dates-availability-child="datesItemZero" @total="calculate">
                            </ret-fun-qualification-group>
                        </div>
                        <div class="panel panel-success">
                            <strong>Anios</strong> @{{this.years}}
                            <strong>Meses</strong> @{{this.months}}
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Años</th>
                                    <th>Meses</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Años de servicio segun certificacion del comando general de la policia</td>
                                    <td>@{{ yearsContributions }}</td>
                                    <td>@{{ monthsContributions }}</td>
                                </tr>
                                <tr>
                                    <td>Cantidad de Aportes de Item "0"</td>
                                    <td>@{{ yearsItemZero }}</td>
                                    <td>@{{ monthsItemZero }}</td>
                                </tr>
                                <tr>
                                    <td>Aportes anteriores a mayo de 1976</td>
                                    <td>@{{ yearsItemZero }}</td>
                                    <td>@{{ monthsItemZero }}</td>
                                </tr>
                                <tr>
                                    <td>Periodos en Disponibilidad</td>
                                    <td>@{{ yearsAvailability }}</td>
                                    <td>@{{ monthsAvailability }}</td>
                                </tr>
                                <tr>
                                    <td>CAS</td>
                                    <td>@{{ yearsAvailability }}</td>
                                    <td>@{{ monthsAvailability }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total de Calificacion para Calificacion</strong></td>
                                    <td><strong>@{{ years }}</strong></td>
                                    <td><strong>@{{ months }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="btn btn-primary" @click="save()"><i class="fa fa-save"></i> Guardar</button>
                    </div>
                </div>
                <div class="ibox" v-if="showEconomicData" :class="showEconomicData ? 'fadeInRight' :''">
                    <div class="ibox-title">
                        <h5>Datos Economicos</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            <a class="close-link"><i class="fa fa-times"></i></a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>foo</th>
                                    <th>bar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Ultimo Sueldo Percibido</td>
                                    <td>Ultimo Sueldo Percibido</td>
                                </tr>
                                <tr>
                                    <td>Salario Promedio Cotizable</td>
                                </tr>
                                <tr>
                                    <td>Densidad Total de Cotizaciones</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </ret-fun-qualification>
    </div>
</div>
@endsection