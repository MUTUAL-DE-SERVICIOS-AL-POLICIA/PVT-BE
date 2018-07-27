
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
                                                    Tipo
                                                </div>
                                                <div class="col-md-6">
                                                    {{ $commitment->commitment_type }}
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
                                                    {{  Util::getStringDate($commitment->commitment_date) }}
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
                                                    Memorandum 
                                                </div>
                                                <div class="col-md-6">
                                                    {{ $commitment->number }}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    Destino
                                                </div>
                                                <div class="col-md-6">
                                                    {{ $commitment->destination }}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    Fecha
                                                </div>
                                                <div class="col-md-6">
                                                    {{  Util::getStringDate($commitment->commision_date) }}
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
    