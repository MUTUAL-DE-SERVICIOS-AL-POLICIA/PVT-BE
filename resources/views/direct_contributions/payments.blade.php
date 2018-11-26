<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h2>Datos de la calificacion</h2>
                    @if ($procedure_type->id == 6)
                        <contribution-create
                            :afid="{{ $affiliate->id }}"
                            {{-- :last_quotable="{{$last_quotable}}"
                            :commitment="{{ $commitment }}"
                            :is_regional="`{{ $is_regional }}`" --}}
                            >
                        </contribution-create>
                    @endif
                    @if ($procedure_type->id == 7)
                    {{-- PASIVO --}}
                        @if ( $direct_contribution->hasActiveContributionProcess())
                            <aid-contribution-edit
                                :disable="true"
                                :direct-contribution-id="{{ $direct_contribution->id }}"
                                :affiliate-id="{{ $affiliate->id }}"
                                :aid-contributions="{{ ($contribution_process->aid_contributions) }}"
                                :total="{{ $contribution_process->aid_contributions()->sum('total') }}"
                            ></aid-contribution-edit>
                            @if (Util::getRol()->id == 62)
                                <div class="row text-center">
                                    <button class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                                </div>
                            @endif
                        @else
                            <aid-contribution-create
                                direct-contribution-id="{{ $direct_contribution->id }}"
                                :afid="{{ $affiliate->id }}"
                            ></aid-contribution-create>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h2>Cobro</h2>                                            
                        <direct-contribution-payment
                            :direct_contribution="{{ $direct_contribution }}"                                
                        ></direct-contribution-payment>                    
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content forum-container">
                    <div class="row">
                        <table class="table table-stripped toggle-arrow-tiny">
                            <thead>
                                <tr>
                                    <th>Codigo</th>
                                    <th>Ubicacion</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($contribution_processes as $contribution_process)
                                    <tr>
                                        <td>{{ $contribution_process->code }}</td>
                                        <td>{{ $contribution_process->wf_state->name }}</td>
                                        <td>{{ $contribution_process->date }}</td>
                                        <td><a href="#"><i class="fa fa-check text-navy"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>

@section('scripts')
    <script src="http://webapplayers.com/inspinia_admin-v2.8/js/plugins/footable/footable.all.min.js"></script>
    <script>
    $(document).ready(function() {
        console.log("hola");
        
            $('.footable').footable();
            $('.footable2').footable();

        });
    </script>
@endsection