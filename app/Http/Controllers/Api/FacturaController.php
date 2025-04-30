<?php
// app/Http/Controllers/Api/LoginController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;

class FacturaController extends Controller
{
    public function procesar_factura(Request $request)
    {
        $data = $request->all();
        try {
            $ip = env('INVENTARIO_API_IP');
            $uri = "http://{$ip}:8080/api/factura/crear";
    
            $client = new Client();
            $response = $client->post($uri, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' =>$data
                
            ]);
    
            $data = json_decode($response->getBody()->getContents(), true);

            return $data;
            
        } catch (ConnectException $e) {
            // Handle connection errors
            $error = [
                'code' => '0',
                'message' => $e->getMessage()
            ];
            return json_encode($error);

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            // Handle 500 Internal Server Error
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $error = [
                'code' => '500',
                'message' => $responseBodyAsString
            ];
            return json_encode($error);
        } catch (\Throwable $th) {
            // Handle any other exceptions
            $error = [
                'code' => '0',
                'message' => $th->getMessage()
            ];
            return json_encode($error);
        }
    }
    public function procesar_depositos(Request $request)
    {
        $data = $request->all();
        try {
            $ip = env('INVENTARIO_API_IP');
            $uri = "http://{$ip}:8080/api/factura/depositos";

            $client = new Client();
            $response = $client->post($uri, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => $data
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            return $data;

        } catch (ConnectException $e) {
            // Handle connection errors
            $error = [
                'code' => '0',
                'message' => $e->getMessage()
            ];
            return json_encode($error);

        } catch (\GuzzleHttp\Exception\ServerException $e) {
            // Handle 500 Internal Server Error
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            $error = [
                'code' => '500',
                'message' => $responseBodyAsString
            ];
            return json_encode($error);
        } catch (\Throwable $th) {
            // Handle any other exceptions
            $error = [
                'code' => '0',
                'message' => $th->getMessage()
            ];
            return json_encode($error);
        }
    }
    public function obtener_inventario(Request $request){
        try {
            $ip = env('INVENTARIO_API_IP');
            $uri = "http://{$ip}:8080/api/articulos";
    
            $client = new Client();
            $response = $client->get($uri);
            $articulos = json_decode($response->getBody()->getContents(), true);
            return $articulos;
          
           
            
        } catch (\Throwable $th) {
            return [
                'code' => '0',
                'message'=> $th->getMessage()
            ];
        }
    }
    public function obtener_tasas(Request $request){
        try {
            $ip = env('INVENTARIO_API_IP');
            $uri = "http://{$ip}:8080/api/tasas";
    
            $client = new Client();
            $response = $client->get($uri);
            $tasas = json_decode($response->getBody()->getContents(), true);
            return $tasas;
            
        } catch (\Throwable $th) {
            return [
                'code' => '0',
                'message'=> $th->getMessage()
            ];
        }
    }

    public function control_emisiones(Request $request){
        try {
            $ip = env('INVENTARIO_API_IP');
            $uri = "http://{$ip}:8080/api/control-emisiones/";
    
            $client = new Client();
            $response = $client->get($uri);
            $tasas = json_decode($response->getBody()->getContents(), true);
            return $tasas;
            
        } catch (\Throwable $th) {
            return [
                'code' => '0',
                'message'=> $th->getMessage()
            ];
        }
    }
    
}