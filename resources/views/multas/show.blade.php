@extends('layouts.master')

@section('title')
    Detalle de la Multa
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Multas @endslot
        @slot('title') Detalle de la Multa @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Información de la Multa</h4>
                    <p><strong>Código:</strong> {{ $multa->codigo }}</p>
                    <p><strong>Persona:</strong> {{ $multa->propiedad->persona->nombre }} {{ $multa->propiedad->persona->papellido }} {{ $multa->propiedad->persona->sapellido }}</p>
                    <p><strong>Actividad:</strong> {{ $multa->actividad->nombre_actividad }}</p>
                    <p><strong>Monto:</strong> {{ $multa->monto }}</p>
                    <p><strong>Estado:</strong> {{ $multa->pagada ? 'Pagada' : 'Pendiente' }}</p>
                    <p><strong>Bloqueo:</strong> {{ $multa->bloqueado ? 'Bloqueada' : 'Editable' }}</p>
                    <p><strong>Fecha de Creación:</strong> {{ $multa->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
