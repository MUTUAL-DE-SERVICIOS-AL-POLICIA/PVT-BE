<div class="ibox">
    <div class="ibox-title">            
        <h5> <b>Aportes Pasivo</b></h5>
        <div class="ibox-tools">
            <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>            
        </div>
    </div>
    <div class="ibox-content table-responsive">
        <div class="text-right">
            {{-- @can('update',new Muserpol\Models\Contribution\AidContribution) --}}
                <a href="{{route('show_aid_contribution', $affiliate->id)}}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Detalle de Aportes"><i class="fa fa-eye"></i> Detalle </a>
                <a href="{{route('edit_aid_contribution', $affiliate->id)}}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Edici&oacute;n de aportes"><i class="fa fa-key"></i> Editar </a>
            {{-- @endcan --}}
            {{-- <a href="{{route('direct_aid_contribution', $affiliate->id)}}" class="btn btn-primary" data-toggle="tooltip" data-placement="top" title="Aportes Directos"><i class="fa fa-pencil"></i> Aporte Directo </a> --}}
        </div>
        <br>
        <table class="table table-striped-1 table-bordered table-hover size-13"  id="fixedheight">
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
                        <th style="" class="alternativetext" >{{  $year_start   }}</th>
                        @for($i=1;$i<=12;$i++)
                            @php
                                $month = $i<10?'0'.$i:$i;
                            @endphp
                            <th @if(isset($reimbursements[$year_start.'-'.$month.'-01'])) bgcolor="#ffe6b3" @endif class="numberformat"> {{ $contributions[$year_start.'-'.$month.'-01']??0 }} </th>
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