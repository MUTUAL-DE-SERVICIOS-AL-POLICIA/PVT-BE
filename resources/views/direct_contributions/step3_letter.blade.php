<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <div class="col-md-12">
                <legend>Datos para la carta de compromiso</legend>
                <div class="row">
                    <div class="col-md-6">
                        <div class="col-md-4">
                            <label class="control-label">Declaración de Pensión</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="pension_declaration" v-model.trim="pension_declaration" class="form-control">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6" :class="{'has-error': errors.has('pension_declaration_date') }">
                        <div class="col-md-4">
                            <label class="control-label">Fecha de Declaración</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" v-date name="pension_declaration_date" v-model.trim="pension_declaration_date" v-validate.initial="'required|date_format:DD/MM/YYYY|max_current_date'">
                            <i v-show="errors.has('pension_declaration_date')" class="fa fa-warning text-danger"></i>
                            <span v-show="errors.has('pension_declaration_date')" class="text-danger">@{{ errors.first('pension_declaration_date') }}</span>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6" :class="{'has-error': errors.has('date_commitment') }">
                        <div class="col-md-4">
                            <label class="control-label">Fecha de compromiso</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" v-date name="date_commitment" v-model.trim="date_commitment" v-validate.initial="'required|date_format:DD/MM/YYYY|max_current_date'">
                            <i v-show="errors.has('date_commitment')" class="fa fa-warning text-danger"></i>
                            <span v-show="errors.has('date_commitment')" class="text-danger">@{{ errors.first('date_commitment') }}</span>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6" :class="{'has-error': errors.has('start_contribution_date') }">
                        <div class="col-md-4">
                            <label class="control-label">Periodo de primer aporte</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" v-date name="start_contribution_date" v-model.trim="start_contribution_date" v-validate.initial="'required|date_format:DD/MM/YYYY|max_current_date'">
                            <i v-show="errors.has('start_contribution_date')" class="fa fa-warning text-danger"></i>
                            <span v-show="errors.has('start_contribution_date')" class="text-danger">@{{ errors.first('start_contribution_date') }}</span>
                        </div>
                    </div>
                </div>
                <br>
            </div>
            <div class="row"></div>
        </div>
    </div>
</div>