@extends('layouts.app')
@section('title', 'Contribuciones')
@section('styles')
    <style>
        table{
            font-size: 14px;
        }
        .table-hover > tbody > tr:hover { background-color: #DBDBDB }
    </style>
@endsection
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        
    </div>
</div>


<div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
        <div class="col-md-12">
            <div class="col-md-6">
                <affiliate-show  :affiliate="{{ $affiliate }}" inline-template> 
                   @include('affiliates.affiliate_personal_information',['affiliate'=>$affiliate,'cities'=>$cities,'birth_cities'=>$cities])
            </affiliate-show> 
            </div>        
            <div class="col-md-6">
                @include('contribution.aditional_info',['summary',$summary]) 
            </div> 
            <div class="col-md-6">
                @include('contribution.commitment',['commitment'=>$commitment,'affiliate_id'=>$affiliate_id]) 
            </div> 
        </div>
            <div class="col-md-12 wrapper wrapper-content animated fadeInRight">

        
    <contribution-create :contributions1="{{ json_encode($new_contributions) }}" :afid="{{ $affiliate_id}}" :last_quotable="{{$last_quotable}}"></contribution-create>

                
            </div>
    </div>
    
    <div class="row">
        
        <div class="col-md-12">
            <div class="text-center m-t-lg">               
                <form>
                    <input type="hidden" name ="affiliate_id" id="affiliate_id" value="{{$affiliate_id}}">
                    <div class="table-responsive col-md-12">
                        <table class="table table-striped table-bordered table-hover ">
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
                $period = $year_start.'-'.($i<10?'0'.$i:$i).'-01';
                @endphp
            @if(isset($contributions[$period]->id))
            <td id="main{{$period}}">{{$contributions[$period]->total}}</td>
            @else
            <td id="main{{$period}}">0</td>
            @endif
            @endfor
            <td>
                <button class="btn btn-default" type="button" title="editar"            
                onclick="toggleNestedComp(this)">
                <i class="fa fa-list-ul"></i>
            </button>
        </td>
    </tr>      
    <tr class="tabl2" style="display:none;">
        
        <td>
            <table class="table table-striped table-bordered">
                <tr>
                    <td>Sueldo</td>
                </tr>
                <tr>
                    <td>Categoria</td>
                </tr>
                <tr>
                    <td>Ganado</td> 
                </tr>
                <tr>
                    <td>Aporte</td> 
                </tr>
                <tr>
                    <td>Reintegro</td> 
                </tr>
            </table>      
        </td>
        @for($i=1;$i<13;$i++)
        @php
                $period = $year_start.'-'.($i<10?'0'.$i:$i).'-01';
                @endphp 
            @if(isset($contributions[$period]->id))
            
            <td>
                <table class="table table-striped table-bordered">
                    <thead style="display: none">

                        <tr><td>
                            <input type="hidden" disabled name="iterator[{{$period}}]" value="{{$contributions[$period]->id}}">
                        </td></tr>
                    </thead>
                    <tr>
                        <td> 
                            <div contenteditable="true" class="editcontent">{{$contributions[$period]->base_wage}} </div> 
                            <input type="hidden" disabled name="base_wage[{{$period}}]" value="{{$contributions[$period]->base_wage}}">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <select class="form-control" name="category[{{$period}}]">
                                    @foreach($categories as $category)
                                    <option value="{{$category->id}}" @if($category->id == $contributions[$period]->category_id) SELECTED @endif >{{$category->percentage}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td> 
                                <div contenteditable="true" class="editcontent">{{$contributions[$period]->gain}} </div> 
                                <input type="hidden" disabled name="gain[{{$period}}]" value="{{$contributions[$period]->gain}}">
                            </td>
                        </tr>
                        <tr>
                            <td> 
                                <div contenteditable="true" class="editcontent">{{$contributions[$period]->total ?? '-'}} </div> 
                                <input type="hidden" disabled name="total[{{$period}}]" value="{{$contributions[$period]->total??'-'}}">
                            </td>
                        </tr>            
                        <tr>                
                            <td id="reim{{$period}}">
                                {{$reims[$period]->total ?? '-'}}
                            </td>
                        </tr>
                    </table>                     
                </td>
                @else
                <td>
                    <table class="table table-bordered table-striped">
                        <thead style="display: none">
                        <tr>
                            <td>
                                <input type="hidden" disabled name="iterator[{{$period}}]" value="0">
                            </td>
                        </tr>
                        </thead>
                        <tr>
                            <td> 
                                <div contenteditable="true" class="editcontent">0</div> 
                                <input type="hidden" disabled name="base_wage[{{$period}}]" value="0">
                            </td>
                        </tr>
                        <tr>
                            <td> 
                                <select class="form-control" name="category[{{$period}}]">
                                        @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->percentage}}</option>
                                        @endforeach
                                    </select>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td> 
                                    <div contenteditable="true" class="editcontent">0</div> 
                                    <input type="hidden" disabled name="gain[{{$period}}]" value="0"></td>
                                </td>
                            </tr>            
                            <tr>
                                <td>                
                                    <div contenteditable="true" class="editcontent">0</div> 
                                    <input type="hidden" disabled name="total[{{$period}}]" value="0">
                                </td>
                            </tr>
                            <tr>
                                <td id="reim{{$period}}}">-</td>
                            </tr>
                        </table>                     
                    </td>
                    @endif
                    
                    @endfor
                    
                    <td>
                        <button class="btn btn-default" data-toggle="tooltip" data-placement="top" type="button" title="Reintegro"            
                        onclick="createReimbursement({{$year_start}})">
                        <i class="fa fa-dollar"></i>
                    </button>
                    <button class="btn btn-default" data-toggle="tooltip" data-placement="left" type="button" title="Guardar"            
                    onclick="storeData(this)">
                    <i class="fa fa-save"></i>
                </button>
                
            </td>
            
        </tr>                                   
        @endfor
    </tbody>
