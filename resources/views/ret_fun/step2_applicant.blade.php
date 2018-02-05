<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Datos del Solicitante <small>something...</small></h5>
            <div class="ibox-tools">
            </div>
        </div>
        <div class="ibox-content">
            <form method="get" class="form-horizontal">
                <div class="hr-line-dashed"></div>
                <div class="form-group"><label class="col-sm-4 control-label">Tipo de Solicitante</label>
                    <div class="col-sm-4">
                        <select class="form-control m-b" name="account" @change="change_applicant()" v-model="applicant_type">
                            <option value="Beneficiario">Beneficiario</option>
                            <option value="Apoderado">Apoderado</option>
                            <option value="Tutor">Tutor</option>
                        </select>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group"><label class="col-sm-4 control-label">Primer Nombre</label>
                            <div class="col-sm-8"><input type="text" class="form-control"></div>
                            </div>
                        </div>
                    <div class="col-md-6">
                        <div class="form-group"><label class="col-sm-4 control-label">Segundo Nombre</label>
                            <div class="col-sm-8"><input type="text" class="form-control"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group"><label class="col-sm-6 control-label">Apellido Paterno</label>
                            <div class="col-sm-6"><input type="text" class="form-control"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label class="col-sm-6 control-label">Apellido Materno</label>
                            <div class="col-sm-6"><input type="text" class="form-control"></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group"><label class="col-sm-6 control-label">Apellido de Casada</label>
                            <div class="col-sm-6"><input type="text" class="form-control"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group"><label class="col-sm-6 control-label">Carnet de Identidad</label>
                            <div class="col-sm-6"><input type="text" class="form-control"></div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group"><label class="col-sm-4 control-label">Ciudad de Expedicion</label>
                            <div class="col-sm-8">
                                <select class="form-control m-b" name="account">
                                    <option>option 1</option>
                                    <option>option 2</option>
                                    <option>option 3</option>
                                    <option>option 4</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hr-line-dashed"></div>
                <div class="row" v-if=" show_advisor_form ">
                    <h3 class="m-t-none m-b">Datos del Tutor Legal</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group"><label class="col-sm-4 control-label">Primer Nombre</label>
                                <div class="col-sm-8"><input type="text" class="form-control"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group"><label class="col-sm-4 control-label">Segundo Nombre</label>
                                <div class="col-sm-8"><input type="text" class="form-control"></div>
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                </div>
                <div class="row" v-if=" show_apoderado_form ">
                    <h3 class="m-t-none m-b">Datos del Apoderado</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group"><label class="col-sm-4 control-label">Primer Nombre</label>
                                <div class="col-sm-8"><input type="text" class="form-control"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group"><label class="col-sm-4 control-label">Segundo Nombre</label>
                                <div class="col-sm-8"><input type="text" class="form-control"></div>
                            </div>
                        </div>
                    </div>
                    <div class="hr-line-dashed"></div>
                </div>
            </form>
        </div>
    </div>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Direccion del Solicitante (@{{ applicant_type }}) <small class="m-l-sm">something //// </small></h5>
            <div class="ibox-tools">
            </div>
        </div>
        <div class="ibox-content">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group"><label class="col-sm-4 control-label">Zona</label>
                        <div class="col-sm-8"><input type="text" class="form-control"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group"><label class="col-sm-4 control-label">Calle</label>
                        <div class="col-sm-8"><input type="text" class="form-control"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group"><label class="col-sm-4 control-label">Nro</label>
                        <div class="col-sm-8"><input type="text" class="form-control"></div>
                    </div>
                </div>
            </div>  
        </div>
    </div>
</div>