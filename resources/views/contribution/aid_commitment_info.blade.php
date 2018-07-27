<div class="col-md-12">    
    <div class="panel panel-primary" >
        <div class="panel-body " >
            <div class="box box-success box-solid">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="box-title"><span class="glyphicon glyphicon-info-sign"></span> Compromiso de Pago</h3>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-responsive" style="width:100%;">
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6">
                                                Aportante
                                            </div>
                                            <div class="col-md-6">
                                                {{ $commitment->contributor=='T'?'Titular':'C&oacute;nyuge' }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6">
                                                Fecha de Compromiso
                                            </div>
                                            <div class="col-md-6">
                                                {{  Util::getStringDate($commitment->date_commitment) }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table" style="width:100%;">
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6">
                                                Declaraci&oacute;n de Pensi&oacute;n 
                                            </div>
                                            <div class="col-md-6">
                                                {{ $commitment->pension_declaration }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6">
                                                Fecha de Declaraci&oacute;;n
                                            </div>
                                            <div class="col-md-6">
                                                {{  Util::getStringDate($commitment->pension_declaration_date) }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
