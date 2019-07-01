@extends('layouts.app')

@section('title', 'Sincronización del {{ $title }}')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-9">
    {{ Breadcrumbs::render('show_loan', $title) }}
  </div>
</div>
<div class="wrapper wrapper-content">
  <div class="row">
    <div class="col-lg-12 text-center">
      <div class="content">
        <p>
          {!! nl2br(e($content)) !!}
        </p>
      </div>
    </div>
  </div>
</div>
@endsection

@section('styles')
<style>
.content {
  border-style: groove;
  background-color: #e5e5e5;
  color: black;
}
</style>
@endsection