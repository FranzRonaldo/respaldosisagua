<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pago;
use App\Models\Recibo;
use App\Models\Consumo;
use Carbon\Carbon;


class PDFController extends Controller
{
    public function index()
    {
        // Retorna la vista con los enlaces a los reportes de consumos
        return view('reportes.index');
    }

    public function show($id)
{
    // Lógica para obtener un reporte específico según el ID
    $reporte = Consumo::findOrFail($id); // o el modelo correspondiente

    return view('reportes.show', compact('reporte'));
}

public function generarRecibo($id)
{
    $pago = Pago::with(['persona', 'propiedad', 'consumos', 'multas'])->findOrFail($id);
    
    // Preparar datos comunes
    $persona = $pago->persona;
    $monto_cobrar = 0;
    $concepto = '';
    $items = [];

    if ($pago->tipo_pago === 'consumo') {
        // Obtener datos de consumo
        $monto_cobrar = $pago->consumos->sum('pivot.monto_pagado');
        $concepto = "Consumo de agua de ";
        
        // Mapear los consumos para incluir mes y año
        $items = $pago->consumos->map(function($consumo) {
            return [
                'mes' => $this->convertirNumeroAMes($consumo->mes), // Convertir el número del mes a su nombre
                'anio' => $consumo->anio,
                'monto' => $consumo->pivot->monto_pagado
            ];
        });

        // Aquí puedes ajustar el concepto para incluir el último mes y año de consumo
        $ultimoConsumo = $items->last();
        $concepto .= $ultimoConsumo['mes'] . ' ' . $ultimoConsumo['anio'];
    } elseif ($pago->tipo_pago === 'multa') {
        // Obtener datos de multas
        $monto_cobrar = $pago->multas->sum('pivot.monto_pagado');
        $concepto = "Multa por actividades";
        $items = $pago->multas->map(function($multa) {
            return [
                'actividad' => $multa->actividad->nombre_actividad,
                'monto' => $multa->pivot->monto_pagado,
                'pagada' => $multa->pagada ? 'Sí' : 'No'
            ];
        });
    }

    // Convertir monto en letras
    $monto_letras = $this->convertirNumeroATexto($monto_cobrar);

    // Datos del recibo
    $data = [
        'persona' => $persona,
        'monto_letras' => $monto_letras,
        'monto_cobrar' => $monto_cobrar,
        'numero_recibo' => $pago->codigo,
        'fecha_pago' => $pago->fecha_pago->format('d/m/Y'),
        'concepto' => $concepto,
        'items' => $items
    ];

    // Renderizar la vista `template.blade.php` y generar PDF o descarga
    $pdf = PDF::loadView('recibos.template', $data);

    // Configurar el tamaño de papel a A5
    $pdf->setPaper('A5', 'landscape');

    return $pdf->download('recibo_'.$pago->codigo.'.pdf');
}

// Función para convertir número de mes a nombre
private function convertirNumeroAMes($numeroMes)
{
    $meses = [
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre'
    ];

    return $meses[$numeroMes] ?? 'Mes no válido';
}
    

    // Función para convertir el monto a texto
    protected function convertirNumeroATexto($numero)
    {
        $unidades = [
            0 => 'cero', 1 => 'uno', 2 => 'dos', 3 => 'tres', 4 => 'cuatro', 5 => 'cinco',
            6 => 'seis', 7 => 'siete', 8 => 'ocho', 9 => 'nueve', 10 => 'diez',
            11 => 'once', 12 => 'doce', 13 => 'trece', 14 => 'catorce', 15 => 'quince',
            16 => 'dieciséis', 17 => 'diecisiete', 18 => 'dieciocho', 19 => 'diecinueve'
        ];
        
        $decenas = [
            2 => 'veinte', 3 => 'treinta', 4 => 'cuarenta', 5 => 'cincuenta',
            6 => 'sesenta', 7 => 'setenta', 8 => 'ochenta', 9 => 'noventa'
        ];

        if ($numero < 20) {
            return $unidades[$numero];
        } elseif ($numero < 100) {
            $decena = intval($numero / 10);
            $unidad = $numero % 10;
            
            if ($unidad === 0) {
                return $decenas[$decena];
            } else {
                return $decenas[$decena] . ' y ' . $unidades[$unidad];
            }
        }

        // Manejar otros casos si es necesario (por ejemplo, cientos, miles, etc.)

        return "Número fuera de rango"; // Para números no manejados
    }

    public function generarReportePagadosPDF(Request $request)
    {
        // Consultar consumos pagados con filtro de mes (si se aplica)
        $query = Consumo::where('estado_pago', 1);
    
        if ($request->has('mes') && $request->mes != '') {
            $query->where('mes', $request->mes);
        }
    
        $consumos = $query->get();
        $totalIngresado = $query->sum('monto_cobrar');
    
        // Generar PDF
        $pdf = Pdf::loadView('consumos.pdf', compact('consumos', 'totalIngresado'));
        return $pdf->download('reporte_consumos_pagados.pdf');
    }

    public function generarReportePendientesPDF(Request $request)
    {
        // Consultar consumos pendientes con filtro de mes (si se aplica)
        $query = Consumo::where('estado_pago', 0);

        if ($request->has('mes') && $request->mes != '') {
            $query->where('mes', $request->mes);
        }

        $consumos = $query->get();
        $totalPendiente = $query->sum('monto_cobrar'); // Sumar el monto de los consumos pendientes

        // Generar PDF
        $pdf = Pdf::loadView('consumos.pdf_pendientes', compact('consumos', 'totalPendiente'));
        return $pdf->download('reporte_consumos_pendientes.pdf');
    }
  
}
