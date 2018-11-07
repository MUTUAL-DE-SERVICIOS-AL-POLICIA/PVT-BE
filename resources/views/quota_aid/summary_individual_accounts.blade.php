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
                        <h4></h4>
                    </div>
                    <ret-fun-qualification-group :dates-child="{{ json_encode($dates['dates']) }}">
                    </ret-fun-qualification-group>
                </div>
            </div>
        </div>
    </div>
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
                                        <th></th>
                                        <th class="text-center">Años</th>
                                        <th class="text-center">Meses</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr class="success">
                                        <td><strong>Total de cotizaciones para Calificacion</strong></td>
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