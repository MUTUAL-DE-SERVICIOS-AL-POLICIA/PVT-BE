<div class="ibox">
    <div class="ibox-title">
            {{-- <div class="pull-left"> <legend > Aportes Activo</legend></div> --}}
        <h5> <b>Aportes Activo</b></h5>
        <div class="ibox-tools">
            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            {{-- <a class="fullscreen-link" data-toggle="tooltip" data-placement="bottom" title="Pantalla completa"><i class="fa fa-expand"></i></a> --}}
        </div>
    </div>
    <div class="ibox-content table-responsive">
        <div class="text-right">
            @can('update',new Muserpol\Models\Contribution\Contribution)
                <a href="{{route('show_contribution', $affiliate->id)}}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Detalle de Aportes"><i class="fa fa-eye"></i> Detalle </a>
                <a href="{{route('edit_contribution', $affiliate->id)}}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Edici&oacute;n de aportes"><i class="fa fa-key"></i> Editar </a>
            @endcan
            {{-- <a href="{{route('direct_contribution', $affiliate->id)}}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Aportes Directos"><i class="fa fa-pencil"></i> Aporte Directo </a> --}}
        </div>
        <br>
         <span class="badge" style="background-color:#a3bbd4; color: #353535; font-size: 12px" >Aportes para Primer FR</span>
         <span class="badge" style="background-color:#a897fc; color: #1b143f; font-size: 12px" >Aportes para Segundo FR</span>
         <span class="badge" style="background-color:#ffe6b3; color: #353535; font-size: 12px" >Aportes con Reintegro</span>
        <table class="table table-striped table-bordered table-hover size-13"  id="fixedheight">
            <thead>
                <tr>
                    <th>A&ntilde;o</th>
                    <th>Enero</th>
                    <th class="ellipsis-text">Febrero</th>
                    <th>Marzo</th>
                    <th>Abril</th>
                    <th>Mayo</th>
                    <th>Junio</th>
                    <th>Julio</th>
                    <th class="ellipsis-text">Agosto</th>
                    <th class="ellipsis-text">Septiembre</th>
                    <th class="ellipsis-text">Octubre</th>
                    <th class="ellipsis-text">Noviembre</th>
                    <th class="ellipsis-text">Diciembre</th>                                
                </tr>
            </thead>
            <tbody>                            
                @while($year_start>=$year_end)
                    <tr>
                        <td style="" class="alternativetext" >{{  $year_start   }}</td>
                        @for($i=1;$i<=12;$i++)
                            @php
                                $month = $i<10?'0'.$i:$i;
                            @endphp
                            <td 
                            @if(isset($reimbursements[$year_start.'-'.$month.'-01'])) style="border: 5px solid #ffe6b3" @endif 
                            @if(isset($contributions[$year_start.'-'.$month.'-01']['fr_procedure']) && $contributions[$year_start.'-'.$month.'-01']['fr_procedure'] == 1)
                            bgcolor="#a3bbd4"
                            @elseif(isset($contributions[$year_start.'-'.$month.'-01']['fr_procedure']) && $contributions[$year_start.'-'.$month.'-01']['fr_procedure'] == 2)
                            bgcolor="#a897fc"
                            @endif
                            class="numberformat"
                            > 
                                {{ $contributions[$year_start.'-'.$month.'-01']['value']??0 }} 
                            </td>
                        @endfor
                        @php
                        $year_start--;
                        @endphp
                    </tr>                                                   
                @endwhile
            </tbody>
        </table>
    </div>    
</div>