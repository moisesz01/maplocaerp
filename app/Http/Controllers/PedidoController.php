<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Almacen;
use App\Models\Cliente;
use App\Models\Documento;
use App\Models\DetalleDocumento;
use App\Models\FormaPago;
use App\Models\MetodoPago;
use App\Models\Status;
use App\Models\TipoDocumento;
use App\Models\Visita;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpParser\Node\Stmt\TryCatch;
use App\Mail\Notificaciones;
use App\Models\AlmacenFacturador;
use App\Models\PagoDocumento;
use Illuminate\Support\Facades\Mail;

class PedidoController extends Controller
{
    public function realizar_pedido($visita_id= null){
        $almacenes = Almacen::all();
        $user = User::find(Auth::id());
        $metodos_pago = MetodoPago::all();
        $formas_pago = FormaPago::all();
        $visita = ($visita_id)?Visita::find($visita_id):null;
        
        try {
            $ip = env('INVENTARIO_API_IP');
            $uri = "http://{$ip}:8080/api/servicios";
    
            $client = new Client();
            $response = $client->get($uri);
            $servicios = json_decode($response->getBody()->getContents(), true);
            
        } catch (\Throwable $th) {
           
        }
        try {
            $ip = env('INVENTARIO_API_IP');
            $uri = "http://{$ip}:8080/api/tasas";
    
            $client = new Client();
            $response = $client->get($uri);
            $tasas = json_decode($response->getBody()->getContents(), true);
            
        } catch (\Throwable $th) {
           
        }
        return view('pedidos.crear_pedido',[
            'almacenes'=>$almacenes,
            'almacen_codigo'=>($user->almacen->codigo)?$user->almacen->codigo:1,
            'metodos_pago'=>$metodos_pago,
            'formas_pago'=>$formas_pago,
            'visita'=>$visita,
            'servicios'=>$servicios['data'] ?? [],
            'tasas'=>$tasas['data'] ?? [],
        ]);
    }

    public function productos(Request $request){
        try {
            $ip = env('INVENTARIO_API_IP');
            $uri = "http://{$ip}:8080/api/inventario/buscar-producto";
    
            $client = new Client();
            $response = $client->get($uri, [
                'query' => [
                    'producto' => $request->search,
                    'almacen_codigo' =>  $request->almacen_codigo,
                    'con_stock' => $request->con_stock
                ],
            ]);
    
            $data = json_decode($response->getBody()->getContents(), true);
            return response()->json($data);
        } catch (ConnectException $e) {
            $error = [
                'message' => 'Error al realizar la solicitud a la API',
                'error_message' => $e->getMessage()
            ];
            return response()->json($error, 500);
        }
    }
    public function exportar_inventario(Request $request){
        $almacen_codigo = $request->almacen_codigo;
        if($almacen_codigo!=''){
            try {
                $ip = env('INVENTARIO_API_IP');
                $uri = "http://{$ip}:8080/api/inventario/exportar";
        
                $client = new Client();
                $response = $client->get($uri, [
                    'query' => [
                        'almacen_codigo' =>  $request->almacen_codigo
                    ],
                ]);
              
                $articulos = json_decode($response->getBody()->getContents(), true);
    
                if($articulos){
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();
                    $max_length = max(array_map(function($item) {
                        return strlen($item['desc87']);
                    }, $articulos['data']));
                    $sheet->getColumnDimension('C')->setWidth($max_length);
                    $header = [
                        'A' => 'ITEM',
                        'B' => 'CÓDIGO',
                        'C' => 'ARTICULO',
                        'D' => 'U/M',
                        'E' => 'KILOS',
                        'F' => 'PRECIO UNITARIO',
                        'G' => 'LINEA',
                        'H' => 'INVENTARIO',
                    ];
                
                    foreach ($header as $key => $campo) {
                        $sheet->setCellValue($key . '1', $campo);
                    }
                    $i = 1;
                    if (!empty($articulos) && isset($articulos['data']) && is_array($articulos['data'])) {
                        foreach ($articulos['data'] as $key => $item) {
                            $i++;
                            $key++;
                            $sheet->setCellValue('A' . $i, $key);
                            $sheet->setCellValue('B' . $i, $item['arti87']);
                            $sheet->setCellValue('C' . $i, $item['desc87']);
                            $sheet->setCellValue('D' . $i, $item['unmd87']);
                            $sheet->setCellValue('E' . $i, $item['peso87']);
                            $sheet->setCellValue('F' . $i, $item['precio_neto']);
                            $sheet->setCellValue('G' . $i, $item['linea']); 
                            $sheet->setCellValue('H' . $i, $item['disp_uni_inv']); 
                        }
                    }
                    
                    $writer = new Xlsx($spreadsheet);
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment;filename="inventario_almacen'.$request->almacen_codigo.'.xlsx"');
                    header('Cache-Control: max-age=0');
                    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                    $writer->save('php://output');
                }
                
    
            } catch (ConnectException $e) {
                return redirect()->route('pedidos.index')->with([
                    "danger" => $e->getMessage(),
                ]);
            }
        }else{
            return redirect()->route('pedidos.index')->with([
                "danger" => "el usuario no tiene un almacen asignado",
            ]);
        }
       
    }
    

