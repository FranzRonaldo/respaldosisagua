@extends('layouts.master')
@section('title')
    @lang('translation.Create_Activity')
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Activities @endslot
        @slot('title') Crear nueva actividad @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('actividades.store') }}" class="needs-validation" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nombre de la actividad</label>
                            <input class="form-control" placeholder="Inserte el nombre de la actividad" type="text" name="nombre_actividad" required>
                            <div class="invalid-feedback">Porfavor ingrese el nombre de la actividad</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Descripcion de la actividad</label>
                            <textarea class="form-control" placeholder="Breve descripcion" name="descripcion" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha</label>
                            <input class="form-control" placeholder="Seleccione la fecha" type="date" name="fecha" required>
                            <div class="invalid-feedback">Porfavor seleccione una fecha valida</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Multa (Bs)</label>
                            <input class="form-control" placeholder="Inserte la multa en bs." type="text" name="multa" value="{{ old('multa', $actividad->multa ?? '') }}">
                            <div class="invalid-feedback">Añada la multa en bs</div>
                        </div>

                        <button type="submit" class="btn btn-success">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
