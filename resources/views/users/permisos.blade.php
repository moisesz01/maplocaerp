@extends('adminlte::page')

@section('title', 'Perfil del Usuario')

@section('content_header')
    <h1>Permisos del Usuario</h1>
@stop

@section('content')
@include('right_sidebar')
<div class="row card">
    <div class="card-header">
        <h5>Permisos del Usuario</h5>
    </div>
    <div class="card-body">
        {!! Form::model($user, ['user.update ']) !!}
        {!! Form::close( ) !!}
        <div class="">
            <form method="POST" action="{{route("users.store_profile")}}">
                @csrf
                <div class="row">
                    <input type="hidden" name="user_id" value="{{$user->id}}">
                    <div class="col-sm-3">
                        <div class="form-group">
                           <label class="label">Nombre</label>
                           <input autocomplete="off" name="name" class="form-control" value="{{$user->name}}"
                               type="text" placeholder="Nombre">
                       </div>   
                       </div>
                       <div class="col-sm-3">
                        <div class="form-group">
                             <label class="label">Correo Electrónico</label>
                             <input required autocomplete="off" name="email" class="form-control" value="{{$user->email}}"
                                type="email"  placeholder="Correo">
                         </div> 
                     </div>
                     <div class="col-sm-3">
                        <label for="sucursal">Sucursal:</label>
                        <select name="sucursal_id" class="form-control">
                           
                            @if (isset($user->sucursal->nombre))
                                <option value="{{$user->sucursal_id}}" selected>{{$user->sucursal->nombre}}</option> 
                            @else
                                <option value="" selected disabled>Seleccione Sucursal</option> 
                            @endif
                            @foreach($sucursales as $sucursal)
                                @if ($user->sucursal_id!=$sucursal->id)
                                    <option value="{{$sucursal->id}}"> {{$sucursal->nombre}}</option>
                                @endif
                            @endforeach
                          </select>
                     </div>
                     
                </div>
            
                <br>
               
                <button class="btn btn-success">Guardar</button>
                <a class="btn btn-primary" href="{{route("user.index")}}">Listado de Usuarios</a>
            </form>
           
        </div>
   </div>
    
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop