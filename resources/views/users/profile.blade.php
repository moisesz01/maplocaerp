@extends('adminlte::page')

@section('title', 'Perfil del Usuario')

@section('content_header')
    <div class="breadcrumbs">
        {{ Breadcrumbs::render('usuarios.edit') }}
    </div>
    <h1>Perfil del Usuario</h1>
@stop

@section('content')
    @include('right_sidebar')
    @if (session('info'))
        <div class="alert alert-dismissable alert-warning">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>
                {{ session('info') }}
            </strong>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-dismissable alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>
                {{ session('success') }}
            </strong>
        </div>
    @endif
    <div class="row card">
        <div class="card-header">
            <h5>Formulario de Perfil de Usuario</h5>
        </div>
        <div class="card-body">
            {!! Form::model($user, ['route' => ['users.store_permisos', $user], 'method' => 'put']) !!}
            <div class="row">
                <input type="hidden" name="user_id" value="{{ $user->id }}">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="label">Nombre</label>
                        <input autocomplete="off" name="name" class="form-control" value="{{ $user->name }}"
                            type="text" placeholder="Nombre">
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="label">Correo Electrónico</label>
                        <input required autocomplete="off" name="email" class="form-control" value="{{ $user->email }}"
                            type="email" placeholder="Correo">
                    </div>
                    @error('email')
                        <small>{{ $message }}</small>
                    @enderror
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="label">Almacen</label>
                        <select name="almacen_id" id="almacen_id" class="form-control">
                            @foreach ($almacenes as $item)
                                <option value="{{$item->id}}"  {{($item->id==$user->almacen_id)?'selected':''}} >{{$item->nombre}}</option>     
                            @endforeach
                        </select>
                    </div>
                    @error('email')
                        <small>{{ $message }}</small>
                    @enderror
                </div>
            </div>
                <div class="row flex flex-nowrap">
                    <div class="col-sm-6">
                        @php
                            $prefijo = substr($user->numero_celular, 0,4);
                        @endphp
                        <label for="operadora">Operadora</label>
                        <select name="operadora" id="operadora" class="form-control" required>
                            <option value="0412" {{($prefijo=='0412')?'selected':''}}>0412</option>
                            <option value="0424" {{($prefijo=='0424')?'selected':''}}>0424</option>
                            <option value="0414" {{($prefijo=='0414')?'selected':''}}>0414</option>
                            <option value="0416" {{($prefijo=='0416')?'selected':''}}>0416</option>
                            <option value="0426" {{($prefijo=='0426')?'selected':''}}>0426</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label for="numero_celular">Número</label>
                        <input autocomplete="off" value="{{ substr($user->numero_celular, 4) }}" type="number" name="numero_celular" class="form-control" placeholder="Celular" required pattern="[0-9]{7}">
                    </div>
                    
                </div>
                
          
            <br>
            <div class="row">
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header">
                            <strong><i class="fas fa-bars"></i> Acceso a Módulos</strong>
                        </div>
                        <div class="card-body" style="height: 380px;">
                            @php
                                $modulos = [
                                    'Módulo Usuarios',
                                    'Módulo Clientes',
                                    'Módulo Facturación',
                                    'Módulo Configuraciones',
                                    'Módulo Planificación',
                                    'Módulo Pedidos',
                                    
                                ];
                            @endphp
                            @foreach ($modulos as $modulo)
                                @foreach ($permisos as $item)
                                    <div>
                                        @if ($modulo == $item)
                                            <label>
                                                @php $flat=false; @endphp
                                                @foreach ($permissionNames as $permiso)
                                                    @if ($permiso == $item)
                                                        @php $flat=true; @endphp
                                                    @endif
                                                @endforeach
                                                {!! Form::checkbox('permisos[]', $item, $flat, ['class' => 'mr-1']) !!}
                                                {{ $item }}
                                            </label>
                                        @endif

                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header">
                            <strong><i class="fas fa-file-invoice"></i> Permisos Especiales</strong>
                        </div>
                        <div class="card-body" style="height: 380px;">
                            @php
                                $modulos = [
                                    'Ver todos los clientes',
                                    'Vendedor Externo',
                                    'Ver planificaciones de todos los usuarios',
                                    'Enviar a Pedido'
                                ];
                            @endphp
                            @foreach ($modulos as $modulo)
                                @foreach ($permisos as $item)
                                    <div>
                                        @if ($modulo == $item)
                                            <label>
                                                @php $flat=false; @endphp
                                                @foreach ($permissionNames as $permiso)
                                                    @if ($permiso == $item)
                                                        @php $flat=true; @endphp
                                                    @endif
                                                @endforeach
                                                {!! Form::checkbox('permisos[]', $item, $flat, ['class' => 'mr-1']) !!}
                                                {{ $item }}
                                            </label>
                                        @endif

                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::submit('Guardar', ['class' => 'btn btn-primary mt-2']) !!}
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="stylesheet" href="{{ Config::get('app.env') == 'local' ? asset('css/maploca_styles.css') : secure_asset('css/maploca_styles.css') }}">
    <style>
        .select2-selection__choice {
            color: black !important ;
        }
    </style>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Seleccione las áreas de trabajo",
            });
        });
    </script>
@stop
