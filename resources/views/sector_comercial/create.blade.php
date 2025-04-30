@extends('adminlte::page')

@section('title', 'Crear Sector Comercial')

@section('content_header')
<div class="breadcrumbs">
    {{ Breadcrumbs::render('sector_comercial.create') }}
    </div>
    <h1>Crear Sector Comercial</h1>
@stop

@section('content')
@include('right_sidebar')
<div class="row">
        <div class="col-4">
            <form method="POST" action="{{route("sector_comercial.store")}}">
                @csrf
                <div class="form-group">
                    <label class="label">Nombre de la Sector Comercial</label>
                    <input required autocomplete="off" name="nombre" class="form-control"
                           type="text" placeholder="Nombre">
                </div>

                <button class="btn btn-success">Guardar</button>
                <a class="btn btn-primary" href="{{route("sector_comercial.index")}}">Volver</a>
            </form>
        </div>
    </div>

@stop
@section('css')
<link rel="stylesheet" href="{{ (Config::get('app.env')=='local')?asset('css/maploca_styles.css'):secure_asset('css/maploca_styles.css') }}">
@stop
