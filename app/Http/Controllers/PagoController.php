<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Consumo;
use App\Models\Persona;
use App\Models\Propiedad;
use App\Models\Asistencia;
use App\Models\Multa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PagoController extends Controller
{
    /**
     * Mostrar todos los pagos.
     */
    public function index()
    {
        $pagos = Pago::with(['persona', 'propiedad', 'consumos', 'multas'])->get()->map(function ($pago) {
            $pago->fecha_pago = Carbon::parse($pago->fecha_pago);
            return $pago;
        });

        return view('pagos.index', compact('pagos'));
    }

    /**
     * Mostrar el formulario para crear un nuevo pago.
     */
    public function create()
    {
        $personas = Persona::all();
        $propiedades = Propiedad::with('persona')->get();
        $consumos = Consumo::where('estado_pago', false)->get(); // Solo consumos pendientes de pago
        $multas = Multa::where('pagada', false)->get(); // Solo multas pendientes de pago

        // Determinar propiedad y consumo predeterminados
        $propiedadPredeterminada = $propiedades->count() === 1 ? $propiedades->first() : null;
        $consumoPredeterminado = $consumos->sortBy('anio')->first();

        return view('pagos.create', compact('personas', 'propiedades', 'consumos', 'multas', 'propiedadPredeterminada', 'consumoPredeterminado'));
    }

    /**
     * Guardar un nuevo pago en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'persona_id' => 'required|exists:personas,id',
            'propiedad_id' => 'required|exists:propiedades,id',
            'fecha_pago' => 'required|date',
            'tipo_pago' => 'required|in:consumo,multa',
            'consumos' => 'required_if:tipo_pago,consumo|array',
            'multas' => 'required_if:tipo_pago,multa|array',
        ]);

        // Generar el código del pago basado en el tipo
        $codigo = Pago::generarCodigo($request->tipo_pago);

        // Crear el pago
        $pago = Pago::create([
            'persona_id' => $request->persona_id,
            'propiedad_id' => $request->propiedad_id,
            'fecha_pago' => $request->fecha_pago,
            'estado_pago' => true,
            'codigo' => $codigo,
            'tipo_pago' => $request->tipo_pago,
        ]);

        // Manejo de pagos de consumo
        if ($request->tipo_pago === 'consumo') {
            $this->registrarPagosConsumos($pago, $request->consumos);
        }

        // Manejo de pagos de multa
        if ($request->tipo_pago === 'multa') {
            $this->registrarPagosMultas($pago, $request->multas);
        }

        return redirect()->route('pagos.index')->with('success', 'Pago registrado correctamente.');
    }

    /**
     * Registrar pagos de consumos.
     */
    protected function registrarPagosConsumos(Pago $pago, array $consumos)
    {
        foreach ($consumos as $consumoId) {
            $consumo = Consumo::find($consumoId);
            if ($consumo) {
                $pago->consumos()->attach($consumoId, [
                    'monto_pagado' => $consumo->monto_cobrar,
                ]);
                $consumo->update(['estado_pago' => true, 'bloqueado' => true]);

                
            }
        }
    }

    /**
     * Registrar pagos de multas.
     */
    protected function registrarPagosMultas(Pago $pago, array $multas)
{
    foreach ($multas as $multaId) {
        $multa = Multa::find($multaId);
        if ($multa) {
            $pago->multas()->attach($multaId, [
                'monto_pagado' => $multa->monto,
            ]);
            $multa->update(['pagada' => true, 'bloqueado' => true]);

            // Bloquear asistencias relacionadas con la multa
            $asistencias = Asistencia::where('actividad_id', $multa->actividad_id) // Asegúrate de que 'actividad_id' es un campo válido en tu tabla de multas
                ->where('bloqueado', false)
                ->get();

            foreach ($asistencias as $asistencia) {
                $asistencia->update(['bloqueado' => true]);
            }
        }
    }
}


    /**
     * Mostrar un pago específico.
     */
    public function show($id)
    {
        $pago = Pago::with(['persona', 'propiedad', 'consumos', 'multas'])->findOrFail($id);
        $pago->fecha_pago = Carbon::parse($pago->fecha_pago);

        return view('pagos.show', compact('pago'));
    }

    /**
     * Eliminar un pago específico.
     */
    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);
        $pago->delete();
        return redirect()->route('pagos.index')->with('success', 'Pago eliminado correctamente.');
    }
  /*  public function propiedadesYConsumos($personaId)
    {
        // Obtener propiedades y consumos asociados a la persona seleccionada
        $propiedades = Propiedad::where('persona_id', $personaId)->get(['id', 'identificador_propiedad']);
        $consumos = Consumo::whereIn('propiedad_id', $propiedades->pluck('id'))
            ->where('estado_pago', false)
            ->get(['id', 'mes', 'anio', 'monto_cobrar']);

        // Obtener las multas pendientes solo de las propiedades asociadas
        $multas = Multa::whereIn('propiedad_id', $propiedades->pluck('id'))
        ->where('pagada', false)
        ->get(['id', 'codigo', 'monto']);

        return response()->json([
            'propiedades' => $propiedades,
            'consumos' => $consumos,
        ]);
    }
*/
 /*  public function fetchData($personaId)
{
    $propiedades = Propiedad::where('persona_id', $personaId)->get();
    $consumos = Consumo::where('persona_id', $personaId)->where('estado', 'pendiente')->get();
    $multas = Multa::where('persona_id', $personaId)->where('pagada', 'pendiente')->get();

    return response()->json([
        'propiedades' => $propiedades,
        'consumos' => $consumos,
        'multas' => $multas,
    ]);
}*/

