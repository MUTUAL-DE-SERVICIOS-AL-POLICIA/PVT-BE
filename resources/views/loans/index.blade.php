@extends('layouts.app')

@section('title', 'Préstamos en Mora')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{ Breadcrumbs::render('loans') }}
    </div>
    <div class="col-lg-3 text-right" style="margin-top: 15px;">
        <form method="post" action="/overdue_loan">
            {{ csrf_field() }}
            <div class="form-group row">
                <div class="offset-sm-3 col-sm-9">
                    <button type="submit" class="btn btn-primary">Sincronizar</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="text-center">
                Sincronizaciones
            </div>
            <div class="text-left">
                @php
                    $file_path = 'sincronizacion_prestamos';
                    $date = Carbon\Carbon::now();
                    if ($date->day == 1) {
                        $date->subMonth();
                    }
                    $files = Storage::disk('local')->files(implode('/', [$file_path, $date->year, $date->format('m')]));
                    rsort($files);
                @endphp
                <ul>
                    @foreach ($files as $file)
                    @php ($file_name = explode('/', $file)[3])
                    <li>
                        <a href="overdue_loan/{{$file_name}}">
                            {{ substr_replace(str_replace("_", " ", explode('.', $file_name)[0]), ':', -3, -2) }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection