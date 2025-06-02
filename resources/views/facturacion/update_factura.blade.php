@extends('adminlte::page')

@section('title', 'Crear '.$tipo_documento->nombre)

@section('content_header')
<div class="breadcrumbs">
    {{ Breadcrumbs::render('pedido.crear') }}
</div>
    <h1>Crear {{$tipo_documento->nombre}}</h1>
@stop


@section('content')

@include('right_sidebar')
@include('modals')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <form method="POST" action="{{route('factura.guardar')}}" enctype="multipart/form-data">
                    @csrf

                <input type="hidden" name="tipo_documento_id" id="tipo_documento_id" value="{{ $tipo_documento->id }}"> 
                <div class="row">
                    <div class="col-sm-12">
                        <label for="cliente">Cliente:</label>
                        <input type="hidden" name="cliente_id" id="cliente_id" value="{{ $factura->cliente_id  }}">
                        <input type="text" id="nombre_cliente" class="form-control" value="{{  $factura->cliente->nombre }}"  disabled>
                    </div>
                </div>
                <div class="row flex flex-nowrap">
                    <div class="col-sm-6">
                        <label for="rif">RIF</label>
                        <input type="text" id="rif" class="form-control" value="{{  $factura->cliente->tipo_documento.'-'.$factura->cliente->numero_documento }}"  disabled>
                    </div>
                    <div class="col-sm-6">
                        <label for="rif">Dirección</label>
                        <input type="text" id="direccion" disabled class="form-control" value="{{  $factura->cliente->direccion }}">
                    </div>
                </div>
                <div class="box-header with-border pt-2">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#search-product-modal" id="agregar_producto">Agregar producto</button>
                    {{-- <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#product-modal" id="agregar_producto_especial">Agregar producto Especial</button> --}}
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#servicio-modal" id="agregar_servicio">Agregar Servicio</button>
                    
                </div>
                
                <div class="box-body mt-2">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#productos-tab">Productos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#pago-tab">Opciones</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#metodos-tab">Métodos de Pago</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="productos-tab">
                            <div class="table-responsive">
                                <table id="items-table" class="table table-sm table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Artículo</th>
                                            <th>Unidad de Medida</th>
                                            <th>Cant.</th>
                                            <th>Precio</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="items-tbody">
                                        @foreach ($detalle as $articulo)
                                            <tr class="fila">
                                                <input type="hidden" value="{{$articulo->estandar}}" name="estandar[]">
                                                <input type="hidden" value="{{$articulo->tipo}}" name="tipo[]">
                                                <input type="hidden" value="{{$articulo->codigo_articulo}}" name="producto_id[]">
                                                <input type="hidden" value="{{$articulo->nombre_articulo}}" name="producto[]">
                                                <input type="hidden" value="{{$articulo->peso}}" name="peso[]" class="peso">
                                                <input type="hidden" value="{{$articulo->unidad_medida}}" name="unidad_medida[]">
                                                <input type="hidden" value="{{$articulo->unidad_conversion}}" name="unidad_venta[]">
                                                <input type="hidden" value="{{$articulo->largo}}" name="largo[]">
                                                <input type="hidden" value="{{round($articulo->precio,2)}}" name="precio_bk[]" class="precio_bk">
                                                <input type="hidden" value="0" name="descuento_producto[]" class="descuento_producto">
                                                <td>{{$articulo->codigo_articulo."-".$articulo->nombre_articulo}}</td>
                                                <td>{{$articulo->unidad_conversion}}</td>
                                                <td><input type="number" value="{{$articulo->cantidad}}" class="form-control cantidad" name="cantidad[]" required=""></td>
                                                <td><input type="number" step="any" class="form-control precio" value="{{round($articulo->precio,2)}}" name="precio[]" required=""></td>
                                                <td class="total_fila">{{$articulo->precio * $articulo->cantidad}}</td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="fas fa-trash-alt"></i></button>
                                                    <button type="button" style="margin-top: 10px;" class="btn btn-primary btn-sm" onclick="mostraPorcentaje(this)"><i class="fas fa-percentage"></i></button>
                                                    <button type="button" style="margin-top: 10px;padding-left: 12px;" class="btn btn-success btn-sm" onclick="verPrecio(this)"><i class="fas fa-dollar-sign"></i></button>
                                                </td>
                                            </tr>    
                                        @endforeach
                                        
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3">SubTotal:</th>
                                            <th id="subtotal-table">0.00</th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th colspan="3">Iva:</th>
                                            <th id="iva-table">0.00</th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th colspan="3">IGTF:</th>
                                            <th id="igtf-table">0.00</th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            <th colspan="3">Total:</th>
                                            <th id="total-table">0.00</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label for="descuento">Descuento (%):</label>
                                        <input type="number" id="descuento" name="descuento" class="form-control" value="0" min="0" max="100">
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="peso_total">Peso Total:</label>
                                        <input type="number" id="peso_total" name="peso_total" class="form-control" value="0" min="0" disabled>
                                    </div>
                                </div>
                                
                            </div>
                            
                        </div>

                        <div class="tab-pane" id="pago-tab">
                            <div class="form-row">
                               
                                <div class="form-group col-md-6">
                                    <label for="vendedor">Vendedor</label>
                                        <select class="form-control" id="vendedor_id" name="vendedor_id" required>
                                            @foreach ($vendedores as $vendedor)
                                                <option value="{{$vendedor->id}}" {{ ($factura->vendedor_id==$vendedor->id)?'selected':'' }}>{{$vendedor->name}}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="vendedor">Vendedor Secundario</label>
                                        <select class="form-control" id="vendedor_secundario_id" name="vendedor_secundario_id" required>
                                            @foreach ($vendedores as $vendedor)
                                                <option value="{{$vendedor->id}}" {{ ($factura->vendedor_secundario_id==$vendedor->id)?'selected':'' }}>{{$vendedor->name}}</option>
                                            @endforeach
                                        </select>
                                </div>
                                
                            </div>
                            <div class="form-row">
                               
                                <div class="form-group col-md-6">
                                    <label for="moneda_extranjera">Moneda Extranjera</label>
                                        <select class="form-control" id="moneda_extranjera" name="moneda_extranjera" required>
                                            <option value=""  selected disabled>Seleccione</option>
                                            @foreach ($tasas as $tasa)
                                                <option data-tasa="{{$tasa->TASA0B}}" value="{{$tasa->MOEX0B}}">{{$tasa->MOEX0B}}</option>
                                            @endforeach
                                                <option data-tasa=1 value="TEC">TEC</option>
                                        </select>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tasa_temp">Tasa</label>
                                        <input type="number" name="tasa_temp" id="tasa_temp" class="form-control" value="1" step="any" readonly>
                                        <input type="hidden" name="tasa" id="tasa" value="1">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="canal_venta">Canal de Venta</label>
                                    <select class="form-control" id="canal_venta" name="canal_venta" required>
                                        @foreach ($canales as $canal)
                                            <option value="{{$canal->CVTA32}}">{{$canal->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="forma_pago">Forma de Pago</label>
                                   <select class="form-control" id="forma_pago" name="forma_pago" required>
                                           <option value="Contado">Contado</option>    
                                           <option value="Crédito">Crédito</option>
                                   </select>
                               </div>
                            </div>
                            <div class="row">
                                
                               <div class="form-group col-md-6" id="credit-days-container" style="display: none;">
                                <label for="credit_days">Días de Crédito</label>
                                <input type="number" id="dias_credito" name="dias_credito" class="form-control" min="0">
                            </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="notas">Notas: </label>
                                    <textarea class="form-control" id="notas" name="notas" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="notas">Dirección de Envío: </label>
                                    <textarea class="form-control" id="direccion_alternativa" name="direccion_alternativa" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="con_iva">Incluir IVA:</label>
                                    <input type="checkbox" id="con_iva" class="form-comtrol" name="con_iva" value="1" checked>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="contribuyente_especial">Contribuyente Especial:</label>
                                    <input type="checkbox" id="contribuyente_especial" class="form-comtrol" name="contribuyente_especial" value="1">
                                </div>
                            </div>

                          
                            
                        </div>
                        <div class="tab-pane" id="metodos-tab">
                            <div class="row mt-2 mb-2">
                                <div class="col-sm-4 text-center">
                                    <label for="importe_me">Importe Total ME:</label>
                                    <input type="number" name="importe_me" id="importe_me" min="0" value="0" disabled class="d-block mx-auto" style="text-align: left">
                                </div>
                                <div class="col-sm-4 text-center">
                                    <label for="importe_pagado_me">Importe Pagado ME:</label>
                                    <input type="number" name="importe_pagado_me" id="importe_pagado_me" value="0" disabled class="d-block mx-auto" style="text-align: left">
                                </div>
                                <div class="col-sm-4 text-center">
                                    <label for="restante_me">Restante ME:</label>
                                    <input type="number" name="restante_me" id="restante_me" value="0" disabled class="d-block mx-auto" style="text-align: left">
                                </div>
                            </div>
                            <div class="row mt-2 mb-2">
                                <div class="col-sm-4 text-center">
                                    <label for="importe_me">Importe Total ML:</label>
                                    <input type="number" name="importe_ml" id="importe_ml" min="0" value="0" disabled class="d-block mx-auto" style="text-align: left">
                                </div>
                                <div class="col-sm-4 text-center">
                                    <label for="importe_pagado_ml">Importe Pagado ML:</label>
                                    <input type="number" name="importe_pagado_ml" id="importe_pagado_ml" value="0" disabled class="d-block mx-auto" style="text-align: left">
                                </div>
                                <div class="col-sm-4 text-center">
                                    <label for="restante_ml">Restante ML:</label>
                                    <input type="number" name="restante_ml" id="restante_ml" value="0" disabled class="d-block mx-auto" style="text-align: left">
                                </div>
                            </div>
                            <table class="table  table-striped table-sm">
                              <thead>
                                <tr>
                                  <th>Tipo de Movimiento</th>
                                  <th>Importe ME</th>
                                  <th>Importe ML</th>
                                  <th>Tasa</th>
                                  <th>Moneda</th>
                                  <th>Tipo</th>
                                </tr>
                              </thead>
                              <tbody id="movimientos-table">
                                {{-- Aquí se agregarán las filas dinámicamente --}}
                                <!-- Aquí se pueden agregar filas con datos -->
                                <tr>
                                  <td colspan="6">Sin pagos realizados.</td>
                                  
                                </tr>
                              </tbody>
                            </table>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="tipo_movimiento">Tipo de Movimiento</label>
                                    <select class="form-control" id="tipo_movimiento" name="tipo_movimiento">
                                        <option value="" selected disabled>Seleccione una opción</option>
                                        @foreach ($tipos_movimiento as $movimiento)
                                            <option value="{{$movimiento->id}}">{{$movimiento->DESC5I}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label for="moneda">Moneda</label>
                                    <select class="form-control" id="moneda" name="moneda">
                                        <option value="" selected disabled>Seleccione una opción</option>
                                        @foreach ($monedas as $moneda)
                                            <option value="{{$moneda->id}}">{{$moneda->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label for="tasa">Tasa:</label>
                                    <input type="number" name="tasa_pago" id="tasa_pago" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="punto_venta">Punto de Venta</label>
                                    <select class="form-control" id="punto_venta" name="punto_venta">
                                        <option value="" selected disabled>Seleccione una opción</option>
                                        @foreach ($puntos_venta as $punto_venta)
                                            <option data-cp="{{$punto_venta->COMP5V}}" value="{{$punto_venta->id}}">{{$punto_venta->PVTA5V." - ".$punto_venta->COMP5V}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label for="moneda">Tipos de Tarjeta</label>
                                    <select class="form-control" id="tipo_tarjeta" name="tipo_tarjeta">
                                        <option value="" selected disabled>Seleccione una opción</option>
                                        @foreach ($tipos_tarjeta as $tarjeta)
                                            <option value="{{$tarjeta->id}}">{{$tarjeta->DEST5S}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label for="transaccion">Número de Transacción:</label>
                                    <input type="number" name="transaccion" id="transaccion" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                
                                <div class="col-sm-4">
                                    <label for="monto">Monto:</label>
                                    <input type="number" name="monto" id="monto" class="form-control">
                                </div>
                                <div class="col-sm-4">
                                    <label for="monto_me">Importe ME:</label>
                                    <input type="number" name="monto_me" id="monto_me" class="form-control" disabled>
                                </div>
                                <div class="col-sm-4">
                                    <label for="monto_ml">Importe ML:</label>
                                    <input type="number" name="monto_ml" id="monto_ml" class="form-control" disabled>
                                </div>
                            </div>
                            <button type="button" id="agregar-pago" class="mb-4 mt-4"><i class="fas fa-plus-circle"></i>Agregar Pago</button>
                          </div>
                         
                          
                    </div>
                </div>
                <button type="submit" id="submit-button" class="btn btn-success">Guardar</button>
            </form>
            </div>
        </div>
    </div>

    @include('facturacion.modal_producto',[
         'almacenes'=>$almacenes,
         'almacen_codigo'=>$almacen_codigo,
    ])
    @include('facturacion.modal_producto_especial',[
        'almacenes'=>$almacenes,
        'almacen_codigo'=>$almacen_codigo,
   ])
   @include('facturacion.modal_servicio',[
        'servicios'=>$servicios
    ])
<!-- Modal para ingresar porcentaje de descuento -->
<div class="modal fade" id="descuento-modal" tabindex="-1" role="dialog" aria-labelledby="descuento-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="descuento-modal-label">Ingresar porcentaje de descuento</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="form-group">
              <label for="descuento-input">Porcentaje de descuento</label>
              <input type="number" class="form-control descuento-input" placeholder="Porcentaje de descuento">
            </div>
            <div class="form-group">
              <p class="descuento-preview"></p>
            </div>
            <button type="button" class="btn btn-primary" name="aplicar" id="aplicar">Aplicar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ (Config::get('app.env')=='local')?asset('css/maploca_styles.css'):secure_asset('css/maploca_styles.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
@stop

@section('js')
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap.min.js"></script>
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="{{ (Config::get('app.env')=='local')?asset('js/factura.js'):secure_asset('js/factura.js') }}"></script>
    
    <script>
        $(document).ready(function() {
            let formas_vueltos = {!! json_encode($tipos_movimiento) !!};
            formas_vueltos = formas_vueltos.filter(forma => forma.TPMV5I === "E" || forma.TPMV5I === "P");
            
            set_formas_vueltos(formas_vueltos);
            const url = '{{route("factura.productos")}}';
            buscar_producto(url);
            const url_clientes = '{{route("clientes.buscar")}}';
            buscar_cliente(url_clientes);
            enviar_formulario();
            updateTotal();
            updatePeso();
            $('#forma_pago').on('change', function() {
                if ($(this).find('option:selected').text() === 'Crédito') {
                    $('#credit-days-container').show();
                } else {
                    $('#credit-days-container').hide();
                }
            });
            $('#metodo_pago').select2({
                placeholder: 'Buscar',
                allowClear: true,
                width: '100%',
                closeOnSelect: true
            });   
        $('#metodo_pago').on('change', function() {
            var selectedMethods = $(this).val();
            var container = $('#monto-pago-container');
            container.html(''); // Limpiar el contenedor
            updateTotal();
            $.each(selectedMethods, function(index, methodId) {
                var methodName = $(`#metodo_pago option[value="${methodId}"]`).text();
                var inputHtml = `
                    <div class="form-group">
                        <label for="monto_pago_${methodId}">Monto de pago - ${methodName}</label>
                        <input type="number" id="monto_pago_${methodId}" name="monto_pago[]" class="form-control monto_pago" required>
                    </div>
                `;
                container.append(inputHtml);
                $('input[name="monto_pago[]"]').on('input', debounce(function(event) {
                    updateTotal();
                }, 500));
            });
        });
           
        });
    </script>
@stop