<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Multa;
use App\Models\Actividad;
use App\Models\Asistencia;

class MultaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        // Obtener actividades con sus asistencias y propiedades
        $actividades = Actividad::with(['asistencias.propiedad'])
            ->when($search, function ($query, $search) {
                return $query->where('nombre_actividad', 'LIKE', "%{$search}%")
                             ->orWhere('descripcion', 'LIKE', "%{$search}%");
            })->get();

        return view('multas.index', compact('actividades'));
    }

    public function show($id)
    {
        // Busca la multa por su ID
        $multa = Multa::with(['propiedad', 'actividad'])->findOrFail($id);

        // Retorna la vista con la multa
        return view('multas.show', compact('multa'));
    }

    public function detalles($actividadId)
{
    // Obtener todas las multas relacionadas con la actividad específica
    $multas = Multa::where('actividad_id', $actividadId)
                   ->with(['propiedad', 'actividad'])
                   ->get();

    // Obtener la actividad para mostrar su nombre en la vista
    $actividad = Actividad::findOrFail($actividadId);

    return view('multas.detalle', compact('multas', 'actividad'));
}
}
