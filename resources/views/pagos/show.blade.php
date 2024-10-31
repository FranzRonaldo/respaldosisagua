@extends('layouts.master')

@section('title')
    Detalle del Pago
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle') Pagos @endslot
        @slot('title') Detalle del Pago @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Información del Pago</h4>
                    <p><strong>Persona:</strong> {{ $pago->persona->nombre }} {{ $pago->persona->papellido }} {{ $pago->persona->sapellido }}</p>
                    <p><strong>Propiedad:</strong> {{ $pago->propiedad->codigo }}</p>
                    <p><strong>Fecha de Pago:</strong> {{ $pago->fecha_pago->format('d/m/Y') }}</p>
                    <p><strong>Estado:</strong> {{ $pago->estado_pago ? 'Pagado' : 'Pendiente' }}</p>
                    <p><strong>Código de Pago:</strong> {{ $pago->codigo }}</p>

                    <h4 class="mt-4">Detalles Asociados al Pago</h4>

                    <!-- Consumos Asociados -->
                    @if($pago->tipo_pago === 'consumo')
                        <h5>Consumos Asociados:</h5>
                        <ul>
                            @foreach($pago->consumos as $consumo)
                                <li>
                                    <strong>Período:</strong> {{ $consumo->mes }}/{{ $consumo->anio }} - 
                                    <strong>Monto:</strong> {{ $consumo->pivot->monto_pagado }} Bs. - 
                                    <strong>Código:</strong> C-{{ str_pad($consumo->id, 6, '0', STR_PAD_LEFT) }}
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <!-- Multas Asociadas -->
                    @if($pago->tipo_pago === 'multa')
                        <h5>Multas Asociadas:</h5>
                        <ul>
                            @foreach($pago->multas as $multa)
                                <li>
                                    <strong>Actividad:</strong> {{ $multa->actividad->nombre_actividad }} - 
                                    <strong>Monto:</strong> {{ $multa->pivot->monto_pagado }}€ - 
                                    <strong>Pagada:</strong> {{ $multa->pagada ? 'Sí' : 'No' }} - 
                                    <strong>Código:</strong> M-{{ str_pad($multa->id, 6, '0', STR_PAD_LEFT) }}
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <a href="{{ route('pagos.index') }}" class="btn btn-secondary">Volver</a>
                    <a href="{{ route('pagos.generar-recibo', $pago->id) }}" class="btn btn-success">Descargar Recibo</a>
                </div>
            </div>
        </div>
    </div>
@endsection
