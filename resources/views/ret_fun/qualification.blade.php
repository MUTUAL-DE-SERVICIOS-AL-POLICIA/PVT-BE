@extends('layouts.app') 
@section('title', 'Fondo de Retiro') 
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-7">
        {!!Breadcrumbs::render('show_qualification_retirement_fund', $retirement_fund)!!}
    </div>
    <div class="col-md-5 text-center" style="margin-top:12px;">
        <div class="pull-left">
            <button class="btn btn-primary dim" type="button" data-toggle="tooltip" data-placement="top" title="Imprimir Calificacion 1" onclick="printJS({printable:'{!! route('ret_fun_print_beneficiaries_qualification', $retirement_fund->id) !!}', type:'pdf', modalMessage: 'Generando documentos de impresión por favor espere un momento.', showModal:true})"><i class="fa fa-print"></i></button>
            <button class="btn btn-primary dim" type="button" data-toggle="tooltip" data-placement="top" title="Imprimir Calificacion 2" onclick="printJS({printable:'{!! route('ret_fun_print_qualification_average_salary_quotable', $retirement_fund->id) !!}', type:'pdf', modalMessage: 'Generando documentos de impresión por favor espere un momento.', showModal:true})"><i class="fa fa-print"></i></button>
            <button class="btn btn-primary dim" type="button" data-toggle="tooltip" data-placement="top" title="Imprimir Calificacion 3" onclick="printJS({printable:'{!! route('ret_fun_print_data_qualification', $retirement_fund->id) !!}', type:'pdf', modalMessage: 'Generando documentos de impresión por favor espere un momento.', showModal:true})"><i class="fa fa-print"></i></button>
            @if($affiliate->hasAvailability())
                <button class="btn btn-warning dim" type="button" data-toggle="tooltip" data-placement="top" title="Imprimir Calificacion 4" onclick="printJS({printable:'{!! route('ret_fun_print_data_qualification_availability', $retirement_fund->id) !!}', type:'pdf', modalMessage: 'Generando documentos de impresión por favor espere un momento.', showModal:true})"><i class="fa fa-print"></i></button>
                <button class="btn btn-warning dim" type="button" data-toggle="tooltip" data-placement="top" title="Imprimir Calificacion 4" onclick="printJS({printable:'{!! route('ret_fun_print_data_qualification_ret_fun_availability', $retirement_fund->id) !!}', type:'pdf', modalMessage: 'Generando documentos de impresión por favor espere un momento.', showModal:true})"><i class="fa fa-print"></i></button>

            @endif

            <button type="button" class="btn btn-info btn-sm dim" data-toggle="modal" data-target="#ModalRecordRetFun" data-placement="top"
                title="Historial del Trámite">
                    <i class="fa fa-history"></i>
                </button>
        </div>
        <div class="pull-right">
            {{-- @if ($has_validate)
            <swal-modal inline-template :doc-id="{{$retirement_fund->id}}" :inbox-state="{{$retirement_fund->inbox_state ? 'true' : 'false'}}">
                <div>
                    <div v-if="status == true" data-toggle="tooltip" data-placement="top" title="Trámite ya procesado">
                        <button data-toggle="tooltip" data-placement="top" title="Trámite ya procesado" class="btn btn-primary btn-circle btn-outline btn-lg active"
                            type="button" :disabled="! status == false "><i class="fa fa-check"></i></button>
                    </div>
                    <div v-else>
                        <button data-toggle="tooltip" data-placement="top" title="Procesar Trámite" class="btn btn-primary btn-circle btn-outline btn-lg"
                            type="button" @click="showModal()" :disabled="! status == false "><i class="fa fa-check"></i></button>
                    </div>
                </div>
            </swal-modal>
            @endif --}}
        </div>
    </div>
</div>

