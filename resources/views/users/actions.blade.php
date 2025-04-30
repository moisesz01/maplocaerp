<a href="{{ route('user.detalle',['user_id'=>$user->id]) }}"  class="text-secondary" title="Editar Permisos"><i class="fas fa-file-signature"></i></a>
<a href="{{ route('user.contrasena',['user_id'=>$user->id]) }}"  class="text-secondary" title="Cambiar Contraseña"><i class="fas fa-key"></i></a>
<a class="text-danger inventario" data-toggle="modal" id="smallButton" data-target="#smallModal" data-producto="{{$user->id}}"
    data-attr="{{ route('users.modal_inactivar',['user_id'=>$user->id]) }}" title="Inactivar">
    <i class="fas fa-ban"></i>
</a>