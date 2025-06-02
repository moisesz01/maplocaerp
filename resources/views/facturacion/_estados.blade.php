<form action="{{route('cotizacion.actualizar_estado',['documento_id'=>$documento_id])}}" method="POST" enctype="multipart/form-data">
    @csrf
    <strong>Estados de la Cotización</strong>
    <div class="row mt-2 mb-2">
        <div class="col-sm-12">
           
            <select name="status_id" id="status_id" class="form-control" required>
                <option value="">Seleccione un Status</option>
                @foreach ($estados as $estado)
                    <option value="{{$estado->id}}">{{$estado->nombre}}</option>
                @endforeach
                
            </select>
        </div>
        <div class="col-sm-12">
            <label for="comentario_estado">Observación</label>
            <input type="text" name="comentario_estado" id="comentario_estado" class="form-control">
        </div>
    </div>
    <div class="float-right">
        <button type="button" class="btn gray btn-secondary" data-dismiss="modal">{{__('Cancelar')}}</button>
        <button type="submit" class="btn gray btn-success" >{{__('Guardar')}}</button>
    </div>

</form>