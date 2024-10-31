@extends('layouts.master')

@section('title')
    Lista de Consumos
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Consumos @endslot
        @slot('title') Lista de Consumos @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <a href="{{ route('consumos.create') }}" class="btn btn-secondary waves-effect waves-light">
                                <i class="mdi mdi-plus me-2"></i> Registrar Consumo
                            </a>
                            <a href="{{ route('consumos.pagados') }}" class="btn btn-success">C. Pagados</a>
                            <a href="{{ route('consumos.pendientes') }}" class="btn btn-warning">C. Pendientes</a>
                            <a href="{{ route('consumos.todos') }}" class="btn btn-info">Todos los consumos</a>

                            
                       </div>
                        <div class="col-md-6">
                            <form action="{{ route('consumos.index') }}" method="GET" class="form-inline float-md-end mb-3">
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
                                    <th>Propietario</th>
                                    <th>Código</th>
                                    <th>Ubicacion</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($propiedadesConConsumos as $propiedad)
                                    <tr>
                                        <td>{{ $propiedad->persona->papellido }} {{ $propiedad->persona->sapellido }} {{ $propiedad->persona->nombre }}</td>
                                        <td>{{ $propiedad->codigo }}</td>
                                        <td>{{ $propiedad->ubicacion }}</td>
                                        <td>
                                            @if($propiedad->consumos->isNotEmpty())
                                                <a href="{{ route('consumos.detallesPorPropiedad', $propiedad->id) }}" class="btn btn-primary btn-sm">
                                                    Ver detalles
                                                </a>
                                            @else
                                                <span class="text-muted">Sin consumos registrados</span>
                                            @endif
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
