<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Actividad;
use Carbon\Carbon;

class ActividadController extends Controller
{
    // Muestra una lista de las actividades.
public function index(Request $request)
{
    // Obtener los parámetros de búsqueda y mes del request
    $search = $request->input('search');
    $mes = $request->input('mes');

    // Construir la consulta de actividades
    $query = Actividad::query();

    // Si se ha ingresado un término de búsqueda, agregar las condiciones de búsqueda
    if ($search) {
        $query->where('nombre_actividad', 'like', "%{$search}%");
    }

    // Filtrar por mes si se ha seleccionado
    if ($mes) {
        $query->whereMonth('fecha', $mes);
    }

    // Aplicar el criterio de ordenamiento por fecha de forma ascendente
    $query->orderBy('fecha', 'asc'); // Ordenar por fecha

    // Obtener los resultados de la consulta con paginación
    $actividades = $query->paginate(10); // Cambia 10 por el número de registros por página que desees

    // Retornar la vista con los resultados
    return view('actividades.index', compact('actividades'));
}


    // Muestra el formulario para crear una nueva actividad.
    public function create()
    {
        return view('actividades.create');
    }

    // Almacena una nueva actividad en la base de datos.
    public function store(Request $request)
    {
        $request->validate([
            'nombre_actividad' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'multa' => 'nullable|numeric|min:0', // Añadir validación para 'multa'
        ]);

        Actividad::create([
            'nombre_actividad' => $request->nombre_actividad,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
            'multa' => $request->multa ?? 0, // Valor por defecto si 'multa' no está presente
        ]);

        return redirect()->route('actividades.index')
                         ->with('success', 'Actividad creada con éxito.');
    }

    // Muestra el formulario para editar una actividad existente.
    public function edit($id)
    {
        $actividad = Actividad::findOrFail($id);
        $actividad->fecha = Carbon::parse($actividad->fecha)->format('Y-m-d'); // Asegúrate de que el formato sea correcto
        return view('actividades.edit', compact('actividad'));
    }

    // Actualiza una actividad existente en la base de datos.
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_actividad' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'fecha' => 'required|date',
            'multa' => 'nullable|numeric|min:0', // Añadir validación para 'multa'
        ]);

        $actividad = Actividad::findOrFail($id);
        $actividad->update([
            'nombre_actividad' => $request->nombre_actividad,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha,
            'multa' => $request->multa ?? 0, // Valor por defecto si 'multa' no está presente
        ]);

        // Actualizar las asistencias relacionadas
        foreach ($actividad->asistencias as $asistencia) {
            $asistencia->update(['multa' => $actividad->multa]);
        }

        return redirect()->route('actividades.index')
                         ->with('success', 'Actividad actualizada con éxito.');
    }

    // Elimina una actividad de la base de datos.
    public function destroy($id)
    {
        $actividad = Actividad::findOrFail($id);
        $actividad->delete();

        return redirect()->route('actividades.index')
                         ->with('success', 'Actividad eliminada con éxito.');
    }
}
