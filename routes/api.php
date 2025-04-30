<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\FacturaController;
use App\Http\Controllers\Api\CategoriaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('login', [LoginController::class, 'login']);
Route::middleware('auth:sanctum')->post('/factura', [FacturaController::class, 'procesar_factura']);
Route::middleware('auth:sanctum')->get('/articulos', [FacturaController::class, 'obtener_inventario']);
Route::middleware('auth:sanctum')->post('/depositos', [FacturaController::class, 'procesar_depositos']);
Route::middleware('auth:sanctum')->get('/tasas', [FacturaController::class, 'obtener_tasas']);
Route::middleware('auth:sanctum')->get('/control-emisiones', [FacturaController::class, 'control_emisiones']);
Route::middleware('auth:sanctum')->get('/categoria', [CategoriaController::class, 'obtener_categoria']);
Route::middleware('auth:sanctum')->get('/sub-categoria', [CategoriaController::class, 'obtener_sub_categoria']);
Route::middleware('auth:sanctum')->get('/productos', [CategoriaController::class, 'get_productos']);
Route::middleware('auth:sanctum')->get('/almacen', [CategoriaController::class, 'get_sucursales']);
Route::middleware('auth:sanctum')->get('/filtrar-categorias-o-subcategorias', [CategoriaController::class, 'filtrarCategoriasOSubcategorias']);
