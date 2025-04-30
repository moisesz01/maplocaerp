<a href="{{ route('editar.cotizacion',['cotizacion_id'=>$documento->id]) }}"  class="text-secondary" title="Editar Cotización" style="font-size: 20px;"><i class="fas fa-edit"></i></a>
<a href="{{ route('exportar.cotizacion',['cotizacion_id'=>$documento->id]) }}"  class="text-secondary" title="Exportar Cotización" style="font-size: 25px;"><i class="far fa-file-pdf"></i></a>

<a class="text-secondary" data-toggle="modal" id="smallButton" data-target="#smallModal" data-cotizacion="{{$documento->id}}" style="font-size: 20px;"
    data-attr="{{ route('cotizacion.modal_estado',['documento_id'=>$documento->id]) }}" title="Cambiar Status">
    <i class="fas fa-exchange-alt"></i>
</a>
@can('Enviar a Pedido')
<a class="text-secondary" style="font-size: 20px;" href="{{ route('procesar.pedido',[
        'documento_id'=>$documento->id,                                               
    ]) }}" onclick="alert_confirmacion(event,this.href,'¿Estás seguro de que deseas convertir la cotizacion a pedido?','Sí, procesar','Pepido procesado!')">
    <i class="fas fa-file-invoice-dollar"></i>
</a>
@endcan

