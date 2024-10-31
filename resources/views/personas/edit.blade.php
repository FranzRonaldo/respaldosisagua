@extends('layouts.master')

@section('title')
    Editar Persona
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Contacts @endslot
        @slot('title') Editar Persona @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <!-- Mostrar errores de validación -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('personas.update', $persona->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Nombre -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre:</label>
                                    <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre', $persona->nombre) }}" pattern="[A-Za-zÀ-ÿ\u00f1\u00d1\s]+" title="Solo se permiten letras y espacios" required>
                                </div>
                            </div>
                        </div>

                        <!-- Apellidos -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="papellido" class="form-label">Primer Apellido:</label>
                                    <input type="text" name="papellido" id="papellido" class="form-control" value="{{ old('papellido', $persona->papellido) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sapellido" class="form-label">Segundo Apellido:</label>
                                    <input type="text" name="sapellido" id="sapellido" class="form-control" value="{{ old('sapellido', $persona->sapellido) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Número de Carnet -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="numero_carnet" class="form-label">Número de Carnet:</label>
                                    <input type="text" id="numero_carnet" name="numero_carnet" class="form-control" value="{{ old('numero_carnet', $persona->numero_carnet) }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="complemento" class="form-label">Complemento del carnet de identidad:</label>
                                    <input type="text" id="complemento" name="complemento" class="form-control" value="{{ old('complemento', $persona->complemento) }}">
                                </div>
                            </div>
                        </div>

                        <!-- Teléfono -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono:</label>
                                    <input type="text" id="telefono" name="telefono" class="form-control" value="{{ old('telefono', $persona->telefono) }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo Electrónico:</label>
                                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $persona->email) }}" >
                                </div>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado:</label>
                                    <select name="estado" id="estado" class="form-select" required>
                                        <option value="1" {{ old('estado', $persona->estado) == 1 ? 'selected' : '' }}>Activo</option>
                                        <option value="0" {{ old('estado', $persona->estado) == 0 ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Actualizar</button>
                            <a href="{{ route('personas.index') }}" class="btn btn-secondary waves-effect waves-light">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.getElementById('nombre').addEventListener('input', function (e) {
            this.value = this.value.replace(/[^A-Za-zÀ-ÿ\u00f1\u00d1\s]/g, '');
        });
    </script>
@endsection
