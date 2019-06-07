<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h2>Datos de la calificacion</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
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
                        @if ($quota_aid->procedure_modality->id == 15)
                            @php($spouse=$affiliate->spouse->first())
                            <td>Cónyugue</td>
                            <td>{{ $spouse->fullName() }}</td>
                        @else
                            <td>Grado</td>
                            <td>{{ $affiliate->degree->shortened ?? '-'}}</td>
                        @endif
                        </tr>
                        <tr>
                            <td>Fecha de Fallecimiento</td>
                            <td class="text-uppercase">{{ $quota_aid->procedure_modality->id == 15 ? $spouse->getDateDeath() : $affiliate->getDateDeath() }}</td>
                        </tr>
                        <tr>
                            <td>Causa de Fallecimiento</td>
                            <td>{{ $quota_aid->procedure_modality->id == 15 ? $spouse->reason_death : $affiliate->reason_death }}</td>
                        </tr>
                        <tr class="font-bold">
                            <td>Total {{ $quota_aid->procedure_modality->procedure_type->name }}</td>
                            <td>Bs {{ Util::formatMoney($quota_aid->total) ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div>
            <div class="ibox" class="fadeInRight">
                <div class="ibox-title">
                    <h5>Descuentos</h5>
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
                            @foreach ($discounts as $d)
                                <tr>
                                    <td>{{ $d->shortened }}</td>
                                    <td>{{ $d->pivot->amount }}</td>
                                    <td>{{ $d->pivot->code }}</td>
                                    <td>{{ $d->pivot->date }}</td>
                                    <td>{{ $d->pivot->note_code }}</td>
                                    <td>{{ $d->pivot->note_code_date }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div>
                <div class="ibox" class="fadeInRight">
                    <div class="ibox-title">
                        <h5>Calculo de las cuotas partes para los derechohabientes</h5>
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
                                    <th></th>
                                    <th class="text-info">Bs {{ Util::formatMoney($quota_aid->total) }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($quota_aid->quota_aid_beneficiaries as $beneficary)
                                <tr>
                                    <td>{{ $beneficary->fullName() }}</td>
                                    <td>{{ $beneficary->percentage }}</td>
                                    <td>{{ Util::formatMoney($beneficary->paid_amount) }}</td>
                                    <td>{{ $beneficary->kinship->name ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>