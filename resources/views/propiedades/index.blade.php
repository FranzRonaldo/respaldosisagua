@extends('layouts.master')
@section('title')
    Lista de Propiedades
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Propiedades @endslot
        @slot('title') Lista de Propiedades @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <!-- Mensaje de éxito -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <a href="{{ route('propiedades.create') }}" class="btn btn-success waves-effect waves-light">
                                    <i class="mdi mdi-plus me-2"></i> Crear Propiedad
                                </a>
                            </div>
                        </div>

                        <!-- Barra de búsqueda -->
                        <div class="col-md-6">
                            <div class="form-inline float-md-end mb-3">
                                <div class="search-box ms-2">
                                    <div class="position-relative">
                                        <form method="GET" action="{{ route('propiedades.index') }}">
                                            <div class="input-group">
                                                <input type="text" name="search" class="form-control" placeholder="Buscar..." value="{{ request('search') }}">
                                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <div class="table-responsive mb-4">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Identificador</th>
                                    <th scope="col">Dueño de la Propiedad</th>
                                    <th scope="col">CI</th>
                                    <th scope="col">Código</th>
                                    <th scope="col">Red</th>
                                    <th scope="col">Ubicación</th>
                                    <th scope="col">Fecha de Ingreso</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col" style="width: 200px;">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($propiedades as $propiedad)
                                    <tr>
                                        <td>{{ $propiedad->identificador_propiedad }}</td>
                                        <td>{{ $propiedad->persona ? "{$propiedad->persona->papellido} {$propiedad->persona->sapellido} {$propiedad->persona->nombre}" : 'Sin persona asignada' }}</td>
                                        <td>{{ $propiedad->persona ? "{$propiedad->persona->numero_carnet} " : 'Sin persona asignada' }}</td>
                                        <td>{{ $propiedad->codigo }}</td>
                                        <td>{{ $propiedad->red }}</td>
                                        <td>{{ $propiedad->ubicacion }}</td>
                                        <td>{{ \Carbon\Carbon::parse($propiedad->fecha_ingreso)->format('d/m/Y') }}</td>
                                        <td>
                                            @if($propiedad->estado)
                                                <span class="badge bg-success">Activo</span>
                                            @else
                                                <span class="badge bg-danger">Inactivo</span>
                                            @endif
                                        </td>
                                        <td>
                                            <ul class="list-inline mb-0">
                                                <!-- Botón de editar -->
                                                <li class="list-inline-item">
                                                    <a href="{{ route('propiedades.edit', $propiedad->id) }}" class="px-2 text-primary">
                                                        <i class="uil uil-pen font-size-18"></i>
                                                    </a>
                                                </li>

                                                <!-- Otros botones de acción (Dropdown) -->
                                                <li class="list-inline-item dropdown">
                                                    <a class="text-muted dropdown-toggle font-size-18 px-2" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="uil uil-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#">Ver Detalles</a>
                                                        <a class="dropdown-item" href="#">Otra Acción</a>
                                                    </div>
                                                </li>

                                                <!-- Botón de cambio de estado (Activar/Inactivar) -->
                                                @if($propiedad->estado)
                                                    <form action="{{ route('propiedades.inactivate', $propiedad->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="px-2 text-danger" style="border: none; background: none;" onclick="return confirm('¿Deseas marcar esta propiedad como inactiva?');">
                                                            <i class="uil uil-minus-circle font-size-18"></i> Inactivar
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('propiedades.activate', $propiedad->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="px-2 text-success" style="border: none; background: none;" onclick="return confirm('¿Deseas marcar esta propiedad como activa?');">
                                                            <i class="uil uil-plus-circle font-size-18"></i> Activar
                                                        </button>
                                                    </form>
                                                @endif
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-3">
                        {{ $propiedades->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
