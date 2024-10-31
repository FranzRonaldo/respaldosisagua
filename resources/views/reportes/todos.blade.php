@extends('layouts.master')

@section('title')
    Todos los Consumos
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Consumos @endslot
        @slot('title') Todos los Consumos @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                <div class="row mb-2">
                        <div class="col-md-6">
                            <a href="{{ route('consumos.pagados') }}" class="btn btn-secondary">Inicio</a>
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

                    <h4 class="card-title">Todos los Consumos</h4>

                    <!-- Formulario para seleccionar el mes -->
                    <form action="{{ route('consumos.todos') }}" method="GET" class="mb-4">
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
                                    <a href="{{ route('consumos.pagados.pdf', ['mes' => request('mes')]) }}" class="btn btn-success">Descargar Reporte</a>
                                </div>
                            </div>
                        </form>


                    <div class="table-responsive mb-4">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Propietario</th>
                                    <th>Codigo Propiedad</th>
                                    <th>Mes</th>
                                    <th>Año</th>
                                    <th>Monto Total</th>
                                    <th>Estado de Pago</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($consumos as $consumo)
                                    <tr>
                                        <td>{{ $consumo->propiedad->persona->nombre }} {{ $consumo->propiedad->persona->papellido }} {{ $consumo->propiedad->persona->sapellido }}</td>
                                        <td>{{ $consumo->propiedad->identificador_propiedad }}</td>
                                        <td>{{ \Carbon\Carbon::create()->month($consumo->mes)->translatedFormat('F') }}</td>
                                        <td>{{ $consumo->anio }}</td>
                                        <td>{{ $consumo->monto_cobrar }} Bs</td>
                                        <td>
                                            @if($consumo->estado_pago)
                                                <span class="badge bg-success">Pagado</span>
                                            @else
                                                <span class="badge bg-danger">Pendiente</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($consumos->isEmpty())
                        <p class="text-center">No hay consumos para el mes seleccionado.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
