<form action="{{route('users.inactivar',['user_id'=>$user->id])}}" method="POST" enctype="multipart/form-data">
    @csrf
    <div>Desea desactivar/activar a el Usuario: {{ $user->name }}</div>
    <div class="float-right">
        <br>
        <button type="button" class="btn gray btn-outline-secondary" data-dismiss="modal">{{__('Cancelar')}}</button>
        <button type="submit" class="btn gray btn-outline-danger" >{{__('Desactivar')}}</button>
    </div>

</form>
