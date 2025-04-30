<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\SubCategoria;
use App\Models\CategorizacionAs400;
use App\Models\Almacen;
use Illuminate\Support\Facades\Log;
use App\Services\AzureBlobService;



use GuzzleHttp\Client;




class CategoriaController extends Controller
{
    public function obtener_categoria(Request $request, AzureBlobService $azureBlobService)
    {
        try {
            $categorias = Categoria::query()->get()->map(function ($cat) use ($azureBlobService) {
                \Log::info("Procesando categoría ID {$cat->id} - Imagen: {$cat->imagen}");

                return [
                    'id' => $cat->id,
                    'categoria' => $cat->categoria,
                    'imagen_temporal' => $cat->imagen
                        ? $azureBlobService->getTemporaryUrl($cat->imagen, 30)
                        : null,
                    'imagen_path' => $cat->imagen
                ];
            });

            return response()->json($categorias);
        } catch (\Throwable $th) {
            \Log::error('Error en obtener_categoria: ' . $th->getMessage());
            return response()->json([
                'code' => 0,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function obtener_sub_categoria(Request $request, AzureBlobService $azureBlobService)
    {
        try {
            $sub_categorias = SubCategoria::all()->map(function ($subcat) use ($azureBlobService) {
                \Log::info("Procesando subcategoría ID {$subcat->id} - Imagen: {$subcat->imagen}");

                return [
                    'id' => $subcat->id,
                    'sub_categoria' => $subcat->subcategoria,
                    'categoria_id' => $subcat->categoria_id,
                    'imagen_temporal' => $subcat->imagen
                        ? $azureBlobService->getTemporaryUrl($subcat->imagen, 30)
                        : null,
                    'imagen_path' => $subcat->imagen,
                ];
            });

            return response()->json($sub_categorias);
        } catch (\Throwable $th) {
            \Log::error('Error en obtener_sub_categoria: ' . $th->getMessage());
            return response()->json([
                'code' => 0,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function get_productos(Request $request)
    {
        try {
            $subcategoriaId = $request->input('subcategoria');
            $almacen = $request->input('almacen');


            $codigosAs400 = CategorizacionAs400::where('subcategoria_id', $subcategoriaId)
                ->pluck('categoria_as400')
                ->unique()
                ->values()
                ->toArray();
            $almacenes = Almacen::where('codigo', $almacen)->pluck('codigo')->toArray();

            Log::info('Parámetros enviados a API externa:', [
                'lineas' => $codigosAs400,
                'almacenes' => $almacenes
            ]);
            if (!$almacen) {
                return response()->json([
                    'message' => 'El parámetro almacen es requerido.',
                    'status' => false
                ], 400);
            }
            if (empty($codigosAs400)) {
                return response()->json([
                    'message' => 'No se encontraron códigos AS400 para esta subcategoría.',
                    'status' => false
                ], 404);
            }



            $ip = env('INVENTARIO_API_IP');
            $uri = "http://{$ip}:8080/api/inventario/app";

            $client = new Client();
            $response = $client->get($uri, [
                'json' => [
                    'lineas' => $codigosAs400,
                    'almacenes' => $almacenes
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            return response()->json($data);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error al obtener productos.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function get_sucursales(Request $request)
    {
        try {
            $sucursales = Almacen::all();
            return response()->json($sucursales);
        } catch (\Throwable $th) {
            return [
                'code' => '0',
                'message' => $th->getMessage()
            ];
        }
    }
    public function filtrarCategoriasOSubcategorias(Request $request, AzureBlobService $azureBlobService)
    {
        $valor = strtolower($request->input('valor'));

        $categorias = Categoria::where('categoria', 'like', "%$valor%")
            ->get()
            ->map(function ($cat) use ($azureBlobService) {
                return [
                    'id' => $cat->id,
                    'categoria' => $cat->categoria,
                    'imagen_temporal' => $cat->imagen
                        ? $azureBlobService->getTemporaryUrl($cat->imagen, 30)
                        : null,
                    'imagen_path' => $cat->imagen
                ];
            });

        $subcategorias = Subcategoria::where('subcategoria', 'like', "%$valor%")
            ->get()
            ->map(function ($subcat) use ($azureBlobService) {
                return [
                    'id' => $subcat->id,
                    'sub_categoria' => $subcat->subcategoria,
                    'categoria_id' => $subcat->categoria_id,
                    'imagen_temporal' => $subcat->imagen
                        ? $azureBlobService->getTemporaryUrl($subcat->imagen, 30)
                        : null,
                    'imagen_path' => $subcat->imagen,
                ];
            });

        return response()->json([
            'categorias' => $categorias,
            'subcategorias' => $subcategorias
        ]);
    }
    

}
