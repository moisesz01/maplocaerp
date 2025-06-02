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
use App\Models\Tasa;
use App\Models\ControlEmision;
use PDF;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpParser\Node\Stmt\TryCatch;
use App\Mail\Notificaciones;
use App\Models\AlmacenFacturador;
use App\Models\PagoDocumento;
use App\Models\CanalVenta;
use App\Models\Factura; 
use App\Models\DetalleFactura;
use App\Models\EstadosDocumentos;
use App\Models\InventarioReservado;
use App\Models\Moneda;
use App\Models\Movimiento;
use App\Models\PuntoVenta;
use App\Models\TipoMovimientoCaja;
use App\Models\TipoTarjeta;
use Illuminate\Support\Facades\Mail;

class FacturacionController extends Controller
{
    public function crear_factura(Request $request) {
        
        $almacenes = Almacen::all();
        $user = User::find(Auth::id());
        $tasas = Tasa::all();
        $servicios = [];
        $vendedores = User::where('almacen_id',$user->almacen_id)->get();
        $canales = CanalVenta::all();
        $tipos_movimiento =  TipoMovimientoCaja::all();
        $almacen_codigo = ($user->almacen->codigo)?$user->almacen->codigo:'01';
        $puntos_venta =  PuntoVenta::where('SUCU5V',$almacen_codigo)->get();
        $monedas =  Moneda::all();
        $tipos_tarjeta =  TipoTarjeta::all();
        $tipo_documento = TipoDocumento::where('nombre',$request->tipo)->first();
        return view('facturacion.crear_factura',[
            'almacenes'=>$almacenes,
            'almacen_codigo'=>$almacen_codigo,
            'servicios'=>$servicios['data'] ?? [],
            'tasas'=>$tasas,
            'vendedores'=>$vendedores,
            'user'=>$user,
            'canales'=>$canales,
            'tipos_movimiento'=>$tipos_movimiento,
            'puntos_venta'=>$puntos_venta,
            'monedas'=>$monedas,
            'tipos_tarjeta'=>$tipos_tarjeta,
            'tipo_documento'=>$tipo_documento

        ]);
    }

