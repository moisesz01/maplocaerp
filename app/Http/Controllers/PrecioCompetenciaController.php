<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PrecioCompetencia;
use Illuminate\Http\Request;
use DB;

class PrecioCompetenciaController extends Controller
{
    //
    public function index(){
        return view('precio_competencia.index');
    }
    public function create(){
        return view('precio_competencia.create');
    }
    public function edit(Request $request){
        $precio_competencia = PrecioCompetencia::find($request->id);
        return view('precio_competencia.update',[
            'precio_competencia' => $precio_competencia
        ]);
    }
    public function get_precios(Request $request){
        
        $precios = DB::table('precios_competencia')
        ->select('id','fecha','codigo_articulo','nombre_articulo','competidor','precio','tipo_precio');

        if ($request->has('articulo') && $request->articulo != '') {
            $precios->where('nombre_articulo', 'like', "%".$request->get('articulo')."%");
        }
        if ($request->has('competencia') && $request->competencia != '') {
            $precios->where('competidor', 'like', "%".$request->get('competencia')."%");
        }
       

        return datatables($precios)
        
        ->editColumn('precio', function ($precio) {
            return round($precio->precio, 2);
        })
        ->addColumn('action', function ($precios) {
            return $this->getActions($precios);
        })
        ->toJson();
        
        return $precios;
    }
    public function getActions($precios){
        return view('precio_competencia.actions',['precio'=>$precios]);
    }
    public function store(Request $request){

        
        
        $codigos =  $request->input('codigo_articulo');
        $articulos =  $request->input('nombre_articulo');
        $competidores = $request->input('competidor');
        $precios = $request->input('precio');
        $tipos_precio = $request->input('tipo_precio');

        try {
            if (count($articulos)) {
                foreach ($articulos as $key => $articulo) {
                    if(isset($request->submit) && $request->submit=='actualizar'){
                        $precio = PrecioCompetencia::find($request->id);
                        $mensaje = "Levantamiento Actualizado Exitosamente";
                    }else{
                        $precio = new PrecioCompetencia();
                        $mensaje = "Levantamiento Creado Exitosamente";
                    }
                    $precio->fecha = date('Y-m-d');
                    $precio->nombre_articulo = $articulo;
                    $precio->codigo_articulo = $codigos[$key];
                    $precio->competidor = $competidores[$key];
                    $precio->precio = $precios[$key];
                    $precio->tipo_precio = $tipos_precio[$key];
                    $precio->save();
                }
            }
            return redirect()->route('precio_competencia.index')->with([
                "info" => $mensaje,
            ]);
        } catch (\Throwable $th) {
            return redirect()->route('precio_competencia.index')->with([
                "warning" => "Levantamiento no se pudo guardar",
            ]);
        }
        
       
        
    }
    public function update(Request $request){
       
        $precio = PrecioCompetencia::find($request->id);
        $mensaje = "Levantamiento Actualizado Exitosamente";
        $precio->fecha = date('Y-m-d');
        $precio->nombre_articulo = $request->nombre_articulo;
        $precio->competidor = $request->competidor;
        $precio->precio = $request->precio;
        $precio->tipo_precio = $request->tipo_precio;
        if($precio->save()){
            return redirect()->route('precio_competencia.index')->with([
                "info" => $mensaje,
            ]);
        }else{
            return redirect()->route('precio_competencia.index')->with([
                "warning" => "Levantamiento no se pudo guardar",
            ]);
        }
    }
}
