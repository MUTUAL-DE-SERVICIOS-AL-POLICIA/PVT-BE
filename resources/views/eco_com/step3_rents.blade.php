<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <legend>
                Registrar Rentas de
                <strong>@{{ namePensionEntity }}</strong>
            </legend>
            <div class="row">
                <div class="col-md-7 col-xs-offset-3">
                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Fracción de Saldo Acumulado</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" v-money name="aps_total_fsa" v-model="aps_total_fsa">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Fracción de Cotización</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" v-money name="aps_total_cc" v-model="aps_total_cc">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Fracción Solidaria</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" v-money name="aps_total_fs" v-model="aps_total_fs">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Renta Invalidez</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" v-money name="aps_total_disability" v-model="aps_total_disability">
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Total Fracciones</label>
                            <div class="col-sm-4">
                                <strong>@{{totalSumFractions}}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 col-xs-offset-3">
                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Total Ganado Renta ó Pensión</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" v-money name="sub_total_rent" v-model="sub_total_rent">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Reintegro</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" v-money name="reimbursement" v-model="reimbursement">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Renta Dignidad</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" v-money name="dignity_pension" v-model="dignity_pension">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Renta Invalidez</label>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" v-money name="aps_total_disability" v-model="aps_total_disability">
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>

                    <div class="row">
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Total Renta ó Pensión</label>
                            <div class="col-sm-4">
                                <strong>@{{totalSumSenasir}}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>