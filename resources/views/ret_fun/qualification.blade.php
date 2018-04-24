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
        <ret-fun-qualification inline-template
            :dates-contributions="{{json_encode($dates_contributions)}}"
            :dates-availability="{{json_encode($dates_availability)}}"
            :dates-item-zero="{{json_encode($dates_item_zero)}}"
            :dates-security-battalion="{{json_encode($dates_security_battalion)}}"
            :dates-no-records="{{json_encode($dates_no_records)}}"
            :dates-cas="{{json_encode($dates_cas)}}"
            :retirement-fund-id="{{$retirement_fund->id}}">
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
                        <div class="form-group" v-if="datesContributions.length > 0">
                            <div class="col-md-4">
                                <h4>Aportes fondo de retiro Policial Solidario </h4>
                            </div>
                            <ret-fun-qualification-group :dates-child="datesContributions" @total="calculate">
                            </ret-fun-qualification-group>
                        </div>
                        <div class="form-group" v-if="datesAvailability.length > 0">
                            <div class="col-md-4">
                                <h4>Destino en Letras de Disponibilidad</h4>
                            </div>
                            <ret-fun-qualification-group :dates-child="datesAvailability" @total="calculate">
                            </ret-fun-qualification-group>
                        </div>
                        <div class="form-group" v-if="datesItemZero.length > 0">
                            <div class="col-md-4">
                                <h4>Periodo de Aportes Item 0</h4>
                            </div>
                            <ret-fun-qualification-group :dates-child="datesItemZero" @total="calculate">
                            </ret-fun-qualification-group>
                        </div>
                        <div class="form-group" v-if="datesSecurityBattalion.length > 0">
                            <div class="col-md-4">
                                <h4>Periodo de Aportes Batallon de Seguridad Fisica</h4>
                            </div>
                            <ret-fun-qualification-group :dates-child ="datesSecurityBattalion" @total="calculate">
                            </ret-fun-qualification-group>
                        </div>
                        <div class="form-group" v-if="datesCas.length > 0">
                            <div class="col-md-4">
                                <h4>Periodo de Aportes segun CAS</h4>
                            </div>
                            <ret-fun-qualification-group :dates-child="datesCas" @total="calculate">
                            </ret-fun-qualification-group>
                        </div>
                        <div class="form-group" v-if="datesNoRecords.length > 0">
                            <div class="col-md-4">
                                <h4>No hay registro</h4>
                            </div>
                            <ret-fun-qualification-group :dates-child="datesNoRecords" @total="calculate">
                            </ret-fun-qualification-group>
                        </div>
                        <div class="panel panel-success">
                            <strong>Años</strong> @{{this.years}}
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
                                <tr v-if="datesContributions.length > 0">
                                    <td>Años de servicio segun certificacion del comando general de la policia</td>
                                    <td>@{{ yearsContributions }}</td>
                                    <td>@{{ monthsContributions }}</td>
                                </tr>
                                <tr v-if="datesAvailability.length > 0">
                                    <td>Periodos en Disponibilidad</td>
                                    <td>@{{ yearsAvailability }}</td>
                                    <td>@{{ monthsAvailability }}</td>
                                </tr>
                                <tr v-if="datesItemZero.length > 0">
                                    <td>Cantidad de Aportes de Item "0"</td>
                                    <td>@{{ yearsItemZero }}</td>
                                    <td>@{{ monthsItemZero }}</td>
                                </tr>
                                <tr v-if="datesSecurityBattalion.length > 0">
                                    <td>Cantidad de Aportes Batallon de Seguirdad Fisica</td>
                                    <td>@{{ yearsSecurityBattalion }}</td>
                                    <td>@{{ monthsSecurityBattalion }}</td>
                                </tr>
                                <tr v-if="datesNoRecords.cas > 0">
                                    <td>CAS</td>
                                    <td>@{{ yearsCas }}</td>
                                    <td>@{{ monthsCas }}</td>
                                </tr>
                                <tr v-if="datesNoRecords.length > 0">
                                    <td>No hay registros</td>
                                    <td>@{{ yearsNoRecords }}</td>
                                    <td>@{{ monthsNoRecords }}</td>
                                </tr>
                                <tr>
                                    <td>Aportes anteriores a mayo de 1976</td>
                                    {{-- <td>@{{ yearsItemZero }}</td>
                                    <td>@{{ monthsItemZero }}</td> --}}
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><strong>Total de cotizaciones para Calificacion</strong></td>
                                    <td><strong>@{{ years }}</strong></td>
                                    <td><strong>@{{ months }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="btn btn-primary" @click="save()"><i class="fa fa-save"></i> Guardar</button>
                    </div>
                </div>
                <div v-if="showEconomicData">
                    <div class="ibox" id="showEconomicData1" class="fadeInRight">
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
                                        <td>@{{ totalAverageSalaryQuotableAnimated | currency }}</td>
                                    </tr>
                                    <tr>
                                        <td>Densidad Total de Cotizaciones</td>
                                        <td>@{{ totalQuotesAnimated }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <button class="btn btn-primary" type="submit" @click="saveAverageQuotable"><i class="fa fa-save"></i> Guardar</button>
                        </div>
                    </div>
                    <div v-show="showEconomicDataTotal">
                        <div class="ibox" class="fadeInRight">
                            <div class="ibox-title">
                                <h5>Datos Economicos Total</h5>
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
                                            <td>Sub Total fondo de retiro</td>
                                            <td>@{{ subTotalRetFun | currency }}</td>
                                        </tr>
                                        <tr>
                                            <td>Anticipo Fondo de Retiro</td>
                                            <td><input type="text" v-model="advancePayment" data-money='true'></td>
                                        </tr>
                                        <tr>
                                            <td>% de Anticipo Fondo de Retiro</td>
                                            <td>@{{ percentageAdvancePayment | percentage }}</td>
                                        </tr>
                                        <tr>
                                            <td>Retencion para pago de prestamo</td>
                                            <td><input type="text" v-model="retentionLoanPayment" data-money='true'></td>
                                        </tr>
                                        <tr>
                                            <td>% de Retencion para pago de prestamo</td>
                                            <td>@{{ percentageRetentionLoanPayment | percentage }}</td>
                                        </tr>
                                        <tr>
                                            <td>Retencion para garantes</td>
                                            <td><input type="text" v-model="retentionGuarantor" data-money='true'></td>
                                        </tr>
                                        <tr>
                                            <td>% de Retencion para garantes</td>
                                            <td>@{{ percentageRetentionGuarantor | percentage }}</td>
                                        </tr>
                                        <tr class="success">
                                            <td>Total fondo de retiro</td>
                                            <td><strong>@{{ totalAnimated | currency }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button class="btn btn-primary" type="submit" @click="saveTotalRetFun"><i class="fa fa-save"></i> Guardar</button>
                            </div>
                        </div>
                        <div v-if="showPercentagesRetFun">
                            <div class="ibox" class="fadeInRight">
                                <div class="ibox-title">
                                    <h5>Calculo de las cuotas partes para los derechohabientes</h5>
                                    <div class="ibox-tools">
                                        <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                        <a class="close-link"><i class="fa fa-times"></i></a>
                                    </div>
                                </div>
                                <div class="ibox-content">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>NOMBRE DEL DERECHOHABIENTE</th>
                                                <th>% DE ASIGNACION</th>
                                                <th>MONTO</th>
                                                <th>PARENTESCO</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th>@{{ totalPercentageRetFun }}</th>
                                                <th>@{{ totalAmountRetFun | currency }}</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <tr v-for="(beneficiary, index) in beneficiaries" :key="index">
                                                <td>@{{ beneficiary.first_name }}</td>
                                                <td><input type="number" step="0.01" v-model="beneficiary.temp_percentage" @change="requalificationTotal(index)"></td>
                                                <td><input type="number" step="0.01" v-model="beneficiary.temp_amount"></td>
                                                <td>@{{ beneficiary.kinship.name }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <button class="btn btn-primary" type="submit" @click="savePercentages"><i class="fa fa-save"></i> Guardar</button>
                                </div>
                            </div>
                            <div v-if="hasAvailability">
                                <div class="ibox" class="fadeInRight">
                                    <div class="ibox-title">
                                        <h5>Devolucion de aportes en disponibilidad</h5>
                                        <div class="ibox-tools">
                                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                            <a class="close-link"><i class="fa fa-times"></i></a>
                                        </div>
                                    </div>
                                    <div class="ibox-content">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Foobar</th>
                                                    <th>baz</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Total aportes en disponibilidad</td>
                                                    <td>@{{ subTotalAvailability }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Con rendimiento del X% Anual</td>
                                                    <td>@{{ totalAnnualYield | currency }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Devolucion de aportes en disponibilidad</td>
                                                    <td>@{{ totalAvailability | currency}}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2"></td>
                                                </tr>
                                                <tr v-for="(discount, index) in arrayDiscounts">
                                                    <td>@{{ discount.name }}</td>
                                                    <td>@{{ discount.amount }}</td>
                                                </tr>
                                                <tr class="success">
                                                    <td>@{{ arrayDiscounts[arrayDiscounts.length-1].name }}</td>
                                                    <td>@{{ arrayDiscounts[arrayDiscounts.length-1].amount }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="ibox" class="fadeInRight">
                                    <div class="ibox-title">
                                        <h5>Calculo de las cuotas partes para los derechohabientes</h5>
                                        <div class="ibox-tools">
                                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                            <a class="close-link"><i class="fa fa-times"></i></a>
                                        </div>
                                    </div>
                                    <div class="ibox-content">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>NOMBRE DEL DERECHOHABIENTE</th>
                                                    <th>% DE ASIGNACION</th>
                                                    <th>MONTO</th>
                                                    <th>PARENTESCO</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th></th>
                                                    <th>@{{ totalPercentageAvailability }}</th>
                                                    <th>@{{ totalAmountAvailability | currency }}</th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                <tr v-for="(beneficiary, index) in beneficiariesAvailability" :key="index">
                                                    <td>@{{ beneficiary.full_name }}</td>
                                                    <td><input type="number" step="0.01" v-model="beneficiary.percentage" @change="requalificationTotalAvailability(index)"></td>
                                                    <td><input type="number" step="0.01" v-model="beneficiary.temp_amount_availability" ></td>
                                                    <td>@{{ beneficiary.kinship.name }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <button class="btn btn-primary" type="submit" @click="savePercentagesAvailability"><i class="fa fa-save"></i> Guardar</button>
                                    </div>
                                </div>
                                <div v-if="showPercentagesRetFunAvailability">
                                    <div class="ibox" class="fadeInRight">
                                        <div class="ibox-title">
                                            <h5>Calculo de las cuotas partes para los derechohabientes Total</h5>
                                            <div class="ibox-tools">
                                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                                <a class="close-link"><i class="fa fa-times"></i></a>
                                            </div>
                                        </div>
                                        <div class="ibox-content">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>NOMBRE DEL DERECHOHABIENTE</th>
                                                        <th>% DE ASIGNACION</th>
                                                        <th>MONTO</th>
                                                        <th>PARENTESCO</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th></th>
                                                        <th>@{{ totalPercentageRetFunAvailability }}</th>
                                                        <th>@{{ totalAmountRetFunAvailability | currency }}</th>
                                                        <th></th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    <tr v-for="(beneficiary, index) in beneficiariesRetFunAvailability" :key="index">
                                                        <td>@{{ beneficiary.full_name }}</td>
                                                        <td><input type="number" step="0.01" v-model="beneficiary.percentage" @change="requalificationTotalRetFunAvailability(index)"></td>
                                                        <td><input type="number" step="0.01" v-model="beneficiary.temp_amount_total"></td>
                                                        <td>@{{ beneficiary.kinship.name }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <button class="btn btn-primary" type="submit" @click="saveTotalRetFunAvailability"><i class="fa fa-save"></i> Guardar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <div class="ibox" class="fadeInRight" v-if="arrayDiscounts.length">
                                    <div class="ibox-title">
                                        <h5>Total Fondo de retiro con descuentos</h5>
                                        <div class="ibox-tools">
                                            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                            <a class="close-link"><i class="fa fa-times"></i></a>
                                        </div>
                                    </div>
                                    <div class="ibox-content">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Foobar</th>
                                                    <th>baz</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(discount, index) in arrayDiscounts">
                                                    <td>@{{ discount.full_name }}</td>
                                                    <td>@{{ discount.amount }}</td>
                                                </tr>
                                                <tr class="success">
                                                    <td>@{{ arrayDiscounts[arrayDiscounts.length-1].name }}</td>
                                                    <td>@{{ arrayDiscounts[arrayDiscounts.length-1].amount }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </ret-fun-qualification>
    </div>
</div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.20.3/TweenMax.min.js"></script>
@endsection