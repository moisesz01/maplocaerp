@extends('adminlte::page')

@section('title', 'Perfil del Usuario')

@section('content_header')
<div class="breadcrumbs">
  {{ Breadcrumbs::render('usuarios.contrasena') }}
  </div>
    <h1>Perfil del Usuario</h1>
@stop

@section('content')
@if (session('info'))
<div class="alert alert-dismissable alert-warning">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <strong>
        {{session('info')}}
    </strong>
</div>

@endif
<div class="row card">
    <div class="card-header">
        <h5>Formulario de Perfil de Usuario</h5>
    </div>
    <div class="card-body">
        {!! Form::model($user, ['route'=>['users.store_contrasena',$user],'method'=>'put']) !!}
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


        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="input-group mb-3">
                    <input autocomplete="off" type="password" name="password" class="form-control" placeholder="Contraseña" required>
                    <div class="input-group-append">
                      <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                      </div>
                    </div>
                  </div>
                  @error('password')
                    <small>{{$message}}</small>
                  @enderror
            </div>
            <div class="col-sm-6">
                <div class="input-group mb-3">
                    <input autocomplete="off" type="password" name="password_confirmation" class="form-control" placeholder="Repetir Contraseña" required>
                    <div class="input-group-append">
                      <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                      </div>
                    </div>
                  </div>
            </div>

        </div>


        <br>

        {!! Form::submit('Guardar', ['class'=>'btn btn-primary mt-2']) !!}

        {!! Form::close() !!}

   </div>

</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
      .breadcrumbs {
          padding: 10px;
          border-radius: 5px;
      }
      
      .breadcrumbs a {
          text-decoration: none;
          color: #333;
      }
      
      .breadcrumbs a:hover {
          color: #8f3131;
      }
       
      .breadcrumbs .current {
          font-weight: bold;
      }
   
      </style>
@stop

