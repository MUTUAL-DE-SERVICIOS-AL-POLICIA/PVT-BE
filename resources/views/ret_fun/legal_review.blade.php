

<div class="col-lg-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="pull-left">Documentos Presentados</h3>
            <div class="text-right">
              @can('update',new Muserpol\Models\RetirementFund\RetFunSubmittedDocument)
                <button type="button" class="btn btn-primary" onclick="editLegalReview()">
                     <i id='icon' class="fa fa-lock"> </i>     
                </button>
              @else
                <br>
              @endcan
            </div>
        </div>
        
      <form action="{{asset('ret_fun/'.$retirement_fund->id.'/legal_review/create')}}" method="POST">
        <div v-if="! editing">
            {{ csrf_field() }}
      {{--   <div class="panel-body">
            {{ csrf_field() }}             --}}
            <div class="row">
                <br>
                <div></div>
                <div class="col-md-1">
                        <strong>N°</strong>
                </div>
                        <div class="col-md-5">
                                <strong>Documentaci&oacute;n Presentada</strong>
                        </div>
                        <div class="col-md-2">
                        <strong> Observaciones:</strong>
                        </div>
                        <div class="col-md-2">
                                <strong>V°B°</strong>
                        </div>
                </div>
                @foreach($documents as $document)
                <div class="row">
                    <div ></div>
                <div class="col-md-1">
                        {{$document->procedure_requirement->number}}
                </div>
                            <p class="documents_comment_text">{{$document->comment}}</p>
                            <input type="text" style="display: none;" name="comment{{$document->id}}" class="from-control col-md-12 documents_comment" value="{{$document->comment}}"> 
                <div class="col-md-5">
                        {{$document->procedure_requirement->procedure_document->name}}</div>
                 <div class="col-md-2">
                                <p class="documents_comment_text">{{$document->comment}}</p>
                                <input type="text" style="display: none;" name="comment{{$document->id}}" class="from-control col-md-12 documents_comment" value="{{$document->comment}}"> 
                </div>
                       <div class="col-md-2" "checkbox">
                    <input type="checkbox" class="documents_check" value="1"@if($document->is_valid) checked @endif name="document{{$document->id}}" disabled>
                </div>
            </div>
                @endforeach
            </div>
                <div class="col-md-1">
                    @foreach($documents as $document)                            
                    <tr>
                      <td>{{$document->procedure_requirement->number}}</td>
                      <td>{{$document->procedure_requirement->procedure_document->name}}</td>
                      <td class="text-center">
                          <div class="checkbox">
                              <input type="checkbox" class="documents_check" value="1"@if($document->is_valid) checked @endif name="document{{$document->id}}" disabled>
                          </div>
                      </td>
                    </tr>
                    <tr>
                        <td>Observaciones:</td>
                        <td>
                            <p class="documents_comment_text">{{$document->comment}}</p>
                            <input type="text" style="display: none;" name="comment{{$document->id}}" class="from-control col-md-12 documents_comment" value="{{$document->comment}}"> 
                        </td>
                    </tr> 
                    @endforeach
            </div>


        </div>
                <div class="ibox-content table-responsive">                    
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
                                  {{-- <div class="checkbox">                                       --}}
                                      <input type="checkbox" class="documents_check" value="1" @if($document->is_valid) checked @endif name="document{{$document->id}}" disabled>
                                  {{-- </div> --}}
                              </td>
                            </tr>
                            <tr>
                                <td>Observaciones:</td>
                                <td>
                                    <p class="documents_comment_text">{{$document->comment}}</p>
                                    <input type="text" style="display: none;" name="comment{{$document->id}}" class="from-control col-md-12 documents_comment" value="{{$document->comment}}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer">
              <div class="text-center">
                <button type="button" id="bt_legal_cancel" class="btn btn-danger documents_button " onclick="editLegalReview()" value="cancelar"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span class="bold">Cancelar</span></button>
                <button type="submit" id="bt_legal_guardar" class="btn btn-primary documents_button " value="Guardar"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
              </div>
            </div>
              
        </div>      
      </form>
    </div>
</div>
@section('scripts')
<script>
    var editing = false;
    $("#bt_legal_cancel").hide();  
    $("#bt_legal_guardar").hide(); 
    function editLegalReview(){
        $('.documents_comment').toggle();
        $('.documents_comment_text').toggle();
        $('.documents_button').toggle();
        $('.documents_check').each(function(i, obj) {              
            if($(this).prop('disabled'))
            $(this).attr('disabled', false);
            else
            $(this).attr('disabled', true);
        }); 

        editing = !editing;
        if(editing){

          $("#icon").attr('class','fa fa-unlock');
          $("#bt_legal_cancel").show();  
          $("#bt_legal_guardar").show();  
        }
        else{
          $("#icon").attr('class','fa fa-lock');
          // location.reload();
           $("#bt_legal_cancel").hide();  
          $("#bt_legal_guardar").hide();  
        }
          

    }
</script> 
@endsection
