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
                            <ret-fun-qualification-group :dates-child="{{ json_encode($dates['dates']) }}">
                            </ret-fun-qualification-group>
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
                                    <th>Cotizacion</th>
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
                                    <td>Bs {{ Util::formatMoney($procedure->amount) ?? '-' }}</td>
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
                                        <th>#</th>
                                        <th>Fecha</th>
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
                                        <td>% de Anticipo </td>
                                        <td>@{{ percentageAdvancePayment | percentage }}</td>
                                    </tr>
                                    {{-- <tr>
                                        <td>Retencion para pago de prestamo</td>
                                        <td>
                                            <input class="form-control" type="text" v-model="retentionLoanPayment" v-money style="width:130px">
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" placeholder="Cite" v-model="retentionLoanPaymentCode">
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
                                        <td>% de Retencion para pago de prestamo</td>
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
                                                    <input type="text" name="applicant_identity_card" v-model.trim="guarantor.identity_card" class="form-control" style="width:130px"
                                                        ref="guarantoridentitycard" @keypress.enter="searchGuarantor(index)"
                                                        placeholder="Buscar por CI">
                                                    <span class="input-group-btn">
                                                                        <button class="btn btn-primary" type="button" @click="searchGuarantor(index)" type="button" role="button"><i class="fa fa-search"></i></button>
                                                                    </span>
                                                </div>
                                                <input type="text" disabled :value="guarantor.full_name" class="form-control" style="width:256px;">
                                                <input type="text" v-if="guarantor.full_name" class="form-control" type="text" v-model="guarantor.amount" v-money
                                                    style="width:130px" @keyup="updateTotalGuarantor" ref=" guarantoramount">
                                                <hr>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Retencion para garantes
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" :value="retentionGuarantor" disabled v-money>
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" placeholder="Cite" v-model="retentionGuarantorCode">
                                        </td>
                                        <td>
                                            <input class="form-control" type="date" v-model="retentionGuarantorDate">
                                        </td>
                                        <td>
                                            <input class="form-control" type="text" placeholder="# de Contrato de Préstamo" v-model="retentionGuarantorNoteCode">
                                        </td>
                                        <td>
                                            <input class="form-control" type="date" v-model="retentionGuarantorNoteCodeDate">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>% de Retencion para garantes</td>
                                        <td colspan="5">@{{ percentageRetentionGuarantor | percentage }}</td>
                                    </tr>--}}
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