<div class="modal inmodal" id="averageSalaryQuotable" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                <h4 class="modal-title">SALARIO PROMEDIO COTIZABLE</h4>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <table class="table table-striped" id="datatables-certification">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Periodo</th>
                                <th>Haber Basico</th>
                                <th>Categoria</th>
                                <th>Salario Cotizable</th>
                                <th>Total Aporte</th>
                                <th>Aporte FRPS</th>
                            </tr>
                        </thead>
                    </table>
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <td>Total Aportes Fondo de Retiro Policial Solidario</td>
                                <td>{{ $total_retirement_fund }}</td>
                            </tr>
                            <tr>
                                <td>Salario Total</td>
                                <td>{{ $sub_total_average_salary_quotable }}</td>
                            </tr>
                            <tr>
                                <td>Salario Promedio</td>
                                <td>{{ $total_average_salary_quotable }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Aportes y periodos considerados</h5>
                </div>
            </div>
        </div>
    </div>
    <ret-fun-qualification inline-template :retirement-fund-id="{{$retirement_fund->id}}" :contributions="{{$all_contributions}}">
        <div>
            <div class="row" v-for="(contributionType, index) in contributions.contribution_types">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content forum-container">
                            <div class="col-md-5">
                                <h4>
                                <i class="fa fa-plus" v-if="contributionType.operator == '+'"></i>
                                <i class="fa fa-minus" v-else-if="contributionType.operator == '-'"></i>
                                    @{{ contributionType.name }} <span data-toggle="tooltip" data-placement="top" :title="contributionType.description"><i class="fa fa-question-circle" style="opacity:.7"></i></span>
                                </h4>
                            </div>
                            <ret-fun-qualification-group :dates-child="contributionType.dates">
                            </ret-fun-qualification-group>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content forum-container">
                            <div class="forum-title">
                                <h3>Tabla de contribuciones</h3>
                            </div>
                            <div class="row">
                                <div class="col-xs-offset-4 col-md-6">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Tipo de contribucion</th>
                                                <th class="text-center">Operacion</th>
                                                <th class="text-center">Años</th>
                                                <th class="text-center">Meses</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(contributionType, index) in contributions.contribution_types">
                                                <td>@{{index+1}}</td>
                                                <td>@{{contributionType.name}}</td>
                                                <td class="text-center">
                                                    <i class="fa fa-plus" v-if="contributionType.operator == '+'"></i>
                                                    <i class="fa fa-minus" v-else-if="contributionType.operator == '-'"></i>
                                                </td>
                                                <td class="text-center">@{{contributionType.years}}</td>
                                                <td class="text-center">@{{contributionType.months}}</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="success">
                                                <td colspan="3"><strong>Total de cotizaciones para Calificacion</strong></td>
                                                <td class="text-center"><strong>@{{ contributions.years }}</strong></td>
                                                <td class="text-center"><strong>@{{ contributions.months }}</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <button class="btn btn-primary" :class="{'btn-outline': !showEconomicData}" @click="firstContinue()"><i class="fa fa-save"></i> Continuar
                                <transition name="fade" enter-active-class="animated bounceInRight" leave-active-class="animated bounceOutLeft" v-if="showEconomicData">
                                    <div>
                                        <i class="fa fa-check"></i>
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                        <circle class="path circle" fill="none" stroke="#ffffff" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                        <polyline class="path check" fill="none" stroke="#ffffff" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
                                        </svg>
                                    </div>
                                </transition>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="showEconomicData">
                <div class="ibox" class="fadeInRight">
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
                                    <td>@{{ totalAverageSalaryQuotableAnimated | currency }}
                                        <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#averageSalaryQuotable" style="margin:15px;"><i class="fa fa-calculator"></i> ver completo</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Densidad Total de Cotizaciones</td>
                                    <td>@{{ totalQuotesAnimated }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="btn btn-primary" :class="{'btn-outline':!showEconomicDataTotal}" type="submit" @click="saveAverageQuotable"><i class="fa fa-save"></i> Guardar
                            <transition name="fade" enter-active-class="animated bounceInRight" leave-active-class="animated bounceOutLeft" v-if="showEconomicDataTotal">
                                <div>
                                    <i class="fa fa-check"></i>
                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                    <circle class="path circle" fill="none" stroke="#ffffff" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                    <polyline class="path check" fill="none" stroke="#ffffff" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
                                    </svg>
                                </div>
                            </transition>
                        </button>
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
                            <button class="btn btn-primary" :class="{'btn-outline': !showPercentagesRetFun}" type="submit" @click="saveTotalRetFun"><i class="fa fa-save"></i> Guardar
                                <transition name="fade" enter-active-class="animated bounceInRight" leave-active-class="animated bounceOutLeft" v-if="showPercentagesRetFun">
                                    <div>
                                        <i class="fa fa-check"></i>
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                        <circle class="path circle" fill="none" stroke="#ffffff" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                        <polyline class="path check" fill="none" stroke="#ffffff" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
                                        </svg>
                                    </div>
                                </transition>
                            </button>
                        </div>
                    </div>
                    <div v-if="showPercentagesRetFun">
                        <div class="ibox" class="fadeInRight">
                            <div class="ibox-title">
                                <h5>Calculo de las cuotas partes para los derechohabientes (Fondo de Retiro)</h5>
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
                                <button class="btn btn-primary" :class="{'btn-outline': !finishRetFun}" type="submit" @click="savePercentages"><i class="fa fa-save"></i> Guardar
                                    <transition name="fade" enter-active-class="animated bounceInRight" leave-active-class="animated bounceOutLeft" v-if="finishRetFun">
                                        <div>
                                            <i class="fa fa-check"></i>
                                            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                            <circle class="path circle" fill="none" stroke="#ffffff" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                            <polyline class="path check" fill="none" stroke="#ffffff" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
                                            </svg>
                                        </div>
                                    </transition>
                                </button>
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
                                                <td>@{{ subTotalAvailability | currency }}</td>
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
                                                <td><input type="number" step="0.01" v-model="beneficiary.temp_amount_availability"></td>
                                                <td>@{{ beneficiary.kinship.name }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <button class="btn btn-primary" :class="{'btn-outline': !showPercentagesRetFunAvailability}" type="submit" @click="savePercentagesAvailability"><i class="fa fa-save"></i> Guardar
                                        <transition name="fade" enter-active-class="animated bounceInRight" leave-active-class="animated bounceOutLeft" v-if="showPercentagesRetFunAvailability">
                                            <div>
                                                <i class="fa fa-check"></i>
                                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                                <circle class="path circle" fill="none" stroke="#ffffff" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                                <polyline class="path check" fill="none" stroke="#ffffff" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
                                                </svg>
                                            </div>
                                        </transition>
                                    </button>
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
                                        <button class="btn btn-primary" :class="{'btn-outline':!finishAvailability}" type="submit" @click="saveTotalRetFunAvailability"><i class="fa fa-save"></i> Guardar
                                            <transition name="fade" enter-active-class="animated bounceInRight" leave-active-class="animated bounceOutLeft" v-if="finishAvailability">
                                                <div>
                                                    <i class="fa fa-check"></i>
                                                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                                    <circle class="path circle" fill="none" stroke="#ffffff" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
                                                    <polyline class="path check" fill="none" stroke="#ffffff" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
                                                    </svg>
                                                </div>
                                            </transition>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            {{-- <div v-else>
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
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ret-fun-qualification>
    {{--
    <div v-if="showEconomicData">

        <div v-show="showEconomicDataTotal">
  
            <div v-if="showPercentagesRetFun">

                <div v-if="hasAvailability">
                    
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
                </div>
            </div>
        </div>
    </div> --}} {{-- </div>

</div>
</div>
</div>
</div> --}}
</div>
@endsection
@section('styles')
<link rel="stylesheet" href="{{asset('/css/datatables.css')}}">
<style>
svg {
  width:20px;
  display: inline-block;
}
.path {
  stroke-dasharray: 1000;
  stroke-dashoffset: 0;
}
.path.circle {
  -webkit-animation: dash 0.9s ease-in-out;
  animation: dash 0.9s ease-in-out;
}
.path.line {
  stroke-dashoffset: 1000;
  -webkit-animation: dash 0.9s 0.35s ease-in-out forwards;
  animation: dash 0.9s 0.35s ease-in-out forwards;
}
.path.check {
  stroke-dashoffset: -100;
  -webkit-animation: dash-check 0.9s 0.35s ease-in-out forwards;
  animation: dash-check 0.9s 0.35s ease-in-out forwards;
}

@-webkit-keyframes dash {
  0% {
    stroke-dashoffset: 1000;
  }
  100% {
    stroke-dashoffset: 0;
  }
}
@keyframes dash {
  0% {
    stroke-dashoffset: 1000;
  }
  100% {
    stroke-dashoffset: 0;
  }
}
@-webkit-keyframes dash-check {
  0% {
    stroke-dashoffset: -100;
  }
  100% {
    stroke-dashoffset: 900;
  }
}
@keyframes dash-check {
  0% {
    stroke-dashoffset: -100;
  }
  100% {
    stroke-dashoffset: 900;
  }
}

</style>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.20.3/TweenMax.min.js"></script>
<script src="{{ asset('/js/datatables.js')}}"></script>
<script>
    $(document).ready(function () {
            var datatable_contri = $('#datatables-certification').DataTable({
                responsive: true,
                fixedHeader: {
                header: true,
                footer: true,
                    headerOffset: $('#navbar-fixed-top').outerHeight()
                },
                order: [],
                ajax: "{{ url('get_data_certification', $retirement_fund->id) }}",
                lengthMenu: [[15, 30, 60, -1], [15, 30, 60, "Todos"]],
                dom: '< "html5buttons"B>lTfgitp',
                buttons:[
                    { extend: 'copy'},
                    { extend: 'csv'},
                    { extend: 'excel', title: "{!! $retirement_fund->id.'-'.date('Y-m-d') !!}"},
                ],
                columns:[
                    {data: 'DT_Row_Index' },
                    {data: 'month_year' },
                    {data: 'base_wage'},
                    {data: 'seniority_bonus'},
                    {data: 'quotable_salary'},
                    {data: 'total'},
                    {data: 'retirement_fund'},
                ],
            });
    });
</script>
@endsection