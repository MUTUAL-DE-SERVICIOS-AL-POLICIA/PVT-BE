@extends('layouts.app') 
@section('title', 'Afiliados') 
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9" id="ret-fun-form-header">
        {{ Breadcrumbs::render('create_eco_com_process', $affiliate) }}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-md-12">
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
                @endif {!! Form::open(['route' => 'economic_complement_store', 'method' => 'POST', 'id'=>'eco-com-form']) !!} {{--
                <input type="hidden" name="eco_com_process_id" value="{{ $eco_com_process->id }}"> --}}
                <input type="hidden" name="eco_com_procedure_id" value="{{ $eco_com_procedure_id }}">
                <eco-com-form inline-template>
                    <form-wizard color="#1AB394" title="" subtitle="" back-button-text="Volver" next-button-text="Siguiente" finish-button-text="Finalizar"
                        error-color="#ED5565" @on-complete="onFinish" @on-loading="setLoading">
                        {{--
                        <ret-fun-create-info></ret-fun-create-info> --}} {{--
                        <tab-content title="Modalidad y Requisitos" ref="uno" icon="mdi mdi-format-list-checks">
                            <eco-com-step1-requirements :last-eco-com="{{ $last_eco_com }}" :pension-entities="{{ $pension_entities }}" :modalities="{{ $modalities }}"
                                :affiliate="{{ $affiliate }}" :requirements="{{ $requirements }}" :user="{{ $user }}" :cities="{{ $cities }}"
                                :show-requirements-error="showRequirementsError">
                            </eco-com-step1-requirements>
                        </tab-content> --}}
                        <tab-content title="Datos del Solicitante" ref="dos" icon="mdi mdi-account-edit">
                            <eco-com-step1-requirements :last-eco-com="{{ $last_eco_com }}" :pension-entities="{{ $pension_entities }}" :modalities="{{ $modalities }}"
                                :affiliate="{{ $affiliate }}" :requirements="{{ $requirements }}" :user="{{ $user }}" :cities="{{ $cities }}"
                                :show-requirements-error="showRequirementsError">
                            </eco-com-step1-requirements>
                            <eco-com-step2-beneficiary :cities="{{ $cities }}">
                            </eco-com-step2-beneficiary>
                        </tab-content>
                        {{--
                        <tab-content title="Registro de rentas" icon="mdi mdi-account-multiple-plus">
                            <eco-com-step3-rents :pension-entity-id="{{ $affiliate->pension_entity_id ?? 0 }}" :eco-com="{{ $eco_com }}" inline-template>
    @include('eco_com.step3_rents')
                            </eco-com-step3-rents>
                        </tab-content> --}}
                    </form-wizard>
                </eco-com-form>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection