@extends('layouts.app') 
@section('title', 'Fondo de Retiro') 
@section('content')
<ret-fun-qualification inline-template :retirement-fund-id="{{$retirement_fund->id}}" :contributions="{{$all_contributions}}" :affiliate="{{$retirement_fund->affiliate}}">
    <div>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-7">
            {!!Breadcrumbs::render('show_qualification_retirement_fund', $retirement_fund)!!}
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
                            <h3>Años de servicio según certificación del Comando General de la Policía</h3>
                        </div>
                        <div class="row">
                            <div class="col-xs-offset-3 col-md-9">
                                <div class="form-inline">
                                    <div :class="{'has-error': errors.has('service_years') }">
                                        <div class="col-md-1">
                                            <label class="control-label">Años</label>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="number" class="form-control" v-model="serviceYears" name="service_years" v-validate.initial="'required|numeric|max_value:100|min_value:0'">
                                            <div v-show="errors.has('service_years')">
                                                <i class="fa fa-warning text-danger"></i>
                                                <span class="text-danger">@{{ errors.first('service_years') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div :class="{'has-error': errors.has('service_months') }">
                                        <div class="col-md-1">
                                            <label class="control-label">Meses</label>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="number" class="form-control" v-model="serviceMonths" name="service_months" v-validate.initial="'required|numeric|max_value:12|min_value:0'">
                                            <div v-show="errors.has('service_months')">
                                                <i class="fa fa-warning text-danger"></i>
                                                <span class="text-danger">@{{ errors.first('service_months') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                            <th>Tipo de contribución</th>
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
                        @if (! $affiliate->selectedContributions() > 0 )
                            <button class="btn btn-primary" :class="{'btn-outline': !showEconomicData}" @click="firstContinue()"><i class="fa fa-save"></i> Continuar
                                <transition name="fade" enter-active-class="animated bounceInRight" leave-active-class="animated bounceOutLeft" v-if="showEconomicData">
                                    <div>
                                        <check-svg></check-svg>
                                    </div>
                                </transition>
                            </button>
                        @else
                            <button class="btn btn-primary" class="btn-outline" disabled title="Verifique antes de continuar que TODAS las contribuciones esten clasificadas." ><i class="fa fa-save"></i> Continuar</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showEconomicData" id="showEconomicData">
            <div class="ibox" class="fadeInRight">
                <div class="ibox-title">
                    <h5>Datos Economicos</h5>
                </div>
                <div class="ibox-content" v-if="!globalPay">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Cotizacion</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Ultimo Sueldo Percibido</td>
                                <td>@{{ lastBaseWage | currency }}</td>
                            </tr>
                            <tr>
                                <td>Salario Promedio Cotizable</td>
                                <td>@{{ totalAverageSalaryQuotableAnimated | currency }}
                                    <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#averageSalaryQuotable" style="margin-left:15px;"><i class="fa fa-calculator"></i> ver completo</button>
                                </td>
                            </tr>
                            <tr v-if="averageSalaryLimit > 0">
                                <td>Salario Promedio Cotizable Limitado</td>
                                <td>Bs @{{ averageSalaryLimit }}</td>
                            </tr>
                            <tr>
                                <td>Densidad Total de Cotizaciones</td>
                                <td>@{{ totalQuotesAnimated }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-primary" :class="{'btn-outline':!showEconomicDataTotal}" type="submit" @click="calculateSubTotal"><i class="fa fa-save"></i> Guardar
                        <transition name="fade" enter-active-class="animated bounceInRight" leave-active-class="animated bounceOutLeft" v-if="showEconomicDataTotal">
                            <div>
                                <check-svg></check-svg>
                            </div>
                        </transition>
                    </button>
                </div>
                <div class="ibox-content" v-else>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Cotizacion</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @if ($retirement_fund->procedure_modality->procedure_type_id == 21)
                                <td>Total Devolución de Aportes</td>
                                @else
                                <td>Total Aportes</td>
                                @endif
                                <td>@{{ totalAporte | currency }}
                                    <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#averageSalaryQuotable" style="margin-left:15px;"><i class="fa fa-calculator"></i> ver completo</button>
                                </td>
                            </tr>
                            <tr>
                                <td>+ Rendimiento del @{{ percentageYield }}% </td>
                                <td>@{{ yield | currency}} </td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-primary" :class="{'btn-outline':!showEconomicDataTotal}" type="submit" @click="calculateSubTotal"><i class="fa fa-save"></i> Guardar
                        <transition name="fade" enter-active-class="animated bounceInRight" leave-active-class="animated bounceOutLeft" v-if="showEconomicDataTotal">
                            <div>
                                <check-svg></check-svg>
                            </div>
                        </transition>
                    </button>
                </div>
            </div>
            <div v-show="showEconomicDataTotal" id="showEconomicDataTotal">
                <div class="ibox" class="fadeInRight">
                    <div class="ibox-title">
                        <h5>Datos Economicos Total (Descuentos)</h5>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th>Monto</th>
                                    <th>Cite</th>
                                    <th>Fecha de Cite</th>
                                    <th>#</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="info">
                                    <td v-if="! globalPay">Sub Total {{ $retirement_fund->procedure_modality->procedure_type->second_name }}</td>
                                    <td v-else>Sub Total {{ $retirement_fund->procedure_modality->procedure_type->second_name }} por {{ $retirement_fund->procedure_modality->name }} </td>
                                    <td>@{{ subTotalRetFun | currency }}</td>
                                </tr>
                                <tr>
                                    <td>Anticipo Fondo de Retiro</td>
                                    <td>
                                        <input class="form-control" type="text" v-model="advancePayment" data-money='true' style="width:130px">
                                    </td>
                                    <td>
                                        <textarea class="form-control" type="text" placeholder="Cite" v-model="advancePaymentCode"></textarea>
                                    </td>
                                    <td>
                                        <input class="form-control" type="date" v-model="advancePaymentDate">
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" placeholder="# de Resolución de Anticipo" v-model="advancePaymentNoteCode">
                                    </td>
                                    <td>
                                        <input class="form-control" type="date" v-model="advancePaymentNoteCodeDate">
                                    </td>
                                </tr>
                                <tr>
                                    <td>% de Anticipo Fondo de Retiro</td>
                                    <td>@{{ percentageAdvancePayment | percentage }}</td>
                                </tr>
                                <tr>
                                    <td>Retención para pago de préstamo</td>
                                    <td>
                                        <input class="form-control" type="text" v-model="retentionLoanPayment" data-money='true' style="width:130px">
                                    </td>
                                    <td>
                                        <textarea class="form-control" type="text" placeholder="Cite" v-model="retentionLoanPaymentCode"></textarea>
                                    </td>
                                    <td>
                                        <input class="form-control" type="date" v-model="retentionLoanPaymentDate">
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" placeholder="# de Contrato de Préstamo" v-model="retentionLoanPaymentNoteCode">
                                    </td>
                                    <td>
                                        <input class="form-control" type="date" v-model="retentionLoanPaymentNoteCodeDate">
                                    </td>
                                </tr>
                                <tr>
                                    <td>% de Retención para pago de préstamo</td>
                                    <td>@{{ percentageRetentionLoanPayment | percentage }}</td>
                                </tr>
                                <tr>
                                    <td>
                                        Garantes
                                        <button class="btn btn-info" @click="addGuarantor"><i class="fa fa-plus"></i></button>
                                    </td>
                                    <td colspan="5">
                                        <div class="form-inline" v-for="(guarantor, index) in guarantors">
                                            <button class="btn btn-danger" type="button" @click="deleteGuarantor(index)" type="button" role="button"><i class="fa fa-trash "></i></button>
                                            <div class="input-group">
                                                <input type="text" name="applicant_identity_card" v-model.trim="guarantor.identity_card" class="form-control" style="width:130px" ref="guarantoridentitycard" @keypress.enter="searchGuarantor(index)" placeholder="Buscar por CI"
                                                >
                                                <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="button" @click="searchGuarantor(index)" type="button" role="button"><i class="fa fa-search"></i></button>
                                                </span>
                                            </div>
                                            <input type="text" disabled :value="guarantor.full_name" class="form-control" style="width:256px;">
                                            <input type="text" v-if="guarantor.full_name" class="form-control" type="text" v-model="guarantor.amount" data-money='true' style="width:130px" @keyup="updateTotalGuarantor" ref=" guarantoramount">
                                            <hr>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Retención para garantes
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" :value="retentionGuarantor" disabled>
                                    </td>
                                    <td>
                                        <textarea class="form-control" type="text" placeholder="Cite" v-model="retentionGuarantorCode"></textarea>
                                    </td>
                                    <td>
                                        <input class="form-control" type="date" v-model="retentionGuarantorDate">
                                    </td>
                                    <td>
                                        <input class="form-control" type="text" v-model="retentionGuarantorNoteCode">
                                    </td>
                                    <td>
                                        <input class="form-control" type="date" v-model="retentionGuarantorNoteCodeDate">
                                    </td>
                                </tr>
                                <tr>
                                    <td>% de Retención para garantes</td>
                                    <td colspan="5">@{{ percentageRetentionGuarantor | percentage }}</td>
                                </tr>
                            </tbody>
                            <thead v-if="haveJudicialRetention">
                                <tr>
                                    <th>Tipo</th>
                                    <th>Monto</th>
                                    <th colspan="2">Descripción</th>
                                    <th>Documento</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody v-if="haveJudicialRetention">
                                <tr>
                                    <td>Retenciones judiciales</td>
                                    <td>
                                        <div :class="{ 'has-error': validateRetentionAmount(judicialRetentionAmount)}">
                                            <input type="text" class="form-control" v-money v-model="judicialRetentionAmount">
                                        </div>
                                    </td>
                                    <td colspan="2">
                                        <textarea
                                            class="form-control"
                                            name="description"
                                            placeholder="No registrado"
                                            v-model="judicialRetentionDetail"
                                            disabled
                                        ></textarea>
                                    </td>
                                    <td>
                                        <textarea 
                                            class="form-control" 
                                            type="text" 
                                            placeholder="Documento" 
                                            v-model="judicialRetentionDocument">
                                        </textarea>
                                    </td>
                                    <td>
                                        <div :class="{ 'has-error': validateRetentionDate(judicialRetentionDate)}">
                                            <input class="form-control" type="date" v-model="judicialRetentionDate">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>% de Retenciones judiciales</td>
                                    <td colspan="5">@{{ percentageRetentionJudicial | percentage }}</td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr class="success">
                                    <td v-if="! globalPay">Total {{ $retirement_fund->procedure_modality->procedure_type->second_name }}</td>
                                    <td v-else>Total {{ $retirement_fund->procedure_modality->procedure_type->second_name }} por {{ $retirement_fund->procedure_modality->name }} </td>
                                    <td colspan="5"><strong>@{{ totalAnimated | currency }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="btn btn-primary" :class="{'btn-outline': !showPercentagesRetFun}" type="submit" @click="saveTotalRetFun(false)"><i class="fa fa-save"></i> Guardar
                            <transition name="fade" enter-active-class="animated bounceInRight" leave-active-class="animated bounceOutLeft" v-if="showPercentagesRetFun">
                                <div>
                                    <check-svg></check-svg>
                                </div>
                            </transition>
                        </button>
                    </div>
                </div>
                <div v-if="showPercentagesRetFun" id="showPercentagesRetFun">
                    <div class="ibox" class="fadeInRight">
                        <div class="ibox-title">
                            @if ($retirement_fund->procedure_modality->id == 1 || $retirement_fund->procedure_modality->id == 4)
                                <h5> </h5>
                                <h5 v-if="! globalPay">Calculo de las cuotas partes para los derechohabientes (Fondo de Retiro)</h5>
                                <h5 v-else>Calculo de las cuotas partes para los derechohabientes (Pago Global por {{ $retirement_fund->procedure_modality->name }})</h5>
                            @else
                                <h5 v-if="! globalPay">Calculo del total ({{ $retirement_fund->procedure_modality->procedure_type->second_name }})</h5>
                                <h5 v-else>Calculo del total ({{ $retirement_fund->procedure_modality->procedure_type->second_name }} por {{ $retirement_fund->procedure_modality->name }})</h5>
                            @endif
                            <button class="btn btn-danger btn-xs" type="button" data-toggle="tooltip" data-placement="top" title="Recalcular" @click="saveTotalRetFun(true)" ><i class="fa fa-refresh"></i></button>
                        </div>
                        <beneficiaries-qualification-amounts :beneficiaries="beneficiaries" :total="parseFloat(totalRetFun)" 
                        :is-holder='@json($is_holder)'
                        @save="savePercentages(false)"> </beneficiaries-qualification-amounts>
                    </div>
                    <template v-for="(refund, index) in refundsData" v-if="refundsData.length > 0">
                        <div :id="'refund'+refund.id" :key="'rc'+index" v-if="refund.show">
                            <div class="ibox" class="fadeInRight">
                                <div class="ibox-title">
                                    <h5>DEVOLUCIÓN DE APORTES - @{{refund.name}}</h5>
                                </div>
                                <div class="ibox-content">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Sub Total</th>
                                                <th>Rendimiento</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>@{{ refund.subtotal | currency }}</td>
                                                <td>@{{ refund.yield | currency }}</td>
                                                <td>
                                                    @{{ refund.total | currency }}
                                                    <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#availability-modal" style="margin-left:15px;"><i class="fa fa-calculator"></i> ver completo</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <beneficiaries-qualification-amounts :beneficiaries="refund.beneficiaries" :total="parseFloat(refund.total)" 
                            :is-holder='@json($is_holder)'
                            @save="saveRefundPercentages(refund)"> </beneficiaries-qualification-amounts>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
    </div>
</ret-fun-qualification>
<div class="modal inmodal" id="averageSalaryQuotable" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                @if ($retirement_fund->procedure_modality->procedure_type_id == 2)
                    <h4 class="modal-title">SALARIO PROMEDIO COTIZABLE</h4>
                @else
                    <h4 class="modal-title">{{$retirement_fund->procedure_modality->procedure_type->name}}</h4>
                @endif
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
                            @if ($retirement_fund->procedure_modality->procedure_type_id == 1)
                                <tr>
                                    <td>Total Aportes FRPS</td>
                                    <td>Bs {{ Util::formatMoney($total_retirement_fund) }}</td>
                                </tr>
                            @elseif($retirement_fund->procedure_modality->procedure_type_id == 21)
                            <tr>
                                    <td>Total Devolución de Aportes </td>
                                    <td>Bs {{ Util::formatMoney($total_retirement_fund) }}</td>
                                </tr>
                            @else
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
                            @endif
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
<div class="modal inmodal" id="availability-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                <h4 class="modal-title">Devolución de Aportes en Disponibilidad</h4>
            </div>
            <div class="modal-body">
                <div class="col-lg-12">
                    <table class="table table-striped" id="datatables-availability">
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
                        <tfoot>
                            <tr>
                                <td>Total</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('styles')
<link rel="stylesheet" href="{{asset('/css/datatables.css')}}">
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
            lengthMenu: [[60, -1], [60, "Todos"]],
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
        var datatable_availability = $('#datatables-availability').DataTable({
            responsive: true,
            fixedHeader: {
            header: true,
            footer: true,
                headerOffset: $('#navbar-fixed-top').outerHeight()
            },
            order: [],
            ajax: "{{ url('get_data_availability', $retirement_fund->id) }}",
            lengthMenu: [[60, -1], [60, "Todos"]],
            dom: '< "html5buttons"B>lTfgitp',
            buttons:[
                { extend: 'copy'},
                { extend: 'csv'},
                { extend: 'excel', title: "Dispobiblidad - {!! $retirement_fund->id.'-'.date('Y-m-d') !!}"},
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

<style>
textarea {
  resize: none;
}
</style>
