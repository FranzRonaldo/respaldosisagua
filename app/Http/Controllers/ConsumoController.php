<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Propiedad; // Cambio de Socio a Propiedad
use App\Models\Consumo;
use App\Models\Pago;
use Carbon\Carbon;

class ConsumoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
    
        // Realiza el join con la tabla 'personas' y construye la consulta
        $query = Propiedad::query()
            ->join('personas', 'propiedades.persona_id', '=', 'personas.id') // Realiza el join con la tabla personas
            ->select('propiedades.*', 'personas.papellido', 'personas.sapellido', 'personas.nombre') // Selecciona las columnas necesarias
            ->whereHas('consumos'); // Asegúrate de que la relación 'consumos' esté definida en el modelo Propiedad
    
        // Si se ha ingresado un término de búsqueda, agregar las condiciones de búsqueda
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('propiedades.codigo', 'like', "%{$search}%") // Filtrar por código
                      ->orWhere('personas.papellido', 'like', "%{$search}%") // Filtrar por papellido
                      ->orWhere('personas.sapellido', 'like', "%{$search}%") // Filtrar por sapellido
                      ->orWhere('personas.nombre', 'like', "%{$search}%") // Filtrar por nombre
                      ->orWhere('personas.numero_carnet', 'like', "%{$search}%"); // Filtrar por número de carnet
            });
        }
    
        // Aplicar el criterio de ordenamiento
        $propiedadesConConsumos = $query->orderBy('propiedades.estado', 'desc') // Primero por estado
                                          ->orderBy('personas.papellido') // Luego por papellido
                                          ->orderBy('personas.sapellido') // Luego por sapellido
                                          ->orderBy('personas.nombre') // Luego por nombre
                                          ->paginate(10); // Cambia 10 por el número de registros por página que desees
    
        // Retornar la vista con los resultados
        return view('consumos.index', compact('propiedadesConConsumos'));
    }

    public function create()
    {
        $propiedades = Propiedad::with(['persona', 'consumos' => function($query) {
            $query->orderBy('anio', 'desc')->orderBy('mes', 'desc');
        }])->get();

        $propiedadesData = [];
        foreach ($propiedades as $propiedad) {
            $ultimoConsumo = $propiedad->consumos->first();

            if ($ultimoConsumo) {
                $nextMonth = $ultimoConsumo->mes == 12 ? 1 : $ultimoConsumo->mes + 1;
                $nextYear = $ultimoConsumo->mes == 12 ? $ultimoConsumo->anio + 1 : $ultimoConsumo->anio;
                $lecturaAnterior = $ultimoConsumo->lectura_actual; // Última lectura como predeterminada
            } else {
                $nextMonth = now()->month;
                $nextYear = now()->year;
                $lecturaAnterior = null; // No hay consumo anterior
            }

            $propiedadesData[] = [
                'propiedad' => $propiedad,
                'mes' => $nextMonth,
                'anio' => $nextYear,
                'lectura_anterior' => $lecturaAnterior // Pasar la lectura anterior al formulario
            ];
        }

        return view('consumos.create', ['propiedadesData' => $propiedadesData]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'propiedad_id' => 'required|exists:propiedades,id',
            'mes' => 'required|integer|min:1|max:12',
            'anio' => 'required|integer|min:2000|max:' . date('Y'),
            'lectura_actual' => 'required|numeric|min:0',
            'lectura_anterior' => 'nullable|numeric|min:0' 
        ]);

        // Validar que lectura_actual no sea menor que lectura_anterior
        if ($validatedData['lectura_actual'] < $request->lectura_anterior) {
            return back()->withErrors(['lectura_actual' => 'La lectura actual no puede ser menor que la lectura anterior.']);
        }

        $propiedad = Propiedad::findOrFail($request->propiedad_id); // Cambio de socio a propiedad
        $ultimoConsumo = $propiedad->consumos()->latest()->first();

        // Permitir que la lectura anterior sea la última o la ingresada por el usuario
        $lectura_anterior = $request->lectura_anterior ?? ($ultimoConsumo ? $ultimoConsumo->lectura_actual : null);

        $consumo = new Consumo($validatedData);
        $consumo->lectura_anterior = $lectura_anterior;
        $consumo->consumo = $consumo->lectura_actual - $lectura_anterior;

        // Calcular el monto a cobrar
        $this->calcularMonto($consumo);
        $consumo->save();

        return redirect()->route('consumos.index')->with('success', 'Consumo registrado correctamente.');
    }

    public function calcularMonto(Consumo $consumo)
    {
        $lecturaAnterior = $consumo->lectura_anterior;
        $lecturaActual = $consumo->lectura_actual;
        $consumo->consumo = $lecturaActual - $lecturaAnterior;

        if ($consumo->consumo < 0) {
            $consumo->consumo = 0; // Asegúrate de que el consumo no sea negativo
        }

        // Definir las tarifas
        $tarifa1 = 1; // Tarifa para los primeros 10 m³
        $tarifa2 = 2; // Tarifa para los m³ adicionales
        $limiteTarifa1 = 10; // Límite para aplicar la primera tarifa

        // Calcular el monto
        if ($consumo->consumo <= $limiteTarifa1) {
            $consumo->monto_cobrar = $consumo->consumo * $tarifa1;
        } else {
            $consumoTarifa1 = $limiteTarifa1 * $tarifa1;
            $consumoTarifa2 = ($consumo->consumo - $limiteTarifa1) * $tarifa2;
            $consumo->monto_cobrar = $consumoTarifa1 + $consumoTarifa2;
        }
    }

    public function detallesPorPropiedad($id) // Cambio de Socio a Propiedad
    {
        // Obtener la propiedad por ID
        $propiedad = Propiedad::findOrFail($id); // Cambio de socio a propiedad

        // Obtener todos los consumos asociados a la propiedad
        $consumos = Consumo::where('propiedad_id', $id)->get(); // Cambio de socio_id a propiedad_id

        // Retornar la vista con la propiedad y los consumos
        return view('consumos.detalle', compact('propiedad', 'consumos')); // Cambio de socio a propiedad
    }

    //////////////20-10

    // Método para mostrar todos los consumos con estado_pago = 1 (pagados)
    public function consumosPagados(Request $request)
    {
        // Si hay un mes seleccionado, filtramos los consumos pagados de ese mes
        $query = Consumo::where('estado_pago', 1);

        if ($request->has('mes') && $request->mes != '') {
            $query->where('mes', $request->mes);
        }

        // Obtenemos los consumos filtrados
        $consumos = $query->get();

        // Sumatoria de los montos cobrados
        $totalIngresado = $query->sum('monto_cobrar');

        return view('consumos.pagados', compact('consumos', 'totalIngresado'));
    }


    // Método para mostrar todos los consumos con estado_pago = 0 (pendientes)
    public function consumosPendientes(Request $request)
    {
        // Iniciamos la consulta con estado_pago = 0 (Pendientes)
        $query = Consumo::where('estado_pago', 0);

        // Filtramos por mes si el usuario selecciona uno
        if ($request->has('mes') && $request->mes != '') {
            $query->where('mes', $request->mes);
        }

        // Obtenemos los consumos filtrados
        $consumos = $query->get();

        // Sumatoria de los montos pendientes
        $totalPendiente = $query->sum('monto_cobrar');

        // Retornamos la vista 'pendientes' con los datos
        return view('consumos.pendientes', compact('consumos', 'totalPendiente'));
    }


    // Método para mostrar todos los consumos (tanto pagados como pendientes)
    public function consumosTodos(Request $request)
    {
        // Iniciamos la consulta para obtener todos los consumos sin filtrar por estado_pago
        $query = Consumo::query();

        // Filtramos por mes si el usuario selecciona uno
        if ($request->has('mes') && $request->mes != '') {
            $query->where('mes', $request->mes);
        }

        // Obtenemos los consumos filtrados (tanto pagados como pendientes)
        $consumos = $query->get();

        // Sumatoria de los montos totales, tanto pagados como pendientes
        $totalMonto = $query->sum('monto_cobrar');

        // Retornamos la vista 'todos' con los datos
        return view('consumos.todos', compact('consumos', 'totalMonto'));
    }

    // Muestra el formulario de edición para un consumo específico
