@extends('layouts.app') 
@section('title', 'Afiliados') 
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9" id="eco-com-form-header">
        {{ Breadcrumbs::render('create_eco_com', $affiliate) }}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <h2>Se encontraron los siguientes errores. ({{ count($errors->all()) }})</h2>
                        <ol>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ol>
                    </div>
                    @endif {!! Form::open(['route' => 'economic_complement_store', 'method' => 'POST', 'id'=>'eco-com-form']) !!}
                    <input type="hidden" name="eco_com_procedure_id" value="{{ $eco_com_procedure_id }}">
                    <input type="hidden" name="affiliate_id" value="{{ $affiliate->id }}">
                    <eco-com-form inline-template>
                        <form-wizard color="#1AB394" title="" subtitle="" back-button-text="Volver" next-button-text="Siguiente" finish-button-text="Finalizar"
                            error-color="#ED5565" @on-complete="onFinish" @on-loading="setLoading">
                            <eco-com-create-info></eco-com-create-info>

                            <tab-content title="Modalidad y Requisitos" icon="mdi mdi-format-list-checks" :before-change="validateFirstStep" key="uno">
                                <eco-com-step1-requirements ref="uno" :last-eco-com="{{ $last_eco_com }}" :pension-entities="{{ $pension_entities }}" :modalities="{{ $modalities }}"
                                    :affiliate="{{ $affiliate }}" :requirements="{{ $requirements }}" :user="{{ $user }}" :cities="{{ $cities }}"
                                    :show-requirements-error="showRequirementsError" :eco-com-procedure-id="{{ $eco_com_procedure_id }}" :eco-com-reception-types="{{ $eco_com_reception_types }}" :eco-com-consecutivo="{{ intval($affiliate->stop_eco_com_consecutively()) }}">
                                </eco-com-step1-requirements>
                            </tab-content>
                            <tab-content title="Datos del Solicitante" ref="dos" icon="mdi mdi-account-edit" :before-change="validateSecondStep" key="dos">
                                <eco-com-step2-beneficiary :cities="{{ $cities }}" :degrees="{{ $degrees }}" :categories="{{ $categories }}" :eco-com-legal-guardian-types="{{ $eco_com_legal_guardian_types }}" :financial-entities="{{ $financial_entities }}" :role-id="{{ Util::getRol()->id }}">
                                </eco-com-step2-beneficiary>
                            </tab-content>
                            <tab-content title="Registro de rentas" ref="tres" icon="mdi mdi-currency-usd" :before-change="validateThirdStep" key="tres">
                                <eco-com-step3-rents>
                                </eco-com-step3-rents>
                            </tab-content>
                        </form-wizard>
                    </eco-com-form>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection