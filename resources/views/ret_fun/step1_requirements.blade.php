@extends('layouts.app')



<link rel="stylesheet" href="{!! asset('css/iCheck/custom.css') !!}" />

@section('title', 'Afiliados')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{ Breadcrumbs::render('affiliate') }}
    </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-md-6">        
            <ret_fun-step1_requirements  :modalities="{{ $modalities}}"   :requirements="{{ $requirements }}" inline-template>
                <div>
                <select v-model="modality" v-on:change="hello">                    
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
                                    <input type="checkbox" value="" name="" class="i-checks"/>
                                </div>
                            </div>                       
                            
                    </div>
              </div>
<!--                <section>
                    
                </section>-->
            </ret_fun-step1_requirements>    
        </div>
    </div>
</div>
@endsection
