<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Propiedad; // Cambio de Socio a Propiedad
use App\Models\Actividad;
use App\Models\Consumo;
use App\Models\Multa;
use App\Http\Controllers\ConsumoController;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    // Mostrar la lista de asistencias
    public function index()
    {
        $asistencias = Asistencia::with('propiedad', 'actividad')->get();
        $actividades = Actividad::all();
        $propiedades = Propiedad::all();
        return view('asistencias.index', compact('asistencias', 'actividades', 'propiedades'));
    }

    // Mostrar el formulario de creación de asistencia
    public function create()
    {
        $propiedades = Propiedad::where('estado', 1)->get(); // Solo propiedades activas
        $actividades = Actividad::all();
        return view('asistencias.create', compact('propiedades', 'actividades'));
    }

    public function store(Request $request, $actividadId)
    {
        $actividad = Actividad::findOrFail($actividadId);
        $propiedades = $request->input('propiedades', []); // Cambio de socios a propiedades

        foreach ($propiedades as $propiedadId => $asistio) {
            // Verificar si la asistencia ya existe y está bloqueada
            $asistenciaExistente = Asistencia::where('propiedad_id', $propiedadId)
                                            ->where('actividad_id', $actividadId)
                                            ->first();

            if ($asistenciaExistente && $asistenciaExistente->bloqueado) {
                // Si la asistencia está bloqueada, no permitir la edición
                continue;
            }

            // Actualizar o crear la asistencia si no está bloqueada
            $asistencia = Asistencia::updateOrCreate(
                [
                    'propiedad_id' => $propiedadId, // Cambio de socio_id a propiedad_id
                    'actividad_id' => $actividadId,
                ],
                [
                    'asistio' => $asistio,
                    'multa_aplicada' => !$asistio ? 1 : 0, // Si no asistió, aplicar multa
                ]
            );
        }

        return redirect()->route('asistencias.index')->with('success', 'Asistencias registradas correctamente.');
    }


    // Mostrar el formulario de edición de asistencia
    public function edit($id)
    {
        $asistencia = Asistencia::findOrFail($id);
        $propiedades = Propiedad::all(); // Cambio de socios a propiedades
        $actividades = Actividad::all();
        return view('asistencias.edit', compact('asistencia', 'propiedades', 'actividades'));
    }

    public function update(Request $request, $id)
    {
        $asistencia = Asistencia::findOrFail($id);

        // Verificar si la asistencia está bloqueada
        if ($asistencia->bloqueado==1) {
            return redirect()->route('asistencias.index')->with('error', 'No se puede editar una asistencia bloqueada.');
        }

        $asistenciaAnterior = $asistencia->asistio;

        // Obtener el nuevo estado de asistencia
        $asistioNuevo = $request->input('asistio');
        $asistencia->asistio = $asistioNuevo;
        $asistencia->multa_aplicada = $asistioNuevo == 0 ? 1 : 0;
        $asistencia->save();

        return redirect()->route('asistencias.index')->with('success', 'Asistencia actualizada correctamente y multa reflejada en consumos.');
    }


    // Eliminar una asistencia
    public function destroy($id)
    {
        $asistencia = Asistencia::findOrFail($id);
        $asistencia->delete();
        return redirect()->route('asistencias.index')->with('success', 'Asistencia eliminada exitosamente.');
    }

    // Método para mostrar los detalles de una actividad y las propiedades relacionadas
    public function detallesPorActividad($id)
    {
        $actividad = Actividad::findOrFail($id);
        $propiedades = Propiedad::where('estado', 1)
            ->with(['persona', 'asistencias' => function($query) use ($id) {
                $query->where('actividad_id', $id);
            }])
            ->get()
            ->sortBy(function($propiedad) {
                return $propiedad->persona->apellido;
            });

        return view('asistencias.detalle', compact('actividad', 'propiedades'));
    }

        public function activate(Request $request, $propiedad_id, $actividad_id)
    {
        $asistencia = Asistencia::where('propiedad_id', $propiedad_id)
                                ->where('actividad_id', $actividad_id)
                                ->first();

        if ($asistencia && $asistencia->bloqueado) {
            return redirect()->back()->with('error', 'No se puede editar esta asistencia porque ya se encuentra pagada.');
        }

        // Actualizar o crear la asistencia y eliminar cualquier multa asociada
        Asistencia::updateOrCreate(
            ['propiedad_id' => $propiedad_id, 'actividad_id' => $actividad_id],
            ['asistio' => 1, 'multa_aplicada' => 0]
        );

        // Eliminar la multa si existe
        $this->eliminarMulta($propiedad_id, $actividad_id);

        return redirect()->back()->with('success', 'Asistencia marcada como asistió y multa eliminada');
    }

    protected function eliminarMulta($propiedadId, $actividadId)
    {
        // Eliminar la multa asociada a la propiedad y actividad
        Multa::where('propiedad_id', $propiedadId)
            ->where('actividad_id', $actividadId)
            ->delete();
    }

    // Función para marcar como inasistencia y crear multa
    public function inactivate(Request $request, $propiedad_id, $actividad_id)
    {
        $asistencia = Asistencia::where('propiedad_id', $propiedad_id)
                                ->where('actividad_id', $actividad_id)
                                ->first();

        if ($asistencia && $asistencia->bloqueado) {
            return redirect()->back()->with('error', 'No se puede editar esta asistencia porque ya se encuentra pagada.');
        }

        // Crear o actualizar asistencia con multa aplicada
        $asistencia = Asistencia::updateOrCreate(
            [
                'propiedad_id' => $propiedad_id,
                'actividad_id' => $actividad_id
            ],
            [
                'asistio' => 0,
                'multa_aplicada' => 1
            ]
        );

        // Obtener la actividad para conocer el monto de la multa
        $actividad = Actividad::findOrFail($actividad_id);

        // Crear la multa si aún no existe para esta asistencia
        $this->crearMulta($propiedad_id, $actividad, $asistencia);

        return redirect()->back()->with('success', 'Asistencia marcada como no asistió y multa generada.');
    }

    // Función para crear la multa asociada a la inasistencia
    protected function crearMulta($propiedadId, $actividad, $asistencia)
    {
        // Verificar si ya existe una multa para esta asistencia
        $existeMulta = Multa::where('propiedad_id', $propiedadId)
                            ->where('actividad_id', $actividad->id)
                            ->where('asistencia_id', $asistencia->id)
                            ->exists();

        if (!$existeMulta) {
            // Obtener el último código creado que inicie con 'R-'
        $ultimaMulta = Multa::where('codigo', 'like', 'R-%')->orderBy('codigo', 'desc')->first();
        $nuevoNumero = $ultimaMulta ? intval(substr($ultimaMulta->codigo, 2)) + 1 : 1;
        $codigo = 'R-' . str_pad($nuevoNumero, 6, '0', STR_PAD_LEFT);

            // Crear la multa
            Multa::create([
                'propiedad_id' => $propiedadId,
                'actividad_id' => $actividad->id,
                'asistencia_id' => $asistencia->id,
                'monto' => $actividad->multa,
                'codigo' => $codigo,
                'pagada' => 0,
                'bloqueado' => 0,
            ]);
        }
    }
}
