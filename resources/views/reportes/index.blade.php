@extends('layouts.master')

@section('title')
    Reportes de Consumos
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Reportes @endslot
        @slot('title') Consumos @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Opciones de Reportes</h4>
                    <p class="card-text">Selecciona el tipo de consumo que deseas visualizar.</p>
                    <a href="{{ route('consumos.pagados') }}" class="btn btn-success mt-2">Consumos Pagados</a>
                    <a href="{{ route('consumos.pendientes') }}" class="btn btn-warning mt-2">Consumos Pendientes</a>
                    <a href="{{ route('consumos.todos') }}" class="btn btn-info mt-2">Todos los Consumos</a>
                </div>
            </div>
        </div>
    </div>
@endsection
