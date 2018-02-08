@extends('layouts.app')

@section('title', 'Cuota y Auxilio Mortuorio')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
   <div class="col-lg-9">
       {{--  {{ Breadcrumbs::render('show_affiliate', $affiliate) }}  --}}
   </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-md-12">
            {!! Form::open(['url' => 'ret_fun', 'method' => 'POST']) !!}
            <input type="hidden" name="affiliate_id" value="{{$affiliate->id}}">
            <form-wizard 
            color="#1AB394"
            error-color="#ED5565"
            >
                    <tab-content
                    title="Modalidad y Requisitos"
                    icon="mdi mdi-format-list-checks"
                    {{--  :before-change="validateAsync"  --}}
                    >
                        <quota-aid-step1-requirements :modalities="{{ $modalities }}" :requirements="{{ $requirements }}" inline-template>
                            @include('quota_aid.step1_requirements')
                        </quota-aid-step1-requirements>
                    </tab-content>
                    <tab-content
                        title="Datos del Solicitante"
                        icon="mdi mdi-account-edit">
                        <quota-aid-step2-applicant :cities="{{ $cities }}" inline-template>
                            @include('quota_aid.step2_applicant')
                        </quota-aid-step2-applicant>
                    </tab-content>
                    <tab-content
                    title="Datos de los Derechohabientes"
                    icon="mdi mdi-account-multiple-plus">
                    <quota-aid-step3-beneficiaries :items="{{ $ret }}" inline-template>
                        @include('quota_aid.step3_beneficiaries')
                    </quota-aid-step3-beneficiaries>
                </tab-content>
                {!! Form::submit('Click Me!') !!}
            </form-wizard>
        </form>
        </div>
    </div>
</div>


@endsection