public function fetchData($personaId, $propiedadId = null)
{
    // Obtener solo las propiedades asociadas a la persona seleccionada
    $propiedades = Propiedad::where('persona_id', $personaId)->get(['id', 'identificador_propiedad', 'codigo']);

    // Si se proporciona un propiedadId, obtener consumos y multas asociados a esa propiedad
    if ($propiedadId) {
        // Obtener consumos asociados a la propiedad y pendientes de pago
        $consumos = Consumo::where('propiedad_id', $propiedadId)
            ->where('estado_pago', false)
            ->get(['id', 'mes', 'anio', 'monto_cobrar']);

        // Obtener multas asociadas a la propiedad y pendientes de pago
        $multas = Multa::where('propiedad_id', $propiedadId)
            ->where('pagada', false)
            ->get(['id', 'codigo', 'monto']);
    } else {
        // Si no se proporciona propiedadId, inicializar consumos y multas como vacíos
        $consumos = collect();
        $multas = collect();
    }

    return response()->json([
        'propiedades' => $propiedades,
        'consumos' => $consumos,
        'multas' => $multas,
    ]);
}


///
// Método para obtener propiedades según la persona seleccionada
public function propiedades($personaId)
{
    // Obtener solo las propiedades asociadas a la persona seleccionada
    $propiedades = Propiedad::where('persona_id', $personaId)->get(['id', 'identificador_propiedad', 'codigo']);

    return response()->json([
        'propiedades' => $propiedades,
    ]);
}

public function consumosYMultas($propiedadId)
{
    // Obtener consumos asociados a la propiedad y pendientes de pago
    $consumos = Consumo::where('propiedad_id', $propiedadId)
        ->where('estado_pago', false)
        ->get(['id', 'mes', 'anio', 'monto_cobrar']);

    // Obtener multas asociadas a la propiedad y pendientes de pago
    $multas = Multa::where('propiedad_id', $propiedadId)
        ->where('pagada', false)
        ->get(['id', 'codigo', 'monto']);

    return response()->json([
        'consumos' => $consumos,
        'multas' => $multas,
    ]);
}

}
