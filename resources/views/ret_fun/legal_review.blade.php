@extends('layouts.app')
@section('title', 'Afiliados')
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{-- {{ Breadcrumbs::render('show_affiliate', $affiliate) }} --}}
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
                {{--!! Form::open(['url' => 'store_ret_fun_legal_review_create', 'method' => 'POST', 'id'=>$retirement_fund->id]) !!--}}
                <form action="{{asset('ret_fun/'.$retirement_fund->id.'/legal_review/create')}}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="retirement_fund_id" value="{{$retirement_fund->id}}">
                <table class="table">
                    <thead>
                      <tr>
                        <th class="col-md-1">N°</th>
                        <th class="col-md-9">Documentaci&oacute;n Presentada</th>
                        <th class="col-md-2">V°B°</th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $document)                            
                            <tr>
                              <td>{{$document->procedure_requirement->number}}</td>
                              <td>{{$document->procedure_requirement->procedure_document->name}}</td>
                              <td class="text-center">
                                  <div class="checkbox">
                                      <input type="checkbox" value="1"  name="document{{$document->id}}">
                                  </div>
                              </td>
                            </tr>
                            <tr>
                                <td>Observaciones:</td>
                                <td><input type="text" name="comment{{$document->id}}" class="from-control col-md-12" value="{{$document->comment}}"> </td>
                            </tr>
                        @endforeach
                    </tbody>
                  </table>
                <input type="submit" class="btn btn-info" value="CERTIFICAR">
                </form>
                {{--!! Form::close() !!--}}
            </div>
        </div>
    </div>
</div>
@endsection