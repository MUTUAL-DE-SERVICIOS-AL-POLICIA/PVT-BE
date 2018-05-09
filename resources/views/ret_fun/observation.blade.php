<div class="col-lg-12">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h3 class="pull-left">Observaciones</h3>
            <div class="text-right">
                @can('create',Muserpol\Models\AffiliateFolder::class)
                <a href="" class="btn btn-primary" data-toggle="modal" data-target="#folderModalRe">
                        <i class="fa fa-plus"> </i>
                </a>
                @else
                <br>
                @endcan
            </div>
        </div>
        <div >
            {{-- <div class="ibox-content table-responsive"> --}}
                <table class="table table-hover table-sprite">
                    <thead>
                        <tr>
                            <th> Fecha </th>
                            <th> Tipo de Observación </th>
                            <th> Descripción </th>
                            <th> Habilidado </th>
                            <th> Opciones </th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($observations as $observation )
                    <tr>
                        <td> {{ $observation->date }} </td>
                        <td> {{ $observation->observation_type_id }} </td>
                        <td> {{ $observation->message }} </td>
                        <td> {{ $observation->is_enabled }} </td>
                            <td><button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#eliminar" data-elim="{{ $observation->id }}"><i class="fa fa-trash" aria-hidden="true" ></i></button></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            {{-- </div> --}}
        </div>
    </div>
</div>