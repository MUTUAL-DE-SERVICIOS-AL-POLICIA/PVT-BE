<div class="wrapper wrapper-content animated fadeInRight">
<!-- <form action="{{asset('ret_fun')}}" method="post">-->
     {{ csrf_field() }}
    <div class="row">
        <div class="col-md-12">        
            <ret_fun-step1_requirements  :modalities="{{ $modalities}}"   :requirements="{{ $requirements }}" inline-template>
                <div>
                <select v-model="modality" v-on:change="hello" name='modality'>   
                    <option v-for="modality in modalities" :value="modality.id">@{{modality.name}}</option>
                </select>
<!--                    <ul class="todo-list m-t" v-for="requirement in requirementsList">                        
                        <li >
                            <input type="checkbox" value="" name="" class="i-checks"/>
                            <span class="m-l-xs">@{{requirement.document}}k</span>
                        </li>                                                
                    </ul>-->
                    <div class="panel panel-success" v-for="requirement in requirementsList">
                        
                        <span v-if="requirement.number != actualTarget(requirement.number)">                            
                            <!--<optgroup>-->
                            <div class="panel-heading">@{{requirement.number}}</div>
                            
                        </span>
                            <div class="panel-body">
                                <div class="col-md-10">
                                    <!--<label class="radio-inline" >@{{requirement.document}} </label>-->
                                    <span class="m-l-xs">@{{requirement.document}}</span>
                                </div>
                                <div class="col-md-2">
                                    <!--<input type="radio" name="option@{{requirement.number}}">-->
                                    <input type="checkbox" value="registered" :name="'document'+requirement.id" class="i-checks"/>
                                </div>
                            </div>                       
                            
                    </div>
              </div>
<!--                <section>
                    
                </section>-->
            </ret_fun-step1_requirements>    
        </div>
    </div>
     <!--<input type="submit" value="Submit">-->
  <!--</form>--> 
</div>
