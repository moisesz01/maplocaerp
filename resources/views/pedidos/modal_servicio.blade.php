{{-- Modal para buscar servicios --}}
<div class="modal fade" id="servicio-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="miModalLabel">Servicios</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="servicio">Servicio:</label>
                        <select name="codigo_servicio" id="codigo_servicio" class="form-control">
                            <option value="" selected disabled>Seleccione El servicio</option>
                            @if (is_array($servicios) && count($servicios))
                            @foreach($servicios as $servicio)
                                
                                <option value="{{ $servicio['codigo'] }}" data-unidad-medida="{{ $servicio['unidad_medida'] }}" data-servicio="{{$servicio['servicio']}}">
                                    {{ $servicio['codigo']."-".$servicio['servicio'] }}
                                </option>
                            @endforeach    
                            @endif
                            
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="unidad_medida">Unidad de Medida:</label>
                        <input type="text" id="unidad" name="unidad" class="form-control" disabled>
                        <input type="hidden" name="unidad_medida_servicio" id="unidad_medida_servicio">
                        <input type="hidden" name="servicio" id="servicio">
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="cantidad">Cantidad:</label>
                        <input type="number" id="cantidad_servicio" name="cantidad_servicio" class="form-control">
                    </div>
                    <div class="col-sm-6">
                        <label for="precio">Precio:</label>
                        <input type="number" step="any" min="0" name="precio_servicio" id="precio_servicio" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="add_servicio">Agregar</button>
            </div>
        </div>
    </div>
</div>