<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCategoria;
use App\Models\Categoria;
use App\Models\CategorizacionAs400;
use App\Services\AzureBlobService;






class SubCategoriaController extends Controller
{

    public function index()
    {
        $categoria = SubCategoria::with('categoria')->get();
        return view("sub_categorias.index", ["subcategoria" => $categoria]);
    }
    public function create()
    {
        $categoria = Categoria::select('categoria', \DB::raw('MIN(id) as id'))
            ->groupBy('categoria')
            ->get();
        return view('sub_categorias.create', compact('categoria'));
    }
    public function store(Request $request, AzureBlobService $azureBlob)
    {
        $request->validate([
            'nombre_sub_categoria' => 'required|string|max:255',
            'categoria_id' => 'required|integer|exists:categorias,id',
            'categoria_as400' => 'required|array|min:1',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $categoria = new SubCategoria();
        $categoria->subcategoria = $request->nombre_sub_categoria;
        $categoria->categoria_id = $request->categoria_id;

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = 'sub_categorias/' . time() . '_' . $imagen->getClientOriginalName();

            $filePath = $azureBlob->uploadFile($imagen, $nombreImagen);
            $urlTemporal = $azureBlob->getTemporaryUrl($nombreImagen, 60);
            $categoria->imagen = $nombreImagen;
        }

        $categoria->save();

        foreach ($request->categoria_as400 as $codigo) {
            $categorizacion_as400 = new CategorizacionAs400();
            $categorizacion_as400->categoria_as400 = $codigo;
            $categorizacion_as400->subcategoria_id = $categoria->id;
            $categorizacion_as400->save();
        }

        return redirect()->route('subcategorias.index')->with('success', 'Subcategoría Creada Correctamente.');
    }


    public function getAs400ByCategoria(Request $request)
    {
        $id = $request->input('id');

        $codigos = CategorizacionAs400::where('categoria_id', $id)
            ->pluck('categoria_as400');

        return response()->json($codigos);
    }

    public function edit(Request $request, AzureBlobService $azureBlobService)
    {
        $categorias = Categoria::select('categoria', \DB::raw('MIN(id) as id'))
            ->groupBy('categoria')
            ->get();

        $id = $request->id;
        $subcategoria = SubCategoria::find($id);

        if ($subcategoria->imagen) {
            try {
                $subcategoria->imagen_temporal = $azureBlobService->getTemporaryUrl($subcategoria->imagen, 30);
            } catch (\Throwable $th) {
                \Log::error("Error generando URL temporal para imagen de subcategoría {$id}: " . $th->getMessage());
                $subcategoria->imagen_temporal = null;
            }
        } else {
            $subcategoria->imagen_temporal = null;
        }

        $codigosAS400 = CategorizacionAs400::where('subcategoria_id', $id)
            ->pluck('categoria_as400')
            ->toArray();

        return view('sub_categorias.edit', compact('subcategoria', 'categorias', 'codigosAS400'));
    }
    public function update(Request $request, AzureBlobService $azureBlob)
    {

        $request->validate([
            'nombre_sub_categoria' => 'required|string|max:255',
            'categoria_id' => 'required|integer|exists:categorias,id',
            'codigos' => 'required|array|min:1',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $id = $request->id;
        $categoria = SubCategoria::find($id);
        $categoria->subcategoria = $request->nombre_sub_categoria;
        $categoria->categoria_id = $request->categoria_id;

        if ($request->hasFile('imagen')) {
            $imagen = $request->file('imagen');
            $nombreImagen = 'sub_categorias/' . time() . '_' . $imagen->getClientOriginalName();

            $filePath = $azureBlob->uploadFile($imagen, $nombreImagen);
            $urlTemporal = $azureBlob->getTemporaryUrl($nombreImagen, 60);

            $categoria->imagen = $nombreImagen;
        }

        $categoria->save();

        $categoria->categorizacionAs400()->delete();

        foreach ($request->codigos as $codigo) {
            $categorizacion_as400 = new CategorizacionAs400();
            $categorizacion_as400->categoria_as400 = $codigo;
            $categorizacion_as400->subcategoria_id = $categoria->id;
            $categorizacion_as400->save();
        }

        return redirect()->route('subcategorias.index')->with('success', 'Subcategoría Editada Correctamente.');
    }



}
