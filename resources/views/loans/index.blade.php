@extends('layouts.app')

@section('title', 'Préstamos en Mora')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
        {{ Breadcrumbs::render('loans') }}
    </div>
</div>
<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-12">
            <div class="text-center">
                Sincronizaciones
            </div>
            <div class="text-left">
                @php ($files = Storage::disk('local')->files('sincronizacion_prestamos'))
                @php (rsort($files))
                @php ($files = array_slice($files, 0, 30))
                <ul>
                    @foreach ($files as $file)
                    @php ($file_name = explode('/', $file)[1])
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