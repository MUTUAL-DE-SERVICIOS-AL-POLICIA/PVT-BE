@extends('layouts.app')

@section('title', 'Afiliados')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{ Breadcrumbs::render('show_affiliate', $affiliate) }}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row text-center">
        @can('create',Muserpol\Models\RetirementFund\RetirementFund::class)
        <a href="{{route('create_ret_fun', $affiliate->id)}}">
            <button class="btn btn-info btn-sm dim" type="button" data-toggle="tooltip" data-placement="top" title="Iniciar tr&aacute;mite de FONDO DE RETIRO" ><i class="fa fa-paste"></i></button>
        </a>
        @endcan
        {{-- <a href="{{route('create_quota_aid', $affiliate->id)}}" >
            <button class="btn btn-info btn-sm  dim" type="button" data-toggle="tooltip" data-placement="top" title="Initar tr&aacute;mite de CUOTA Y AUXILIO MORTUORIO"><i class="fa fa-paste"></i> </button>
        </a> --}}
        
        @can('create',Muserpol\Models\Contribution\Contribution::class)
        <a href="{{route('create_contribution', $affiliate->id)}}" >
            <button class="btn btn-info btn-sm  dim" type="button" data-toggle="tooltip" data-placement="top" title="Initar tr&aacute;mite de PAGO DE APORTES"><i class="fa fa-paste"></i> </button>
        </a>
        @endcan
        @can('view',new Muserpol\Models\Contribution\Contribution)
        <a href="{{route('show_contribution', $affiliate->id)}}" >
            <button class="btn btn-info btn-sm  dim" type="button" data-toggle="tooltip" data-placement="top" title="Ver Aportes"><i class="fa fa-paste"></i> </button>
        </a>
        @endcan
    </div>

    <div class="row">
        <div class="col-md-6">
            <affiliate-show  :affiliate="{{ $affiliate }}" inline-template> 
                   @include('affiliates.affiliate_personal_information',['affiliate'=>$affiliate,'cities'=>$cities,'birth_cities'=>$birth_cities])
            </affiliate-show> 
        </div>
        <div class="col-md-6">
            <affiliate-police :affiliate="{{ $affiliate }}" inline-template>
                @include('affiliates.affiliate_police_information', ['affiliate'=>$affiliate])
            </affiliate-police>
        </div>
        <div class="col-md-6">
            @include('affiliates.information_of_the_procedure')                     
        </div>    
    </div>

</div>

@endsection
