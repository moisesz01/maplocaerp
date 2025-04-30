@include('css_pdf')
@php
    date_default_timezone_set("America/Caracas");
@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <style>
            * {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font: inherit;
            vertical-align: baseline;
            }
            @page {
                margin: 100px 25px;
            }

            header {
                position: fixed;
                top: 0px;
                left: 0px;
                right: 0px;
                height: 35px;
                font-size: 15px !important;
                background-color: #A3A7AA;
                color: white;
                line-height: 30px;
                margin-left:20px;
                margin-right:20px;
            }

            footer {
                position: fixed;
                bottom: 20px;
                left: 0px;
                right: 0px;
                font-size: 12px !important;
                text-align: center;
            }
            .cabecera_tabla{
                background-color: #A3A7AA;
                color: #ffffff;
            }
            .columna {
                text-align: center;
                vertical-align: middle;
                justify-content: center;
                align-items: center;
            }
            .contenido{
                font-size: 12px !important;
            }
            .letra_pequena{
                font-size: 10px !important;
            }
            .margenes-laterales{
                margin-left:50px;
                margin-right:50px;
            }
            .margenes_up_down{
                margin-top: 40px;
                margin-bottom: 40px;
            }
            .columna-derecha {
                text-align: right;
            }
            .schedule {
                width: 100%;
                border-collapse: collapse;
            }
            .schedule th,
            .schedule td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
            .schedule th {
                background-color: #f2f2f2;
                color: black;
            }
            .day-header {
                background-color: #e0e0e0;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
            <div style="padding-bottom: 10px;">
                <span class="float-left"></span> 
                <span class="float-right">
                    <strong style="text-decoration: underline">
                        Planificación de 
                        @if ($user instanceof \App\Models\User)
                        {{ $user->name }} 
                        @else
                        {{ implode(', ', $user->pluck('name')->toArray()) }}

                        @endif
                    </strong>
                </span>
            </div>
           
            
        </header>

        <footer>
           
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            <div class="contenedor margenes-laterales margenes_up_down" style="height: 50px;">
                <div class="row">
                    <div class="col-sm-3 float-left">
                      
                            <div style="margin-left:5px;">
                                <img class="img logo_cotizacion" style="object-fit: cover; width: 120px; display: block;" src="{{URL::asset('/imgs/logo_maploca_slogan.png')}}" />
                            </div>
                            
                        
                    </div>
                    <div class="col-sm-9 float-right mt-3" style="">
                        <p class="letra_pequena">
                            DIRECCION FISCAL: AV.PPAL.LOS CORTIJOS DE LOURDES.EDIF.MAPLOCA II.
                        </p>
                        <p class="letra_pequena"> EDO. MIRANDA TLF 239-09-11</p>
                    </div>
                    
                </div>
                <br>
                
            
            </div>
            @php
                setlocale(LC_TIME, 'es_ES.UTF-8');
                $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            @endphp
            <div class="contenido contenedor margenes-laterales" style="height: 50px;">
                <div class="row" style="font-size: 12px !important; margin-bottom:0px;">
                    <table class="schedule">
                        <thead>
                            <tr>
                                <th class="text-center"><strong>Hora</strong></th>
                                <th class="text-center"><strong>Descripción del Evento</strong></th>
                                <th class="text-center"><strong>Cliente</strong></th>
                                <th class="text-center"><strong>Vendedor</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fechas_unicas as $fecha)
                            <tr class="day-header">
                                <td colspan="4">{{ $dias[strftime("%w", strtotime($fecha))] }} - {{ strftime("%d", strtotime($fecha)) }} de {{ $meses[strftime("%m", strtotime($fecha)) - 1] }} de {{ strftime("%Y", strtotime($fecha)) }}</td>
                            </tr>
                            @foreach ($planificaciones as $planificacion)
                            @if (date('Y-m-d', strtotime($planificacion->fecha_inicio)) == $fecha)
                                <tr>
                                    <td>{{ date('H:i', strtotime($planificacion->fecha_inicio)) }} - {{ date('H:i', strtotime($planificacion->fecha_fin)) }}</td>
                                    @php
                                        $accion = (isset($planificacion->accion->tipo_accion->nombre))?$planificacion->accion->tipo_accion->nombre:'';
                                        $accion.= isset($planificacion->accion->nombre)?' - '.$planificacion->accion->nombre: '';
                                    @endphp
                                    <td>{{ $accion }}</td>
                                    <td>{{ $planificacion->cliente->nombre ?? '' }}</td>
                                    <td>{{ $planificacion->vendedor->name ?? '' }}</td>
                                </tr>
                            @endif
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>  
                    
                </div>
            </div>
            
        </main>
    </body>
</html>
