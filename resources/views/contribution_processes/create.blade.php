@extends('layouts.app') 
@section('title', 'Afiliados') 
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9" id="ret-fun-form-header">
        {{ Breadcrumbs::render('create_contribution_process', $affiliate) }}
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
                @endif {!! Form::open(['url' => 'contribution_process', 'method' => 'POST', 'id'=>'contribution-process-form']) !!}
                <input type="hidden" name="affiliate_id" value="{{$affiliate->id}}">
                <contribution-process-form affiliate-id="{{$affiliate->id}}" inline-template>
                    <form-wizard color="#1AB394" title="" subtitle="" back-button-text="Volver" next-button-text="Siguiente" finish-button-text="Finalizar"
                        error-color="#ED5565" @on-complete="onFinish" @on-loading="setLoading">
                        <ret-fun-create-info></ret-fun-create-info>
                        <tab-content title="Modalidad y Requisitos" ref="uno" icon="mdi mdi-format-list-checks" :before-change="validateFirstStep">
                            <contribution-process-step1-requirements
                                :modalities="{{ $modalities }}"
                                :requirements="{{ $requirements }}"
                                :user="{{ $user }}"
                                :cities="{{ $cities }}"
                                :procedure-types="{{$procedure_types}}"
                                :show-requirements-error="showRequirementsError"
                                inline-template>
                                @include('contribution_processes.step1_requirements')
                            </contribution-process-step1-requirements>
                        </tab-content>
                        <tab-content title="Datos del Solicitante" ref="dos" icon="mdi mdi-account-edit" :before-change="validateSecondStep">
                            <contribution-process-step2-contributor :cities="{{ $cities }}" :kinships="{{ $kinships }}" :affiliate="{{ $affiliate }}" :spouse="{{ $spouse }}" inline-template :today="`{{ now()->format('d/m/Y') }}`">
                                @include('contribution_processes.step2_contributor')
                            </contribution-process-step2-contributor>
                        </tab-content>
                        <tab-content title="Datos de los Derechohabientes" ref="tres" icon="mdi mdi-account-multiple-plus">
                            <contribution-process-step3-letter inline-template >
                                @include('contribution_processes.step3_letter')
                            </contribution-process-step3-letter>
                        </tab-content>
                    </form-wizard>
                </contribution-process-form>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection