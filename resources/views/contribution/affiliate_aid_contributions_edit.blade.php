@extends('layouts.app')
@section('title', 'Contribuciones de Auxilio Mortuorio')
@section('styles')
<style>
    .disableddiv {
        pointer-events: none;
        opacity: 0;
    }
    .size-13{
        font-size: 13px;
    }
    .table-striped-1>tbody>tr:nth-child(4n+1){background-color:#f2f2f2}
    .table-hover>tbody>tr:hover {
        background-color: #DBDBDB
    }
</style>
@endsection

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
            {{ Breadcrumbs::render('edit_affiliate_aid_contributions', $affiliate) }}            
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-6">
                @include('contribution.aid_aditional_info',['summary',$summary])
            </div>            
            <div class="col-md-6">
                @include('contribution.aid_commitment',['aid_commitment'=>$aid_commitment,'affiliate_id'=>$affiliate_id,'today_date'=>$today_date])
            </div>
        </div>        
    </div>
    <div class = "col-md-12">
        <aid-contribution-create :aid-contributions="{{ json_encode($new_contributions) }}" :afid="{{ $affiliate->id }}" ></aid-contribution-create>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form>
                <input type="hidden" name="affiliate_id" id="affiliate_id" value="{{$affiliate_id}}">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>Aportes</h5>
                            <div class="ibox-tools">
                                <a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                <a class="fullscreen-link" data-toggle="tooltip" data-placement="bottom" title="Pantalla completa"><i class="fa fa-expand"></i></a>
                            </div>
                        </div>
                        <div class="ibox-content table-responsive">
                            <table class="table table-striped-1 table-bordered table-hover size-13">
                                <thead>
                                    <tr>
                                        <th>A&ntilde;o</th>
                                        <th>Enero</th>
                                        <th>Febrero</th>
                                        <th>Marzo</th>
                                        <th>Abril</th>
                                        <th>Mayo</th>
                                        <th>Junio</th>
                                        <th>Julio</th>
                                        <th>Agosto</th>
                                        <th>Septiembre</th>
                                        <th>Octubre</th>
                                        <th>Noviembre</th>
                                        <th>Diciembre</th>
                                        <th>Editar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for(;$year_start>=$year_end;$year_start--)
                                        <tr>
                                            <td> {{ $year_start }} </td>
                                            @for($i=1;$i<13;$i++)
                                                @php
                                                    $period = $year_start. '-'.($i<10? '0'.$i:$i). '-01';
                                                    $valid_period = true;
                                                    if(date('Y')==$year_start && date('m')<=$i)
                                                        $valid_period = false;
                                                @endphp
                                                @if($valid_period)
                                                    @if(isset($contributions[$period]->id))
                                                    <td class="numberformat" id="aid_main{{$period}}">{{$contributions[$period]->total}}</td>
                                                    @else
                                                        <td class="numberformat" id="aid_main{{$period}}">0</td>
                                                    @endif
                                                @else
                                                       <td>-</td>
                                                @endif
                                            @endfor
                                            <td>
                                                <button class="btn btn-default" type="button" data-toggle="tooltip" data-placement="top" title="Editar" onclick="toggleNestedComp(this)"><i class="fa fa-pencil"></i></button>
                                            </td>
                                        </tr>
                                        <tr class="tabl2" style="display:none;">
                                            <td>
                                                <table class="table table-striped table-bordered size-13">
                                                    <tr>
                                                        <td>Renta</td>
                                                    </tr>                                                    
                                                    <tr>
                                                        <td>Renta Dignidad</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Aporte</td>
                                                    </tr>                                                    
                                                </table>
                                            </td>
                                            @for($i=1;$i<13;$i++)
                                                @php
                                                    $period = $year_start. '-'.($i<10? '0'.$i:$i). '-01';
                                                    $valid_period = true;
                                                    if(date('Y')==$year_start && date('m')<=$i)
                                                        $valid_period = false;
                                                @endphp
                                                @if($valid_period)
                                                    @if(isset($contributions[$period]->id))
                                                    <td>
                                                        <table class="table table-striped table-bordered size-13">
                                                            <thead style="display: none">
                                                                <tr>
                                                                    <td>
                                                                        <input type="hidden" disabled  id="iterator[{{$period}}]" name="iterator[{{$period}}]" value="{{$contributions[$period]->id}}">
                                                                    </td>
                                                                </tr>
                                                            </thead>
                                                            <tr>
                                                                <td>
                                                                    <div contenteditable="true" class="editcontent numberformat">{{$contributions[$period]->rent}} </div>
                                                                    <input data-money="true" type="hidden" disabled name="rent[{{$period}}]" value="{{$contributions[$period]->rent}}">
                                                                </td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td>
                                                                    <div contenteditable="true" class="editcontent numberformat">{{$contributions[$period]->dignity_rent}} </div>
                                                                    <input type="hidden" disabled name="dignity_rent[{{$period}}]" value="{{$contributions[$period]->dignity_rent}}">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div contenteditable="true" class="editcontent numberformat">{{$contributions[$period]->total ?? '-'}} </div>
                                                                    <input type="hidden" disabled name="total[{{$period}}]" value="{{$contributions[$period]->total??'-'}}">
                                                                </td>
                                                            </tr>                                                            
                                                        </table>
                                                    </td>
                                                    @else
                                                    <td>
                                                        <table class="table table-bordered table-striped size-13">
                                                            <thead style="display: none">
                                                                <tr>
                                                                    <td>
                                                                        <input type="hidden" disabled id="iterator[{{$period}}]" name="iterator[{{$period}}]" value="0">
                                                                    </td>
                                                                </tr>
                                                            </thead>
                                                            <tr>
                                                                <td>
                                                                    <div contenteditable="true" class="editcontent numberformat" data-money="true">0</div>
                                                                    <input type="hidden" disabled name="rent[{{$period}}]" value="0">
                                                                </td>
                                                            </tr>
                                                            
                                                            <tr>
                                                                <td>
                                                                    <div contenteditable="true" class="editcontent numberformat">0</div>
                                                                    <input type="hidden" disabled name="dignity_rent[{{$period}}]" value="0">
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <div contenteditable="true" class="editcontent numberformat">0</div>
                                                                    <input type="hidden" disabled name="total[{{$period}}]" value="0">
                                                                </td>
                                                            </tr>                                                            
                                                        </table>
                                                    </td>
                                                    @endif
                                                @else
                                                    <td></td>
                                                @endif
                                            @endfor
                                            <td>                                                
                                                <button class="btn btn-default" data-toggle="tooltip" data-placement="left" type="button" title="Guardar" onclick="storeData(this)"><i class="fa fa-save"></i></button>
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>
<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
@endsection

@section('scripts')
<script>
           
    $('.numberformat').each(function(i, obj) {            
            Inputmask(moneyInputMask()).mask(obj);        
    });    
    function moneyInputMask() {    
        return {
            alias: "numeric",
            groupSeparator: ",",
            autoGroup: true,
            digits: 2,
            digitsOptional: false,        
            placeholder: "0"
        };
    }
    $('body').addClass("mini-navbar");
    //$('.money').mask("#,##0.00", {reverse: true});
    var actual_year = "1700";
    function toggleNestedComp(mode){
        $(mode).closest('tr').next().toggle();
        $(mode).closest('tr').toggleClass('warning');
        $(mode).closest('tr').next().toggleClass('warning');
    }
    function storeData(button){
        $(button).closest('tr').toggle();
        $(button).closest('tr').toggleClass('warning');
        $(button).closest('tr').prev().toggleClass('warning');
        var formdata = $('form').serialize();
        $.ajax({
            url: "{{asset('store_aid_contributions')}}",
            method: "POST",
            data: formdata,
            beforeSend: function (xhr, settings) {
                if (settings.url.indexOf(document.domain) >= 0) {
                    xhr.setRequestHeader("X-CSRF-Token", "{{csrf_token()}}");
                }
            },
            success: function(result){                               
                $.each(result, function(index,value){
                    $('#aid_main'+value.month_year).html(value.total);                    
                    $('#iterator['+value.month_year+']').prop('disabled',true);
                 });                                  
                 flash('exito');                 
            },
            error: function(xhr, status, error) {
                //console.log(xhr.responseText);
                var resp = jQuery.parseJSON(xhr.responseText);
                $.each(resp, function(index, value)
                {                    
                    flash(value,'error',10000);
                });                            
            }
        });
    }
$('.editcontent').blur(function() {
    $(this).next('input').val($(this).html());
    $(this).next('input').removeAttr('disabled');
    $(this).closest('table').find('tr:first').find('td:first').find('input').removeAttr('disabled');
});
function setPeriodData(period,amount){
    alert(period+' - '+amount);
    $('#main'+period).html(amount);
}
//function enableDirectContribution(){
//    $(".directContribution").removeClass('disableddiv');
//}
</script>
@endsection