@extends('adminlte::page')

@section('title', 'Crear Cotización')

@section('content_header')
<div class="breadcrumbs">
    {{ Breadcrumbs::render('pedido.crear') }}
</div>
    <h1>Crear Cotización</h1>
@stop

@section('content')

@include('right_sidebar')
@include('modals')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-solid">
                <form method="POST" action="{{route('pedido.guardar')}}" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="visita_id" id="visita_id" value="{{  (isset($visita->id))?$visita->id:null}}">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="cliente">Cliente:</label>
                        <input type="hidden" name="cliente_id" id="cliente_id" value="{{  (isset($visita->cliente_id))?$visita->cliente_id:''  }}">
                        <input type="text" value="{{  (isset($visita->cliente_id))?$visita->cliente->nombre:''  }}"  
                            {{  (isset($visita->cliente_id))?'disabled':''  }}
                            id="search-client-input" class="form-control" placeholder="Buscar cliente..." autocomplete="off" required>
                        <ul id="search-client-list" class="list-group">
                            {{-- Resultados de la búsqueda --}}
                        </ul>
                    </div>
                </div>
                <div class="row flex flex-nowrap">
                    <div class="col-sm-6">
                        <label for="rif">RIF</label>
                        <input type="text" id="rif" class="form-control" value="{{  (isset($visita->cliente_id))?$visita->cliente->tipo_documento.'-'.$visita->cliente->numero_documento:''  }}"  disabled>
                    </div>
                    <div class="col-sm-6">
                        <label for="rif">Dirección</label>
                        <input type="text" id="direccion" disabled class="form-control" value="{{  (isset($visita->cliente_id))?$visita->cliente->direccion:''  }}">
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
                            <a class="nav-link" data-toggle="tab" href="#pago-tab">Método de Pago</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="productos-tab">
                            <div class="table-responsive">
                                <table id="items-table" class="table table-sm table-striped table-bordered dt-responsive nowrap" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Artículo</th>
                                            <th>Cant.</th>
                                            <th>Precio</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="items-tbody">
                                        {{-- Filas dinámicas --}}
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
                                <label for="descuento">Descuento (%):</label>
                                <input type="number" id="descuento" name="descuento" class="form-control" value="0" min="0" max="100">
                            </div>
                            
                        </div>

                        <div class="tab-pane" id="pago-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="metodo_pago">Método de Pago</label>
                                        <select class="form-control" id="metodo_pago" name="metodo_pago[]" required multiple>
                                            <option disabled>Seleccione</option>
                                            @foreach ($metodos_pago as $metodo)
                                                <option value="{{$metodo->id}}">{{$metodo->nombre}}</option>    
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- Contenedor para los inputs dinámicos -->
                                <div id="monto-pago-container"></div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="forma_pago">Forma de Pago</label>
                                        <select class="form-control" id="forma_pago" name="forma_pago" required>
                                            <option value="" >Seleccione</option>
                                            @foreach ($formas_pago as $forma)
                                                <option value="{{$forma->id}}">{{$forma->nombre}}</option>    
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="moneda_extranjera">Moneda Extranjera</label>
                                        <select class="form-control" id="moneda_extranjera" name="moneda_extranjera" required>
                                            <option value=""  selected disabled>Seleccione</option>
                                            @foreach ($tasas as $tasa)
                                                <option data-tasa="{{$tasa['tasa0b']}}" value="{{$tasa['moex0b']}}">{{$tasa['moex0b']}}</option>
                                            @endforeach
                                                <option data-tasa=1 value="TEC">TEC</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tasa_temp">Tasa</label>
                                        <input type="number" name="tasa_temp" id="tasa_temp" class="form-control" value="1" step="any" readonly>
                                        <input type="hidden" name="tasa" id="tasa">
                                    </div>
                                </div>
                                
                            </div>
                            <div class="form-group" id="credit-days-container" style="display: none;">
                                <label for="credit_days">Días de Crédito</label>
                                <input type="number" id="dias_credito" name="dias_credito" class="form-control" min="0">
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="notas">Notas: </label>
                                    <textarea class="form-control" id="notas" name="notas" rows="2"></textarea>
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

                           {{--  <div class="form-group col-md-6">
                                <label for="numero_ticket">Adjuntar Comprobante de pago:</label>
                                <input type="file" name="file[]" class="form-control file">
                                @error('file')
                                <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                                @enderror
                            </div> --}}
                            
                        </div>
                    </div>
                </div>
                <button type="submit" id="submit-button" class="btn btn-success">Guardar</button>
            </form>
            </div>
        </div>
    </div>

    @include('pedidos.modal_producto',[
         'almacenes'=>$almacenes,
         'almacen_codigo'=>$almacen_codigo,
    ])
    @include('pedidos.modal_producto_especial',[
        'almacenes'=>$almacenes,
        'almacen_codigo'=>$almacen_codigo,
   ])
   @include('pedidos.modal_servicio',[
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
    <script src="{{ (Config::get('app.env')=='local')?asset('js/pedido.js'):secure_asset('js/pedido.js') }}"></script>
    
    <script>
        $(document).ready(function() {

            $('#moneda_extranjera').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var tasa = selectedOption.attr('data-tasa');
                var texto = selectedOption.text();
                $('#tasa').val(tasa);
                $('#tasa_temp').val(tasa);
                if (texto === 'TEC') {
                    $('#tasa_temp').prop('readonly', false);
                } else {
                    $('#tasa_temp').prop('readonly', true);
                }
            });
            $('#tasa_temp').on('input', function() {
                $('#tasa').val($(this).val());
            });


            const url = '{{route("pedido.productos")}}';
            buscar_producto(url);
            const url_clientes = '{{route("clientes.buscar")}}';
            buscar_cliente(url_clientes);
            enviar_formulario()
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