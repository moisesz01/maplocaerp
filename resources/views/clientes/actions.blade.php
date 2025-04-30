<a href="{{ route('clientes.edit',['cliente_id'=>$cliente->id]) }}"  class="text-secondary mr-2" title="Actualizar Cliente" style="font-size: 25px;"><i class="fas fa-user-edit"></i></a>
<a href="{{ route('clientes.visita',['cliente_id'=>$cliente->id]) }}"  class="text-secondary mr-2" title="Gestionar Cliente" style="font-size: 25px;"><i class="far fa-clipboard"></i></a>
@if (isset($cliente->latitud) && $cliente->latitud!='')
<a href="https://www.google.com/maps?q={{ $cliente->latitud }},{{ $cliente->longitud }}" target="_blank" style="font-size: 25px;"><i class="fas fa-map-marked-alt"></i></a>    
@endif
<a class="text-secondary" style="font-size: 25px;" href="{{ route('inactivar.cliente',[
        'cliente_id'=>$cliente->id,                                               
    ]) }}" onclick="alert_confirmacion(event,this.href,'¿Estás seguro de que deseas eliminar el cliente?','Sí, eliminar','Cliente Eliminado!')">
    <i class="fas fa-trash-alt"></i>
</a>

