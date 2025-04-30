@extends('adminlte::page')

@section('title', 'Crear permiso')

@section('content_header')
    <h1>Crear Permiso</h1>
@stop

@section('content')
<form method="POST" action="{{route("user.store_permiso_spatie")}}">
    @csrf
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label class="label">Nombre del Permiso</label>
                <input required autocomplete="off" name="permiso" class="form-control"
                    type="text" placeholder="Nombre del Permiso">
            </div>
        </div>

        
    </div>
   
    <button class="btn btn-success">Guardar</button>

</form>
@stop

