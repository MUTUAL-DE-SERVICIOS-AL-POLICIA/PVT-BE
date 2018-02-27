
<div class="col-md-12">
    <div class="panel panel-primary" >
        <div class="panel-body " >
            <div class="box box-success box-solid">
                <div class="box-header with-border">
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="box-title"><span class="glyphicon glyphicon-info-sign"></span> Información Adicional</h3>
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
                                                Fecha de Ingreso
                                            </div>
                                            <div class="col-md-6">
                                                {{ $summary['dateentry'] }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6">
                                                Total Aporte
                                            </div>
                                            <div class="col-md-6">
                                                Bs {{ number_format($summary['total'],2) }}
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
                                                Total Fondo Retiro
                                            </div>
                                            <div class="col-md-6">
                                                Bs {{ number_format($summary['fondoret'],2) }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6">
                                                Total Cuota Mortuoria
                                            </div>
                                            <div class="col-md-6">
                                                Bs {{ number_format($summary['quotaaid'],2) }}
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
