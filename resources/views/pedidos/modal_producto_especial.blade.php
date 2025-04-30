{{-- Modal para buscar productos --}}
<div class="modal fade" id="product-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                        
                        <label for="search-product-input">Ingrese Producto:</label>
                        <input type="text" id="nombre_producto" class="form-control" placeholder="Ingrese producto..." autocomplete="off">
                        
                    </div>
                    <div class="col-sm-12">
                        
                        <label for="search-product-input">Ingrese Código:</label>
                        <input type="text" id="codigo_producto" class="form-control" placeholder="Ingrese código..." autocomplete="off">
                        
                    </div>
                    
                </div>
                <div class="row flex flex-nowrap">
                    <div class="col-sm-6">
                        <label for="cantidad">precio:</label>
                        <input type="number" name="precio" id="precio_especial" class="form-control" step="any">
                    </div>
                    <div class="col-sm-6">
                        <label for="cantidad">Cantidad:</label>
                        <input type="number" step="1" pattern="[0-9]*" id="cantidad" name="cantidad">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="add_especial">Agregar</button>
            </div>
        </div>
    </div>
</div>