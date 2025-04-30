@extends('adminlte::page')

@section('title', 'Crear Usuario')

@section('content_header')
    <div class="breadcrumbs">
        {{ Breadcrumbs::render('usuarios.create') }}
    </div>
    <h1>Crear Usuario</h1>
@stop

@section('content')
    @include('right_sidebar')

    <body class="hold-transition register-page">
        <div class="register-box mt-5" style="margin: auto;">
            <div class="card">
                <div class="card-body register-card-body">
                    <p class="login-box-msg">Crear Nuevo Usuario</p>

                    {!! Form::model($user, ['route' => ['user.store', $user], 'method' => 'post']) !!}
                    @csrf
                    <div class="input-group mb-3">
                        <input autocomplete="off" type="text" name="name" value="{{ old('name') }}"
                            class="form-control" placeholder="Nombres" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input autocomplete="off" type="email" name="email" value="{{ old('email') }}"
                            class="form-control" placeholder="Correo Electrónico" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    @error('email')
                        <small>{{ $message }}</small>
                    @enderror
                   

                    <div class="input-group mb-3">
                        <input autocomplete="off" type="password" name="password" class="form-control"
                            placeholder="Contraseña" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    @error('password')
                        <small>{{ $message }}</small>
                    @enderror
                    <div class="input-group mb-3">
                        <input autocomplete="off" type="password" name="password_confirmation" class="form-control"
                            placeholder="Repetir Contraseña" required>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        
                        <select name="almacen_id" id="almacen_id" class="form-control">
                            @foreach ($almacenes as $item)
                                <option value="{{$item->id}}"  {{($item->id==$user->almacen_id)?'selected':''}} >{{$item->nombre}}</option>     
                            @endforeach
                        </select>
                    </div>
                    <div class="row flex flex-nowrap">
                        <div class="col-sm-6">
                            <select name="operadora" id="operadora" class="form-control" required>
                                <option value="0412">0412</option>
                                <option value="0412">0424</option>
                                <option value="0412">0414</option>
                                <option value="0412">0416</option>
                                <option value="0412">0426</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <input autocomplete="off" type="number" name="numero_celular" class="form-control" placeholder="Celular" required pattern="[0-9]{7}">
                        </div>
                        
                    </div>

                    <div class="row">
                        <!-- /.col -->
                        <div class="col-4">
                            {!! Form::submit('Guardar', ['class' => 'btn btn-primary mt-2']) !!}
                        </div>
                        <!-- /.col -->
                    </div>

                    {!! Form::close() !!}
                </div>
                <!-- /.form-box -->
            </div><!-- /.card -->
        </div>
        <!-- /.register-box -->

        <!-- jQuery -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
        <!-- AdminLTE App -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js"></script>
        <!-- Select2 -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>

    </body>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet"
        href="{{ Config::get('app.env') == 'local' ? asset('css/maploca_styles.css') : secure_asset('css/maploca_styles.css') }}">
        <style>
            .select2-selection__choice {
                color: black !important;
            }
        </style>
@stop

