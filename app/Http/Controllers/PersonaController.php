<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Propiedad;

class PersonaController extends Controller
{
    public function index(Request $request)
    {
        // Obtener los parámetros de búsqueda y ordenamiento del request
        $search = $request->input('search');
        
        // Construir la consulta de personas
        $personas = Persona::query();

        // Si se ha ingresado un término de búsqueda, agregar las condiciones de búsqueda
        if ($search) {
            $personas->where(function ($query) use ($search) {
                $query->where('nombre', 'like', "%$search%")
                    ->orWhere('papellido', 'like', "%$search%")
                    ->orWhere('sapellido', 'like', "%$search%")
                    ->orWhere('numero_carnet', 'like', "%$search%")
                    ->orWhere('telefono', 'like', "%$search%");
            });
        }

        // Aplicar el criterio de ordenamiento
        $personas->orderBy('estado', 'desc') // Primero por estado: activos arriba de inactivos
             ->orderBy('papellido') // Luego por primer apellido
             ->orderBy('sapellido'); // Luego por segundo apellido

        // Obtener los resultados de la consulta con paginación
        $personas = $personas->paginate(10); // Paginar resultados (10 por página)

        // Retornar la vista con los resultados
        return view('personas.index', compact('personas'));
    }


    public function create()
    {
        return view('personas.create');
    }

    public function store(Request $request)
    {
        // Validar los datos incluyendo la lógica condicional de apellidos y la combinación de número de carnet
        $request->validate([
            'nombre' => 'required|max:40',
            'papellido' => 'required|string|max:20',
            'sapellido' => 'nullable|string|max:20',
            'numero_carnet' => 'required|numeric|unique:personas,numero_carnet', // Agregado como único
            'complemento' => 'nullable|string|max:5',
            'telefono' => 'required|string|max:15',
            'email' => 'nullable|email|unique:personas,email',
            'estado' => 'required|boolean',
        ], [
            'numero_carnet.unique' => 'El número de carnet ingresado ya está registrado.',
            'numero_carnet.required' => 'Debe proporcionar el número de carnet.',
            'numero_carnet.numeric' => 'El número de carnet debe contener solo números.',
            'numero_carnet.max' => 'El número de carnet no puede tener más de 12 dígitos.',
        ]);
        // Combinar número de carnet y complemento
        $numeroCarnetCompleto = $request->numero_carnet . '' . $request->complemento;

        // Crear la nueva persona
        Persona::create([
            'nombre' => $request->nombre,
            'papellido' => $request->papellido,
            'sapellido' => $request->sapellido,
            'numero_carnet' => $numeroCarnetCompleto, // Guardar carnet con complemento
            'telefono' => $request->telefono,
            'email' => $request->email,
            'estado' => $request->estado ?? 1, // Por defecto activo
        ]);

        return redirect()->route('personas.index')->with('success', 'Persona creada con éxito.');
    }

    public function edit(Persona $persona)
    {
        return view('personas.edit', compact('persona'));
    }

    public function update(Request $request, Persona $persona)
    {
        $request->validate([
            'nombre' => 'required|max:40',
            'papellido' => 'required|string|max:20',
            'sapellido' => 'nullable|string|max:20',
            'numero_carnet' => 'required|numeric|unique:personas,numero_carnet,' . $persona->id, // Agregado como único (ignorar el actual)
            'complemento' => 'nullable|string|max:5',
            'telefono' => 'required|string|max:15',
            'email' => 'nullable|email|unique:personas,email,' . $persona->id,
            'estado' => 'required|boolean',
        ], [
            'numero_carnet.unique' => 'El número de carnet ingresado ya está registrado.',
            'numero_carnet.required' => 'Debe proporcionar el número de carnet.',
            'numero_carnet.numeric' => 'El número de carnet debe contener solo números.',
            'numero_carnet.max' => 'El número de carnet no puede tener más de 12 dígitos.',
        ]);

        // Combinar número de carnet y complemento
        $numeroCarnetCompleto = $request->numero_carnet . '' . $request->complemento;

        // Actualizar la persona
        $persona->update([
            'nombre' => $request->nombre,
            'papellido' => $request->papellido,
            'sapellido' => $request->sapellido,
            'numero_carnet' => $numeroCarnetCompleto,
            'telefono' => $request->telefono,
            'email' => $request->email,
            'estado' => $request->estado,
        ]);

        return redirect()->route('personas.index')->with('success', 'Persona actualizada con éxito.');
    }

    public function inactivate(Persona $persona)
    {
        $persona->estado = 0; // Cambiar el estado a inactivo
        $persona->save();

        return redirect()->route('personas.index')->with('success', 'Persona marcada como inactiva.');
    }

    public function activate(Persona $persona)
    {
        $persona->estado = 1; // Cambiar el estado a activo
        $persona->save();

        return redirect()->route('personas.index')->with('success', 'Persona rehabilitada.');
    }

    public function destroy(Persona $persona)
    {
        // Opción 1: Marcar como inactivo (actual)
        $persona->estado = 0; 
        $persona->save();

        // Opción 2: Eliminar físicamente
        $persona->delete();

        return redirect()->route('personas.index')->with('success', 'Persona eliminada con éxito.');
    }
}