    public function pedidos_index(){
        $estados = Status::all();
        $user = Auth::user();
        $almacen = $user->almacen ?? '01';
        return view('pedidos.index',[
                'estados'=>$estados,
                'almacen_codigo'=>$almacen->codigo
            ]
        );
    }

    public function get_documentos(Request $request){
        
        $documentos = DB::table('documentos')
        ->select('documentos.id','clientes.nombre as cliente','users.name as vendedor','documentos.fecha_creacion','tipos_documentos.nombre as tipo_documento','status.nombre as status');
        $documentos->leftJoin('status', 'status.id',"=",'documentos.status_id');
        $documentos->leftJoin('clientes', 'clientes.id',"=",'documentos.cliente_id');
        $documentos->leftJoin('users', 'users.id',"=",'documentos.vendedor_id');
        $documentos->leftJoin('tipos_documentos', 'tipos_documentos.id',"=",'documentos.tipo_documento_id');

        if ($request->has('cliente') && $request->cliente != '') {
            $documentos->where('clientes.nombre', 'like', "%".$request->get('cliente')."%");
        }
        if (request()->has('status_id') && $request->get('status_id')!='') {
            $documentos->where('documentos.status_id', '=', $request->get('status_id'));
        }
       

        return datatables($documentos)
        
            ->filter(function ($query) use ($request) {
        })
        ->addColumn('action', function ($documentos) {
            return $this->getActionsDocumentos($documentos);
        })
        ->toJson();
        
        return $documentos;
    }
    public function getActionsDocumentos($documento){
        return view('pedidos.actions',[
            'documento' => $documento,
        ])->render();
    }

