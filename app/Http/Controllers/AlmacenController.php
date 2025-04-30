<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\AlmacenFacturador;
use App\Models\User;
use Illuminate\Http\Request;

class AlmacenController extends Controller
{
    public function index()
    {
        return view("almacen.index", ["almacenes"=>Almacen::all()]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("almacen.create",[
            'vendedores' => User::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $almacen = new Almacen();
        $almacen->nombre = $request->nombre;
        $almacen->codigo = $request->codigo;
        $almacen->direccion = $request->direccion;
        $almacen->whastapp = $request->whastapp;
        $almacen->save();
        return redirect()->route('almacen.index')->with([
            "info" => "almacen Creado!",
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
        $almacen = Almacen::where('id',$request->id_almacen)->first();
        $users = User::all();
        $almacenes_facturadores = AlmacenFacturador::where('almacen_id',$request->id_almacen)->pluck('user_id');

        $vendedores = [];
        foreach ($users as $user) {
            $seleccionado = in_array($user->id, $almacenes_facturadores->all()) ? true : false;
            $vendedores[] = [
                'id' => $user->id,
                'name' => $user->name,
                'seleccionado' => $seleccionado,
            ];
        }

        return view("almacen.edit", [
            "almacen" => $almacen,
            "vendedores" => $vendedores
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $almacen =  Almacen::where('id',$request->id_almacen)->first();
        $almacen->nombre = $request->nombre;
        $almacen->codigo = $request->codigo;
        $almacen->direccion = $request->direccion;
        $almacen->whastapp = $request->whastapp;
        $almacen->save();
        $vendedores = $request->vendedores;
        $almacenes_facturadores = AlmacenFacturador::where('almacen_id',$request->id_almacen)->delete();
        if(is_array($vendedores)){
            foreach ($vendedores as $key => $value) {
                $almacen_facturador = new AlmacenFacturador();
                $almacen_facturador->almacen_id = $request->id_almacen;
                $almacen_facturador->user_id = $value;
                $almacen_facturador->save();
            }
        }


        return redirect()->route('almacen.index')->with([
            "info" => "almacen actualizado!",
        ]);
    }

    
}
