

<div class="col-lg-12">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="pull-left">Documentos Presentados</h3>
            <div class="text-right">
                <button type="button" class="btn btn-primary" onclick="editLegalReview()">
                     <i id='icon' class="fa fa-lock"> </i>     
                </button>
            </div>
        </div>
        
        <div class="panel-body">
          <form action="{{asset('ret_fun/'.$retirement_fund->id.'/legal_review/create')}}" method="POST">
            {{ csrf_field() }}
            <div class="row">
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
                        </tbody>
                    </table>
                </div>
            </div>
              <button type="submit" class="btn btn-primary documents_button col-md-6" style="display: none;" value="Guardar"><i class="fa fa-check-circle"></i>&nbsp;Guardar</button>
              <button class="btn btn-danger documents_button col-md-6" style="display: none;" onclick="editLegalReview()" value="cancelar"><i class="fa fa-times-circle"></i>&nbsp;&nbsp;<span class="bold">Cancelar</span></button>
          </form>
        </div>      
    </div>
</div>
@section('scripts')
<script>
    var editing = false;
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
        if(editing)
          $("#icon").attr('class','fa fa-unlock');  
        else
          $("#icon").attr('class','fa fa-lock');
    }
</script> 
@endsection