    public function guardar_pedido(Request $request){
       
        date_default_timezone_set('America/Caracas');
        $status_inicial =  Status::where('nombre','Entregada')->first();
        DB::beginTransaction();
        try {
            $ids_productos =  $request->input('producto_id');
            $productos =  $request->input('producto');
            $tipos =  $request->input('tipo');
            $cantidades = $request->input('cantidad');
            $descuentos = $request->input('descuento_producto');
            $precios =  $request->input('precio');
            $precios_base =  $request->input('precio_bk');
            $pesos =  $request->input('peso');
            $unidades_medida =  $request->input('unidad_medida');
            if ($request->has('documento_id')) {
                $documento = Documento::find($request->input('documento_id'));
                DetalleDocumento::where('documento_id', $request->input('documento_id'))->delete();
                PagoDocumento::where('documento_id', $request->input('documento_id'))->delete();
                $mensaje = "Cotización Actualizada Exitosamente";
            }else{
                $documento = new Documento();
                $mensaje = "Cotización Creada  Exitosamente";
                $documento->status_id = $status_inicial->id;
            }
           
            $documento->cliente_id = $request->input('cliente_id');
            $documento->forma_pago_id = $request->input('forma_pago');
            $documento->vendedor_id = Auth::id();
            $documento->fecha_creacion =  date('Y-m-d');
            $documento->notas =  $request->input('notas');
            $documento->porcentaje_descuento =  $request->input('descuento');
            $documento->visita_id =  $request->input('visita_id');
            $documento->tipo_documento_id = TipoDocumento::where('nombre','cotizacion')->first()->id;
            $documento->con_iva = ($request->has('con_iva'))?1:0;
            $documento->contribuyente_especial = ($request->has('contribuyente_especial'))?1:0;
            $documento->dias_credito = $request->input('dias_credito');
            $documento->moneda_extranjera = $request->input('moneda_extranjera');
            $documento->tasa = $request->input('tasa');
            $flat_descuento_individual = 0;

            



            if($documento->save()){
                $metodos = $request->input('metodo_pago');
                $montos = $request->input('monto_pago');

                if(count($metodos)){
                    foreach ($metodos as $key => $metodo) {
                        $pago = new PagoDocumento();
                        $pago->documento_id = $documento->id;
                        $pago->metodo_pago_id = $metodo;
                        $pago->monto = $montos[$key];
                        $pago->save();
                    }
                }
               

                if(count($ids_productos)){
                    foreach ($ids_productos as $key => $id) {

                        if ($descuentos[$key]!=0) {
                            $flat_descuento_individual++;
                        } 
                        $documento_detalle = new DetalleDocumento();
                        $documento_detalle->codigo_articulo = $id;
                        $documento_detalle->nombre_articulo = $productos[$key];
                        $documento_detalle->cantidad = $cantidades[$key];
                        $documento_detalle->precio = $precios[$key];
                        $documento_detalle->precio_base = $precios_base[$key];
                        $documento_detalle->cantidad = $cantidades[$key];
                        $documento_detalle->costo = $cantidades[$key];
                        $documento_detalle->peso = $pesos[$key];
                        $documento_detalle->unidad_medida = $unidades_medida[$key];
                        $documento_detalle->porcentaje_descuento = $descuentos[$key];
                        $documento_detalle->documento_id = $documento->id;
                        $documento_detalle->tipo = $tipos[$key];
                        $documento_detalle->save();
                    }
                    if ($flat_descuento_individual>0) {
                        $documento->descuento_individual = 1;
                        $documento->save();
                    }
                }
            }
            DB::commit();
            return redirect()->route('pedidos.index')->with([
                "info" => $mensaje,
            ]);
            
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('pedidos.index')->with([
                "danger" => "Cotización no pudo ser creada!",
            ]);
        } 
        
    }
    public function exportar_cotizacion(Request $request){
        $documento = Documento::where('id',$request->cotizacion_id)->first();
        $documento_detalle = DetalleDocumento::where('documento_id',$request->cotizacion_id)->get();
        $pagos = PagoDocumento::where('documento_id',$request->cotizacion_id)->get();
        $pdf = \PDF::loadView('pedidos.pdf_cotizacion', [
            'cotizacion' => $documento,
            'articulos'=> $documento_detalle,
            'pagos' => $pagos,
        ]);
        return $pdf->download('Cotización '.$documento->cliente->nombre.'.pdf');
    }

    public function modal_estado(Request $request){
        $estados = Status::all();
        return view('pedidos._estados',[
            'documento_id'=>$request->documento_id,
            'estados'=>$estados
        ])->render();
    }

    public function actualizar_estado(Request $request){
        $documento = Documento::find($request->documento_id);
        $documento->status_id = $request->status_id;
        $documento->comentario_estado = $request->comentario_estado;
        $documento_detalle = DetalleDocumento::where('documento_id',$request->documento_id)->get();
        $pagos = PagoDocumento::where('documento_id',$request->documento_id)->get();
        
        if($request->status_id==3){
            
            $data = [
                'cotizacion' => $documento,
                'articulos'=> $documento_detalle,
                'pagos' => $pagos
            ];
            $user = User::find(Auth::id());
            $facturadores = AlmacenFacturador::where('almacen_id',$user->almacen_id)->get();  
            try {
                if(count($facturadores)){
                    foreach ($facturadores as $key => $facturador) {
                        Mail::to($facturador->vendedor->email)->send(new \App\Mail\NotificacionPedido('Nueva Solicitud de Pedido',$request->comentario_estado,$user->email,$data));
                    }
                }
                
            } catch (\Exception $e) {
                
                return redirect()->route('pedidos.index')->with([
                    "danger" => "No se pudo actualizar el status: ".$e->getMessage(),
                ]);
            }
             
           
        }
        


        if($documento->save()){
            return redirect()->route('pedidos.index')->with([
                "info" => "Status de Cotización actualizado Exitosamente!",
            ]);
        } else {
            
            return redirect()->route('pedidos.index')->with([
                "danger" => "No se pudo actualizar el status",
            ]);
        }
        
    }

