<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Facades\DB;
use App\Models\Tasa;

class SincronizacionController extends Controller
{

    public function parametros()
    {
        return view('sincronizacion.parametros');
    }
    public function sincronizarTasas()
    {
        try {
            $url_endpoint = env('API_URL');
            $uri = "{$url_endpoint}/api/tasas";
            $token = env('API_TOKEN');

            $client = new Client();
            $response = $client->get($uri, [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Accept' => 'application/json',
                ],
                
            ]);
            
            $respuesta = json_decode($response->getBody()->getContents(), true);
            
            if (isset($respuesta['data'])) {
                DB::beginTransaction();
                
                try {
                    // Borrar todos los registros de la tabla tasas_cambio
                    DB::table('tasas_cambio')->delete();
                    
                    // Insertar los nuevos registros
                    foreach ($respuesta['data'] as $tasa) {
                        Tasa::create([
                            'MOEX0B' => $tasa['moex0b'] ?? null,
                            'DESC0B' => $tasa['desc0b'] ?? null,
                            'TASA0B' => $tasa['tasa0b'] ?? null
                        ]);
                    }
                    
                    DB::commit();
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'El proceso de obtención de tasas se completó satisfactoriamente.'
                    ]);
                    
                } catch (\Exception $ex) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al guardar las tasas: ' . $ex->getMessage()
                    ], 500);
                }
            } elseif (isset($respuesta['code']) && $respuesta['code'] != 1) {
                return response()->json([
                    'success' => false,
                    'message' => $respuesta['message'] ?? 'Error en la respuesta del servidor'
                ], 400);
            }

            return response()->json([
                'success' => false,
                'message' => 'La respuesta del servidor no contiene datos válidos'
            ], 400);

        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            $errorResponse = $ex->hasResponse() ? json_decode($ex->getResponse()->getBody()->getContents(), true) : null;
            $errorMessage = $errorResponse['message'] ?? $ex->getMessage();

            return response()->json([
                'success' => false,
                'message' => 'Error al conectarse al servidor: ' . $errorMessage
            ], 500);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error inesperado: ' . $ex->getMessage()
            ], 500);
        }
    }
    public function sincronizarInventario()
    {
        try {
            $url_endpoint = env('API_URL');
            $uri = "{$url_endpoint}/api/articulos";
            $token = env('API_TOKEN');

            $client = new Client();
            $response = $client->get($uri, [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Accept' => 'application/json',
                ],
                
            ]);
            
            $respuesta = json_decode($response->getBody()->getContents(), true);
            
            if (isset($respuesta['data'])) {
                DB::beginTransaction();
                
                try {
                    // Borrar todos los registros de la tabla tasas_cambio
                    DB::table('inventario')->delete();
                    
                    // Insertar los nuevos registros
                    foreach ($respuesta['data'] as $inventario) {
                        Inventario::create([
                            'codigo' => $inventario['arti87'] ?? null,
                            'unidad_medida' => $inventario['unmd87'] ?? null,
                            'peso' => $inventario['peso87'] ?? null,
                            'precio' => $inventario['precio_neto'] ?? null,
                            'linea' => $inventario['linea'] ?? null,
                            'disponible' => $inventario['disp_uni_inv'] ?? null,
                            'codigo_almacen' => $inventario['almacen'] ?? null,
                            'articulo' => $inventario['desc87'] ?? null,
                            'peso_conversion' => $inventario['peso_conversion'] ?? null,
                            'ancho_conversion' => $inventario['ancho_conversion'] ?? null,
                            'alto_espesor_conversion' => $inventario['alto_espesor_conversion'] ?? null,
                            'estandar' => $inventario['estandar'] ?? null
                        ]);
                    }
                    
                    DB::commit();
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'El proceso de obtención de inventario se completó satisfactoriamente.'
                    ]);
                    
                } catch (\Exception $ex) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al guardar el inventario: ' . $ex->getMessage()
                    ], 500);
                }
            } elseif (isset($respuesta['code']) && $respuesta['code'] != 1) {
                return response()->json([
                    'success' => false,
                    'message' => $respuesta['message'] ?? 'Error en la respuesta del servidor'
                ], 400);
            }

            return response()->json([
                'success' => false,
                'message' => 'La respuesta del servidor no contiene datos válidos'
            ], 400);

        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            $errorResponse = $ex->hasResponse() ? json_decode($ex->getResponse()->getBody()->getContents(), true) : null;
            $errorMessage = $errorResponse['message'] ?? $ex->getMessage();

            return response()->json([
                'success' => false,
                'message' => 'Error al conectarse al servidor: ' . $errorMessage
            ], 500);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error inesperado: ' . $ex->getMessage()
            ], 500);
        }
    }
    public function sincronizarControlEmision(){
        try {
            $url_endpoint = env('API_URL');
            $uri = "{$url_endpoint}/api/control-emisiones";
            $token = env('API_TOKEN');

            $client = new Client();
            $response = $client->get($uri, [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Accept' => 'application/json',
                ],
                
            ]);
            
            $respuesta = json_decode($response->getBody()->getContents(), true);
            
            if (isset($respuesta['data'])) {
                DB::beginTransaction();
                
                try {
                    // Borrar todos los registros de la tabla tasas_cambio
                    DB::table('inventario')->delete();
                    
                    // Insertar los nuevos registros
                    foreach ($respuesta['data'] as $control) {
                        Inventario::create([
                            
                            'COMP96' => $control['comp96'] ?? null,
                            'SUCU96' => $control['sucu96'] ?? null,
                            'CDDO96' => $control['cddo96'] ?? null,
                            'NUDO96' => $control['nudo96'] ?? null,
                        ]);
                    }
                    
                    DB::commit();
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'El proceso de obtención de control de emisi[on se completó satisfactoriamente.'
                    ]);
                    
                } catch (\Exception $ex) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al guardar el inventario: ' . $ex->getMessage()
                    ], 500);
                }
            } elseif (isset($respuesta['code']) && $respuesta['code'] != 1) {
                return response()->json([
                    'success' => false,
                    'message' => $respuesta['message'] ?? 'Error en la respuesta del servidor'
                ], 400);
            }

            return response()->json([
                'success' => false,
                'message' => 'La respuesta del servidor no contiene datos válidos'
            ], 400);

        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            $errorResponse = $ex->hasResponse() ? json_decode($ex->getResponse()->getBody()->getContents(), true) : null;
            $errorMessage = $errorResponse['message'] ?? $ex->getMessage();

            return response()->json([
                'success' => false,
                'message' => 'Error al conectarse al servidor: ' . $errorMessage
            ], 500);
        } catch (\Exception $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error inesperado: ' . $ex->getMessage()
            ], 500);
        }
    }
}
