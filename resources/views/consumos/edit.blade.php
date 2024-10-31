@extends('layouts.master')

@section('title')
    Editar Consumo
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Consumos @endslot
        @slot('title') Editar Consumo @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('consumos.update', $consumo->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- ID de Propiedad -->
                        <input type="hidden" name="propiedad_id" value="{{ $consumo->propiedad_id }}">

                        <!-- Información de la persona -->
                        <div class="mb-3">
                            <h5>Información del propietario: </h5>
                            <div>
                                <strong>Nombre:</strong> {{ $consumo->propiedad->persona->nombre }} {{ $consumo->propiedad->persona->papellido }} {{ $consumo->propiedad->persona->sapellido }}<br>
                                <strong>Código de Propiedad:</strong> {{ $consumo->propiedad->codigo }}<br>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="mes" class="form-label">Mes</label>
                            <select class="form-control" id="mes" name="mes" required>
                                @foreach(['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $index => $month)
                                    <option value="{{ $index + 1 }}" {{ $consumo->mes == $index + 1 ? 'selected' : '' }}>{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="anio" class="form-label">Año</label>
                            <input type="number" class="form-control" id="anio" name="anio" value="{{ $consumo->anio }}" required min="2000" max="{{ date('Y') }}">
                        </div>

                        <div class="mb-3">
                            <label for="lectura_anterior" class="form-label">Lectura Anterior (m³)</label>
                            <input type="number" class="form-control" id="lectura_anterior" name="lectura_anterior" value="{{ $consumo->lectura_anterior }}" step="0.01">
                            <small class="form-text text-muted">Este campo es editable si es necesario.</small>
                        </div>

                        <div class="mb-3">
                            <label for="lectura_actual" class="form-label">Lectura Actual (m³)</label>
                            <input type="number" class="form-control" id="lectura_actual" name="lectura_actual" value="{{ $consumo->lectura_actual }}" required step="0.01">
                        </div>

                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="{{ route('consumos.index') }}" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