    public function editar_cotizacion(Request $request){
        $documento = Documento::find($request->cotizacion_id);
        $detalle = DetalleDocumento::where('documento_id',$request->cotizacion_id)->get();
        $almacenes = Almacen::all();
        $user = User::find(Auth::id());
        $metodos_pago = MetodoPago::all();
        $formas_pago = FormaPago::all();
        $pagos = PagoDocumento::where('documento_id',$request->cotizacion_id)->get();
        try {
            $ip = env('INVENTARIO_API_IP');
            $uri = "http://{$ip}:8080/api/servicios";
    
            $client = new Client();
            $response = $client->get($uri);
            $servicios = json_decode($response->getBody()->getContents(), true);
            
        } catch (\Throwable $th) {
           
        }
        try {
            $ip = env('INVENTARIO_API_IP');
            $uri = "http://{$ip}:8080/api/tasas";
    
            $client = new Client();
            $response = $client->get($uri);
            $tasas = json_decode($response->getBody()->getContents(), true);
            
        } catch (\Throwable $th) {
           
        }
        return view('pedidos.update_cotizacion', [
            'documento' => $documento,
            'detalle' => $detalle,
            'almacenes'=>$almacenes,
            'almacen_codigo'=>$user->almacen->codigo,
            'metodos_pago'=>$metodos_pago,
            'formas_pago'=>$formas_pago,
            'pagos'=>$pagos,
            'servicios'=>$servicios['data'] ?? [],
            'tasas'=>$tasas['data'] ?? [],
        ]);
    }

    public function procesar_pedido(Request $request){
        $documento = Documento::find($request->documento_id);
        $documento_detalle = DetalleDocumento::where('documento_id',$request->documento_id)->get();
        $fecha = explode(' ', $documento->fecha_creacion);
        $fecha = explode('-', $fecha[0]);
      
        $cotiza = [
            "identi"=>$documento->id,
            "fecha"=> intval($fecha[0] . $fecha[1] . $fecha[2]),
            "notas"=> $documento->notas ?? ' ',
            "coniva"=> $documento->con_iva,
            "pordes"=> $documento->porcentaje_descuento ?? 0,
            "vended"=> $documento->vendedor->VEND94,
            "forpag"=> $documento->forma->nombre ?? ' ',
            "diacre"=> $documento->dias_credito ?? 0,
            "metpag"=> $documento->metodo_pago_id ?? ' ',
            "nommet"=> $documento->metodo->nombre ?? ' ',
            "iderif"=> $documento->cliente->tipo_documento,
            "nrorif"=> $documento->cliente->numero_documento,
            "seccom"=> $documento->cliente->sector->nombre ?? '',
            "stasin"=> 0,
            'moneda' => $documento->moneda_extranjera ?? '',
            'tasa' => $documento->tasa? round($documento->tasa, 2) : 0,
        ];
        $detcot = [];
        foreach ($documento_detalle as $key => $articulos) {
            $precio_descuento = $articulos->precio * (1 - ($articulos->porcentaje_descuento / 100));
            $precio_descuento = $precio_descuento * (1 - ($documento->porcentaje_descuento / 100));
            $detcot[]= [
                "identi" => $documento->id,
                "articu" => $articulos->codigo_articulo,
                "descri" => $articulos->nombre_articulo,
                "cantid" => intval($articulos->cantidad),
                "precio" => floatval(round($precio_descuento,2)),
                "prebas" => floatval($articulos->precio_base),
                "peso" => floatval($articulos->peso),
                "porcen" => floatval($articulos->porcentaje_descuento),
                "tipo" => $articulos->tipo,
            ];
        }
        $data = [
            "cotiza" => $cotiza,
            "detcot" => $detcot
        ];
   
       
        try {
            $ip = env('INVENTARIO_API_IP');
            $uri = "http://{$ip}:8080/api/pedido/crear";
    
            $client = new Client();
            $response = $client->post($uri, [
                'headers' => ['Content-Type' => 'application/json'],
                'json' =>$data
                
            ]);
    
            $data = json_decode($response->getBody()->getContents(), true);

           if($data['code']==1){
                return redirect()->route('pedidos.index')->with([
                    "info" => "sincronización con as400 correcta!",
                ]);
           }else{
                return redirect()->route('pedidos.index')->with([
                    "danger" => $data['message'],
                ]);
           }

            
            
        } catch (ConnectException $e) {
           
            return redirect()->route('pedidos.index')->with([
                "danger" => $e->getMessage(),
            ]);
        }

        
    }
    
}

