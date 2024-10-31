<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Consumos Pendientes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }
        /* Contenedor principal con margen */
        .main-container {
            margin: 40px; /* Ajusta el margen principal aquí */
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            text-align: center;
            flex: 1;
        }
        .logo {
            width: 100px; /* Ajusta el tamaño del logo */
            height: auto;
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
        }
        .date {
            font-size: 12px;
            text-align: right;
            color: #555;
        }
        .total-ingresado {
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .badge {
            color: white;
            background-color: #28a745;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        .no-data {
            text-align: center;
            margin-top: 20px;
            color: #888;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="header">
            <!-- Logo en la parte superior izquierda -->
            <div class="logo">
                <img src="{{ public_path('images/logo.jpg') }}" alt="Logo" style="width: 100%; height: auto;">
            </div>
            <h2>Reporte de Consumos Pendientes</h2>
            <!-- Fecha de generación del reporte en la parte superior derecha -->
            <div class="date">
            <strong> Fecha: </strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }} 
            </div>
        </div>

        <!-- Mostrar el total pendientes -->
        <div class="total-pendiente">
            <strong>Total Pendiente:</strong> {{ number_format($totalPendiente, 2) }} Bs
        </div>

        <!-- Tabla de consumos pendientes -->
        @if($consumos->isNotEmpty())
            <table class="table">
                <thead>
                    <tr>
                        <th>Propietario</th>
                        <th>Código Propiedad</th>
                        <th>Mes</th>
                        <th>Año</th>
                        <th>Monto Total</th>
                        <th>Estado de Pago</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($consumos as $consumo)
                        <tr>
                            <td>{{ $consumo->propiedad->persona->nombre }} {{ $consumo->propiedad->persona->papellido }} {{ $consumo->propiedad->persona->sapellido }}</td>
                            <td>{{ $consumo->propiedad->identificador_propiedad }}</td>
                            <td>{{ \Carbon\Carbon::create()->month($consumo->mes)->translatedFormat('F') }}</td>
                            <td>{{ $consumo->anio }}</td>
                            <td>{{ number_format($consumo->monto_cobrar, 2) }} Bs</td>
                            <td><span class="danger">Pendiente</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="no-data">No hay consumos pendientes para el mes seleccionado.</p>
        @endif
    </div>
</body>
</html>
