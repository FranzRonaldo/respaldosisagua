@extends('layouts.master')

@section('title')
    Crear Persona
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Contacts @endslot
        @slot('title') Crear Persona @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <!-- Mostrar errores de validación 
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif-->

                    <form action="{{ route('personas.store') }}" method="POST">
                        @csrf

                        <!-- Nombre -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre:</label>
                                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre') }}" pattern="[A-Za-zÀ-ÿ\u00f1\u00d1\s]+" title="Solo se permiten letras y espacios" required>
                                    
                                </div>
                            </div>
                        </div>

                        <!-- Apellidos -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="papellido" class="form-label">Primer Apellido:</label>
                                    <input type="text" name="papellido" id="papellido" class="form-control" value="{{ old('papellido') }}" pattern="[A-Za-zÀ-ÿ\u00f1\u00d1\s]+" title="Solo se permiten letras y espacios" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sapellido" class="form-label">Segundo Apellido:</label>
                                    <input type="text" name="sapellido" id="sapellido" class="form-control" value="{{ old('sapellido') }}" pattern="[A-Za-zÀ-ÿ\u00f1\u00d1\s]+" title="Solo se permiten letras y espacios">
                                </div>
                            </div>
                        </div>

                        <!-- Número de Carnet -->
                        <div class="row mb-3">
                            <div class="col-md-6"> 
                                <div class="mb-3">
                                     <label for="numero_carnet" class="form-label">Número de Carnet:</label>
                                     <input type="number" name="numero_carnet" id="numero_carnet" class="form-control" value="{{ old('numero_carnet') }}" required maxlength="12" min="6" oninput="if(this.value.length > 12) this.value = this.value.slice(6, 12);">
                                     @include('common-components.input-error', ['field' => 'numero_carnet'])
                                    </div>
                          </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="complemento" class="form-label">Complemento del carnet de identidad:</label>
                                    <input type="text" name="complemento" id="complemento" class="form-control" value="{{ old('complemento') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Teléfono -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono:</label>
                                    <input type="text" name="telefono" id="telefono" class="form-control" value="{{ old('telefono') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo Electrónico:</label>
                                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Estado -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado:</label>
                                    <select name="estado" id="estado" class="form-select" required>
                                        
                                        <option value="0" {{ old('estado') == '0' ? 'selected' : '' }}>Inactivo</option>
                                        <option value="1" {{ old('estado', '1') == '1' ? 'selected' : '' }}>Activo</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Guardar</button>
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
        const fields = ['nombre', 'papellido', 'sapellido'];
        fields.forEach(field => {
            document.getElementById(field).addEventListener('input', function (e) {
                this.value = this.value.replace(/[^A-Za-zÀ-ÿ\u00f1\u00d1\s]/g, '');
            });
        });
        </script>
   
@endsection
