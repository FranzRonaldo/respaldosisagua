@extends('layouts.master')

@section('title')
    Lista de Actividades
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Asistencias @endslot
        @slot('title') Lista de Actividades @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <a href="{{ route('actividades.create') }}" class="btn btn-success waves-effect waves-light">
                                <i class="mdi mdi-plus me-2"></i> Registrar Actividad
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Actividad</th>
                                    <th>Multa</th>
                                    <th>Fecha</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($actividades as $actividad)
                                    <tr>
                                        <td>{{ $actividad->nombre_actividad }}</td>
                                        <td>{{ $actividad->multa }}</td>
                                        <td>{{ \Carbon\Carbon::parse($actividad->fecha)->format('d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('asistencias.detallesPorActividad', $actividad->id) }}" class="btn btn-primary btn-sm">
                                                Realizar Controles
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
