@extends('layouts.app')

@section('title', 'Afiliados')

@section('styles')
<link rel="stylesheet" href="/css/plugins/jquery.steps.css">
@endsection



@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
   <div class="col-lg-9">
       {{--  {{ Breadcrumbs::render('show_affiliate', $affiliate) }}  --}}
   </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-md-12">
            <affiliate-police :affiliate="{{ $affiliate }}" inline-template>
                @include('affiliates.simple_info', ['affiliate'=>$affiliate])
            </affiliate-police>
        </div>
        <div class="col-md-12">
            <div class="ibox-content">
                <ret-fun-form-create :requirements="{{ $requirements }}" :modalities="{{ $modalities }}" :modality="7">
                </ret-fun-form-create>
            </div>
        </div>
        {{--  <div class="col-md-12">
            {!! Form::open(['url' => 'ret_fun', 'method' => 'POST']) !!}
            <input type="hidden" name="affiliate_id" value="{{$affiliate->id}}">
            <form-wizard 

            color="#1AB394"
            title=""
            subtitle=""
            back-button-text="Volver"
            next-button-text="Siguiente"
            finish-button-text="Finish"
            error-color="#ED5565"
            >
                    <tab-content
                    title="Modalidad y Requisitos"
                    icon="mdi mdi-format-list-checks"
                    >
                        <ret-fun-step1-requirements :modalities="{{ $modalities }}" :requirements="{{ $requirements }}" inline-template>
                            @include('ret_fun.step1_requirements')
                        </ret-fun-step1-requirements>
                    </tab-content>
                    <tab-content
                        title="Datos del Solicitante"
                        icon="mdi mdi-account-edit">
                        <ret-fun-step2-applicant :cities="{{ $cities }}" inline-template>
                            @include('ret_fun.step2_applicant')
                        </ret-fun-step2-applicant>
                    </tab-content>
                    <tab-content
                    title="Datos de los Derechohabientes"
                    icon="mdi mdi-account-multiple-plus">
                    <ret-fun-step3-beneficiaries :items="{{ $ret }}" inline-template>
                        @include('ret_fun.step3_beneficiaries')
                    </ret-fun-step3-beneficiaries>
                </tab-content>
                {!! Form::submit('Click Me!') !!}
            </form-wizard>
        </form>
        </div>  --}}
    </div>
</div>



@endsection
@section('scripts')
<script src="/js/plugins/jquery.steps.min.js"></script>
<script src="/js/plugins/jquery.validator.min.js"></script>
    <script>
        $(document).ready(function(){

            $("#form").steps({
                bodyTag: "fieldset",
                onStepChanging: function (event, currentIndex, newIndex)
                {
                    // Always allow going backward even if the current step contains invalid fields!
                    if (currentIndex > newIndex)
                    {
                        return true;
                    }
                    // Forbid suppressing "Warning" step if the user is to young
                    if (newIndex === 3 && Number($("#age").val()) < 18)
                    {
                        return false;
                    }
                    var form = $(this);
                    // Clean up if user went backward before
                    if (currentIndex < newIndex)
                    {
                        // To remove error styles
                        $(".body:eq(" + newIndex + ") label.error", form).remove();
                        $(".body:eq(" + newIndex + ") .error", form).removeClass("error");
                    }
                    // Disable validation on fields that are disabled or hidden.
                    form.validate().settings.ignore = ":disabled,:hidden";
                    // Start validation; Prevent going forward if false
                    return form.valid();
                },
                onStepChanged: function (event, currentIndex, priorIndex)
                {
                    // Suppress (skip) "Warning" step if the user is old enough.
                    if (currentIndex === 2 && Number($("#age").val()) >= 18)
                    {
                        $(this).steps("next");
                    }
                    // Suppress (skip) "Warning" step if the user is old enough and wants to the previous step.
                    if (currentIndex === 2 && priorIndex === 3)
                    {
                        $(this).steps("previous");
                    }
                },
                onFinishing: function (event, currentIndex)
                {
                    var form = $(this);
                    // Disable validation on fields that are disabled.
                    // At this point it's recommended to do an overall check (mean ignoring only disabled fields)
                    form.validate().settings.ignore = ":disabled";
                    // Start validation; Prevent form submission if false
                    return form.valid();
                },
                onFinished: function (event, currentIndex)
                {
                    var form = $(this);
                    // Submit form input
                    form.submit();
                }
            }).validate({
                errorPlacement: function (error, element)
                {
                    element.before(error);
                },
                rules: {
                    confirm: {
                        equalTo: "#password"
                    }
                }
            });
       });
    </script>

@endsection