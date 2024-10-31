@extends('layouts.master')

@section('title')
    Lista de Pagos
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Pagos @endslot
        @slot('title') Lista de Pagos @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <a href="{{ route('pagos.create') }}" class="btn btn-success waves-effect waves-light">
                                <i class="mdi mdi-plus me-2"></i> Registrar Pago
                            </a>
                        </div>
                        <div class="col-md-6">
                            <form action="{{ route('pagos.index') }}" method="GET" class="form-inline float-md-end mb-3">
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
                                    <th>Persona</th>
                                    <th>Propiedad</th>
                                    <th>Fecha de Pago</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pagos as $pago)
                                    <tr>
                                        <td>{{ $pago->persona->nombre }} {{ $pago->persona->papellido }}</td>
                                        <td>{{ $pago->propiedad->identificador_propiedad }}</td>
                                        <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                                        <td>{{ $pago->estado_pago ? 'Pagado' : 'Pendiente' }}</td>
                                        <td>
                                            <a href="{{ route('pagos.show', $pago->id) }}" class="btn btn-primary btn-sm">
                                                Ver detalles
                                            </a>
                                            <form action="{{ route('pagos.destroy', $pago->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este pago?')">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
