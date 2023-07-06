<div class="col-lg-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="pull-left">Informaci&oacute;n Policial</h3>
            @can('update',$affiliate)
            <div class="text-right">
                <button data-animation="flip" class="btn btn-primary" :class="editing ? 'active': ''" @click="toggle_editing"><i class="fa" :class="editing ?'fa-unlock':'fa-lock'" ></i> </button>
            </div>
            @else
            <br>
            @endcan
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="ibox-content table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td class="font-bold">Cédula de identidad:</td>
                                <td>{{ $affiliate->identity_card }}
                                <td class="font-bold">Matr&iacute;cula:</td>
                                <td>{{ $affiliate->registration }}</td>
                                <td class="font-bold">Primer Nombre:</td>
                                <td>{{ $affiliate->first_name }}</td>
                                <td class="font-bold">Segundo Nombre:</td>
                                <td>{{ $affiliate->second_name }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold">Apellido Paterno:</td>
                                <td>{{ $affiliate->last_name }}</td>
                                <td class="font-bold">Apellido Materno:</td>
                                <td>{{ $affiliate->mothers_last_name }}</td>
                                <td class="font-bold">Apellido de Casada:</td>
                                <td>{{ $affiliate->surname_husband }}</td>
                                <td class="font-bold">Fecha de Nac.:</td>
                                <td>{{ $affiliate->birth_date }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold">Estado:</td>
                                <td>{{ $affiliate->affiliate_state ?? '' }}</td>
                                <td class="font-bold">Fecha de Ingreso a la Institucional Policial:</td>
                                <td>{{ $affiliate->date_entry }}</td>
                                <td class="font-bold">Categoria:</td>
                                <td>{{ $affiliate->category }}</td>
                                <td class="font-bold">Grado:</td>
                                <td data-toggle="tooltip" data-placement="right" title="{{ $affiliate->degree->name ?? '' }}">{{ $affiliate->degree }} </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
