<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SectorComercial;
use Illuminate\Http\Request;

class SectoresComercialesController extends Controller
{
    public function index()
    {
        return view("sector_comercial.index", ["sectores"=>SectorComercial::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("sector_comercial.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $sector = new SectorComercial();
        $sector->nombre = $request->nombre;
        $sector->save();
        return redirect()->route('sector_comercial.index')->with([
            "info" => "Sector Comercial Creado!",
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $sector = SectorComercial::where('id',$request->id_sector)->first();
        return view("sector_comercial.edit", ["sector" => $sector]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $sector =  SectorComercial::where('id',$request->id_sector)->first();
        $sector->nombre = $request->nombre;
        $sector->save();
        return redirect()->route("sector_comercial.index");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $TipoCliente = SectorComercial::find($request->id_sector);
        $TipoCliente->delete();
        return redirect()->route("sector_comercial.index");
    }
}
