@php
    $tipo_operaciones = [
        'Guardar',
        'Actualizar'
    ];
@endphp
@foreach ($tipo_operaciones as $tipo_operacion)
<div class="modal fade" id="modal_{{strtolower($tipo_operacion)}}" tabindex="-1" role="dialog" aria-labelledby="miModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="miModalLabel">Actividad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <label for="numero_documento">Buscar Por Documento o Nombre de Cliente</label>
                        <input type="text" class="form-control" id="numero_documento{{(strtolower($tipo_operacion)=='actualizar')?'_'.strtolower($tipo_operacion):''}}" name="numero_documento" autocomplete="off" required>
                        <input type="hidden" name="cliente_id" id="cliente_id{{(strtolower($tipo_operacion)=='actualizar')?'_'.strtolower($tipo_operacion):''}}">
                        <input type="hidden" name="event_id" id="event_id{{(strtolower($tipo_operacion)=='actualizar')?'_'.strtolower($tipo_operacion):''}}" value="0">
                        <div id="documentoList">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="nombre">Nombre</label>
                        <input type="text"  id="nombre{{(strtolower($tipo_operacion)=='actualizar')?'_'.strtolower($tipo_operacion):''}}" name="nombre" class="form-control" disabled>
                    </div>
                    <div class="col-sm-6">
                        <label for="ciudad">Ciudad</label>
                        <input type="text" name="ciudad" class="form-control" id="ciudad{{(strtolower($tipo_operacion)=='actualizar')?'_'.strtolower($tipo_operacion):''}}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="">Tipo Acción</label>
                        <select name="tipo_accion" id="tipo_accion{{(strtolower($tipo_operacion)=='actualizar')?'_'.strtolower($tipo_operacion):''}}" class="form-control">
                            <option disabled selected>Seleccione un Tipo</option>
                            @foreach ($tipo_acciones as $item)
                                <option value="{{$item->id}}">{{$item->nombre}}</option>    
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label for="">Actividad</label>
                        <select name="accion_vendedor_id" id="accion_vendedor_id{{(strtolower($tipo_operacion)=='actualizar')?'_'.strtolower($tipo_operacion):''}}" class="form-control">
                            <option disabled value="">Seleccione una acción</option>    
                        </select>
                    </div>    
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <label for="actividad">Notas</label>
                        <input type="text" class="form-control" name="actividad" id="actividad{{(strtolower($tipo_operacion)=='actualizar')?'_'.strtolower($tipo_operacion):''}}" autocomplete="off">
                        <span id="actividadError" class="text-danger"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="fecha">Fecha</label>
                        <input type="datetime-local" name="fecha_inicio" id="fecha_inicio{{(strtolower($tipo_operacion)=='actualizar')?'_'.strtolower($tipo_operacion):''}}" class="form-control">
                    </div>
                    <div class="col-sm-6">
                        <label for="duracion">Duración</label>
                        <select name="duracion" class="form-control" id="duracion{{(strtolower($tipo_operacion)=='actualizar')?'_'.strtolower($tipo_operacion):''}}">
                            <option value="0.5">(0.5) Hrs</option>
                            <option value="1">(1) Hrs</option>
                            <option value="1.5">(1.5) Hrs</option>
                            <option value="2">(2) Hrs</option>
                            <option value="2.5">(2.5) Hrs</option>
                            <option value="3">(3) Hrs</option>
                            <option value="3.5">(3.5) Hrs</option>
                            <option value="4">(4) Hrs</option>
                            <option value="4.5">(4.5) Hrs</option>
                            <option value="5">(5) Hrs</option>
                            <option value="5.5">(5.5) Hrs</option>
                            <option value="6">(6) Hrs</option>
                        </select>
                    </div>
                </div>
                   
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary cerrar" data-dismiss="modal">Cerrar</button>
               
                @if (strtolower($tipo_operacion)=='actualizar')
                    <button type="button" class="btn btn-danger" id="eliminar"><i class="fas fa-trash-alt"></i> Eliminar</button>         
                @endif
               
                <button type="button" class="btn btn-primary" id="{{ strtolower($tipo_operacion)}}">{{$tipo_operacion}}</button>
            </div>
        </div>
    </div>
</div>
@endforeach
