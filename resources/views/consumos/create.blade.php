@extends('layouts.master')

@section('title')
    Registrar Consumo
@endsection

@section('css')
    <!-- CSS de Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Ajuste de la posición del botón de limpieza */
        .select2-container--default .select2-selection--single .select2-selection__clear {
            right: 28px;
            z-index: 2;
        }
    </style>
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Consumos @endslot
        @slot('title') Registrar Consumo @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('consumos.store') }}" method="POST">
                        @csrf
                        
                        <!-- Selector de Propiedad -->
                        <div class="mb-3">
                            <label for="propiedad_id" class="form-label">Propiedad</label>
                            <select class="form-control select2" id="propiedad_id" name="propiedad_id" required>
                                <option value="" selected disabled>Seleccione una propiedad</option>
                                @foreach($propiedadesData as $data)
                                    <option value="{{ $data['propiedad']->id }}" 
                                            data-lectura-anterior="{{ $data['lectura_anterior'] }}"
                                            data-mes="{{ $data['mes'] }}"
                                            data-anio="{{ $data['anio'] }}">
                                        {{ $data['propiedad']->persona->nombre }} {{ $data['propiedad']->persona->papellido }} || {{ $data['propiedad']->codigo }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Otros campos -->
                        <div class="mb-3">
                            <label for="mes" class="form-label">Mes</label>
                            <select class="form-control" id="mes" name="mes" required>
                                @foreach(['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'] as $index => $month)
                                    <option value="{{ $index + 1 }}">{{ $month }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="anio" class="form-label">Año</label>
                            <input type="number" class="form-control" id="anio" name="anio" required min="2000" max="{{ date('Y') }}">
                        </div>

                        <div class="mb-3">
                            <label for="lectura_anterior" class="form-label">Lectura Anterior (m³)</label>
                            <input type="number" class="form-control" id="lectura_anterior" name="lectura_anterior" step="0.01">
                            <small class="form-text text-muted">Si es el primer registro de la propiedad, deje este campo en blanco. Este campo es editable si es necesario.</small>
                        </div>

                        <div class="mb-3">
                            <label for="lectura_actual" class="form-label">Lectura Actual (m³)</label>
                            <input type="number" class="form-control" id="lectura_actual" name="lectura_actual" required step="0.01">
                        </div>

                        <button type="submit" class="btn btn-primary">Registrar</button>
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
            // Inicializa Select2 para el selector de propiedad
            $('#propiedad_id').select2({
                placeholder: 'Seleccione una propiedad',
                allowClear: true,
                width: '100%'
            });

            // Actualiza los campos correspondientes cuando cambia la propiedad seleccionada
            $('#propiedad_id').on('change', function() {
                var selectedOption = this.options[this.selectedIndex];
                var lecturaAnterior = selectedOption.getAttribute('data-lectura-anterior');
                var mes = selectedOption.getAttribute('data-mes');
                var anio = selectedOption.getAttribute('data-anio');

                $('#lectura_anterior').val(lecturaAnterior || '');
                $('#mes').val(mes || '');
                $('#anio').val(anio || '');
            });
                // Validación antes de enviar el formulario
            $('form').on('submit', function(e) {
                var lecturaAnterior = parseFloat($('#lectura_anterior').val());
                var lecturaActual = parseFloat($('#lectura_actual').val());

                if (lecturaActual < lecturaAnterior) {
                    e.preventDefault(); // Detiene el envío del formulario
                    alert('La lectura actual no puede ser menor que la lectura anterior.');
                    $('#lectura_actual').focus(); // Mueve el foco al campo de lectura actual
                }
            });
        });
    </script>

    
@endsection