    public function productos(Request $request) {
        // Subconsulta para obtener la suma de cantidades reservadas por producto y almacén
        $reservadoSubquery = DB::table('maploca_desktop.dbo.inventario_reservado')
            ->select(
                'codigo',
                'codigo_almacen',
                DB::raw('SUM(cantidad_reservada) as total_reservado')
            )
            ->groupBy('codigo', 'codigo_almacen');
    
        $productos = DB::table('inventario')
            ->leftJoinSub($reservadoSubquery, 'reservado', function($join) {
                $join->on('inventario.codigo', '=', 'reservado.codigo')
                     ->on('inventario.codigo_almacen', '=', 'reservado.codigo_almacen');
            })
            ->select(
                'inventario.codigo as knum87', 
                'inventario.codigo as arti87', 
                'inventario.articulo as desc87',
                'inventario.unidad_medida as unmd87', 
                DB::raw('ROUND(inventario.peso, 2) as peso87'), // Redondeado a 2 decimales
                DB::raw('ROUND(inventario.precio, 2) as precio_neto'), // Redondeado a 2 decimales
                'inventario.linea', 
                DB::raw('CASE 
                    WHEN reservado.total_reservado IS NOT NULL 
                    THEN inventario.disponible - reservado.total_reservado 
                    ELSE inventario.disponible 
                    END as disp_uni_inv'), 
                'inventario.codigo_almacen as almacen', 
                'inventario.peso_conversion', 
                'inventario.ancho_conversion', 
                'inventario.alto_espesor_conversion', 
                'inventario.largo', 
                'inventario.estandar'
            );
    
        // Filtro por almacén
        $productos->where('inventario.codigo_almacen', $request->almacen_codigo);
    
        // Filtro por código o artículo si se proporciona el parámetro 'busqueda'
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = '%' . $request->search . '%';
            $productos->where(function($query) use ($searchTerm) {
                $query->where('inventario.codigo', 'LIKE', $searchTerm)
                      ->orWhere('inventario.articulo', 'LIKE', $searchTerm);
            });
        }
        
        $productos->distinct();
    
        $data = ['data' => $productos->get()];
        
        return response()->json($data);
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
    

    public function facturas_index(){
     
        $user = Auth::user();
        $almacen = $user->almacen ?? '01';
        $tipo_documento = TipoDocumento::where('nombre','Factura')->first();
        return view('facturacion.index',[
            'almacen_codigo'=>$almacen->codigo,
            'tipo_documento_id'=> $tipo_documento->id
        ]);
    }
    public function cotizaciones_index(){
     
        $user = Auth::user();
        $almacen = $user->almacen ?? '01';
        $tipo_documento = TipoDocumento::where('nombre','Cotización')->first();
        return view('facturacion.index_cotizaciones',[
            'almacen_codigo'=>$almacen->codigo,
            'tipo_documento_id'=> $tipo_documento->id
        ]);
    }

    public function get_documentos(Request $request){
        
        $documentos = DB::table('facturas')
        ->select('facturas.id','clientes.nombre as cliente','users.name as vendedor','facturas.fecha_creacion','tipos_documentos.nombre as tipo_documento');
       
        $documentos->leftJoin('clientes', 'clientes.id',"=",'facturas.cliente_id');
        $documentos->leftJoin('users', 'users.id',"=",'facturas.vendedor_id');
        $documentos->join('tipos_documentos', 'tipos_documentos.id',"=",'facturas.tipo_documento_id');
        $documentos->where('tipos_documentos.id', '=', $request->get('tipo_documento_id'));

        if ($request->has('cliente') && $request->cliente != '') {
            $documentos->where('clientes.nombre', 'like', "%".$request->get('cliente')."%");
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
        return view('facturacion.actions',[
            'documento' => $documento,
        ])->render();
    }
    public function conversion_modal(Request $request){
        $documento = Factura::find($request->documento_id);
        $tipo_cotizacion = TipoDocumento::where('nombre','Factura')->first();
        return view('facturacion.modal_conversion',[
            'documento'=>$documento,
            'tipo_documento_id'=>$tipo_cotizacion->id,
        ])->render();
    }
    public function convertir_documento(Request $request){
        $documento = Factura::find($request->factura_id);
        $documento->tipo_documento_id = $request->tipo_documento_id;
        $documento->save();
        return redirect()->route('factura.index')->with([
            "info" => "Tipo de Documento actualizado Exitosamente!",
        ]);
    }
    public function get_codigo_almacen(){
        $user = User::find(Auth::id());
        $almacen = Almacen::where('id',$user->almacen_id)->first();
        return $almacen->codigo ?? '';
    }

    public function guardar_factura(Request $request){ 
       
        date_default_timezone_set('America/Caracas');
        $tipo_documento = TipoDocumento::find($request->input('tipo_documento_id'))->nombre ;
        DB::beginTransaction();
        try {
            $ids_productos =  $request->input('producto_id');
            $productos =  $request->input('producto');
            $tipos =  $request->input('tipo');
            $cantidades = $request->input('cantidad');
            $descuentos = $request->input('descuento_producto');
            $precios =  $request->input('precio');
            $estandar =  $request->input('estandar');
            $largos =  $request->input('largo');
            $precios_base =  $request->input('precio_bk');
            $pesos =  $request->input('peso');
            $unidades_medida =  $request->input('unidad_medida');
            $unidades_venta =  $request->input('unidad_venta');
            $tipos_movimiento =  $request->input('tipos_movimiento');
            $puntos_venta =  $request->input('punto_venta');
            $tipos_tarjeta =  $request->input('tipo_tarjeta');
            $numero_transaccion =  $request->input('numeros_transaccion');
            $importes_ml =  $request->input('importe_ml');
            $importes_me =  $request->input('importe_me');
            $monedas =  $request->input('monedas');
            $tasas =  $request->input('tasas');
            $factores =  $request->input('factores');
            $tipo_in_out = $request->input('tipo_in_out');
            if ($request->has('factura_id')) {
                $documento = Factura::find($request->input('factura_id'));
                DetalleDocumento::where('factura_id', $request->input('factura_id'))->delete();
                PagoDocumento::where('factura_id', $request->input('factura_id'))->delete();
                $mensaje = "Factura Actualizada Exitosamente";
            }else{
                $documento = new Factura();
                $mensaje = "Factura Creada  Exitosamente";
                $documento->status_id = 1;
            }
           
            $documento->cliente_id = $request->input('cliente_id');
           
            $documento->vendedor_id = $request->input('vendedor_id');
            $documento->vendedor_secundario_id = $request->input('vendedor_secundario_id');
            $documento->fecha_creacion =  date('Y-m-d');
            $documento->notas =  $request->input('notas');
            $documento->porcentaje_descuento =  $request->input('descuento');
            $documento->CVTA32 = $request->input('canal_venta');
           
            $documento->con_iva = ($request->has('con_iva'))?1:0;
            $documento->contribuyente_especial = ($request->has('contribuyente_especial'))?1:0;
            $documento->dias_credito = $request->input('dias_credito');
            $documento->moneda_extranjera = $request->input('moneda_extranjera');
            $documento->tasa = $request->input('tasa');
            $flat_descuento_individual = 0;
            $documento->compania = ($request->has('con_iva'))?'MP':'AB';
            $documento->codigo_almacen = $this->get_codigo_almacen();
            $documento->direccion_alternativa = $request->input('direccion_alternativa');
            $documento->tipo_documento_id = $request->input('tipo_documento_id');

            if($documento->save()){
                $documento->NUDO96 = $this->control_emision($documento->con_iva);
                $documento->save();
                if(count($ids_productos)){
                    $total = 0;
                    foreach ($ids_productos as $key => $id) {

                        if ($descuentos[$key]!=0) {
                            $flat_descuento_individual++;
                        } 
                        $documento_detalle = new DetalleFactura();
                        $documento_detalle->codigo_articulo = $id;
                        $documento_detalle->nombre_articulo = $productos[$key];
                        $documento_detalle->cantidad = $cantidades[$key];
                        $documento_detalle->precio = $precios[$key];
                        $documento_detalle->cantidad = $cantidades[$key];
                        $documento_detalle->costo = $cantidades[$key];
                        $documento_detalle->peso = $pesos[$key];
                        $documento_detalle->unidad_medida = $unidades_medida[$key];
                        $documento_detalle->unidad_conversion = $unidades_venta[$key];
                        $documento_detalle->porcentaje_descuento = $descuentos[$key];
                        $documento_detalle->factura_id = $documento->id;
                        $documento_detalle->tipo = $tipos[$key];
                        $documento_detalle->estandar = $estandar[$key];
                        $documento_detalle->largo = $largos[$key];
                        $documento_detalle->codigo_almacen = $this->get_codigo_almacen();
                        $documento_detalle->factor = $factores[$key];
                        $documento_detalle->save();
                        $total += ($precios[$key] * $cantidades[$key]);
                    }
                    $documento->cod_almacen_virtual = $this->facturacion_virtual($total);
                    if ($flat_descuento_individual>0) {
                        $documento->descuento_individual = 1;
                    }
                    $documento->save();
                     if($tipo_documento=='Factura'){
                        $this->descuento_inventario($documento->id);
                     }
                    
                }
                if(count($tipos_movimiento)){
                    foreach ($tipos_movimiento as $key => $tipo) {
                        $pago_documento = new Movimiento();
                        $pago_documento->factura_id = $documento->id;
                        $pago_documento->tipo = $tipo_in_out[$key];
                        $pago_documento->tipo_movimiento_caja_id = $tipo;
                        $pago_documento->punto_venta_id = $puntos_venta[$key];
                        $pago_documento->tipo_tarjeta_id = $tipos_tarjeta[$key];
                        $pago_documento->numero_transaccion = $numero_transaccion[$key];
                        $pago_documento->importe_ml = $importes_ml[$key];
                        $pago_documento->importe_me = $importes_me[$key];
                        $pago_documento->moneda_id = $monedas[$key];
                        $pago_documento->tasa = $tasas[$key];
                        $pago_documento->save();
                    }
                }


            }
            DB::commit();
            if($tipo_documento=='Factura'){
                return redirect()->route('factura.index')->with([
                    "info" => $mensaje,
                ]);
            }else{
                if ($request->has('factura_id')) {
                
                $mensaje = "Cotización Actualizada Exitosamente";
                }else{
                    $documento = new Factura();
                    $mensaje = "Cotización Creada  Exitosamente";
                    $documento->status_id = 1;
                }
                return redirect()->route('cotizaciones.index')->with([
                    "info" => $mensaje,
                ]);
            }
            
            
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('factura.index')->with([
                "danger" => "Factura no pudo ser creada!",
            ]);
        } 
        
    }
    public function descuento_inventario($factura_id){
        
        $detalle_factura = DetalleFactura::where('factura_id',$factura_id)->get();

        foreach ($detalle_factura as $key => $detalle) {
            $cantidad = 0;
            if ($detalle->estandar=='S') {
                $cantidad = $detalle->cantidad;
            } else {
                $cantidad = $detalle->cantidad;
            }
            $reserva = new InventarioReservado();
            $reserva->codigo = $detalle->codigo_articulo;
            $reserva->articulo = $detalle->nombre_articulo;
            $reserva->codigo_almacen = $detalle->codigo_almacen;
            $reserva->cantidad_reservada = $cantidad;
            $reserva->factura_id = $factura_id;
            $reserva->save();
            
        }

    }
    public function facturacion_virtual($monto_total){
        $user = User::find(Auth::id());
        $almacen = Almacen::where('id',$user->almacen_id)->first();
        return ($almacen->limite >= $monto_total)?$almacen->almacen_virtual:null;
    }
    public function control_emision($con_iva){
        $compania = ($con_iva)?'MP':'AB';
        $documento = ($con_iva)?'FTVA':'ABNE';
        $codigo_almacen = $this->get_codigo_almacen();
        $control = ControlEmision::where('COMP96',$compania)->where('SUCU96',$codigo_almacen)->where('CDDO96',$documento)->first();
        if($control){
            $control->NUDO96 = $control->NUDO96 + 1;
            $control->save();
            return $control->NUDO96;
        }
        return 0;
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

    public function editar_factura(Request $request){

        $factura = Factura::find($request->factura_id);
        $detalle = DetalleFactura::where('factura_id',$request->factura_id)->get();
        $almacenes = Almacen::all();
        $user = User::find(Auth::id());
        $tasas = Tasa::all();
        $servicios = [];
        $vendedores = User::where('almacen_id',$user->almacen_id)->get();
        $canales = CanalVenta::all();
        $tipos_movimiento =  TipoMovimientoCaja::all();
        $almacen_codigo = ($user->almacen->codigo)?$user->almacen->codigo:'01';
        $puntos_venta =  PuntoVenta::where('SUCU5V',$almacen_codigo)->get();
        $monedas =  Moneda::all();
        $tipos_tarjeta =  TipoTarjeta::all();
        $tipo_documento = TipoDocumento::find($factura->tipo_documento_id);
        
        return view('facturacion.update_factura', [
            'factura'=>$factura,
            'detalle'=>$detalle,
            'almacenes'=>$almacenes,
            'almacen_codigo'=>$almacen_codigo,
            'servicios'=>$servicios['data'] ?? [],
            'tasas'=>$tasas,
            'vendedores'=>$vendedores,
            'user'=>$user,
            'canales'=>$canales,
            'tipos_movimiento'=>$tipos_movimiento,
            'puntos_venta'=>$puntos_venta,
            'monedas'=>$monedas,
            'tipos_tarjeta'=>$tipos_tarjeta,
            'tipo_documento'=>$tipo_documento
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

