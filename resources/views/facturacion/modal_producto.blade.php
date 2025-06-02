{{-- Modal para buscar productos --}}
<div class="modal fade" id="search-product-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="miModalLabel">Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="almacen_codigo" id="almacen_codigo" value="{{$almacen_codigo}}">
           
                <div class="row">
                    <div class="col-sm-12">
                        <input type="hidden" name="producto_id_tmp" id="producto_id_tmp">
                        <input type="hidden" name="unidad_medida" id="unidad_medida">
                        <input type="hidden" name="estandar" id="estandar">
                        <input type="hidden" name="ancho" id="ancho">
                        <input type="hidden" name="calibre_alto_espesor" id="calibre_alto_espesor">
                        <label for="search-product-input">Ingrese Producto:</label>
                        <input type="text" id="search-product-input" class="form-control" placeholder="Buscar producto..." autocomplete="off">
                        <ul id="search-product-list" class="list-group">
                            {{-- Resultados de la búsqueda --}}
                        </ul>
                    </div>
                    
                </div>
                
                <div class="row flex flex-nowrap">
                    <div class="col-sm-6">
                        <label for="codigo">codigo:</label>
                        <input type="text" name="codigo" id="codigo" class="form-control" disabled>
                    </div>
                    <div class="col-sm-6">
                        <label for="linea">Linea:</label>
                        <input type="text" name="linea" id="linea" class="form-control" disabled>
                    </div>
                </div>
                <div class="row flex flex-nowrap">
                    <div class="col-sm-6">
                        <label for="disponible">Disponible:</label>
                        <input type="text" name="disponible" id="disponible" class="form-control" disabled>
                    </div>
                    <div class="col-sm-6">
                        <label for="peso">Peso:</label>
                        <input type="number" name="peso" id="peso" class="form-control"  step="0.01"  disabled>
                    </div>
                </div>
                <div class="row flex flex-nowrap">
                    <div class="col-sm-6">
                        <label for="unidad_inventario">Unidad Inventario:</label>
                        <input type="text" name="unidad_inventario" id="unidad_inventario" class="form-control" disabled>
                    </div>
                    <div class="col-sm-6">
                        <label for="ancho_conversion">Ancho:</label>
                        <input type="number" name="ancho_conversion" id="ancho_conversion" class="form-control" disabled>
                    </div>
                </div>
                <div class="row flex flex-nowrap">
                    <div class="col-sm-6">
                        <label for="alto_espesor_conversion">Calibre, alto o espesor:</label>
                        <input type="number" name="alto_espesor_conversion" id="alto_espesor_conversion" class="form-control" disabled>
                    </div>
                    <div class="col-sm-6">
                        <label for="largo">Largo:</label>
                        <input type="number" name="largo" id="largo" class="form-control" disabled>
                    </div>
                </div>
                <div class="row flex flex-nowrap">
                    <div class="col-sm-6">
                        <label for="unidad_venta">Unidad de Venta</label>
                        <select name="unidad_venta" id="unidad_venta" disabled class="form-control">
                            <option value="" selected disabled>Seleccione</option>
                            <option value="KGS">KGS</option>
                            <option value="PZAS">PZAS</option>
                            <option value="MTS">MTS</option>
                            <option value="GAL">GAL</option>
                            <option value="1/4">1/4</option>    
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label for="factor">Factor:</label>
                        <input type="number" name="factor" id="factor" class="form-control" disabled>
                    </div>
                </div>
                <div class="row flex flex-nowrap">
                    <div class="col-sm-6">
                        <label for="precio">precio:</label>
                        <input type="number" name="precio" id="precio" class="form-control" step="0.01">
                    </div>
                    <div class="col-sm-6">
                        <label for="cantidad">Cantidad:</label>
                        <input type="number" name="cantidad" id="cantidad" class="form-control">
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="add">Agregar</button>
            </div>
        </div>
    </div>
</div>