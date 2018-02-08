<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Derechohabientes</h5>
            <div class="ibox-tools">
                <a class="collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </a>
            </div>
        </div>
        <div class="ibox-content">
            <div class="feed-activity-list">
                @foreach ($beneficiaries as $beneficiary)
                    <div class="feed-element">
                        <div>
                            <small class="pull-right">(%)</small>
                            <strong>Primer Nombre: </strong><span> {{ $beneficiary->first_name }}</span>
                            <br>
                            <strong>Segundo Nombre: </strong><span> {{ $beneficiary->second_name }}</span>
                            <br>
                            <strong>Apellido Paterno:</strong><span>{{ $beneficiary->last_name }}</span>
                            <br>
                            <strong>Apellido Materno:</strong><span>{{ $beneficiary->mothers_last_name }}</span>
                            <br>
                            <strong>Apellido de Casada:</strong><span>{{ $beneficiary->surname_husband }}</span>
                            <br>
                            <small class="text-muted">Fecha de Nac.: {{ $beneficiary->birth_date }}</small>
                            <br>
                            <small class="text-muted">Parentesco: {{ $beneficiary->kinship->name ?? '' }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>