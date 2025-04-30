<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\PlanificacionController;
use App\Http\Controllers\SectoresComercialesController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PrecioCompetenciaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\SubCategoriaController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::group(['middleware' => ['auth']], function () {


    Route::get('planificacion/calendario', [PlanificacionController::class, 'calendario'])->name('planificacion.calendario');
    Route::post('planificacion/guardar', [PlanificacionController::class, 'guardar_actividad'])->name('planificacion.guardar_actividad');
    Route::post('planificacion/actualizar', [PlanificacionController::class, 'actualizar_actividad'])->name('planificacion.actualizar_actividad');
    Route::get('planificacion/documento-autocompletado', [PlanificacionController::class, 'numeroDocumentoAutocomplete'])->name('numero_documento.search');
    Route::get('planificacion/obtener-acciones', [PlanificacionController::class, 'obtener_acciones'])->name('planificacion.obtener_acciones');
    Route::get('planificacion/detalle', [PlanificacionController::class, 'detalle_planificacion'])->name('planificacion.detalle');
    Route::delete('planificacion/eliminar', [PlanificacionController::class, 'eliminar'])->name('planificacion.eliminar');
    Route::get('planificacion/exportar', [PlanificacionController::class, 'exportar_planificacion'])->name('planificacion.exportar');
    Route::get('planificacion/exportar-excel', [PlanificacionController::class, 'exportar_excel_planificacion'])->name('planificacion.exportar_excel');
    Route::get('planificacion/obtener-planificacion', [PlanificacionController::class, 'obtener_planificaciones'])->name('planificacion.obtener_eventos');

    Route::get('index/categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::get('create/categorias', [CategoriaController::class, 'create'])->name('categorias.create');
    Route::post('store/categorias', [CategoriaController::class, 'store'])->name('categorias.store');
    Route::get('edit/categorias', [CategoriaController::class, 'edit'])->name('categorias.edit');
    Route::put('update/categorias', [CategoriaController::class, 'update'])->name('categorias.update');


    Route::get('index/subcategorias', [SubCategoriaController::class, 'index'])->name('subcategorias.index');
    Route::get('create/subcategorias', [SubCategoriaController::class, 'create'])->name('subcategorias.create');
    Route::get('categorias/as400', [SubCategoriaController::class, 'getAs400ByCategoria'])->name('subcategorias.filter');
    Route::post('store/subcategorias', [SubCategoriaController::class, 'store'])->name('subcategorias.store');
    Route::get('edit/subcategorias', [SubCategoriaController::class, 'edit'])->name('subcategorias.edit');
    Route::put('update/subcategorias', [SubCategoriaController::class, 'update'])->name('subcategorias.update');


    Route::group(['middleware' => ['permission:Módulo Configuraciones']], function () {
        Route::get('sector-comercial/index', [SectoresComercialesController::class, 'index'])->name('sector_comercial.index');
        Route::get('sector-comercial/create', [SectoresComercialesController::class, 'create'])->name('sector_comercial.create');
        Route::post('sector-comercial/store', [SectoresComercialesController::class, 'store'])->name('sector_comercial.store');
        Route::get('sector-comercial/edit', [SectoresComercialesController::class, 'edit'])->name('sector_comercial.edit');
        Route::delete('sector-comercial/destroy', [SectoresComercialesController::class, 'destroy'])->name('sector_comercial.destroy');
        Route::put('sector-comercial/update', [SectoresComercialesController::class, 'update'])->name('sector_comercial.update');

        Route::get('almacen/index', [AlmacenController::class, 'index'])->name('almacen.index');
        Route::get('almacen/create', [AlmacenController::class, 'create'])->name('almacen.create');
        Route::post('almacen/store', [AlmacenController::class, 'store'])->name('almacen.store');
        Route::get('almacen/edit', [AlmacenController::class, 'edit'])->name('almacen.edit');
        Route::delete('almacen/destroy', [AlmacenController::class, 'destroy'])->name('almacen.destroy');
        Route::put('almacen/update', [AlmacenController::class, 'update'])->name('almacen.update');

    });

    Route::group(['middleware' => ['permission:Módulo Clientes']], function () {
        Route::get('clientes/index', [ClientesController::class, 'index'])->name('clientes.index');
        Route::get('clientes/obtener-clientes', [ClientesController::class, 'obtener_clientes'])->name('clientes.listado');
        Route::get('clientes/create', [ClientesController::class, 'create'])->name('clientes.create');
        Route::get('clientes/edit', [ClientesController::class, 'edit'])->name('clientes.edit');
        Route::get('clientes/ciudades-por-estado', [ClientesController::class, 'obtener_ciudades_estado'])->name('clientes.obtener_ciudades_estado');
        Route::post('clientes/guardar', [ClientesController::class, 'guardar_cliente'])->name('clientes.guardar');
        Route::get('clientes/visita', [ClientesController::class, 'visita'])->name('clientes.visita');
        Route::get('clientes/checkout_visita/{visita_id}', [ClientesController::class, 'checkout_visita'])->name('clientes.checkout_visita');
        Route::post('clientes/guardar-visita', [ClientesController::class, 'guardar_visita'])->name('clientes.guardar_visita');
        Route::post('clientes/guardar-checkout', [ClientesController::class, 'guardar_checkout'])->name('clientes.guardar_checkout');
        Route::get('visitas/index', [ClientesController::class, 'visita_index'])->name('visita.index');
        Route::get('visitas/obtener', [ClientesController::class, 'obtener_visitas'])->name('visita.listado');
        Route::get('visitas/view', [ClientesController::class, 'ver_visita'])->name('clientes.ver_visita');
        Route::get('cliente/buscar', [ClientesController::class, 'buscar_cliente'])->name('clientes.buscar');
        Route::get('cliente/verificar_documento', [ClientesController::class, 'verificar_documento'])->name('clientes.verificar_documento');
        Route::get('cliente/inactivar', [ClientesController::class, 'inactivar_cliente'])->name('inactivar.cliente');

    });

    Route::group(['middleware' => ['permission:Módulo Pedidos']], function () {

        Route::get('pedidos/crear/{visita_id}', [PedidoController::class, 'realizar_pedido'])->name('pedido.crear');
        Route::get('pedidos/productos', [PedidoController::class, 'productos'])->name('pedido.productos');
        Route::get('pedidos/index', [PedidoController::class, 'pedidos_index'])->name('pedidos.index');
        Route::get('documentos/get', [PedidoController::class, 'get_documentos'])->name('get.documentos');
        Route::post('pedido/guardar', [PedidoController::class, 'guardar_pedido'])->name('pedido.guardar');
        Route::get('cotizacion/exportar', [PedidoController::class, 'exportar_cotizacion'])->name('exportar.cotizacion');
        Route::get('cotizacion/modal-estado', [PedidoController::class, 'modal_estado'])->name('cotizacion.modal_estado');
        Route::post('cotizacion/actualizar-estado', [PedidoController::class, 'actualizar_estado'])->name('cotizacion.actualizar_estado');
        Route::get('cotizacion/editar', [PedidoController::class, 'editar_cotizacion'])->name('editar.cotizacion');
        Route::get('exportar/inventario', [PedidoController::class, 'exportar_inventario'])->name('exportar.inventario');
        Route::get('pedido/procesar', [PedidoController::class, 'procesar_pedido'])->name('procesar.pedido');

    });

    Route::group(['middleware' => ['permission:Módulo Usuarios']], function () {
        Route::get('user/crear-permiso', [UserController::class, 'crear_permiso'])->name('user.crear_permiso');
        Route::get('user/profile', [UserController::class, 'profile'])->name('user.profile');
        Route::get('user/contrasena', [UserController::class, 'cambio_contrasena'])->name('user.contrasena');
        Route::get('user/', [UserController::class, 'index'])->name('user.index');
        Route::get('user/create', [UserController::class, 'create'])->name('user.create');
        Route::get('user/get-users', [UserController::class, 'getUsuarios'])->name('get.usuarios');
        Route::post('user/store-profile', [UserController::class, 'store_profile'])->name('users.store_profile');
        Route::get('user/modal-inactivar', [UserController::class, 'modal_inactivar'])->name('users.modal_inactivar');
        Route::post('user/inactivar', [UserController::class, 'inactivar'])->name('users.inactivar');
        Route::put('user/store-permisos', [UserController::class, 'store_permisos'])->name('users.store_permisos');
        Route::put('user/store-contrasena', [UserController::class, 'store_contrasena'])->name('users.store_contrasena');
        Route::get('user/detalle', [UserController::class, 'detalle'])->name('user.detalle');
        Route::post('user/store-permiso-spatie', [UserController::class, 'store_permiso_spatie'])->name('user.store_permiso_spatie');
        Route::post('user/store', [UserController::class, 'store'])->name('user.store');
    });

    Route::group(['middleware' => ['permission:Módulo precios de competencia']], function () {
        Route::get('precio-competencia/index', [PrecioCompetenciaController::class, 'index'])->name('precio_competencia.index');
        Route::get('precio-competencia/create', [PrecioCompetenciaController::class, 'create'])->name('precio_competencia.create');
        Route::get('precio-competencia/get', [PrecioCompetenciaController::class, 'get_precios'])->name('precio_competencia.get');
        Route::post('precio-competencia/store', [PrecioCompetenciaController::class, 'store'])->name('precio_competencia.store');
        Route::get('precio-competencia/edit', [PrecioCompetenciaController::class, 'edit'])->name('precio_competencia.edit');
        Route::post('precio-competencia/update', [PrecioCompetenciaController::class, 'update'])->name('precio_competencia.update');
    });

});
