@extends('adminlte::page')

@section('title', 'Ejecución de la Visita')

@section('content_header')
<div class="breadcrumbs"> 
   
    </div>
    <h1>Ejecución de la Visita</h1>
@stop

@section('content')
@include('right_sidebar')
@php
    date_default_timezone_set('America/Caracas');
@endphp
<div class="card">
    <div class="card-header">
        <div class="left" style=" float: left;">
            Visita al Cliente: {{ucwords($cliente->nombre)}}
        </div>
        <div class="right" style="float: right;">
            <a href="https://www.google.com/maps?q={{ $cliente->latitud }},{{ $cliente->longitud }}" target="_blank">Ver en Google Maps</a>
        </div>
        
    </div>
    <div class="card-body">
       
            <form method="POST" action="{{route('clientes.guardar_visita')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="cliente_id" id="cliente_id" value="{{$cliente->id}}">
                <div class="row">
                    <div class="col-sm-4">
                        <label for="tipo_documento">Tipo de Documento:</label>
                        <select name="tipo_documento" id="tipo_documento" class="form-control" disabled>
                            <option value="{{$cliente->tipo_documento}}" selected>{{$cliente->tipo_documento}}</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label for="numero_documento">Número de Documento:</label>
                        <input type="number" name="numero_documento" id="numero_documento" value="{{$cliente->numero_documento}}" class="form-control" disabled>
                    </div>
                    <div class="col-sm-4">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" value="{{$cliente->nombre}}" name="nombre" disabled>
                    </div>     
                    
                </div>
               
                


                <div class="row">
                    <div class="col-sm-4">
                        <label for="fecha_checkin">Inicio de Visita</label>
                        <input type="datetime-local" name="fecha_checkin" id="fecha_checkin" class="form-control">
                    </div>    
                    <div class="col-sm-4">
                        <label for="latitud">Latitud:</label>
                        <input id="latitud" type="number" step="any" class="form-control" name="latitud" required>
                    </div>
                    
                    <div class="col-sm-4">
                        <label for="longitud">Longitud:</label>
                        <input id="longitud" type="number" step="any" class="form-control" name="longitud" required>
                    </div>

                </div>
               
                <div class="row mt-2">
                    <div class="col-sm-12">
                        <button type="button" id="get-location" class="btn btn-default">Check-In</button>    
                    </div>
                </div>
              
               
                <br><br>

                <button type="submit" id="submit-button" class="btn btn-success">Guardar</button>
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="{{ (Config::get('app.env')=='local')?asset('js/maploca.js'):secure_asset('js/maploca.js') }}"></script>
<script>
   $(document).ready(function(){
    
   });
</script>
@stop