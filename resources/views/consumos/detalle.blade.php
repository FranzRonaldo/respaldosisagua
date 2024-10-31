@extends('layouts.master')

@section('title')
    Detalle de Consumos de la Propiedad
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Consumos @endslot
        @slot('title') Detalle de Consumos de la Propiedad @endslot
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
                    <h4>Consumos de la propiedad de {{ $propiedad->persona->nombre }} con código {{ $propiedad->identificador_propiedad }} </h4>
                    <div class="table-responsive mb-4">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Mes</th>
                                    <th>Año</th>
                                    <th>Lectura Anterior (m³)</th>
                                    <th>Lectura Actual (m³)</th>
                                    <th>Consumo (m³)</th>
                                    <th>Monto por Consumo (Bs)</th>
                                    <th>Bloqueado</th>
                                    <th>Estado de Pago</th>
                                    <th>Acciones</th> <!-- Columna para acciones -->
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                                @endphp
                                @foreach($consumos as $consumo)
                                    <tr>
                                        <td>{{ $meses[$consumo->mes - 1] }}</td>
                                        <td>{{ $consumo->anio }}</td>
                                        <td>{{ $consumo->lectura_anterior }}</td>
                                        <td>{{ $consumo->lectura_actual }}</td>
                                        <td>{{ $consumo->lectura_actual - $consumo->lectura_anterior }}</td>
                                        <td>{{ $consumo->monto_cobrar }}</td>
                                        <td>
                                            @if($consumo->bloqueado == 1)
                                                Sí
                                            @else
                                                No
                                            @endif
                                        </td>
                                        <td>
                                            @if($consumo->estado_pago)
                                                <span class="badge bg-success">Pagado</span>
                                            @else
                                                <span class="badge bg-warning">Pendiente</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('consumos.edit', $consumo->id) }}" class="text-warning" title="Editar Consumo">
                                                <i class="fas fa-edit"></i> <!-- Ícono de edición -->
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
