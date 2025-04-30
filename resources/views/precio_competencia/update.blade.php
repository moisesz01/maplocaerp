@extends('adminlte::page')

@section('title', 'Registrar Precios')

@section('content_header')
<div class="breadcrumbs"> 
   
    </div>
    <h1>Registrar de Precios</h1>
@stop

@section('content')
@include('right_sidebar')
@php
    date_default_timezone_set('America/Caracas');
@endphp
<div class="card">
    <div class="card-header">
        Formulario de Registro de Precios de competencia
    </div>
    <div class="card-body">
       
            <form method="POST" action="{{route('precio_competencia.update')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{$precio_competencia->id}}">
                <div class="row">
                    <div class="col-sm-6">
                        <label for="nombre_articulo">Articulo:</label>
                        <input type="text" class="form-control" value="{{$precio_competencia->nombre_articulo}}" name="nombre_articulo">
                    </div>
                    <div class="col-sm-6">
                        <label for="competidor">Competidor:</label>
                        <input type="text" name="competidor" class="form-control" value="{{$precio_competencia->competidor}}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="tipo_precio">Tipo de Precio:</label>
                        <select name="tipo_precio" id="tipo_precio" class="form-control">
                            <option value="detal" {{($precio_competencia->tipo_precio=='detal')?'selected':''}}>Al Detal</option>
                            <option value="mayor" {{($precio_competencia->tipo_precio=='mayor')?'selected':''}}>Al Mayor</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label for="precio">precio:</label>
                        <input type="number" step="any" name="precio" class="form-control" value="{{round($precio_competencia->precio,2)}}">
                    </div>
                </div>
                
                <button type="submit" name="submit" value="actualizar" class="btn btn-success mt-2">Actualizar</button>
                
            </form>
        
    </div>
</div>
@php
    $env =Config::get('app.env');
@endphp
@stop

@section('css')
<link rel="stylesheet" href="{{ (Config::get('app.env')=='local')?asset('css/maploca_styles.css'):secure_asset('css/maploca_styles.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
@stop

@section('js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="{{ (Config::get('app.env')=='local')?asset('js/maploca.js'):secure_asset('js/maploca.js') }}"></script>
@stop