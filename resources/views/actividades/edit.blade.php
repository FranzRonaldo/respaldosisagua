@extends('layouts.master')

@section('title')
    @lang('translation.Edit_Activity')
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Activities @endslot
        @slot('title') Editar actividad @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('actividades.update', $actividad->id) }}" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT') <!-- Para especificar el método PUT en el formulario -->
                        <div class="mb-3">
                            <label class="form-label">Nombre de la actividad</label>
                            <input class="form-control" placeholder="Inserte el nombre de la actividad" type="text" name="nombre_actividad" value="{{ old('nombre_actividad', $actividad->nombre_actividad) }}" required>
                            <div class="invalid-feedback">Por favor ingrese el nombre de la actividad</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripción de la actividad</label>
                            <textarea class="form-control" placeholder="Breve descripción" name="descripcion" rows="3">{{ old('descripcion', $actividad->descripcion) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha</label>
                            <input class="form-control" placeholder="Seleccione la fecha" type="date" name="fecha" value="{{ old('fecha', $actividad->fecha) }}" required>
                            <div class="invalid-feedback">Por favor seleccione una fecha válida</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Multa (Bs)</label>
                            <input class="form-control" placeholder="Inserte la multa en Bs." type="text" name="multa" value="{{ old('multa', $actividad->multa) }}">
                            <div class="invalid-feedback">Añada la multa en Bs</div>
                        </div>

                        <button type="submit" class="btn btn-success">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
