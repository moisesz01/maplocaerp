<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccionVendedor;
use App\Models\Cliente;
use App\Models\Planificacion;
use App\Models\TipoAccionVendedor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PlanificacionController extends Controller
{
    public function calendario(){
        
        $tipo_acciones = TipoAccionVendedor::all();
        $usuarios = User::orderBy('name', 'asc')->get();
        return view('planificacion.calendario',[
            //'eventos' => $eventos,
            'tipo_acciones' => $tipo_acciones,
            'usuarios' => $usuarios
        ]);
    }
    public function obtener_planificaciones(Request $request){
        // Obtener los parámetros start y end del request
        $startOfWeek = \Carbon\Carbon::parse($request->start)->startOfDay(); // Primera hora del día de inicio
        $endOfWeek = \Carbon\Carbon::parse($request->end)->endOfDay(); // Última hora del día de fin
    
        // Si el user_id no está vacío, filtrar por ese user_id
        if($request->user_id && is_array($request->user_id) && count($request->user_id) > 0){
            $planificaciones = Planificacion::whereIn('user_id', $request->user_id)
                ->whereBetween('fecha_inicio', [$startOfWeek, $endOfWeek])->get();
        } else {
            // Si no, verificar los permisos del usuario autenticado
            if (Auth::user()->can('Ver planificaciones de todos los usuarios')) {
                // Si el usuario tiene permiso para ver planificaciones de todos, se obtienen todas las planificaciones en el rango
                $planificaciones = Planificacion::whereBetween('fecha_inicio', [$startOfWeek, $endOfWeek])->get();
            } else {
                // Si no, solo se obtienen las planificaciones del usuario autenticado
                $planificaciones = Planificacion::where('user_id', Auth::id())
                    ->whereBetween('fecha_inicio', [$startOfWeek, $endOfWeek])->get();
            }
        }
    
        $eventos = array();
        foreach ($planificaciones as $key => $plan) {
            $actividad = isset($plan->accion->tipo_accion->nombre) ? $plan->accion->tipo_accion->nombre : '';
            $titulo = $plan->cliente->nombre . "-" . $actividad;
            $eventos[] = [
                'id' => $plan->id,
                'title' => $titulo,
                'start' => $plan->fecha_inicio,
                'end' => $plan->fecha_fin
            ];
        }
        return response()->json($eventos);
    }
    public function guardar_actividad(Request $request){
        
        if($request->event_id!=0){
            $planificacion = Planificacion::find($request->event_id);
        }else{
            $planificacion = new Planificacion;
        }
       
        $planificacion->actividad = $request->actividad;
        $planificacion->fecha_inicio = $request->fecha_inicio;
        $duracion_horas = floatval($request->duracion);
        $fecha_inicio_timestamp = strtotime($request->fecha_inicio);
        $fecha_fin_timestamp = $fecha_inicio_timestamp + ($duracion_horas * 60 * 60);
        $fecha_fin = date('Y-m-d H:i:s', $fecha_fin_timestamp);
        $planificacion->fecha_fin = $fecha_fin;
        $planificacion->user_id =  Auth::id(); 
        $planificacion->cliente_id =  $request->cliente_id;
        $planificacion->accion_vendedor_id = $request->accion_vendedor_id;
        $planificacion->save();
        $data = [
            'planificacion'=>$planificacion,
            'cliente' => $planificacion->cliente->nombre,
            'accion' => $planificacion->accion->nombre,
            'tipo_accion' => $planificacion->accion->tipo_accion->nombre

        ];
        return response()->json($data);
    }
    public function actualizar_actividad(Request $request){
       
        $planificacion = Planificacion::find($request->id);
        $planificacion->fecha_inicio = date('Y-m-d H:i:s',strtotime($request->fecha_inicio)) ;
        $planificacion->fecha_fin = date('Y-m-d H:i:s',strtotime($request->fecha_fin)) ;
        $planificacion->save();
        return response()->json($planificacion);
    }
    
    public function numeroDocumentoAutocomplete(Request $request)
    {
        $search = $request->input('term');
        $documentos = Cliente::where('numero_documento', 'like', "%{$search}%")
        ->OrWhere('nombre','LIKE', '%' . $search . '%')
        ->get();

        $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
        foreach ($documentos as $documento) {
            $output .= '
            <li><a  data-documento="'.$documento->tipo_documento.'-'.$documento->numero_documento.'" data-id="'.$documento->id.'" data-nombre="'.$documento->nombre.'" data-ciudad="'. (isset($documento->ciudad->nombre)?$documento->ciudad->nombre:'').'" style="text-decoration:none;color:black;" href="#">'.$documento->numero_documento.'-'.$documento->nombre.'</a></li>
            ';
        }
        return $output;
    }
    public function obtener_acciones(Request $request)
    {
        $accionVendedores = AccionVendedor::where('tipo_accion_id', $request->tipo_accion_id)->get();
        return response()->json($accionVendedores);
    }
    public function detalle_planificacion(Request $request){
        $planificacion = Planificacion::find($request->id);
        $data = [
            'planificacion'=>$planificacion,
            'cliente' => $planificacion->cliente->nombre,
            'ciudad' => $planificacion->cliente->ciudad->nombre ?? '',
            'accion' => $planificacion->accion->nombre,
            'tipo_accion_id' => $planificacion->accion->tipo_accion_id,
            'tipo_accion'=>$planificacion->accion->tipo_accion->nombre ?? ''
        ];
        return response()->json($data);
    }
    public function eliminar(Request $request){
        $planificacion = Planificacion::find($request->event_id);
        if ($planificacion) {
            $planificacion->delete();
            return response()->json(['message' => 'Planificación eliminada con éxito']);
        } else {
            return response()->json(['message' => 'Planificación no encontrada'], 404);
        }
    }
    public function exportar_planificacion(Request $request){
        
        
        
        $start = now()->startOfWeek(); //opcion se semana
        $end= now()->endOfWeek(); //opcion se semana
        switch ($request->opcion_fecha) {
            case 'm':
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                break;
            case 'd':
                $start = now();
                $end = now();
                break;
            case 'n':
                $range = explode(" - ", $request->get('daterange'));
                $start = date('Y-m-d', strtotime(trim($range[0])));
                $end = date('Y-m-d 23:59:59', strtotime(trim($range[1]))); 
                break;
        }
        if ($request->user_id && $request->user_id !== '' && $request->user_id != 'undefined') {
            // Separar los user_id por coma y convertirlos a un array
            $users = explode(",", $request->user_id);
            
            // Filtrar las planificaciones por el array de user_id
            $planificaciones = Planificacion::whereIn('user_id', $users)
            ->whereBetween('fecha_inicio', [$start, $end]);
            
            // Obtener información del usuario
            $user = User::whereIn('id', $users)->get();
        }else{
            if (Auth::user()->can('Ver planificaciones de todos los usuarios')) {
                // Si el usuario tiene permiso para ver planificaciones de todos, se obtienen todas las planificaciones en el rango
                $planificaciones = Planificacion::whereBetween('fecha_inicio', [$start, $end]);
            } else {
                // Si no, solo se obtienen las planificaciones del usuario autenticado
                $planificaciones = Planificacion::where('user_id', Auth::id())
                    ->whereBetween('fecha_inicio', [$start, $end]);
            }
            $user = User::find(intval(Auth::id()));echo "puta madre";
        }
    
        $planificaciones = $planificaciones->orderBy('fecha_inicio', 'asc')->get();
        $fechas_unicas = $planificaciones->pluck('fecha_inicio')
        ->map(function ($dateString) {
            $date = \Carbon\Carbon::parse($dateString);
            return $date->format('Y-m-d');
        })
        ->all();
        $pdf = \PDF::loadView('planificacion.pdf_calendario', [
            'planificaciones' => $planificaciones,
            'user' => $user,
            'fechas_unicas' => array_unique($fechas_unicas),
        ]);
        return $pdf->download('Planificación.pdf');
        
        
    }
    public function exportar_excel_planificacion(Request $request){
        date_default_timezone_set("America/Caracas");
        setlocale(LC_TIME, 'es_ES.UTF-8');
        
        $start = now()->startOfWeek(); //opcion se semana
        $end= now()->endOfWeek(); //opcion se semana
        switch ($request->opcion_fecha) {
            case 'm':
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                break;
            case 'd':
                $start = now();
                $end = now();
                break;
            case 'n':
                $range = explode(" - ", $request->get('daterange'));
                $start = date('Y-m-d', strtotime(trim($range[0])));
                $end = date('Y-m-d 23:59:59', strtotime(trim($range[1]))); 
                break;
        }
       
        if ($request->user_id && $request->user_id !== '' && $request->user_id != 'undefined') {
            // Separar los user_id por coma y convertirlos a un array
            $users = explode(",", $request->user_id);
            
            // Filtrar las planificaciones por el array de user_id
            $planificaciones = Planificacion::whereIn('user_id', $users)
            ->whereBetween('fecha_inicio', [$start, $end]);
            
            // Obtener información del usuario
            $user = User::whereIn('id', $users)->get();
        }else{
            if (Auth::user()->can('Ver planificaciones de todos los usuarios')) {
                // Si el usuario tiene permiso para ver planificaciones de todos, se obtienen todas las planificaciones en el rango
                $planificaciones = Planificacion::whereBetween('fecha_inicio', [$start, $end]);
            } else {
                // Si no, solo se obtienen las planificaciones del usuario autenticado
                $planificaciones = Planificacion::where('user_id', Auth::id())
                    ->whereBetween('fecha_inicio', [$start, $end]);
            }
            $user = User::find(intval(Auth::id()));
        }
    
        $planificaciones = $planificaciones->orderBy('fecha_inicio', 'asc')->get();
        $fechas_unicas = $planificaciones->pluck('fecha_inicio')
        ->map(function ($dateString) {
            $date = \Carbon\Carbon::parse($dateString);
            return $date->format('Y-m-d');
        })
        ->all(); 
        $fechas_unicas = array_unique($fechas_unicas);
         
        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']; 
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $header = [
            'A' => 'Hora',
            'B' => 'Descripción del Evento',
            'C' => 'Cliente',
            'D' => 'Vendedor'
        ];
    
        foreach ($header as $key => $campo) {
            $sheet->setCellValue($key . '1', $campo);
        }
        if (!empty($planificaciones)) {
            $i = 2; // Iniciar en la fila 2 para los datos
            foreach ($fechas_unicas as $key => $fecha) {
                $valor = $dias[strftime("%w", strtotime($fecha))]." - ".strftime("%d", strtotime($fecha))." de ".$meses[strftime("%m", strtotime($fecha)) - 1].
                " de ".strftime("%Y", strtotime($fecha));
                
                // Establecer la fila con el valor
                $sheet->setCellValue('A' . $i, $valor);
                $sheet->mergeCells('A' . $i . ':D' . $i); // Unir las columnas A, B, C y D
                $sheet->getStyle('A' . $i)->getFont()->setBold(true); // Poner en negrita
                $sheet->getStyle('A' . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $sheet->getStyle('A' . $i)->getFill()->getStartColor()->setARGB('D3D3D3'); // Color gris claro
    
                $i++; // Incrementar la fila para los siguientes datos
                
                foreach ($planificaciones as $planificacion) {
                    if ((date('Y-m-d', strtotime($planificacion->fecha_inicio)) == $fecha)) {
                        $sheet->setCellValue('A' . $i, date('H:i', strtotime($planificacion->fecha_inicio)). " - ".date('H:i', strtotime($planificacion->fecha_fin)));
                        $accion = (isset($planificacion->accion->tipo_accion->nombre))?$planificacion->accion->tipo_accion->nombre:'';
                        $accion.= isset($planificacion->accion->nombre)? " - ".$planificacion->accion->nombre: '';
                        $sheet->setCellValue('B' . $i, $accion);
                        $sheet->setCellValue('C' . $i, $planificacion->cliente->nombre ?? '' );
                        $sheet->setCellValue('D' . $i, $planificacion->vendedor->name ?? '' );
                        $i++; // Incrementar la fila para los siguientes datos
                    }
                } 
            }
        }
    
               
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="planificaciones.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    
        
    }
}
