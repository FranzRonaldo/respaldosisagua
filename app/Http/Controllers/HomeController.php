<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consumo;
use App\Models\Propiedad;
use App\Models\Pago;
use Carbon\Carbon;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        
        // Obtener el total del dinero ingresado (suma de 'monto_cobrar' donde 'estado_pago' es 1)
        $totalIngresado = Consumo::where('estado_pago', 1)->sum('monto_cobrar');

        // Obtener el total del dinero ingresado (suma de 'monto_cobrar' donde 'estado_pago' es 1)
        $totalNoPagado = Consumo::where('estado_pago', 0)->sum('monto_cobrar');

        // Total de propiedades activas
        $totalPropiedadesActivas = Propiedad::where('estado', 1)->count();

        // Si la vista existe, retornarla pasando la variable $totalIngresado
        if (view()->exists($request->path())) {
            return view($request->path(), compact('totalIngresado', 'totalNoPagado', 'totalPropiedadesActivas'));
        }

        return abort(404);
        

    }
    public function root()
    {
        // Pasar el totalIngresado a la vista del dashboard
        $totalIngresado = Consumo::where('estado_pago', 1)->sum('monto_cobrar');
        $totalNoPagado = Consumo::where('estado_pago', 0)->sum('monto_cobrar');
        $totalPropiedadesActivas = Propiedad::where('estado', 1)->count();
        return view('index', compact('totalIngresado','totalNoPagado', 'totalPropiedadesActivas'));
        
        
    }

    /*Language Translation*/
    public function lang($locale)
    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        } else {
            return redirect()->back();
        }
    }

    public function FormSubmit(Request $request)
    {
        return view('form-repeater');
    }

    public function getPagosMensuales()
{
    // Obtener el año actual
    $currentYear = Carbon::now()->year;

    // Obtener los pagos sumados por mes del año actual
    $pagosMensuales = Pago::selectRaw('MONTH(fecha_pago) as mes, SUM(monto) as total')
        ->whereYear('fecha_pago', $currentYear)
        ->where('estado_pago', true) // Solo pagos completados
        ->groupByRaw('MONTH(fecha_pago)')
        ->orderByRaw('MONTH(fecha_pago)')
        ->get();

    // Transformar los datos para la gráfica
    $data = [];
    for ($i = 1; $i <= 12; $i++) {
        $pago = $pagosMensuales->firstWhere('mes', $i);
        $data[] = $pago ? $pago->total : 0; // Si no hay pagos, poner 0
    }

    return response()->json($data);
}
}
