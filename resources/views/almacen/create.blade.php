@extends('adminlte::page')

@section('title', 'Crear Almacen')

@section('content_header')
<div class="breadcrumbs">
    {{ Breadcrumbs::render('almacen.create') }}
    </div>
    <h1>Crear Almacen</h1>
@stop

@section('content')
@include('right_sidebar')

            <form method="POST" action="{{route("almacen.store")}}">
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <label class="label">Nombre de la Almacen</label>
                        <input required autocomplete="off" name="nombre" class="form-control"
                           type="text" placeholder="Nombre">
                    </div>
                    <div class="col-sm-6">
                        <label class="label">Código del Almacen</label>
                        <input required autocomplete="off" name="codigo" class="form-control"
                           type="text" placeholder="codigo">
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label class="label">Dirección</label>
                        <input required autocomplete="off" name="direccion" class="form-control"
                           type="text" placeholder="direccion">
                    </div>
                    <div class="col-sm-6">
                        <label class="label">Whastapp</label>
                        <input required autocomplete="off" name="whastapp" class="form-control"
                           type="text" placeholder="whastapp">
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label class="label">Vendedores</label>
                        <select required multiple name="vendedores[]" class="form-control" id="vendedores">
                            @foreach($vendedores as $vendedor)
                                <option value="{{$vendedor->id}}">{{$vendedor->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row pt-4">
                    <div class="col-sm-6">
                        <button class="btn btn-success">Guardar</button>
                <a class="btn btn-primary" href="{{route("almacen.index")}}">Volver</a>
                    </div>        
                </div>
                
            </form>


@stop
@section('css')
<link rel="stylesheet" href="{{ (Config::get('app.env')=='local')?asset('css/maploca_styles.css'):secure_asset('css/maploca_styles.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
@stop

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#vendedores').select2({
        placeholder: 'Buscar',
        allowClear: true,
        width: '100%',
        closeOnSelect: true
    });    
});
</script>

@stop