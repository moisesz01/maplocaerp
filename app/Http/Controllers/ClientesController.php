<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Estado;
use App\Models\Ciudad;
use App\Models\Cliente;
use App\Models\ImagenCliente;
use App\Models\SectorComercial;
use App\Models\Visita;
use App\Models\User;
use App\Models\Almacen;
use Illuminate\Http\Request;
use DB;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ClientesController extends Controller
{
    public function create(Request $request){
        $estados = Estado::all();
        $sectores = SectorComercial::all();
        $ciudades = Ciudad::all();
        return view('clientes.create',[
            'estados' => $estados,
            'ciudades' => $ciudades,
            'sectores' => $sectores
        ]);
    }
    public function edit(Request $request){
        $estados = Estado::all();
        $ciudades = Ciudad::all();
        $sectores = SectorComercial::all();
        $cliente = Cliente::find($request->cliente_id);
        $vendedores = User::all();
       
        return view('clientes.update',[
            'estados' => $estados,
            'ciudades' => $ciudades,
            'sectores' => $sectores,
            'cliente' => $cliente,
            'vendedores' => $vendedores
        ]);
    }
    public function obtener_ciudades_estado(Request $request)
    {
        $estadoId = $request->input('estado_id');
        $ciudades = Ciudad::where('estado_id', $estadoId)->get();

        return response()->json($ciudades);
    }
    public function guardar_cliente(Request $request){
        $messages = [
            'file.*.mimes' => 'El archivo debe ser una imagen.',
            'file.*.max' => 'El archivo no debe superar los 10MB.',
            'numero_documento.unique' => 'Ya existe un cliente con este tipo y número de documento.',
        ];

        $request->validate([
           
            'numero_documento' => [
                'required',
                Rule::unique('clientes')->where(function ($query) use ($request) {
                    return $query->where('tipo_documento', $request->tipo_documento);
                })->ignore($request->cliente_id)
            ],
        ], $messages);
    
        DB::beginTransaction();
        try {
            if(isset($request->update) && $request->update==1){
                $cliente = Cliente::find($request->cliente_id);
            }else{
                // Verificar si el cliente ya existe
                $clienteExistente = Cliente::where([
                    'tipo_documento' => $request->input('tipo_documento'),
                    'numero_documento' => $request->input('numero_documento')
                ])->first();

                if($clienteExistente){
                    DB::rollBack();
                    return redirect()->back()->withInput()->with([
                        "danger" => "Ya existe un cliente con este tipo y número de documento.",
                    ]);
                }

                $cliente = new Cliente();
            }

            $cliente->nombre = $request->input('nombre');
            $cliente->tipo_documento = $request->input('tipo_documento');
            $cliente->numero_documento = $request->input('numero_documento');
            $cliente->ciudad_id = $request->input('ciudad_id');
            $cliente->sector_comercial_id = $request->input('sector_comercial_id');
            $cliente->latitud = $request->input('latitud');
            $cliente->longitud = $request->input('longitud');
            $cliente->correo = $request->input('correo');
            $cliente->telefono = $request->input('telefono');
            $cliente->direccion = $request->input('direccion');
            $cliente->observaciones = $request->input('observaciones');
            $cliente->denominacion_comercial = $request->input('denominacion_comercial');
            $cliente->persona_contacto = $request->input('persona_contacto');
            $cliente->cargo_profesion = $request->input('cargo_profesion');
            
            $user = auth()->user();
            if ($user->can('Vendedor Externo')) {
                $cliente->vendedor_id = auth()->user()->id;
            }
            if($request->has('vendedor_id')){
                $cliente->vendedor_id = $request->input('vendedor_id');
            }
            
            $cliente->save();
            
            

            DB::commit();
        
            return redirect()->route('clientes.index')->with([
                "info" => "Cliente Creado Exitosamente!",
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('clientes.index')->with([
                "danger" => "Cliente no pudo ser creado",
            ]);
        }  
    }
    public function index(){
        $sectores = SectorComercial::all();
        $estados = Estado::orderBy('nombre','asc')->get();
        return view('clientes.index',[
            'sectores' => $sectores,
            'estados' => $estados,
        ]);
    }
    public function obtener_clientes(Request $request){
        $user = auth()->user();
        $clientes = DB::table('clientes')
        ->select(
            'clientes.id',
            'clientes.nombre',
            'clientes.numero_documento',
            'estados.nombre as estado',
            'ciudades.nombre as ciudad',
            'sectores_comerciales.nombre as sector',
            'clientes.latitud',
            'clientes.longitud'
        );
        $clientes->leftJoin('ciudades', 'ciudades.id',"=",'clientes.ciudad_id');
        $clientes->leftJoin('estados', 'estados.id',"=",'ciudades.estado_id');
        $clientes->leftJoin('sectores_comerciales', 'sectores_comerciales.id',"=",'clientes.sector_comercial_id');
        

        if ($request->has('cliente') && $request->cliente != '') {
            $clientes->where('clientes.nombre', 'like', "%".$request->get('cliente')."%");
        }
        if (request()->has('estado') && $request->get('estado')!='') {
            $clientes->where('estados.id', '=', $request->get('estado'));
        }
        if (request()->has('sector') && $request->get('sector')!='') {
            $clientes->where('sectores_comerciales.id', '=', $request->get('sector'));
        }
        if (request()->has('sin_vendedor') && $request->get('sin_vendedor')!=0) {
            $clientes->whereNull('clientes.vendedor_id');
        }
        


        return datatables($clientes)
        
            ->filter(function ($query) use ($request) {
        })
        ->addColumn('action', function ($clientes) {
            return $this->getActions($clientes);
        })
        ->toJson();
        
        return $clientes;
    }
    public function getActions($clientes){
        return view('clientes.actions',['cliente'=>$clientes]);
    }
    public function visita(Request $request){
        $cliente = Cliente::find($request->cliente_id);
        return view('clientes.visita',[
            'cliente' => $cliente
        ]);
    }
    public function guardar_visita(Request $request){
        $cliente = Cliente::find($request->cliente_id);
        $visita = new Visita();
        $visita->cliente_id = $request->cliente_id;
        $visita->latitud = $request->latitud;
        $visita->longitud = $request->longitud;
        $visita->fecha_checkin = date('Y-m-d H:i:s',strtotime($request->fecha_checkin)) ;
        $visita->save();
        return redirect()->route('clientes.checkout_visita',[
            'visita_id' => $visita->id
        ])->with([
            "info" => "Visita Creada Exitosamente",
        ]);
        
    }
    public function checkout_visita($visita_id){

        $visita = Visita::find($visita_id);
        $cliente = Cliente::find($visita->cliente_id);
        return view('clientes.checkout_visita', [
            'cliente' => $cliente,
            'visita' => $visita
        ]);
    }
    public function guardar_checkout(Request $request){
        $visita = Visita::find($request->visita_id);
        $visita->notas = $request->notas;
        $visita->fecha_checkout = date('Y-m-d H:i:s') ;
        $visita->save();
        if ($request->input('accion') == 'guardar_pedido') {
            return redirect()->route('pedido.crear',[
                'visita_id' => $visita->id
            ])->with([
                "info" => "Visita Creada Exitosamente",
            ]);
        } else {
            // Realizar acción de guardar solo
        }
        return redirect()->route('visita.index')->with([
            "info" => "Visita Actualzida Exitosamente",
        ]);
    }
    public function visita_index(){
        $sectores = SectorComercial::all();
        return view('clientes.visitas_index',[
            'sectores' => $sectores
        ]);
    }

    public function obtener_visitas(Request $request){

        $visitas = DB::table('visitas')
        ->select('visitas.id as visita_id','clientes.id','clientes.nombre','clientes.numero_documento','visitas.fecha_checkin', 'visitas.fecha_checkout','sectores_comerciales.nombre as sector');
        $visitas->leftJoin('clientes', 'clientes.id',"=",'visitas.cliente_id');
        $visitas->leftJoin('sectores_comerciales', 'sectores_comerciales.id',"=",'clientes.sector_comercial_id');

        if ($request->has('cliente') && $request->cliente != '') {
            $visitas->where('clientes.nombre', 'like', "%".$request->get('cliente')."%");
        }
        if (request()->has('estado') && $request->get('estado')!='') {
            $visitas->where('estados.id', '=', $request->get('estado'));
        }
        if (request()->has('sector') && $request->get('sector')!='') {
            $visitas->where('sectores_comerciales.id', '=', $request->get('sector'));
        }

        return datatables($visitas)
        
            ->filter(function ($query) use ($request) {
        })
        ->addColumn('action', function ($visitas) {
            return $this->getActionsVisitas($visitas);
        })
        ->toJson();
        
        return $visitas;

    }

    public function getActionsVisitas($visita){
        return view('clientes.visitas_actions',[
            'visita' => $visita
        ])->render();
    }
    public function buscar_cliente(Request $request)
    {
        $search = $request->input('search');
        $clientes = Cliente::where('nombre', 'LIKE', '%' . $search . '%')
        ->OrWhere('numero_documento','LIKE', '%' . $search . '%')
        ->get();
        $data = json_decode($clientes, true);
        return response()->json($data);
    }
   
    public function verificar_documento(Request $request)
    {
        $tipoDocumento = $request->input('tipo_documento');
        $numeroDocumento = $request->input('numero_documento');
    
        $existe = Cliente::where('tipo_documento', $tipoDocumento)
            ->where('numero_documento', $numeroDocumento)
            ->exists();
    
        return response()->json(['existe' => $existe]);
        
    }
    public function crear_cliente_as(Cliente $cliente){
      
        try {
            $ip = env('INVENTARIO_API_IP');
            $uri = "http://{$ip}:8080/api/clientes/crear";
    
            $client = new Client();
            $response = $client->post($uri, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                'id_app' => $cliente->id,
                'tipo_documento' =>  $cliente->tipo_documento,
                'numero_documento'=> $cliente->numero_documento,
                'nombre_cliente'=>$cliente->nombre,
                'esta15'=>$cliente->ciudad->ESTA15 ?? '', 
                'ciud16'=> $cliente->ciudad->CIUD16 ?? '',
                'sccm10'=>$cliente->sector->SCCM10 ?? '',
                'direccion'=>$cliente->direccion,
                'telefono'=>$cliente->telefono,
                'correo'=>$cliente->correo,
                'vend94'=>$cliente->vendedor->VEND94 ?? '',
                'dencom'=>$cliente->denominacion_comercial, 
                'percon' =>$cliente->persona_contacto,
                'cargo1'=>$cliente->cargo_profesion,
                ]
                
            ]);
    
            $data = json_decode($response->getBody()->getContents(), true);
            
        } catch (ConnectException $e) {
           
            $error = [
                'message' => 'Error al realizar la solicitud a la API',
                'error_message' => $e->getMessage()
            ];
            return response()->json($error, 500);
        }
    }
    public function actualizar_cliente_as(Cliente $cliente){
      
        try {
            $ip = env('INVENTARIO_API_IP');
            $uri = "http://{$ip}:8080/api/clientes/actualizar";
    
            $client = new Client();
            $response = $client->put($uri, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'id_app' => $cliente->id,
                    'tipo_documento' =>  $cliente->tipo_documento,
                    'numero_documento'=> $cliente->numero_documento,
                    'nombre_cliente'=>$cliente->nombre,
                    'esta15'=>$cliente->ciudad->ESTA15 ?? '', 
                    'ciud16'=> $cliente->ciudad->CIUD16 ?? '',
                    'sccm10'=>$cliente->sector->SCCM10 ?? '',
                    'direccion'=>$cliente->direccion,
                    'telefono'=>$cliente->telefono,
                    'correo'=>$cliente->correo,
                    'vend94'=>$cliente->vendedor->VEND94 ?? '',
                    'dencom'=>$cliente->denominacion_comercial, 
                    'percon' =>$cliente->persona_contacto,
                    'cargo1'=>$cliente->cargo_profesion,
                ]
                
            ]);
    
            $data = json_decode($response->getBody()->getContents(), true);
            
        } catch (ConnectException $e) {
           
            $error = [
                'message' => 'Error al realizar la solicitud a la API',
                'error_message' => $e->getMessage()
            ];
            return response()->json($error, 500);
        }
    }
    public function inactivar_cliente(Request $request){
        $id = $request->input('cliente_id');
        $cliente = Cliente::find($id)->delete();
        if($cliente){
            return redirect()->route('clientes.index')->with([
                "info" => "Cliente eliminado  Exitosamente!",
            ]);
        }else{
            return redirect()->route('clientes.index')->with([
                "warning" => "No se pudo eliminar el cliente",
            ]);
        }
        

    }
    public function ver_visita(Request $request){
        $visita = Visita::find($request->visita_id);
        $cliente = Cliente::find($visita->cliente_id);
        return view('clientes.visita_view', [
            'cliente' => $cliente,
            'visita' => $visita
        ]);
    }
    
}
