<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\PropiedadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActividadController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\ConsumoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\MultaController;
use App\Http\Controllers\PDFController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar las rutas web para tu aplicación.
| Estas rutas son cargadas por el RouteServiceProvider dentro de un grupo 
| que contiene el middleware "web". ¡Crea algo genial!
|
*/

// Rutas de autenticación
Auth::routes();

// Ruta raíz
Route::get('/', [HomeController::class, 'root']);
Route::get('/api/pagos-mensuales', [HomeController::class, 'getPagosMensuales'])->name('api.pagos.mensuales');

// Rutas para la traducción de idiomas
Route::get('index/{locale}', [HomeController::class, 'lang']);

// Ruta para manejar formularios
Route::post('/formsubmit', [HomeController::class, 'formSubmit'])->name('formSubmit');

// Rutas para generar PDFs
Route::resource('reportes', PDFController::class);
Route::get('/generate-pdf', [PDFController::class, 'generatePDF']);
Route::get('/pagos/{id}/pdf', [PDFController::class, 'generatePagoPDF'])->name('pagos.pdf');
Route::get('/pagos/{id}/generar-recibo', [PDFController::class, 'generarRecibo'])->name('pagos.generar-recibo');
Route::get('/consumos/pagados/pdf', [PDFController::class, 'generarConsumosPagadosPDF'])->name('consumos.pagados.pdf');
Route::get('/reportes/pagados/pdf', [PDFController::class, 'generarReportePagadosPDF'])->name('reportes.pagados.pdf');

//
Route::get('/consumos/pagados/pdf', [PDFController::class, 'generarReportePagadosPDF'])->name('consumos.pagados.pdf');
Route::get('/consumos/pendientes/pdf', [PDFController::class, 'generarReportePendientesPDF'])->name('consumos.pendientes.pdf');


Route::get('reportes', [PDFController::class, 'index'])->name('reportes.index');
Route::get('reportes/pagados', [PDFController::class, 'consumosPagados'])->name('reportes.pagados');
Route::get('reportes/pendientes', [PDFController::class, 'consumosPendientes'])->name('reportes.pendientes');
Route::get('reportes/todos', [PDFController::class, 'consumosTodos'])->name('reportes.todos');





// Rutas para gestionar "personas"
Route::resource('personas', PersonaController::class);
Route::patch('personas/{persona}/inactivate', [PersonaController::class, 'inactivate'])->name('personas.inactivate');
Route::patch('personas/{persona}/activate', [PersonaController::class, 'activate'])->name('personas.activate');

// Rutas para gestionar "propiedades"
Route::resource('propiedades', PropiedadController::class);
Route::patch('propiedades/{id}/activate', [PropiedadController::class, 'activate'])->name('propiedades.activate');
Route::patch('propiedades/{id}/inactivate', [PropiedadController::class, 'inactivate'])->name('propiedades.inactivate');

// Rutas para la gestión de usuarios
Route::resource('users', UserController::class);

// Rutas para gestionar "actividades"
Route::resource('actividades', ActividadController::class);

// Rutas para gestionar "consumos"
Route::resource('consumos', ConsumoController::class)->except(['show']);
Route::get('/consumos/{id}/detalle', [ConsumoController::class, 'detalle'])->name('consumos.detalle');
Route::get('/consumos/propiedad/{id}', [ConsumoController::class, 'detallesPorPropiedad'])->name('consumos.detallesPorPropiedad');
Route::post('/consumos/{consumo}/calcular-monto', [ConsumoController::class, 'calcularMonto'])->name('consumos.calcularMonto');
Route::post('/consumos/{consumo}/marcar-pago/{estadoPago}', [ConsumoController::class, 'marcarPago'])->name('consumos.marcarPago');
Route::get('/consumos/pagados', [ConsumoController::class, 'pagados'])->name('consumos.pagados');
Route::get('/consumos/pendientes', [ConsumoController::class, 'pendientes'])->name('consumos.pendientes');
Route::get('/consumos/todos', [ConsumoController::class, 'todos'])->name('consumos.todos');
//
Route::get('/consumos/{id}/edit', [ConsumoController::class, 'edit'])->name('consumos.edit');
Route::put('/consumos/{id}', [ConsumoController::class, 'update'])->name('consumos.update');


// Filtros de consumos
Route::get('consumos/pagados', [ConsumoController::class, 'consumosPagados'])->name('consumos.pagados');
Route::get('consumos/pendientes', [ConsumoController::class, 'consumosPendientes'])->name('consumos.pendientes');
Route::get('consumos/todos', [ConsumoController::class, 'consumosTodos'])->name('consumos.todos');

// Rutas para gestionar asistencias
Route::resource('asistencias', AsistenciaController::class);
Route::get('asistencias/actividad/{id}', [AsistenciaController::class, 'detallesPorActividad'])->name('asistencias.detallesPorActividad');
Route::post('/asistencias/inactivate/{propiedad_id}/{actividad_id}', [AsistenciaController::class, 'inactivate'])->name('asistencias.inactivate');
Route::post('/asistencias/activate/{propiedad_id}/{actividad_id}', [AsistenciaController::class, 'activate'])->name('asistencias.activate');


//multas

Route::resource('multas', MultaController::class);
Route::get('/multas', [MultaController::class, 'index'])->name('multas.index');
//Route::get('/actividades', [MultaController::class, 'index'])->name('actividades.index');
Route::get('/multas/actividad/{actividadId}', [MultaController::class, 'detalles'])->name('multas.detalle');



// Rutas para gestionar "pagos"
Route::resource('pagos', PagoController::class);
Route::get('/pagos/create', [PagoController::class, 'create'])->name('pagos.create');
Route::post('/pagos', [PagoController::class, 'store'])->name('pagos.store');
/// anterio api
Route::get('/api/propiedades-y-consumos/{personaId}', [PagoController::class, 'propiedadesYConsumos']);
Route::get('/api/fetch-data/{persona}', [PagoController::class, 'fetchData']);

//nuevos apis
// Ruta para obtener las propiedades según la persona
Route::get('/api/propiedades/{personaId}', [PagoController::class, 'propiedades']);
// Nueva ruta para obtener los consumos y multas de una propiedad específica
Route::get('/api/consumos-y-multas/{propiedadId}', [PagoController::class, 'consumosYMultas']);


// Ruta para registrar el pago de una multa
Route::post('/pagos/multas', [PagoController::class, 'registrarPagoMulta'])->name('pagos.multas.store');

// Ruta de fallback para cualquier otra URL
Route::fallback(function () {
    return redirect('/');
});
