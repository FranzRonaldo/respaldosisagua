@extends('layouts.master')

@section('title')
    Registrar Pago
@endsection

@section('css')
    <!-- CSS de Select2 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-selection--single .select2-selection__clear {
            right: 28px;
            z-index: 2;
        }
    </style>
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Pagos @endslot
        @slot('title') Registrar Pago @endslot
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

                    <form action="{{ route('pagos.store') }}" method="POST">
                        @csrf
                        
                        <!-- Selección de Persona con Select2 -->
                        <div class="mb-3">
                            <label for="persona_id" class="form-label">Persona</label>
                            <select class="form-control select2" id="persona_id" name="persona_id" required>
                                <option value="" selected disabled>Seleccione una persona</option>
                                @foreach($personas as $persona)
                                    <option value="{{ $persona->id }}">
                                        {{ $persona->nombre }} {{ $persona->papellido }} {{ $persona->sapellido }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                       <!-- Selección de Propiedad -->
                       <div class="mb-3">
                            <label for="propiedad_id" class="form-label">Propiedad</label>
                            <select class="form-control" id="propiedad_id" name="propiedad_id" required>
                                @if($propiedadPredeterminada)
                                    <option value="{{ $propiedadPredeterminada->id }}" selected>{{ $propiedadPredeterminada->codigo }}</option>
                                @else
                                    <option value="" selected disabled>Seleccione una propiedad</option>
                                    @foreach($propiedades as $propiedad)
                                        <option value="{{ $propiedad->id }}">
                                            {{ $propiedad->codigo }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        

                         <!-- Selección de Tipo de Pago -->
                         <div class="form-group">
                            <label for="tipo_pago">Tipo de Pago</label>
                            <select name="tipo_pago" class="form-control" required id="tipo_pago">
                                <option value="consumo">Consumo</option>
                                <option value="multa">Multa</option>
                            </select>
                        </div>

                         <!-- Selección de Consumo -->
<div class="mb-3" id="consumo_field" style="display: none;">
    <label for="consumo_id" class="form-label">Consumo</label>
    <select class="form-control" id="consumo_id" name="consumos[]">
        @if($consumoPredeterminado)
            <option value="{{ $consumoPredeterminado->id }}" selected>Consumo de {{ $consumoPredeterminado->mes }}/{{ $consumoPredeterminado->anio }} - {{ $consumoPredeterminado->monto_cobrar }}€</option>
        @else
            <option value="" selected disabled>Seleccione un consumo</option>
            @foreach($consumos as $consumo)
                <option value="{{ $consumo->id }}">
                    Consumo de {{ $consumo->mes }}/{{ $consumo->anio }} - {{ $consumo->monto_cobrar }}€
                </option>
            @endforeach
        @endif
    </select>
</div>

<!-- Campo para seleccionar multas -->
<div class="form-group" id="multa_field" style="display: none;">
    <label for="multas[]">Seleccionar Multa(s)</label>
    <select name="multas[]" class="form-control" multiple>
        @foreach ($multas as $multa)
            <option value="{{ $multa->id }}">
                {{ $multa->codigo }} - {{ $multa->monto }} €
            </option>
        @endforeach
    </select>
</div>

                        <div class="form-group">
                            <label for="fecha_pago">Fecha de Pago</label>
                            <input type="date" name="fecha_pago" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success">Registrar Pago</button>
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
            // Inicializa el select de persona con Select2
            $('#persona_id').select2({
                placeholder: 'Seleccione una persona',
                allowClear: true,
                width: '100%'
            });

           // Lógica para cargar propiedades basadas en la persona seleccionada
$('#persona_id').on('change', function() {
    var personaId = $(this).val();
    if (personaId) {
        fetch(`/api/propiedades/${personaId}`)
            .then(response => response.json())
            .then(data => {
                // Limpiar opciones actuales
                let propiedadSelect = $('#propiedad_id');
                propiedadSelect.html('<option value="" selected disabled>Seleccione una propiedad</option>');

                // Cargar nuevas opciones de propiedades
                data.propiedades.forEach(function(propiedad) {
                    propiedadSelect.append(new Option(`${propiedad.identificador_propiedad} - ${propiedad.codigo}`, propiedad.id));
                });
                propiedadSelect.prop('disabled', false);

                // Limpiar selects de consumos y multas
                $('#consumo_id').html('<option value="" selected disabled>Seleccione un consumo</option>').prop('disabled', true);
                $('#multa_id').html('<option value="" selected disabled>Seleccione una multa</option>').prop('disabled', true);
            });
    }
});

// Lógica para cargar consumos y multas basados en la propiedad seleccionada
$('#propiedad_id').on('change', function() {
    var propiedadId = $(this).val();
    if (propiedadId) {
        fetch(`/api/consumos-y-multas/${propiedadId}`)
            .then(response => response.json())
            .then(data => {
                // Cargar consumos específicos de la propiedad
                let consumoSelect = $('#consumo_id');
                consumoSelect.html('<option value="" selected disabled>Seleccione un consumo</option>');
                data.consumos.forEach(function(consumo) {
                    consumoSelect.append(new Option(`Consumo de ${consumo.mes}/${consumo.anio} - ${consumo.monto_cobrar}BS`, consumo.id));
                });
                consumoSelect.prop('disabled', false);

                // Cargar multas específicas de la propiedad
                let multaSelect = $('#multa_id');
                multaSelect.html('<option value="" selected disabled>Seleccione una multa</option>');
                data.multas.forEach(function(multa) {
                    multaSelect.append(new Option(`Multa: ${multa.codigo} - ${multa.monto}BS`, multa.id));
                });
                multaSelect.prop('disabled', false);
            });
    }
});


            // Chequea el valor inicial de tipo de pago al cargar la página
            const tipoPagoInicial = $('#tipo_pago').val();
            if (tipoPagoInicial === 'consumo') {
                $('#consumo_field').show();
                $('#consumo_id').prop('required', true);
                $('#multa_field').hide();
            } else if (tipoPagoInicial === 'multa') {
                $('#multa_field').show();
                $('#multas').prop('required', true);
                $('#consumo_field').hide();
            }

            // Cambia la visibilidad de campos de consumo/multa según el tipo de pago
            $('#tipo_pago').on('change', function() {
                if ($(this).val() === 'consumo') {
                    $('#consumo_field').show();
                    $('#consumo_id').prop('required', true);
                    $('#multa_field').hide();
                    $('#multas').prop('required', false);
                } else if ($(this).val() === 'multa') {
                    $('#multa_field').show();
                    $('#multas').prop('required', true);
                    $('#consumo_field').hide();
                    $('#consumo_id').prop('required', false);
                }
            });
        });
    </script>
@endsection

