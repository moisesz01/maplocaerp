<form action="{{route('convertir.documento',['factura_id'=>$documento->id])}}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="">
        <label for="estado_id">¿Esta seguro que quiere convertir la cotización a pedido?</label>
        <input type="hidden" name="tipo_documento_id" id="tipo_documento_id" value="{{$tipo_documento_id}}">
        
    </div>
    <div class="float-right">
        <button type="submit" class="btn btn-success" id="btn-enviar" >Aceptar</button>
        
    </div>
</form>
<script>
    $(document).ready(function() {
        $('#btn-enviar').on('click', function(event) {
            event.preventDefault();
            $(this).prop('disabled', true);
            $(this).closest('form').submit();
        });
        
    });
</script>