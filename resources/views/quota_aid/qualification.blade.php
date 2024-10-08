@extends('layouts.app') 
@section('title', 'Fondo de Retiro') 
@section('content')
<quota-aid-qualification inline-template :quota-aid-id="{{$quota_aid->id}}" :contributions="{{json_encode($contributions)}}"
    :affiliate="{{json_encode($affiliate)}}">
    <div>
        <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-7">
                {!!Breadcrumbs::render('show_qualification_quota_aid', $quota_aid)!!}
            </div>
            <div class="col-md-5 text-center" style="margin-top:12px;">
                <div class="pull-right">
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
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox">
                        <div class="ibox-content forum-container">
                            <div class="col-md-5">
                                <h4>
                                    {{-- @{{ contributionType.name}} <span data-toggle="tooltip" data-placement="top" :title="contributionType.description"><i class="fa fa-question-circle" style="opacity:.7"></i></span>                                    --}}
                                </h4>
                            </div>
                            <quota-aid-qualification-group :dates-child="{{ json_encode($dates['dates']) }}">
                            </quota-aid-qualification-group>
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="showEconomicData" id="showEconomicData">
                <div class="ibox" class="fadeInRight">
                    <div class="ibox-title">
                        <h5>Datos Economicos</h5>
                    </div>
                    <div class="ibox-content">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Cotización</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Grado del Titular</td>
                                    <td>{{ $affiliate->degree->shortened }}</td>
                                </tr>
                                <tr>
                                    <td>Fecha de Fallecimiento del {{ ($quota_aid->getDeceased() instanceof Muserpol\Models\Affiliate) ? 'Titular' : ' o de la Esposa (o)' }} </td>
                                    <td class="text-uppercase">{{ $quota_aid->getDeceased()->getDateDeath() }}</td>
                                </tr>
                                <tr>
                                    <td>Causa de Fallecimiento del {{ ($quota_aid->getDeceased() instanceof Muserpol\Models\Affiliate) ? 'Titular' : ' o de la Esposa (o)' }} </td>
                                    <td>{{ $quota_aid->getDeceased()->reason_death }}</td>
                                </tr>
                                <tr class="font-bold">
                                    <td>Total {{ $quota_aid->procedure_modality->procedure_type->name }}</td>
                                    <td>Bs {{$procedure? (Util::formatMoney($procedure->amount) ?? '-'):'NO TIENE CUANTIA DE CÁLCULO' }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <button class="btn btn-primary" :class="{'btn-outline':!showPercentagesQuotaAid}" type="submit" @click="calculateTotal(false)"><i class="fa fa-save"></i> Continuar
                            <transition name="fade" enter-active-class="animated bounceInRight" leave-active-class="animated bounceOutLeft" v-if="showPercentagesQuotaAid">
                                <div>
                                    <check-svg></check-svg>
                                </div>
                            </transition>
                        </button>
                    </div>

                </div>
                <div>
                    <div class="ibox" class="fadeInRight" v-if="showDiscounts" id="showDiscounts">
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
                                        <th>N° Resolución</th>
                                        <th>Fecha de Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="info">
                                        <td>Sub Total</td>
                                        <td>@{{ subTotalQuotaAid | currency }}</td>
                                    </tr>
                                    <tr>
                                        <td>Anticipo </td>
                                        <td>
                                            <input class="form-control" type="text" v-model="advancePayment" v-money style="width:130px">
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" placeholder="Cite" v-model="advancePaymentCode">
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
                                        <td>Porcentaje de Anticipo </td>
                                        <td>@{{ percentageAdvancePayment | percentage }}</td>
                                    </tr>

                                </tbody>
                            </table>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tipo</th>
                                        <th>Monto</th>
                                        <th>Descripción</th>
                                        <th>Fecha de Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Retenciones judiciales</td>
                                        <td>
                                            <div :class="{ 'has-error': validateRetentionAmount(judicialRetentionAmount)}">
                                                <input type="text" class="form-control" v-money v-model="judicialRetentionAmount">
                                            </div>
                                        </td>
                                        <td>
                                            <textarea
                                                class="form-control"
                                                name="description"
                                                placeholder="Descripción"
                                                style="resize: none; width: 350px; height: 70px;"
                                                v-model="judicialRetentionDetail"
                                                disabled
                                            ></textarea>
                                        </td>
                                        <td>
                                            <div :class="{ 'has-error': validateRetentionDate(judicialRetentionDate)}">
                                                <input class="form-control" type="date" v-model="judicialRetentionDate">
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="success">
                                        <td>Total</td>
                                        <td colspan="5"><strong>@{{ totalAnimated | currency }}</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                            <button class="btn btn-primary" :class="{'btn-outline': !showPercentagesQuotaAid}" type="submit" @click="saveDiscounts(false)"><i class="fa fa-save"></i> Guardar
                                <transition name="fade" enter-active-class="animated bounceInRight" leave-active-class="animated bounceOutLeft" v-if="showPercentagesQuotaAid">
                                    <div>
                                        <check-svg></check-svg>
                                    </div>
                                </transition>
                            </button>
                        </div>
                    </div>
                    <div>
                        <div class="ibox" class="fadeInRight" v-if="showPercentagesQuotaAid" id="showPercentagesQuotaAid">
                            <div class="ibox-title">
                                <h5>Calculo de las cuotas partes para los derechohabientes</h5>
                                <button class="btn btn-danger btn-xs" type="button" data-toggle="tooltip" data-placement="top" title="Recalcular" @click="saveDiscounts(true)"><i class="fa fa-refresh"></i></button>
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
                                            <th :class="colorClass(totalPercentageQuotaAid, maxPercentage)">@{{ totalPercentageQuotaAid | percentage }}</th>
                                            <th :class="colorClass(totalAmountQuotaAid, totalAnimated)">@{{ totalAmountQuotaAid | currency }}</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <tr v-for="(beneficiary, index) in beneficiaries" :key="index">
                                            <td>@{{ beneficiary.full_name }}</td>
                                            <td>
                                                <div :class="{ 'has-error': max(totalPercentageQuotaAid, maxPercentage) }">
                                                    <input class="form-control" type="number" step="0.01" v-model="beneficiary.temp_percentage">
                                                </div>
                                            </td>
                                            <td>
                                                <div :class="{ 'has-error': max(totalAmountQuotaAid,totalAnimated) }">
                                                    <input class="form-control" :class="{'text-danger':max(totalAmountQuotaAid,totalAnimated)}" type="number" step="0.01" v-model="beneficiary.temp_amount">
                                                </div>
                                            </td>
                                            <td>@{{ beneficiary.kinship.name }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <button
                                    class="btn btn-primary"
                                    :class="{'btn-outline': !finishQuotaAid}"
                                    type="submit" @click="savePercentages(false)"
                                >
                                    <i class="fa fa-save"></i> Guardar
                                    <transition name="fade" enter-active-class="animated bounceInRight" leave-active-class="animated bounceOutLeft" v-if="finishQuotaAid">
                                        <div>
                                            <check-svg></check-svg>
                                        </div>
                                    </transition>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</quota-aid-qualification>
@endsection