public function edit($id)
{
    $consumo = Consumo::findOrFail($id); // Busca el consumo por su ID
    
    if ($consumo->bloqueado == 1) {
        // Redirige con un mensaje de error si el consumo está bloqueado
        return redirect()->back()->with('error', 'No se puede editar este consumo porque ya se encuentra pagada.');
    }
    
    $propiedad = $consumo->propiedad; // Relacionar con la propiedad
    return view('consumos.edit', compact('consumo', 'propiedad'));
}

// Procesa la actualización del consumo
public function update(Request $request, $id)
{
    $consumo = Consumo::findOrFail($id); // Obtiene el consumo a editar

    $validatedData = $request->validate([
        'lectura_actual' => 'required|numeric|min:0',
        'lectura_anterior' => 'nullable|numeric|min:0'
    ]);

    $lecturaAnterior = $request->lectura_anterior ?? $consumo->lectura_anterior;
    $consumo->lectura_actual = $validatedData['lectura_actual'];
    $consumo->lectura_anterior = $lecturaAnterior;

    // Calcular el consumo y el monto a cobrar
    $consumo->consumo = $consumo->lectura_actual - $consumo->lectura_anterior;
    $this->calcularMonto($consumo);
    $consumo->save();

    return redirect()->route('consumos.index')->with('success', 'Consumo actualizado correctamente.');
}


}
