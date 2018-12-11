<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="ibox-content">
            <div class="col-md-12">
                <legend>Datos para la carta de compromiso</legend>
                <div class="row">
                    <div class="col-md-6">
                        <div class="col-md-4">
                            <label class="control-label">Nro de Documento </label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="document_number" v-model.trim="document_number" class="form-control">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6" :class="{'has-error': errors.has('document_date') }">
                        <div class="col-md-4">
                            <label class="control-label">Fecha de Documento</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" v-date name="document_date" v-model.trim="document_date" v-validate.initial="'required|date_format:DD/MM/YYYY|max_current_date'">
                            <i v-show="errors.has('document_date')" class="fa fa-warning text-danger"></i>
                            <span v-show="errors.has('document_date')" class="text-danger">@{{ errors.first('document_date') }}</span>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6" :class="{'has-error': errors.has('commitment_date') }">
                        <div class="col-md-4">
                            <label class="control-label">Fecha de compromiso</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" v-date name="commitment_date" v-model.trim="commitment_date" v-validate.initial="'required|date_format:DD/MM/YYYY|max_current_date'">
                            <i v-show="errors.has('commitment_date')" class="fa fa-warning text-danger"></i>
                            <span v-show="errors.has('commitment_date')" class="text-danger">@{{ errors.first('commitment_date') }}</span>
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
                            <input type="text" class="form-control" v-month-year name="start_contribution_date" v-model.trim="start_contribution_date" v-validate.initial="'required|date_format:MM/YYYY|max_current_date_month_year'">
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