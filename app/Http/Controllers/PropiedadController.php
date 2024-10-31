<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Propiedad;
use App\Models\Pago;
use App\Models\Persona;

class PropiedadController extends Controller
{
    public function index(Request $request)
    {
        // Obtener los parámetros de búsqueda del request
        $search = $request->input('search');
        $query = Propiedad::query()
            ->join('personas', 'propiedades.persona_id', '=', 'personas.id') // Realiza el join con la tabla personas
            ->select('propiedades.*', 'personas.papellido', 'personas.sapellido', 'personas.nombre'); // Selecciona las columnas necesarias
    
        // Si se ha ingresado un término de búsqueda, agregar las condiciones de búsqueda
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('propiedades.codigo', 'like', "%{$search}%") // Filtrar por código
                      ->orWhere('personas.papellido', 'like', "%{$search}%") // Filtrar por papellido
                      ->orWhere('personas.sapellido', 'like', "%{$search}%") // Filtrar por papellido
                      ->orWhere('personas.nombre', 'like', "%{$search}%") // Filtrar por papellido
                      ->orWhere('personas.numero_carnet', 'like', "%{$search}%"); // Filtrar por número de carnet
            });
        }
    
        // Aplicar el criterio de ordenamiento
        $propiedades = $query->orderBy('propiedades.estado', 'desc') // Primero por estado
                              ->orderBy('personas.papellido') // Luego por papellido
                              ->orderBy('personas.sapellido') // Luego por sapellido
                              ->orderBy('personas.nombre') // Luego por nombre
                              ->paginate(10); // Cambia 10 por el número de registros por página que desees
    
        // Retornar la vista con los resultados
        return view('propiedades.index', compact('propiedades'));
    }

    public function create()
    {
        $personas = Persona::all(); // Obtener personas para el dropdown
        return view('propiedades.create', compact('personas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'nullable|string|max:20|unique:propiedades',
            'red' => 'required|string|max:40',
            'ubicacion' => 'required|string|max:40',
            'fecha_ingreso' => 'required|date',
            'estado' => 'required|integer',
            'persona_id' => 'required|exists:personas,id',
        ]);

        // Crear la propiedad
        $propiedad = Propiedad::create($request->all());

        return redirect()->route('propiedades.index')
                         ->with('success', 'Propiedad creada con éxito.');
    }

    public function edit($id)
    {
        $propiedad = Propiedad::findOrFail($id);
        $personas = Persona::all();

        return view('propiedades.edit', compact('propiedad', 'personas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'codigo' => 'required|string|max:20',
            'red' => 'required|string|max:40',
            'ubicacion' => 'required|string|max:40',
            'fecha_ingreso' => 'required|date',
            'estado' => 'required|integer',
            'persona_id' => 'required|exists:personas,id',
        ]);

        // Actualizar la propiedad
        $propiedad = Propiedad::findOrFail($id);
        $propiedad->update($request->all());

        return redirect()->route('propiedades.index')->with('success', 'Propiedad actualizada con éxito.');
    }

    public function activate($id)
    {
        $propiedad = Propiedad::findOrFail($id);
        $propiedad->estado = 1;
        $propiedad->save();

        return redirect()->route('propiedades.index')->with('success', 'La propiedad ha sido activada exitosamente.');
    }

    public function inactivate($id)
    {
        $propiedad = Propiedad::findOrFail($id);
        $propiedad->estado = 0;
        $propiedad->save();

        return redirect()->route('propiedades.index')->with('success', 'La propiedad ha sido inactivada exitosamente.');
    }

    public function destroy(Propiedad $propiedad)
    {
        // Cambiar estado a inactivo en lugar de eliminar
        $propiedad->estado = 0; // Inactivo
        $propiedad->save();

        return redirect()->route('propiedades.index')
                         ->with('success', 'Propiedad marcada como inactiva.');
    }
}
