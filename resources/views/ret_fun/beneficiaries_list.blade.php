@foreach ($beneficiaries as $beneficiary)
    <div class="panel-body ">
        <div class="col-md-6">
            <dl class="dl-">
                <dt>Cedula de identidad:</dt>
                <dd>{{ $beneficiary->identity_card }} {{ !!$beneficiary->city_identity_card ? $beneficiary->city_identity_card->first_shortened
                    : '' }}</dd>
                <dt>Primer Nombre:</dt>
                <dd>{{ $beneficiary->first_name }}</dd>
                <dt>Segundo Nombre:</dt>
                <dd>{{ $beneficiary->second_name }}</dd>
                <dt>Apellido Paterno:</dt>
                <dd>{{ $beneficiary->last_name }}</dd>
                <dt>Apellido Materno:</dt>
                <dd>{{ $beneficiary->mothers_last_name }}</dd>
                <dt>Apellido de Casada:</dt>
                <dd>{{ $beneficiary->surname_husband }}</dd>
            </dl>
        </div>
        <div class="col-md-6">
            <dl class="dl-">
                <dt>Fecha de Nacimiento:</dt>
                <dd>{{ $beneficiary->birth_date }}</dd>
                <dt>Edad:</dt>
                <dd>{{ $beneficiary->birth_date }}</dd>
                <dt>Parentesco:</dt>
                <dd>{{ $beneficiary->kinship->name }}</dd>
            </dl>
        </div>
    </div>
@endforeach