@extends('layouts.app') 
@section('title', 'Fondo de Retiro') 
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {!!Breadcrumbs::render('show_qualification_retirement_fund', $retirement_fund)!!}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Aportes y periodos considerados</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <ret-fun-qualification inline-template
                :dates-contributions="{{json_encode($dates_contributions)}}"
                :dates-availability="{{json_encode($dates_availability)}}"
                :dates-item-zero="{{json_encode($dates_item_zero)}}"
                >
                <div class="ibox-content" style="">
                    <div class="form-group" id="data_5">
                        <div class="col-md-4">
                            <h4>Aportes fondo de retiro Policial Solidario </h4>
                        </div>
                        <ret-fun-qualification-group
                        :dates-availability-child="datesContributions"
                        @total="calculate"
                        >
                    </ret-fun-qualification-group>
                    {{-- @{{ total }} --}}
                    {{--  @forelse ($dates_contributions as $key=>$date)  --}}
                    {{-- <div class="input-daterange input-group col-md-offset-4 col-md-8" v-for="(date, index) in datesContributions" :key="index">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>{!! Form::date('date_availability_start',
                        null, ['class'=>'form-control', 'v-model'=>"date.start"]) !!}
                        <span class="input-group-addon"><i class="fa fa-arrow-right"></i></span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>{!! Form::date('date_availability_start',
                        null, ['class'=>'form-control', 'v-model' => 'date.end']) !!}
                    </div> --}}
                    {{--  {{--  @empty  --}}
                    {{-- <div class="input-daterange input-group col-md-offset-4 col-md-8">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>{!! Form::date('date_availability_start',
                        null, ['class'=>'form-control']) !!}
                        <span class="input-group-addon"><i class="fa fa-arrow-right"></i></span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>{!! Form::date('date_availability_start',
                        null, ['class'=>'form-control']) !!}
                    </div> --}}
                    {{--  @endforelse  --}}
                </div>
                <div class="form-group" id="data_5">
                    <div class="col-md-4">
                        <h4>Destino en Letras de Disponibilidad</h4>
                    </div>
                    <ret-fun-qualification-group 
                    :dates-availability-child="datesAvailability"
                    @total="calculate"
                    >
                </ret-fun-qualification-group>
                
                {{--  @forelse ($dates_availability as $date)  --}}
                {{-- <div class="input-daterange input-group col-md-offset-4 col-md-8" v-for="(date, index) in datesAvailability" :key="index">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>{!! Form::date('date_availability_start',
                    null, ['class'=>'form-control','v-model' => 'date.start']) !!}
                    <span class="input-group-addon"><i class="fa fa-arrow-right"></i></span>
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>{!! Form::date('date_availability_start',
                    null, ['class'=>'form-control','v-model' => 'date.end']) !!}
                    <div>
                        <strong>
                            @{{ totalMonths(index) }}
                        </strong>
                    </div>
                </div> --}}
                {{--  @empty
                    <div class="input-daterange input-group col-md-offset-4 col-md-8">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>{!! Form::date('date_availability_start',
                        null, ['class'=>'form-control']) !!}
                        <span class="input-group-addon"><i class="fa fa-arrow-right"></i></span>
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>{!! Form::date('date_availability_start',
                        null, ['class'=>'form-control']) !!}
                    </div>
                    @endforelse  --}}
                </div>
                
                <div class="form-group" id="data_5">
                    <div class="col-md-4">
                        <h4>Periodo de Aportes Item 0</h4>
                    </div>
                    <ret-fun-qualification-group 
                    :dates-availability-child="datesItemZero"
                    @total="calculate"
                    >
                    </ret-fun-qualification-group>
                    {{--  @forelse ($dates_item_zero as $date)  --}}
                    {{-- <div class="input-daterange input-group col-md-offset-4 col-md-8" v-for="(date,index) in datesItemZero" :key="index" >
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>{!! Form::date('date_availability_start',
                                null, ['class'=>'form-control', 'v-model'=> 'date.start']) !!}
                                <span class="input-group-addon"><i class="fa fa-arrow-right"></i></span>
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>{!! Form::date('date_availability_start',
                                null, ['class'=>'form-control', 'v-model'=> 'date.end']) !!}
                            </div> --}}
                            {{--  @empty
                            <div class="input-daterange input-group col-md-offset-4 col-md-8">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>{!! Form::date('date_availability_start',
                                null, ['class'=>'form-control']) !!}
                                <span class="input-group-addon"><i class="fa fa-arrow-right"></i></span>
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>{!! Form::date('date_availability_start',
                                null, ['class'=>'form-control']) !!}
                            </div>
                            @endforelse  --}}
                        </div>
                        {{--
                        <div class="form-group" id="data_5">
                            <div class="col-md-4">
                                <h4>Periodo de Aportes Batallon de Seguridad Fisica</h4>
                            </div>
                            <div class="input-daterange input-group col-md-8">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control"
                                    value="03/04/2014">
                                <span class="input-group-addon"><i class="fa fa-arrow-right"></i></span>
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control"
                                    value="03/04/2014">
                            </div>
                        </div>  --}}
                    {{-- </div> --}}
                    <div class="panel panel-success">
                        <strong>Anios</strong> @{{this.years}}
                        <strong>Meses</strong> @{{this.months}}
                    </div>
                </ret-fun-qualification>
            </div>
            {{-- <div class="ibox">
                <div class="ibox-title">
                    <h5>Aportes y periodos considerados</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content" style="">
                    <div class="form-group" id="data_5">
                        <div class="col-md-4">
                            <h4>Aportes fondo de retiro Policial Solidario </h4>
                        </div>
                        <div class="input-daterange input-group col-md-8">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control"
                                value="03/04/2014">
                            <span class="input-group-addon"><i class="fa fa-arrow-right"></i></span>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control"
                                value="03/04/2014">
                        </div>
                    </div>
                    <div class="form-group" id="data_5">
                        <div class="col-md-4">
                            <h4>Destino en Letras de Disponibilidad</h4>
                        </div>
                        <div class="input-daterange input-group col-md-8">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control"
                                value="03/04/2014">
                            <span class="input-group-addon"><i class="fa fa-arrow-right"></i></span>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control"
                                value="03/04/2014">
                        </div>
                    </div>
                    <div class="form-group" id="data_5">
                        <div class="col-md-4">
                            <h4>Periodo de Aportes Item 0</h4>
                        </div>
                        <div class="input-daterange input-group col-md-8">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control"
                                value="03/04/2014">
                            <span class="input-group-addon"><i class="fa fa-arrow-right"></i></span>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control"
                                value="03/04/2014">
                        </div>
                    </div>
                    <div class="form-group" id="data_5">
                        <div class="col-md-4">
                            <h4>Periodo de Aportes Batallon de Seguridad Fisica</h4>
                        </div>
                        <div class="input-daterange input-group col-md-8">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control"
                                value="03/04/2014">
                            <span class="input-group-addon"><i class="fa fa-arrow-right"></i></span>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control"
                                value="03/04/2014">
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
        {{--
        <div class="col-md-6">
            <affiliate-show :affiliate="{{ $affiliate }}" inline-template>
    @include('affiliates.affiliate_personal_information',['affiliate'=>$affiliate,'cities'=>$cities_pluck,'birth_cities'=>$birth_cities])
            </affiliate-show>
        </div> --}} {{-- @can('update',$retirement_fund)
        <div class="col-md-6">
            <ret-fun-info :retirement_fund="{{ $retirement_fund }}" :rf_city_start="{{$retirement_fund->city_start}}" :rf_city_end="{{$retirement_fund->city_end}}"
                :rf_procedure_modality=" {{$retirement_fund->procedure_modality}}" inline-template>
    @include('ret_fun.info', ['retirement_fund'=>$retirement_fund,'cities'=>$birth_cities])
            </ret-fun-info>
        </div>
        @endcan --}}
    </div>
    {{--
    <div class="row">
        @can('view',new Muserpol\Models\RetirementFund\RetFunBeneficiary)
        <div class="col-md-6">
    @include('ret_fun.beneficiaries_list', ['beneficiaries'=>$beneficiaries,'cities'=>$cities,'kinships'=>$kinships])
        </div>
        @endcan
    </div> --}}
</div>
@endsection