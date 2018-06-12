@extends('layouts.app') 
@section('title', 'Mi bandeja') 
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {!!Breadcrumbs::render('inbox')!!}
    </div>
</div>
<div class="wrapper wrapper-content">
    <div class="row">
        <tabs-content :rol-id="{{Muserpol\Helpers\Util::getRol()}}" :user="{{Muserpol\Helpers\Util::getAuthUser()}}" :inbox-state="`received`"
            inline-template>
            <div>
                <div class="col-lg-3">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content mailbox-content">
                            <div class="file-manager">
                                {{-- <a class="btn btn-block btn-primary compose-mail" ui-sref="email_compose">Compose Mail</a>--}}
                                <div class="space-25"></div>
                                <h5>Tramites</h5>
                                <ul class="folder-list m-b-md" style="padding: 0">
                                    <li>
                                        <a href="{{ url('inbox/received') }}" class="btn-outline" style="border-left:5px solid #59B75C; padding-left:10px; color: #3c3c3c; background:#F8F8F9;font-weight: bold;"> <i class="fa fa-envelope-o "></i> Recibidos
                                            <span class="label label-warning pull-right">@{{totalDocs}}</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ url('inbox/edited') }}" class="btn-outline"> <i class="fa fa-check"></i> Revisados
                                            {{-- <span class="label label-warning pull-right">@{{totalDocs}}</span> --}}
                                        </a>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 animated fadeInRight">
                    <div class="mail-box-header">
                        {{--
                        <form method="get" action="index.html" class="pull-right mail-search ng-pristine ng-valid">
                            <div class="input-group">
                                <input type="text" class="form-control input-sm" name="search" placeholder="Search email">
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                Search
                            </button>
                                </div>
                            </div>
                        </form> --}}
                        <h2>
                            <span>Recibidos (@{{totalDocs}})</span>
                        </h2>
                        {{--
                        <div class="mail-tools tooltip-demo m-t-md">
                            <div class="btn-group pull-right">
                                <button class="btn btn-white btn-sm"><i class="fa fa-arrow-left"></i></button>
                                <button class="btn btn-white btn-sm"><i class="fa fa-arrow-right"></i></button>
                            </div>
                            <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="left" title="Refresh inbox"><i class="fa fa-refresh"></i> Refresh</button>
                            <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Mark as read"><i class="fa fa-eye"></i></button>
                            <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Mark as important"><i class="fa fa-exclamation"></i></button>
                            <button class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Move to trash"><i class="fa fa-trash-o"></i></button>
                        </div> --}}
                    </div>
                    <div class="mail-box">
                        <vue-tabs>
                            <v-tab :title="`${itab.name} (${classification(itab.id).length})`" v-for="(itab, index) in workflows" :dataId="itab.id" icon="fa fa-check"
                                :key="`tab-received-${index}`" :suffix="`<span class='badge'> ${classification(itab.id).length} </span>`">
                                <inbox-content :inbox-state="`received`" :workflow-id="itab.id" :documents="classification(itab.id)"></inbox-content>
                            </v-tab>
                        </vue-tabs>
                    </div>
                </div>
            </div>
        </tabs-content>
    </div>
</div>
@endsection