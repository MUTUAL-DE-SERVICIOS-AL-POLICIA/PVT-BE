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
                @endif {!! Form::open(['route' => 'eco_com_process_store', 'method' => 'POST', 'id'=>'eco-com-process-form']) !!}
                <input type="hidden" name="affiliate_id" value="{{$affiliate->id}}">
                <eco-com-process-form inline-template>
                    <form-wizard color="#1AB394" title="" subtitle="" back-button-text="Volver" next-button-text="Siguiente" finish-button-text="Finalizar"
                        error-color="#ED5565" @on-complete="onFinish" @on-loading="setLoading">
                        {{--
                        <ret-fun-create-info></ret-fun-create-info> --}}
                        <tab-content title="Modalidad y Requisitos" ref="uno" icon="mdi mdi-format-list-checks">
                            <eco-com-process-step1-requirements :modalities="{{ $modalities }}" :requirements="{{ $requirements }}" :user="{{ $user }}"
                                :cities="{{ $cities }}" :pension-entities="{{ $pension_entities }}" :initial-pension-entity-id="{{ $affiliate->pension_entity_id ?? 0 }}"
                                :procedure-types="{{$procedure_types}}" :show-requirements-error="showRequirementsError" inline-template>
                                @include('eco_com_process.step1_requirements')
                            </eco-com-process-step1-requirements>
                        </tab-content>
                        <tab-content title="Datos del Solicitante" ref="dos" icon="mdi mdi-account-edit">
                            <eco-com-process-step2-applicant :cities="{{ $cities }}" :affiliate="{{ $affiliate }}" :spouse="{{ $spouse }}"
                                inline-template>
                                @include('eco_com_process.step2_applicant')
                            </eco-com-process-step2-applicant>
                        </tab-content>
                        {{-- <tab-content title="Datos de los Derechohabientes" icon="mdi mdi-account-multiple-plus">
                            <ret-fun-step3-beneficiaries :items="{{ $ret }}" :kinhsips="{{ $kinships }}" inline-template>
    @include('ret_fun.step3_beneficiaries')
                            </ret-fun-step3-beneficiaries>
                        </tab-content> --}}
                    </form-wizard>
                </eco-com-process-form>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection