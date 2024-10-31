@extends('layouts.master')
@section('title')
    Lista de Actividades
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Actividades @endslot
        @slot('title') Lista de Actividades @endslot 
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <a href="{{ route('actividades.create') }}" class="btn btn-success waves-effect waves-light">
                                    <i class="mdi mdi-plus me-2"></i> Crear Nueva Actividad
                                </a>
                            </div>
                        </div>

                        <!-- Barra de búsqueda -->
                        <div class="col-md-6">
                            <div class="form-inline float-md-end mb-3">
                                <div class="search-box ms-2">
                                    <div class="position-relative">
                                        <form method="GET" action="{{ route('actividades.index') }}">
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

                    <!-- Formulario para seleccionar el mes -->
                    <form action="{{ route('actividades.index') }}" method="GET" class="mb-4">
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="mes">Seleccionar Mes:</label>
                                    <select name="mes" id="mes" class="form-control">
                                        <option value="">-- Todos los Meses --</option>
                                        @foreach(range(1, 12) as $m)
                                            <option value="{{ $m }}" {{ request('mes') == $m ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                                <a href="{{ route('actividades.index') }}" class="btn btn-secondary">Limpiar Filtros</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive mb-4">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Multa (Bs)</th>
                                    <th scope="col">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($actividades as $actividad)
                                    <tr>
                                        <td>{{ $actividad->nombre_actividad }}</td>
                                        <td>{{ \Carbon\Carbon::parse($actividad->fecha)->format('d/m/Y') }}</td>
                                        <td>{{ number_format($actividad->multa, 2) }} Bs</td>
                                        <td>
                                            <ul class="list-inline mb-0">
                                                <li class="list-inline-item">
                                                    <a href="{{ route('actividades.edit', $actividad->id) }}" class="px-2 text-primary">
                                                        <i class="uil uil-pen font-size-18"></i>
                                                    </a>
                                                </li>
                                                <!-- Formulario de eliminación -->
                                                <form action="{{ route('actividades.destroy', $actividad->id) }}" method="POST" style="display:inline;" id="delete-form-{{ $actividad->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="javascript:void(0);" class="px-2 text-danger" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $actividad->id }}').submit();">
                                                        <i class="uil uil-trash-alt font-size-18"></i>
                                                    </a>
                                                </form>
                                            </ul>
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
