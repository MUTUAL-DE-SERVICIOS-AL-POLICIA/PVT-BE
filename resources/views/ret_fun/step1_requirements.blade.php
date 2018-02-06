<div>
   <select v-model="modality" v-on:change="hello" name="ret_fun_modality">                    
       <option v-for="modality in modalities" :value="modality.id">@{{modality.name}}</option>
   </select>
   <div class="panel panel-success" v-for="requirement in requirementsList">
       <span v-if="requirement.number != actualTarget(requirement.number)">                            
           <div class="panel-heading">@{{requirement.number}}</div>
       </span>
       <div class="panel-body">
           <div class="col-md-12">                          
            <div class="col-md-10">
                <span class="m-l-xs">@{{requirement.document}}</span>
            </div>
            <div class="col-md-2">
                <input type="checkbox" value="checked" :name="'document'+requirement.id" class="i-checks" />
            </div>
           </div>
           <div class="col-md-12">                          
               <div class="col-md-2">
                   Comentarios
               </div>
               <div class="col-md-8">
                   <input type="text" class="form-control" :name="'comment'+requirement.id">
               </div>
           </div>
       </div>
   </div>
</div>