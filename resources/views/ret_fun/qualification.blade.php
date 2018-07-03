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
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Aportes y periodos considerados</h5>
                </div>
            </div>
        </div>
    </div>
    <ret-fun-qualification inline-template :retirement-fund-id="{{$retirement_fund->id}}" :contributions="{{$contributions}}">
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
                            <ret-fun-qualification-group :dates-child="contributionType.dates" @total="calculate">
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
                        </div>
                    </div>
                </div>
            </div>
            <button class="btn btn-primary" @click="firstContinue()"><i class="fa fa-save"></i> Continuar</button>
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
                                        <span class="label label-info"> <i class="fa fa-calculator"></i> ver completo</span>
                                        <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#averageSalaryQuotable"  onclick="clickable">gh</button>
                                    </td>
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

                <div class="modal inmodal" id="averageSalaryQuotable" tabindex="-1" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content animated bounceInRight">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                                <h4 class="modal-title">Registro de Antecedente</h4>
                            </div>
                            <div class="modal-body">
                                <div class="col-lg-12">
                                    <div class="ibox ">
                                        <div class="ibox-title">
                                            <h5>SALARIO PROMEDIO COTIZABLE</h5>
                                            <div class="ibox-tools">
                                                <a class="collapse-link">
                                                    <i class="fa fa-chevron-up"></i>
                                                </a>
                                                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                                    <i class="fa fa-wrench"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-user">
                                                    <li><a href="#">Config option 1</a>
                                                    </li>
                                                    <li><a href="#">Config option 2</a>
                                                    </li>
                                                </ul>
                                                <a class="close-link">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="ibox-content">
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
                                                        {{-- <td>{{ $total_retirement_fund }}</td> --}}
                                                    </tr>
                                                    <tr>
                                                        <td>Salario Total</td>
                                                        {{-- <td>{{ $sub_total_average_salary_quotable }}</td> --}}
                                                    </tr>
                                                    <tr>
                                                        <td>Salario Promedio</td>
                                                        {{-- <td>{{ $total_average_salary_quotable }}</td> --}}
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </div>
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
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.20.3/TweenMax.min.js"></script>
<script src="{{ asset('/js/datatables.js')}}"></script>
<script>
    $(document).ready(function () {
        console.log("hguiasdhu");
        
        
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
                    {extend: 'colvis', columnText: function ( dt, idx, title ) { return (idx+1)+': '+title; }},
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
                "fnDrawCallback": function(oSettings){ clickable(); }
            });
        function clickable() {
            console.log("hola");
            
        }
            
    });

</script>
@endsection