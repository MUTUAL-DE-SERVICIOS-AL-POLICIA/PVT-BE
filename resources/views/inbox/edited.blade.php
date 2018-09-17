@extends('layouts.app') 
@section('title', 'Mi bandeja') 
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {!!Breadcrumbs::render('inbox')!!}
    </div>
    <div class="col-lg-5" style="padding-top: 15px">
        @if(Util::getRol()->id == 13)
            <button class="btn btn-warning dim"
            onclick="printJS({printable:'{!! route('print_pre_qualification') !!}', type:'pdf', modalMessage: 'Generando documentos de impresión por favor espere un momento.', showModal:true})"
            > <i class="fa fa-print"></i> Imprimir Pre calificados </button>
        @endif
        @if(Util::getRol()->id == 29)
            <button class="btn btn-warning dim"
            onclick="printJS({printable:'{!! route('print_send_daa') !!}', type:'pdf', modalMessage: 'Generando documentos de impresión por favor espere un momento.', showModal:true})"
            > <i class="fa fa-print"></i> Imprimir Envio DAA</button>
        @endif
    </div>
</div>
<div class="wrapper wrapper-content">
    <div class="row">
        <tabs-content :rol-id="{{Muserpol\Helpers\Util::getRol()}}" :user="{{Muserpol\Helpers\Util::getAuthUser()}}" :inbox-state="`edited`"
            inline-template>
            <div>
                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content mailbox-content">
                            <div class="file-manager">
                                {{-- <a class="btn btn-block btn-primary compose-mail" ui-sref="email_compose">Compose Mail</a> --}}
                                <div class="space-25"></div>
                                <h5>Trámites</h5>
                                <ul class="folder-list m-b-md" style="padding: 0">
                                    <li>
                                        <a href="{{ url('inbox/received') }}" class="btn-outline"> <i class="fa fa-envelope-o "></i> Recibidos
                                            <transition name="fade" mode="out-in" :duration="300" enter-active-class="animated tada" leave-active-class="animated bounceOutRight">    
                                                <span class="label label-default pull-right" v-if="documentsReceivedTotal != null" key="value">
                                                    @{{documentsReceivedTotal}}
                                                </span>    
                                                <span v-else class="label label-default pull-right" key="icon" style="padding-left:15px">
                                                    <i  class="fa fa-refresh fa-spin fa-fw" aria-hidden="true"></i>
                                                </span>
                                            </transition>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('inbox/edited') }}" class="btn-outline" style="border-left:5px solid #59B75C; padding-left:10px; color: #3c3c3c; background:#F8F8F9;font-weight: bold;"> <i class="fa fa-check"></i> Revisados
                                            <transition name="fade" mode="out-in" :duration="300" enter-active-class="animated tada" leave-active-class="animated bounceOutRight">
                                                <span class="label label-warning pull-right" v-if="totalDocs != null" key="valueEdited">
                                                    @{{totalDocs}}
                                                </span>    
                                                <span v-else class="label label-warning pull-right" key="iconEdited" style="padding-left:15px">
                                                    <i  class="fa fa-refresh fa-spin fa-fw" aria-hidden="true"></i>
                                                </span>
                                            </transition>
                                        </a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 animated fadeInRight my-inbox" :class="showLoading ? 'sk-loading' : ''">
                    <div class="mail-box-header">
                        <h2>
                            <span>
                            Revisados (@{{totalDocs}})
                        </span>
                        </h2>

                        <div class="mail-tools tooltip-demo" >
                            <div class="row text-center">
                                <transition name="some" enter-active-class="animated bounceIn" leave-active-class="animated bounceOut" :duration="{ enter: 400, leave: 400 }" mode="out-in">
                                    <div v-if="docs > 0" key="send">
                                        {{-- backward  --}}
                                        <div class="col-md-6 text-center">
                                            <transition name="fade" enter-active-class="animated bounceInLeft" leave-active-class="animated bounceOutLeft">
                                                <div class="input-group" >
                                                    <span class="input-group-btn">
                                                        <button :disabled="! (docs > 0 && wfSequenceBack != null) " class="btn " :class="{'btn-warning': docs > 0  }" @click="sendBackward()" data-toggle="tooltip"
                                                            data-placement="top" title="Enviar los Trámites seleccionados"><i class="fa fa-arrow-left"></i> DEVOLVER <i class="fa fa-send"></i> <strong>(@{{docs}})</strong></button>
                                                    </span>
                                                    <select name="" v-model="wfSequenceBack" id="" class="form-control">
                                                        <option :value="null"> Seleccione a donde devolverá los Trámites </option>
                                                        <option :value="wfs.wf_state_id" v-for="(wfs, index) in wfSequenceBackList">@{{wfs.wf_state_name}}</option>
                                                    </select>
                                                </div>
                                            </transition>
                                        </div>
                                        {{-- forward --}}
                                        <div class="col-md-6 text-center">
                                                <div class="input-group" >
                                                    <select name="" v-model="wfSequenceNext" id="" class="form-control">
                                                        <option :value="null"> Seleccione a donde derivará los Trámites </option>
                                                        <option :value="wfs.wf_state_id"  v-for="(wfs, index) in wfSequenceNextList">@{{wfs.wf_state_name}}</option>
                                                    </select>
                                                    <span class="input-group-btn">
                                                        <button :disabled="! (docs > 0 && wfSequenceNext != null)" class="btn" :class="{'btn-primary': docs > 0  }" @click="sendForward" data-toggle="tooltip"
                                                            data-placement="top" title="Enviar los Trámites seleccionados"> DERIVAR <i class="fa fa-send"></i> <strong>(@{{docs}})</strong>  <i class="fa fa-arrow-right">  </i></button>
                                                    </span>
                                                </div>
                                            
                                        </div>
                                    </div>
                                    <div v-else key="refresh">
                                        <div class="col-md-12 text-center" >
                                            <button class="btn btn-default" @click="getData()" data-toggle="tooltip" data-placement="top" title="Actualizar">
                                                Actualizar <i class="fa fa-refresh"></i>
                                            </button>
                                        </div>
                                    </div>
                                </transition-group>
                            </div>
                        </div>
                    </div>
                    <div class="mail-box">
                        <div class="sk-folding-cube" v-show="showLoading">
                            <div class="sk-cube1 sk-cube"></div>
                            <div class="sk-cube2 sk-cube"></div>
                            <div class="sk-cube4 sk-cube"></div>
                            <div class="sk-cube3 sk-cube"></div>
                        </div>
                        <vue-tabs @tab-change="handleTabChange">
                            <v-tab :title="`${itab.name} (${classification(itab.id).length})`" :dataId="itab.id" icon="fa fa-file-text-o" v-for="(itab, index) in workflows"
                            :key="`tab-edited-${index}`" :suffix="` <span class='badge'> ${classification(itab.id).length} </span>`"
                            >
                                <inbox-content :workflow-id="itab.id" :inbox-state="`edited`" :documents="classification(itab.id)"></inbox-content>
                                {{-- <inbox-content :workflow-id="itab.id" :inbox-state="`edited`" :documents="docss"></inbox-content> --}}
                            </v-tab>
                        </vue-tabs>
                    </div>
                </div>
            </div>
        </tabs-content>
    </div>
@endsection
@section('styles')
    {{-- <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet"> --}}
    <link rel="stylesheet" href="{!! asset('css/vuetify.css') !!}" />
@endsection