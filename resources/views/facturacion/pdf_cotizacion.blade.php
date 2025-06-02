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
        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
            <div style="padding-bottom: 10px;">
                <span class="float-left"></span> 
                <span class="float-right"><strong style="text-decoration: underline">COTIZACIÓN N° {{$cotizacion->id}}</strong></span>
            </div>
           
            
        </header>

        <footer>
            <div class="margenes-laterales" style="font-size: 10px">
                <p style="margin-left:100px;">{{$cotizacion->vendedor->almacen->direccion}}</p>
                <p style="margin-left:0px;">Página web: www.maploca.com; Instagram: @maploca.ve; Facebook: maploca.ve </p>
            </div>
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
                            <strong>RIF: J-000244995</strong>
                        </p>
                        <p class="letra_pequena"> DIRECCION FISCAL: AV.PPAL.LOS CORTIJOS DE LOURDES.EDIF.MAPLOCA II. EDO. MIRANDA TLF 239-09-11</p>
                    </div>
                    
                </div>
                <br>
                
            
            </div>
            <div class="contenido contenedor margenes-laterales" style="height: 50px;">
                <div class="row" style="font-size: 12px !important; margin-bottom:0px;">
                    
                    <div class="col-sm-6" style="float: left;margin-left:-20px;">
                        <div><strong> Cliente: </strong>{{ ucwords(strtolower($cotizacion->cliente->nombre)) }}</div>
                            <div><strong>RIF:</strong>{{$cotizacion->cliente->tipo_documento.'-'.$cotizacion->cliente->numero_documento}}</div>
                    </div>
                    <div class="col-sm-6" style="float: right;">
                        @php
                        $enlace_vendedor = 'https://wa.me/58'.$cotizacion->vendedor->numero_celular;
                        $enlace_sucursal =  ($cotizacion->vendedor->almacen)?'https://wa.me/58'.$cotizacion->vendedor->almacen->whastapp:'';
                        $numero_vendedor = $cotizacion->vendedor->numero_celular;
                        $numero_sucursal = ($cotizacion->vendedor->almacen)?$cotizacion->vendedor->almacen->whastapp:'';
                         @endphp
                        <div><strong> Vendedor: </strong>{{ ucwords(strtolower($cotizacion->vendedor->name)) }}</div>
                        <div><strong> Contacto Vendedor: </strong><a href="{{$enlace_vendedor}}">{{ $numero_vendedor}}</a></div>
                        <div><strong> Fecha: </strong>{{date('d-m-Y')}}</div>
                    </div>
                </div>
            </div>
            <div class="margenes-laterales mb-3" style="height: 80px; margin-top:-40px;">
                <div class="row" style="font-size: 12px !important;">
                    <div class="col-sm-12" style="margin-left:-20px;">     
                        <div><strong> Dirección: </strong>{{$cotizacion->cliente->direccion}}</div>
                        <div><strong>Ciudad:</strong>{{ $cotizacion->cliente->ciudad->estado->nombre ?? '' }} {{ $cotizacion->cliente->ciudad->nombre ?? ''}}</div>
                    </div>
                </div>

            </div>
            <br>
            <div class="row" style="mb-4">
                <div class="contenido contenedor margenes-laterales" style="font-size: 12px !important;">
                    <p style="margin-bottom: 0;"> Distinguido Cliente:</p>
                    <p>
                        Tenemos el agrado de presentarle nuestra mejor oferta por lo reglones que a continuación detallamos:
                    </p>
                </div>
            </div>
            
            <div class="contenido contenedor margenes-laterales">
                <div class="row">
                    <div class="table-responsive-sm" style="font-size: 10px;">
                        <table class="table table-sm table-bordered" style="font-size: 10px;">
                            <thead>
                                <tr class="cabecera_tabla">
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">DESCRIPCIÓN</th>
                                    <th class="text-center">CANTIDAD</th>
                                    <th class="text-center">U/M</th>
                                    <th class="text-center">KILOS</th>
                                    <th class="text-center">PRECIO UNITARIO</th>
                                    @if ($cotizacion->descuento_individual==1)
                                    <th class="text-center">% DESCUENTO</th>
                                    <th class="text-center">PRECIO DESCUENTO</th>
                                    @endif
                                    <th class="text-center">IMPORTE NETO</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0; // Define la variable $total
                                    $total_kilos = 0; // Define la variable $total_kilos
                                @endphp
                                @foreach ($articulos as $key => $item)
                                @php
                                    $key++;
                                    $precio = round($item->precio, 2);
                                   
                                    if ($cotizacion->descuento_individual==1) {
                                        $porcentaje_descuento = $item->porcentaje_descuento;
                                        $precio_descuento = $precio * (1 - ($porcentaje_descuento / 100));
                                        $total += $item->cantidad * $precio * (1 - ($porcentaje_descuento / 100));
                                    } else {
                                        $total += $item->cantidad*$precio;
                                    }
                                    $peso = round($item->peso, 2);
                                    $total_kilos += $peso * $item->cantidad; // Acumula el peso total
                                @endphp
                                <tr>
                                    <td>{{$key}}</td>
                                    <td>{{$item->nombre_articulo}}</td>
                                    <td class="columna-derecha">{{round($item->cantidad,2)}}</td>
                                    <td>{{$item->unidad_medida}}</td>
                                    <td class="columna-derecha">{{number_format($peso,2,',','.')}}</td>
                                    <td class="columna-derecha">{{ number_format($precio,2,',','.') }}</td>
                                    @if ($cotizacion->descuento_individual==1)
                                    <td class="columna-derecha">{{ round($porcentaje_descuento,2) }}%</td>
                                    <td class="columna-derecha">{{ number_format($precio_descuento,2,',','.') }}</td>
                                    <td class="columna-derecha">{{ number_format($item->cantidad*$precio_descuento,2,',','.') }}</td>
                                    @else
                                    <td class="columna-derecha">{{ number_format($item->cantidad*$precio,2,',','.') }}</td>
                                    @endif
                                   
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                @php
                                    $colspan = ($cotizacion->descuento_individual==1)?8:6;
                                    $igtf = 0;
                                    $valor_iva = ($cotizacion->con_iva==1)?1.16:1;
                                @endphp
                                <tr>
                                    <td colspan="4" class="text-right"><strong>Total Kilos:</strong></td>
                                    <td class="columna-derecha">{{ number_format($total_kilos,2,',','.') }}</td>
                                    <td></td>
                                    <td></td>
                                    @if ($cotizacion->descuento_individual==1)
                                    <td></td>
                                    <td></td>
                                    @endif
                                </tr>
                                <tr>
                                    <td colspan="{{$colspan}}" class="text-right"><strong>Sub-Total:</strong></td>
                                    <td class="columna-derecha">{{ number_format($total,2,',','.') }}</td>
                                </tr>
                            
                                @if (count($pagos))
                                @foreach ($pagos as $pago)
                                    @if (isset($pago->metodo->nombre) && $pago->metodo->nombre=='Efectivo Divisas' && $cotizacion->con_iva==1)                    
                                    <tr>
                                        <td colspan="{{$colspan}}" class="text-right"><strong>IGTF(3%):</strong></td>
                                        <td class="columna-derecha">{{ number_format($pago->monto*0.03,2,',','.') }}</td>
                                        @php
                                            $igtf = $pago->monto*0.03;
                                        @endphp
                                    </tr>  
                                    @endif  
                                @endforeach     
                            @endif
                                @if (isset($cotizacion->metodo->nombre)  && ($cotizacion->metodo->nombre == 'Efectivo Divisas') && $cotizacion->con_iva==1)
                                <tr>
                                    <td colspan="{{$colspan}}" class="text-right"><strong>IGTF(3%):</strong></td>
                                    <td class="columna-derecha">{{ number_format($total*0.03,2,',','.') }}</td>
                                    @php
                                        $igtf = $total*0.03;
                                    @endphp
                                </tr>  
                                @endif
                                @if ($cotizacion->porcentaje_descuento > 0)
                                    @php
                                        $descuento = $total * ($cotizacion->porcentaje_descuento / 100);
                                        $iva = ($total-$descuento)*0.16;
                                        $total = ($total-$descuento)*$valor_iva;
                                        $total = $total + $igtf;
                                    @endphp
                                <tr>
                                    <td colspan="{{$colspan}}" class="text-right"><strong>Descuento({{ round($cotizacion->porcentaje_descuento)}}%):</strong></td>
                                    <td class="columna-derecha">{{ number_format($descuento,2,',','.') }}</td>
                                </tr>
                                @else
                                    @php
                                        $descuento = $total * ($cotizacion->porcentaje_descuento / 100);
                                        $iva = ($total)*0.16;
                                        $total = ($total-$descuento)*$valor_iva;
                                        $total = $total + $igtf;
                                    @endphp
                                @endif
                                @if ($cotizacion->con_iva==1)
                                    <tr>
                                        <td colspan="{{$colspan}}" class="text-right"><strong>IVA (16%):</strong></td>
                                        <td class="columna-derecha">{{ number_format($iva,2,',','.') }}</td>
                                    </tr>
                                @endif
                               
                                <tr>
                                    <td colspan="{{$colspan}}" class="text-right"><strong>Total:</strong></td>
                                    <td class="columna-derecha">{{ number_format($total,2,',','.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="contenido contenedor margenes-laterales">
                <div><strong style="text-decoration: underline;">Observaciones:</strong></div>
                <div> <strong style="">Condiciones de Pago:</strong> {{ $cotizacion->forma ? $cotizacion->forma->nombre : '' }}</div>
                @if (($cotizacion->contribuyente_especial!=0))
                <div> <strong>Tipo de Cliente:</strong>Contribuyente Especial</div>    
                @endif
                @if (($cotizacion->dias_credito!=0))
                <div> <strong style="">Días de Crédito:</strong> {{ $cotizacion->dias_credito }}</div>    
                @endif
                
                @if (count($pagos))
                    @foreach ($pagos as $pago)
                        <div> <strong style="">Método de Pago:</strong> {{ $pago->metodo->nombre ?? '' }} - <strong>Monto:</strong> {{number_format($pago->monto,2,',','.') ?? ''}}</div>    
                    @endforeach     
                @else
                    <div> <strong style="">Método de Pago:</strong> {{ $cotizacion->metodo ? $cotizacion->metodo->nombre : '' }}</div>
                @endif
               
                
                @if ($cotizacion->notas!="")
                    <div> <strong style="">Notas:</strong> {{ $cotizacion->notas}}</div>
                @endif
                <div><strong style="">Validez de la oferta:</strong>1 día</div>
                <div><span>Material Sujeto a Stock</span></div>
            </div>

            <div class="contenido contenedor margenes-laterales">
                <span>Precios sujetos a cambios sin previo aviso según nuestros proveedores</span>
                <span>Descarga del material por cuenta del cliente</span>
            </div>

            
        </main>
    </body>
</html>