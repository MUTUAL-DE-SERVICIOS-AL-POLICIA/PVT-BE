@extends('layouts.app')
@section('title', 'Afiliados')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9" id="ret-fun-form-header">
        {{ Breadcrumbs::render('create_retirement_fund', $affiliate) }}
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
                {!! Form::open(['url' => 'ret_fun', 'method' => 'POST', 'id'=>'ret-fun-form']) !!}
                <input type="hidden" name="affiliate_id" value="{{$affiliate->id}}">
                <ret-fun-form inline-template>
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
                        <ret-fun-create-info></ret-fun-create-info> 
                            <tab-content title="Modalidad y Requisitos" ref="uno" icon="mdi mdi-format-list-checks" :before-change="validateFirstStep">
                                <ret-fun-step1-requirements :modalities="{{ $modalities }}" :user="{{ $user }}" :cities="{{ $cities }}" :procedure-types="{{$procedure_types}}" :affiliate="{{ $affiliate }}"
                                >
                                </ret-fun-step1-requirements>
                            </tab-content>
                            <tab-content title="Datos del Solicitante" ref="dos" icon="mdi mdi-account-edit" :before-change="validateSecondStep" >
                                <ret-fun-step2-applicant v-if="showSecondStep" :cities="{{ $cities }}" :kinships="{{ $kinships }}" :affiliate="{{ $affiliate }}" :spouse="{{ $spouse }}" :has_ret_fun="{{ $has_ret_fun }}" inline-template>
                                    @include('ret_fun.step2_applicant')
                                </ret-fun-step2-applicant>
                            </tab-content>
                            <tab-content title="Datos de los Derechohabientes" icon="mdi mdi-account-multiple-plus">
                                <ret-fun-step3-beneficiaries :items="{{ $ret }}" :kinhsips="{{ $kinships }}" :kinship_beneficiaries="{{ $kinship_beneficiaries }}" inline-template>
                                    @include('ret_fun.step3_beneficiaries')
                                </ret-fun-step3-beneficiaries>
                            </tab-content>
                        </form-wizard>
                        <div v-if="loading" class="spinner-overlay">
                            <div class="spinner"></div>
                        </div>
                    </div>
                </ret-fun-form>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection
