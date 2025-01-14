@extends('layouts.app')
@section('title', 'Cuota y Auxilio Mortuorio')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{ Breadcrumbs::render('create_quota_aid', $affiliate) }}
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
                @endif
                {!! Form::open(['url' => 'quota_aid', 'method' => 'POST', 'id'=>'quota-aid-form']) !!}
                <input type="hidden" name="affiliate_id" value="{{$affiliate->id}}">
                <quota-aid-form inline-template>
                    <div class="form-wizard-container">
                        <form-wizard
                            color="#1AB394"
                            title=""
                            subtitle=""
                            back-button-text="Volver"
                            next-button-text="Siguiente"
                            finish-button-text="Finalizar"
                            error-color="#ED5565"
                            @on-complete="onFinish"
                            @on-loading="setLoading"
                        >
                            <quota-aid-create-info :affiliate="{{ $affiliate }}" :hierarchy="{{ $hierarchy }}"></quota-aid-create-info>
                            <tab-content title="Modalidad y Requisitos" ref="one" icon="mdi mdi-format-list-checks" :before-change="validateFirstStep">
                                <quota-aid-step1-requirements :modalities="{{ $modalities }}" :requirements="{{ $requirements }}" :user="{{ Auth::user() }}" :cities="{{ $cities }}" :procedure-types="{{$procedure_types}}" :show-requirements-error="showRequirementsError" :affiliate="{{ $affiliate }}"
                                        inline-template>
                                    @include('quota_aid.step1_requirements')
                                </quota-aid-step1-requirements>
                            </tab-content>
                            <tab-content title="Datos del Solicitante" ref="two" icon="mdi mdi-account-edit" :before-change="validateSecondStep">
                                <quota-aid-step2-applicant :cities="{{ $cities }}" :kinships="{{ $kinships }}" :affiliate="{{ $affiliate }}" :spouse="{{ $spouse }}" :degrees="{{ $degrees }}" inline-template>
                                    @include('quota_aid.step2_applicant')
                                </quota-aid-step2-applicant>
                            </tab-content>
                            <tab-content title="Datos de los Derechohabientes" icon="mdi mdi-account-multiple-plus">
                                <quota-aid-step3-beneficiaries :items="{{ $ret }}" :kinhsips="{{ $kinships }}" inline-template>
                                    @include('quota_aid.step3_beneficiaries')
                                </quota-aid-step3-beneficiaries>
                            </tab-content>
                        </form-wizard>
                        <div v-if="loading" class="spinner-overlay">
                            <div class="spinner"></div>
                        </div>
                    </div>
                </quota-aid-form>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection

