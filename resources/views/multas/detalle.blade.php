@extends('layouts.master')

@section('title')
    Detalles de Multas - {{ $actividad->nombre_actividad }}
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Multas @endslot
        @slot('title') Detalles de Multas - {{ $actividad->nombre_actividad }} @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <a href="{{ route('multas.create') }}" class="btn btn-success waves-effect waves-light">
                                <i class="mdi mdi-plus me-2"></i> Registrar Multa
                            </a>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('multas.index') }}" method="GET" class="form-inline float-md-end mb-3">
                                <div class="search-box ms-2">
                                    <div class="position-relative">
                                        <input type="text" name="search" class="form-control rounded bg-light border-0" placeholder="Buscar..." value="{{ request('search') }}">
                                        <i class="mdi mdi-magnify search-icon"></i>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive mb-4">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Código</th>
                                    <th>Propiedad</th>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                    <th>Bloqueo</th>
                                    <th>Fecha de Creación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($multas as $multa)
                                    <tr>
                                        <td>{{ $multa->codigo }}</td>
                                        <td>{{ $multa->propiedad->persona->nombre }} {{ $multa->propiedad->persona->papellido }} {{ $multa->propiedad->persona->sapellido }}</td>
                                        <td>{{ $multa->monto }}</td>
                                        <td>{{ $multa->pagada ? 'Pagada' : 'Pendiente' }}</td>
                                        <td>{{ $multa->bloqueado ? 'Bloqueada' : 'Editable' }}</td>
                                        <td>{{ $multa->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            <a href="{{ route('multas.show', $multa->id) }}" class="btn btn-primary btn-sm">
                                                Ver detalles
                                            </a>
                                            <form action="{{ route('multas.destroy', $multa->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta multa?')">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No hay multas registradas para esta actividad.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
