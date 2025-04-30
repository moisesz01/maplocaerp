<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Categoria;
use App\Models\CategorizacionAs400;
use App\Services\AzureBlobService;



use Illuminate\Support\Facades\DB;



class CategoriaController extends Controller
{
    // Controlador


    public function index()
    {

        $categoria = Categoria::all();
        return view("categorias.index", ["categoria" => $categoria]);

    }
    public function create()
    {
        try {
            $ip = env('INVENTARIO_API_IP');
            $uri = "http://{$ip}:8080/api/lineas";

            $client = new Client();
            $response = $client->get($uri);
            $responseData = json_decode($response->getBody()->getContents(), true);
            $lineas = $responseData['data'];

            return view('categorias.create', compact('lineas'));
        } catch (\Throwable $th) {
            return view('categorias.create', [
                'lineas' => [],
                'error' => $th->getMessage(),
            ]);
        }
    }



    public function store(Request $request, AzureBlobService $azureBlob)
    {
        $request->validate([
            'categoria' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'codigos' => 'required|array|min:1',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $categoria = new Categoria();
        $categoria->categoria = $request->categoria;
        $categoria->descripcion = $request->descripcion;

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = 'categorias/' . time() . '_' . $imagen->getClientOriginalName();

            try {
                $filePath = $azureBlob->uploadFile($imagen, $nombreImagen);
                $urlTemporal = $azureBlob->getTemporaryUrl($nombreImagen, 60);
                $categoria->imagen = $nombreImagen;
            } catch (\Exception $e) {
                dd($e->getMessage());

                return back()->with('error', 'Error al subir la imagen: ' . $e->getMessage());
            }
        }

        if (!$categoria->save()) {
            return back()->with('error', 'Error al guardar la categoría');
        }

        foreach ($request->codigos as $codigo) {
            $categorizacion_as400 = new CategorizacionAs400();
            $categorizacion_as400->categoria_as400 = $codigo;
            $categorizacion_as400->categoria_id = $categoria->id;

            if (!$categorizacion_as400->save()) {
                $categoria->delete();
                return back()->with('error', 'Error al guardar los códigos AS400');
            }
        }

        return redirect()->route('categorias.index')->with('success', 'Categoría Creada Correctamente.');
    }
    public function edit(Request $request, AzureBlobService $azureBlobService)
    {
        $ip = env('INVENTARIO_API_IP');
        $uri = "http://{$ip}:8080/api/lineas";

        $client = new Client();
        $response = $client->get($uri);
        $responseData = json_decode($response->getBody()->getContents(), true);
        $lineas = $responseData['data'];

        $id = $request->id;
        $categoria = Categoria::find($id);

        if ($categoria->imagen) {
            try {
                $categoria->imagen_temporal = $azureBlobService->getTemporaryUrl($categoria->imagen, 30);
            } catch (\Throwable $th) {
                \Log::error("Error generando URL temporal para imagen de categoría {$id}: " . $th->getMessage());
                $categoria->imagen_temporal = null;
            }
        } else {
            $categoria->imagen_temporal = null;
        }

        $codigosAS400 = CategorizacionAs400::where('categoria_id', $id)
            ->pluck('categoria_as400')
            ->toArray();

        return view('categorias.edit', compact('categoria', 'lineas', 'codigosAS400'));
    }
    public function update(Request $request, AzureBlobService $azureBlob)
    {
        $request->validate([
            'categoria' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'codigos' => 'required|array|min:1',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $id = $request->id;
        $categoria = Categoria::find($id);

        $categoria->categoria = $request->categoria;
        $categoria->descripcion = $request->descripcion;

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = 'categorias/' . time() . '_' . $imagen->getClientOriginalName();

            $filePath = $azureBlob->uploadFile($imagen, $nombreImagen);
            $urlTemporal = $azureBlob->getTemporaryUrl($nombreImagen, 60);

            $categoria->imagen = $nombreImagen;
        }

        $categoria->save();

        $categoria->categorizacionAs400()->delete();

        foreach ($request->codigos as $codigo) {
            $categorizacion_as400 = new CategorizacionAs400();
            $categorizacion_as400->categoria_as400 = $codigo;
            $categorizacion_as400->categoria_id = $categoria->id;
            $categorizacion_as400->save();
        }

        return redirect()->route('categorias.index')->with('success', 'Categoría Editada Correctamente.');
    }





}
