<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h2>Aportes y periodos considerados</h2>
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
                            <i class="fa fa-minus" v-else-if="contributionType.operator == '-'"></i> @{{ contributionType.name
                            }} <span data-toggle="tooltip" data-placement="top" :title="contributionType.description"><i class="fa fa-question-circle" style="opacity:.7"></i></span>
                        </h4>
                    </div>
                    <ret-fun-qualification-group :dates-child="contributionType.dates">
                    </ret-fun-qualification-group>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row">
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
    </div> --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content forum-container">
                    <div class="forum-title">
                        <h3>Tabla de contribuciones</h3>
                    </div>
                    <div class="row">
                        <div class="col-xs-offset-1 col-md-10">
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
                </div>
            </div>
        </div>
    </div>
</div>