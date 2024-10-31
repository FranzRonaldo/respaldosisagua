@extends('layouts.master')

@section('title')
    Lista de Multas
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Multas @endslot
        @slot('title') Lista de Multas @endslot
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
                                    <th>Actividad</th>
                                  <!--  <th>Propiedades con Multas</th> -->
                                    <th>Total Multas</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($actividades as $actividad)
                                    <tr>
                                        <td>{{ $actividad->nombre_actividad }}</td>
                                      <!--     <td>
                                          @foreach ($actividad->asistencias as $asistencia)
                                               @if ($asistencia->multa_aplicada && $asistencia->bloqueado)
                                                    <div>{{ $asistencia->propiedad->nombre }}</div>
                                                @endif 
                                            @endforeach  
                                        </td>   -->
                                        <td>
                                            {{ $actividad->asistencias->where('multa_aplicada', 1)->count() }}
                                        </td>
                                        <td>
                                            <a href="{{ route('multas.detalle', $actividad->id) }}" class="btn btn-primary btn-sm">
                                                Ver Detalles
                                            </a>
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
