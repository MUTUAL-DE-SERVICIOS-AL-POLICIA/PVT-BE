@extends('layouts.app') 
@section('title', 'Afiliados') 
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9" id="ret-fun-form-header">
        {{ Breadcrumbs::render('create_direct_contribution', $affiliate) }}
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
                @endif {!! Form::open(['url' => 'direct_contribution', 'method' => 'POST', 'id'=>'direct-contribution-form']) !!}
                <input type="hidden" name="affiliate_id" value="{{$affiliate->id}}">
                <direct-contribution-form affiliate-id="{{$affiliate->id}}" inline-template>
                    <form-wizard color="#1AB394" title="" subtitle="" back-button-text="Volver" next-button-text="Siguiente" finish-button-text="Finalizar"
                        error-color="#ED5565" @on-complete="onFinish" @on-loading="setLoading">
                        <ret-fun-create-info></ret-fun-create-info>
                        <tab-content title="Modalidad y Requisitos" ref="uno" icon="mdi mdi-format-list-checks" :before-change="validateFirstStep">
                            <direct-contribution-step1-requirements
                                :modalities="{{ $modalities }}"
                                :requirements="{{ $requirements }}"
                                :user="{{ $user }}"
                                :cities="{{ $cities }}"
                                :procedure-types="{{$procedure_types}}"
                                :show-requirements-error="showRequirementsError"
                                inline-template>
                                @include('direct_contributions.step1_requirements')
                            </direct-contribution-step1-requirements>
                        </tab-content>
                        <tab-content title="Datos del Solicitante" ref="dos" icon="mdi mdi-account-edit" :before-change="validateSecondStep">
                            <direct-contribution-step2-contributor :cities="{{ $cities }}" :kinships="{{ $kinships }}" :affiliate="{{ $affiliate }}" :spouse="{{ $spouse }}" inline-template>
                                @include('direct_contributions.step2_contributor')
                            </direct-contribution-step2-contributor>
                        </tab-content>
                        <tab-content title="Datos de los Derechohabientes" ref="tres" icon="mdi mdi-account-multiple-plus">
                            <direct-contribution-step3-letter inline-template :today="`{{ now()->format('d/m/Y') }}`">
                                @include('direct_contributions.step3_letter')
                            </direct-contribution-step3-letter>
                        </tab-content>
                    </form-wizard>
                </direct-contribution-form>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection