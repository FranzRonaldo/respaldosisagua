<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px; /* Reducir el tamaño de la fuente */
        }
        .bordered-container {
            border: 4px solid black;
            padding: 10px; /* Reducir el padding interno */
            margin: 5px; /* Reducir el margen externo */
        }
        .header, .footer {
            text-align: center;
            font-weight: bold;
            font-size: 12px; /* Reducir el tamaño de la fuente */
            position: relative; /* Añadir posición relativa para la imagen */
        }
        .header img {
            position: absolute;
            top: 10px; /* Ajustar la posición según sea necesario */
            left: 10px; /* Ajustar la posición según sea necesario */
            width: 150px; /* Ajustar el tamaño de la imagen según sea necesario */
            height: 100px;
        }
        .content {
            margin: 10px; /* Reducir el margen */
        }
        .content p {
            margin: 3px 0; /* Reducir el margen */
        }
        .content .amount {
            font-size: 14px; /* Reducir el tamaño de la fuente */
            font-weight: bold;
        }
        .content .signatures {
            margin-top: 20px; /* Reducir el margen */
        }
        .content .signatures div {
            display: inline-block;
            width: 40%;
            text-align: center;
            margin-top: 30px; /* Reducir el margen superior */
        }
        .green-text {
            color: green;
        }
        .amount-table {
            margin-top: 5px; /* Reducir el margen superior */
            border: 1px solid black;
            width: 100%;
            padding: 5px; /* Reducir el padding */
        }
        .signature-line {
            border-top: 1px solid black;
            width: 150px; /* Reducir el ancho */
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="bordered-container">
    <div class="header">
        <img src="{{ public_path('images/logo.jpg') }}" class="header-image" alt="Logo_recibo">
        <p>COOPERATIVA DE AGUA POTABLE</p>
        <p>"GUADALUPE MARQUINA"</p>
        <p>Cochabamba - Bolivia</p>
        <h2>RECIBO DE INGRESO</h2>
        <p><strong>N°:</strong> {{ $numero_recibo }}</p>
    </div>

    <div class="content">
        <p><strong>Recibí de Sr.(a):</strong> {{ $persona->nombre }} {{ $persona->papellido }} {{ $persona->sapellido }}</p>
        <p><strong>La suma de:</strong> {{ $monto_letras }} <strong>Bolivianos</strong></p>
        <p><strong>Por concepto de:</strong> {{ $concepto }}</p>

        <!-- Mostrar detalles de multas como texto -->
        @if($concepto == 'Multa por actividades')
            <p><strong>Detalles de la multa:</strong></p>
            @foreach($items as $item)
                <p>Multa por No asistencia a "{{ $item['actividad'] }}" Monto:  {{ number_format($item['monto'], 2) }} Bs.</p>
            @endforeach
        @elseif($concepto == 'Consumo de agua') <!-- Mostrar detalles del consumo -->
            <p><strong>Detalles del consumo:</strong></p>
            <ul>
                @foreach($consumos as $consumo) <!-- Asumiendo que $consumos contiene la lista de consumos -->
                    <li>
                        <strong>Período:</strong> {{ $consumo->mes }}/{{ $consumo->anio }} - 
                        <strong>Monto:</strong> {{ number_format($consumo->pivot->monto_pagado, 2) }} Bs. - 
                        <strong>Código:</strong> C-{{ str_pad($consumo->id, 6, '0', STR_PAD_LEFT) }}
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="amount-table">
            <table style="width: 100%;">
                <tr>
                    <td>Bs.</td>
                    <td class="amount">{{ number_format($monto_cobrar, 2) }}</td>
                </tr>
            </table>
        </div>
        <p><strong>Número de recibo:</strong> {{ $numero_recibo }}</p>
        <p><strong>Bolivianos</strong> <span class="green-text">Bolivianos</span></p>
        <p>Marquina, {{ $fecha_pago }}</p>
        <div class="signatures">
            <div>
                <div class="signature-line"></div>
                <p>RECIBÍ CONFORME</p>
            </div>
            <div>
                <div class="signature-line"></div>
                <p>ENTREGUÉ CONFORME</p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