</table>
</div>
</form>

</div>
</div>
</div>
</div>

<div class="modal inmodal" id="reimbursement_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content animated bounceInRight">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
                
                <h4 class="modal-title">Reintegro</h4>
            </div>
            <div class="modal-body">                
                <div class="form-group"><label>Mes</label>
                     <select class="form-control" name="month" id="month">                         
                        <option value="01">Enero</option>
                        <option value="02">Febrero</option>
                        <option value="03">Marzo</option>
                        <option value="04">Abril</option>
                        <option value="05">Mayo</option>
                        <option value="06">Junio</option>
                        <option value="07">Julio</option>
                        <option value="08">Agosto</option>
                        <option value="09">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>                                             
                     </select>
                </div>
                <div class="form-group">
                    
                    <input id="reim_salary" name="reim_salary" type="text" placeholder="Sueldo" class="form-control">                    
                    <select class="form-control" name="reim_category" id="reim_category">
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->percentage}}</option>
                        @endforeach
                    </select>
                    <input id="reim_gain" name="reim_gain" type="text" placeholder="Total ganado" class="form-control">
                    <input id="reim_amount" name="reim_amount" type="text" placeholder="Aporte" class="form-control">
                </div>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Cerrar</button>
                <!--<button type="submit" class="btn btn-primary">Guardar</button>-->
                <button class="btn btn-default" type="button" title="Guardar" onclick="storeReimbursement(this)">
                    Guardar
                </button>
            </div>
        </div>
    </div>
</div>


<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />

@endsection
@section('scripts')
<script>
$('body').addClass("mini-navbar");
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
            url: "{{asset('store_contributions')}}", 
            method: "POST",
            data: formdata,
            beforeSend: function (xhr, settings) {
                if (settings.url.indexOf(document.domain) >= 0) {
                    xhr.setRequestHeader("X-CSRF-Token", "{{csrf_token()}}");
                }                
            },    
            success: function(result){
                console.log('saved');
                console.log(result);
                 $('#main'+result.month_year).html(result.total);
            },
            error: function(xhr, status, error) {                
                console.log(xhr.responseText);                                
            }
        });

    }
function rei(){
    
}
$('.editcontent').blur(function() {        
    $(this).next('input').val($(this).html()); 
    $(this).next('input').removeAttr('disabled');        
    $(this).closest('table').find('tr:first').find('td:first').find('input').removeAttr('disabled');

});
function createReimbursement(year){
    //alert(year);
    this.actual_year = year;
    $('#reimbursement_modal').modal('show');
}
function storeReimbursement(){       
    year = this.actual_year;
    month = $('#month').val();
    salary = $('#reim_salary').val();
    category = $('#reim_category').val();
    gain = $('#reim_gain').val();
    total =  $('#reim_amount').val();    
    affiliate_id = $("#affiliate_id").val();
    $.ajax({
        url: "{{asset('reimbursement')}}", 
        method: "POST",
        data: {affiliate_id:affiliate_id,year:year,month:month,salary:salary,category:category,gain:gain,total:total},
        beforeSend: function (xhr, settings) {
            if (settings.url.indexOf(document.domain) >= 0) {
                xhr.setRequestHeader("X-CSRF-Token", "{{csrf_token()}}");
            }
            //console.log($(button).closest('form').serialize());
        },    
        success: function(result){
            console.log('saved reim');
            console.log(result);
            $("#reim"+result.month_year).html(result.total);
        },
        error: function(xhr, status, error) {                
            console.log(xhr.responseText);                                
        }
    });
    
    $('#reimbursement_modal').modal('hide');
   
}
function setPeriodData(period,amount){
    alert(period+' - '+amount);
    $('#main'+period).html(amount);
}
function alertxd(){
    alert('adfadsf');
}
</script>

@endsection