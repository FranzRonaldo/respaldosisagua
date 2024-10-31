@extends('layouts.master')

@section('title')
    Crear Propiedad
@endsection

@section('css')
    <!-- CSS de Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Ajuste de la posición del botón de limpieza */
        .select2-container--default .select2-selection--single .select2-selection__clear {
            right: 28px; /* Asegura que el ícono de borrar quede a la izquierda del desplegable */
            z-index: 2; /* Da prioridad al botón de limpiar sobre el de desplegar */
        }
    </style>
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Propiedades @endslot
        @slot('title') Crear Propiedad @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('propiedades.store') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="persona_id" class="form-label">Persona:</label>
                                    <!-- Agrega un valor vacío para permitir "clear" -->
                                    <select name="persona_id" id="persona_id" class="form-control select2" required>
                                        <option value="">Seleccione una persona</option>
                                        @foreach ($personas as $persona)
                                            <option value="{{ $persona->id }}" {{ old('persona_id') == $persona->id ? 'selected' : '' }}>
                                                {{ $persona->nombre }} {{ $persona->papellido }} {{ $persona->sapellido }} | | {{ $persona->numero_carnet }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <!-- Otros campos aquí -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="codigo" class="form-label">Código:</label>
                                    <input type="text" name="codigo" id="codigo" class="form-control" value="{{ old('codigo') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="red" class="form-label">Red:</label>
                                    <input type="text" name="red" id="red" class="form-control" value="{{ old('red') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ubicacion" class="form-label">Ubicación:</label>
                                    <input type="text" name="ubicacion" id="ubicacion" class="form-control" value="{{ old('ubicacion') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fecha_ingreso" class="form-label">Fecha de Ingreso:</label>
                                    <input type="date" name="fecha_ingreso" id="fecha_ingreso" class="form-control" value="{{ old('fecha_ingreso') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado:</label>
                                    <select name="estado" id="estado" class="form-select" required>
                                        <option value="1" {{ old('estado','1') == '1' ? 'selected' : '' }}>Activo</option>
                                        <option value="0" {{ old('estado') == '0' ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- JS de Select2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#persona_id').select2({
                placeholder: 'Seleccione una persona',
                allowClear: true, // Permite borrar la selección
                width: '100%' // Asegura que ocupa todo el ancho del contenedor
            });
        });
    </script>
@endsection
