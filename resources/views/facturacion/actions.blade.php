<a href="{{ route('editar.factura',['factura_id'=>$documento->id]) }}"  class="text-secondary" title="Editar Factura" style="font-size: 20px;"><i class="fas fa-edit"></i></a>
@if ($documento->tipo_documento == 'Cotización')
    <a class="text-secondary" data-toggle="modal" id="smallButton" data-target="#smallModal" data-cotizacion="{{$documento->id}}"
        data-attr="{{ route('conversion.modal',['documento_id'=>$documento->id]) }}" title="Convertir a Factura" style="font-size: 20px;"> 
        <i class="fas fa-file-invoice-dollar"></i>
    </a>    
@endif
