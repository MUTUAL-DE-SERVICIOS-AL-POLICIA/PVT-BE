@extends('layouts.app')

@section('title', 'Afiliados')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{--  {{ Breadcrumbs::render('show_affiliate', $affiliate) }}  --}}
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
<!--    <div class="row">
        <div class="col-md-12">
            
        </div>
            
    </div>-->
    <input type="hidden" name="affiliate_id" value="{{$affiliate->id}}">    
    <div class="row">
        <div class="col-md-12">
            {{--  <form-wizard-ret-fund></form-wizard-ret-fund>  --}}
            <form-wizard 
        color="#1AB394"
        error-color="#ED5565">
        <!-- <tab-content v-for="tab in tabs"
                    v-if="!tab.hide"
                    :key="tab.title"
                    :title="tab.title"
                    :icon="tab.icon"
                    :before-change="validateAsync">
            <component :is="tab.component"></component>
        </tab-content> -->
        <tab-content
            title="Modalidad y Requisitos"
            icon="mdi mdi-format-list-checks"
            {{--  :before-change="validateAsync"  --}}
            >
                <step1-requirement inline-template>
                    @include('ret_fun.step1_requirements')
                </step1-requirement>
        </tab-content>
        <tab-content
            title="Datos del Solicitante"
            icon="mdi mdi-account-edit">
                <step2-applicant inline-template>
                    @include('ret_fun.step2_applicant')
                </step2-applicant>
        </tab-content>
        <tab-content
            title="Datos de los Derechohabientes"
            icon="mdi mdi-account-multiple-plus">
                <temp inline-template>
                    <div>tres</div>
                </temp>
        </tab-content>
    </form-wizard>
        </div>
    </div> 
</div>

@endsection
