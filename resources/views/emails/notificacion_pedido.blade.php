@php
    $cotizacion = $data['cotizacion'];
    $articulos  = $data['articulos'];
    $pagos = $data['pagos'];
@endphp

<div><strong> Mensaje: </strong>{{ ucwords(strtolower($mensaje)) }}</div>


<div><strong> Cliente: </strong>{{ ucwords(strtolower($cotizacion->cliente->nombre)) }}</div>
<div><strong>RIF:</strong>{{$cotizacion->cliente->tipo_docuemnto.'-'.$cotizacion->cliente->numero_documento}}</div>
<div><strong> Vendedor: </strong>{{ ucwords(strtolower($cotizacion->vendedor->name)) }}</div>
<br>
<div class="row">
    <div class="table-responsive-sm" style="font-size: 10px;">
        <table style="border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 12px;">
            <thead>
                <tr style="background-color: #f0f0f0; border-bottom: 1px solid #ccc;">
                    <th style="padding: 10px; text-align: center;">ITEM</th>
                    <th style="padding: 10px; text-align: center;">CÓDIGO</th>
                    <th style="padding: 10px; text-align: center;">DESCRIPCIÓN</th>
                    <th style="padding: 10px; text-align: center;">CANTIDAD</th>
                    <th style="padding: 10px; text-align: center;">U/M</th>
                    <th style="padding: 10px; text-align: center;">KILOS</th>
                    <th style="padding: 10px; text-align: center;">PRECIO UNITARIO</th>
                    @if ($cotizacion->descuento_individual==1)
                    <th style="padding: 10px; text-align: center;">% DESCUENTO</th>
                    <th style="padding: 10px; text-align: center;">PRECIO DESCUENTO</th>
                    @endif
                    <th style="padding: 10px; text-align: center;">IMPORTE NETO</th>
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
                <tr style="border-bottom: 1px solid #ccc;">
                    <td style="padding: 10px; text-align: center;">{{$key}}</td>
                    <td style="padding: 10px; text-align: left;">{{$item->codigo_articulo}}</td>
                    <td style="padding: 10px; text-align: left;">{{$item->nombre_articulo}}</td>
                    <td style="padding: 10px; text-align: right;">{{$item->cantidad}}</td>
                    <td style="padding: 10px; text-align: center;">{{$item->unidad_medida}}</td>
                    <td style="padding: 10px; text-align: right;">{{number_format($peso,2,',','.')}}</td>
                    <td style="padding: 10px; text-align: right;">{{ number_format($precio,2,',','.') }}</td>
                    @if ($cotizacion->descuento_individual==1)
                    <td style="padding: 10px; text-align: right;">{{ round($porcentaje_descuento,2) }}%</td>
                    <td style="padding: 10px; text-align: right;">{{ number_format($precio_descuento,3,',','.') }}</td>
                    <td style="padding: 10px; text-align: right;">{{ number_format($item->cantidad*$precio_descuento,2,',','.') }}</td>
                    @else
                    <td style="padding: 10px; text-align: right;">{{ number_format($item->cantidad*$precio,2,',','.') }}</td>
                    @endif
                   
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                @php
                    $colspan = ($cotizacion->descuento_individual==1)?9:7;
                    $igtf = 0;
                    $valor_iva = ($cotizacion->con_iva==1)?1.16:1;
                @endphp
               <tr style="background-color: #f0f0f0; border-top: 1px solid #ccc;">
                    <td colspan="5" style="padding: 10px; text-align: right;"><strong>Total Kilos:</strong></td>
                    <td style="text-align: right;">{{ number_format($total_kilos,2,',','.') }}</td>
                    <td></td>
                    <td></td>
                    @if ($cotizacion->descuento_individual==1)
                    <td></td>
                    <td></td>
                    @endif
                    
                </tr>
                <tr>
                    <td colspan="{{$colspan}}" style="padding: 10px;text-align: right;"><strong>Sub-Total:</strong></td>
                    <td style="padding: 10px;text-align: right;">{{ number_format($total,2,',','.') }}</td>
                </tr>
                
                @if (isset($cotizacion->metodo->nombre)  && ($cotizacion->metodo->nombre == 'Efectivo Divisas') && $cotizacion->con_iva==1)
                    <td colspan="{{$colspan}}" style="padding: 10px;text-align: right;"><strong>IGTF(3%):</strong></td>
                    <td style="padding: 10px;text-align: right;">{{ number_format($total*0.03,2,',','.') }}</td>
                    @php
                        $igtf = $total*0.03;
                    @endphp
                    
                @endif
                @if ($cotizacion->porcentaje_descuento > 0)
                    @php
                        $descuento = $total * ($cotizacion->porcentaje_descuento / 100);
                        $iva = ($total-$descuento)*0.16;
                        $total = ($total-$descuento)*$valor_iva;
                    @endphp
                    <td colspan="{{$colspan}}" class="text-right"><strong>Descuento({{ round($cotizacion->porcentaje_descuento)}}%):</strong></td>
                    <td style="padding: 10px; text-align: right;">{{ number_format($descuento,2,',','.') }}</td>
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
                        <td colspan="{{$colspan}}" style="padding: 10px; text-align: right;"><strong>IVA (16%):</strong></td>
                        <td style="padding: 10px; text-align: right;">{{ number_format($iva,2,',','.') }}</td>
                    </tr>
                @endif
               
                <tr>
                    <td colspan="{{$colspan}}" style="padding: 10px; text-align: right;"><strong>Total:</strong></td>
                    <td style="padding: 10px; text-align: right;">{{ number_format($total,2,',','.') }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<div style="font-size: 12px;">
    
    <div><strong style="text-decoration: underline;">Observaciones:</strong></div>
    <div> <strong style="">Condiciones de Pago:</strong> {{ $cotizacion->forma ? $cotizacion->forma->nombre : '' }}</div>
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
   
</div>