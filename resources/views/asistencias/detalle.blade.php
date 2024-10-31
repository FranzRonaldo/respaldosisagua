@extends('layouts.master')

@section('title')
    Detalles de la Actividad: {{ $actividad->nombre_actividad }}
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Asistencias @endslot
        @slot('title') Detalles de la Actividad @endslot
    @endcomponent

    <!-- Mostrar mensajes de éxito o error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Actividad: {{ $actividad->nombre_actividad }} ({{ \Carbon\Carbon::parse($actividad->fecha)->format('d/m/Y') }})</h4>
                    <h4 class="card-title">Multa: {{ $actividad->multa }} Bs</h4>
                    <div class="table-responsive mb-4">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Propiedad</th>
                                    <th>Código</th>
                                    <th>Asistencia</th>
                                    <th>Bloqueado</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($propiedades as $propiedad)
                                    <tr>
                                        <td>{{ $propiedad->persona->nombre }} {{ $propiedad->persona->papellido }}</td>
                                        <td>{{ $propiedad->codigo }}</td>
                                        <td>
                                            <!-- Formulario para cambiar asistencia -->
                                            <form action="{{ route('asistencias.activate', ['propiedad_id' => $propiedad->id, 'actividad_id' => $actividad->id]) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="px-2 text-success" style="border: none; background: none;" onclick="return confirm('¿Deseas marcar como asistió?');">
                                                    <i class="uil uil-plus-circle font-size-18"></i> Asistió
                                                </button>
                                            </form>
                                            <form action="{{ route('asistencias.inactivate', ['propiedad_id' => $propiedad->id, 'actividad_id' => $actividad->id]) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="px-2 text-danger" style="border: none; background: none;" onclick="return confirm('¿Deseas marcar como no asistió?');">
                                                    <i class="uil uil-minus-circle font-size-18"></i> No Asistió
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            @php
                                                $asistencia = $propiedad->asistencias->firstWhere('actividad_id', $actividad->id);
                                            @endphp
                                            @if($asistencia)
                                                {{ $asistencia->bloqueado == 1 ? 'Sí' : 'No' }}
                                            @else
                                                Pendiente
                                            @endif
                                        </td>
                                        <td>
                                            @if($propiedad->asistencias->contains('actividad_id', $actividad->id))
                                                @if($propiedad->asistencias->firstWhere('actividad_id', $actividad->id)->asistio == 1)
                                                    <span class="badge bg-success">Asistió</span>
                                                @else
                                                    <span class="badge bg-danger">No Asistió</span>
                                                @endif
                                            @else
                                                <span class="badge bg-warning">Pendiente</span>
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
