@extends('adminlte::page')

@section('title', 'Sector Comercial')

@section('content_header')
<div class="breadcrumbs">
    {{ Breadcrumbs::render('sector_comercial.edit') }}
    </div>
    <h1>Actualizar Sector Comercial</h1>
@stop

@section('content')
@include('right_sidebar')
<div class="row">
    <div class="col-12">
        <form method="POST" action="{{route("sector_comercial.update", ['id_sector'=>$sector->id])}}">
            @method("PUT")
            @csrf
            <div class="form-group">
                <label class="label">Nombre:</label>
                <input required value="{{$sector->nombre}}" autocomplete="off" name="nombre" class="form-control"
                       type="text" placeholder="Sector Comercial">
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
