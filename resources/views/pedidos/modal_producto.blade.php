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
                <div class="row">
                    <div class="col-sm-12">
                        
                        <label for="almacen_codigo">Almacen:</label>
                        <select name="almacen_codigo" id="almacen_codigo" class="form-control">
                            @foreach ($almacenes as $item)
                                <option value="{{$item->codigo}}"  {{($item->id==$almacen_codigo)?'selected':''}} >{{$item->nombre}}</option>     
                            @endforeach
                        </select>
                        
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <input type="hidden" name="producto_id_tmp" id="producto_id_tmp">
                        <input type="hidden" name="unidad_medida" id="unidad_medida">
                        <label for="search-product-input">Ingrese Producto:</label>
                        <input type="text" id="search-product-input" class="form-control" placeholder="Buscar producto..." autocomplete="off">
                        <ul id="search-product-list" class="list-group">
                            {{-- Resultados de la búsqueda --}}
                        </ul>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <label>
                            <input type="checkbox" id="searchWithStock" name="searchWithStock" checked>
                            Productos con existencias
                        </label>
                    </div>
                </div>
                <div class="row flex flex-nowrap">
                    <div class="col-sm-6">
                        <label for="codigo">codigo:</label>
                        <input type="text" name="codigo" id="codigo" class="form-control" disabled>
                    </div>
                    <div class="col-sm-6">
                        <label for="cantidad">Linea:</label>
                        <input type="text" name="linea" id="linea" class="form-control" disabled>
                    </div>
                </div>
                <div class="row flex flex-nowrap">
                    <div class="col-sm-6">
                        <label for="disponible">Disponible:</label>
                        <input type="text" name="disponible" id="disponible" class="form-control" disabled>
                    </div>
                    <div class="col-sm-6">
                        <label for="cantidad">Peso:</label>
                        <input type="number" name="peso" id="peso" class="form-control" disabled>
                    </div>
                </div>
                <div class="row flex flex-nowrap">
                    <div class="col-sm-6">
                        <label for="cantidad">precio:</label>
                        <input type="number" name="precio" id="precio" class="form-control" disabled>
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