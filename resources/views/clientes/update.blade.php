@extends('adminlte::page')

@section('title', 'Actualizar Cliente')

@section('content_header')
<div class="breadcrumbs"> 
   
    </div>
    <h1>Actualizar Cliente</h1>
@stop

@section('content')
@include('right_sidebar')
@php
    date_default_timezone_set('America/Caracas');
@endphp
<div class="card">
    <div class="card-header">
        Formulario de actualización de clientes
    </div>
    <div class="card-body">
       
            <form method="POST" action="{{route('clientes.guardar',['cliente_id'=>$cliente->id])}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="update" value="1">
                <div class="row">
                    <div class="col-sm-4">
                        <label for="tipo_documento">Tipo de Documento:</label>
                        <select name="tipo_documento" id="tipo_documento" class="form-control">
                            <option value="V" {{($cliente->tipo_documento=='V')? 'selected':''}}>V</option>
                            <option value="J" {{($cliente->tipo_documento=='J')? 'selected':''}}>J</option>
                           
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label for="numero_documento">Número de Documento:</label>
                        <input type="text" name="numero_documento" value="{{$cliente->numero_documento}}" class="form-control">
                    </div>
                    <div class="col-sm-4">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" value="{{$cliente->nombre}}" name="nombre" id="nombre">
                    </div>     
                    <div class="col-sm-4">
                        <label for="copiar_datos">Copiar datos</label>
                        <input type="checkbox" name="copiar_datos" id="copiar_datos">
                    </div> 
                    
                </div>



                <div class="row">
                    <div class="col-sm-4">
                        <label for="denominacion_comercial">Denominación Comercial</label>
                        <input type="text" class="form-control" name="denominacion_comercial" id="denominacion_comercial" value="{{$cliente->denominacion_comercial}}">
                    </div>
                    <div class="col-sm-4">
                        <label for="persona_contacto">Persona Contacto</label>
                        <input type="text" class="form-control" name="persona_contacto" id="persona_contacto" value="{{$cliente->persona_contacto}}">
                    </div>
                    <div class="col-sm-4">
                        <label for="cargo_profesion">Cargo o Profesión</label>
                        <input type="text" class="form-control" name="cargo_profesion" id="cargo_profesion" value="{{$cliente->cargo_profesion}}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <label for="correo">Correo:</label>
                        <input type="email" class="form-control" value="{{$cliente->correo}}" name="correo">
                    </div>
                    <div class="col-sm-6">
                        <label for="telefono">Teléfono: (Formato ejm: 0424-1234567)</label>
                        <input type="text" name="telefono" class="form-control" value="{{$cliente->telefono}}" pattern="[0-9]{4}-[0-9]{7}">
                    </div>
                </div>
                @php
                    $estado_id = $cliente->ciudad->estado_id ?? 0;
                @endphp
                <div class="row">
                    <div class="col-sm-6">
                        <label for="pais_id">Estado:</label>
                        <select class="form-control estado_id" id="estado_id" name="estado_id" required>
                            <option value="">Seleccione un estado:</option>
                            @foreach($estados as $estado)
                                <option value="{{ $estado->id }}" {{ ( $estado_id==$estado->id )?'selected':'' }}>{{ $estado->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label for="ciudad_id">Ciudad:</label>
                        <select class="form-control ciudad_id" id="ciudad_id" name="ciudad_id" required>
                            <option value="">Seleccione una ciudad</option>
                            @foreach($ciudades as $ciudad)
                                <option value="{{ $ciudad->id }}" data-estado-id="{{ $ciudad->estado_id }}">{{ $ciudad->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="direccion">Dirección: </label>
                        <textarea class="form-control" id="direccion" name="direccion" rows="2">{{$cliente->direccion}}</textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="sector_comercial_id">Sector Comercial:</label>
                        <select class="form-control sector_comercial_id" id="sector_comercial_id" name="sector_comercial_id" required>
                            <option value="">Seleccione un sector</option>
                            @foreach($sectores as $sector)
                                <option value="{{ $sector->id }}" {{($cliente->sector_comercial_id==$sector->id)?'selected':''}}>{{ $sector->nombre }}</option>

                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label for="vendedor_id">Vendedor Asignado:</label>
                        <select class="form-control" id="vendedor_id" name="vendedor_id" required>
                            <option value="">Seleccione un Vendedor</option>
                            @foreach($vendedores as $vendedor)
                                <option value="{{ $vendedor->id }}" {{($cliente->vendedor_id==$vendedor->id)?'selected':''}}>{{ $vendedor->name }}</option>

                            @endforeach
                        </select>
                    </div>
                    
                </div>


            
              

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="direccion">Observaciones: </label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="2">{{$cliente->observaciones}}</textarea>
                    </div>
                </div>

                

                <br><br>

                <button type="submit" id="submit-button" class="btn btn-success">Actualizar</button>
            </form>
        
    </div>
</div>
<div id="myModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="img01">
</div>
@stop

@section('css')
<link rel="stylesheet" href="{{ (Config::get('app.env')=='local')?asset('css/maploca_styles.css'):secure_asset('css/maploca_styles.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
<style>
     .modal {
display: none;
position: fixed;
z-index: 1;
padding-top: 120px;
left: 0;
top: 0;
width: 100%;
height: 100%;
overflow: auto;
background-color: rgb(0,0,0);
background-color: rgba(0,0,0,0.9);
}

.modal-content {
margin: auto;
display: block;
width: 80%;
max-width: 720px;
}

.close {
position: absolute;
top: 55px;
right: 35px;
color: #f1f1f1;
font-size: 40px;
font-weight: bold;
transition: 0.3s;
}

.close:hover,
.close:focus {
color: #bbb;
text-decoration: none;
cursor: pointer;
}
</style>
@stop

@section('js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
<script src="{{ (Config::get('app.env')=='local')?asset('js/maploca.js'):secure_asset('js/maploca.js') }}"></script>
<script>
    $(document).ready(function() {

        $('#copiar_datos').on('change', function() {
            
            if ($(this).is(':checked')) {
                var nombre = $('#nombre').val();
                $('#denominacion_comercial').val(nombre);
                $('#persona_contacto').val(nombre);
               
            } else {
                $('#denominacion_comercial').val('');
                $('#persona_contacto').val('');
               
            }
        });
        var ciudades = {!! json_encode($ciudades) !!};
        var modal = document.getElementById("myModal");
        var modalImg = document.getElementById("img01");
        $('.open-popup').click(function(){
            modal.style.display = "block";
            modalImg.src = this.src;
        });
        $('.close').click(function(){
            modal.style.display = "none";
        });


        
        get_ciudades({{$cliente->ciudad->estado_id ?? 0}},{{$cliente->ciudad_id ?? 0}});
        function get_ciudades(estadoId,ciudadId){
            var estadoId = estadoId;
            var ciudadSelect = $('#ciudad_id');
            ciudadSelect.empty();
            $.each(ciudades, function(index, ciudad) {
                let selected = (ciudad.id == ciudadId) ? 'selected' : '';
                ciudadSelect.append('<option value="' + ciudad.id + '"' + selected + '>' + ciudad.nombre + '</option>');
            });
            ciudadSelect.trigger('change');

           
        }
        $('#estado_id').on('change', function() {
            var estadoId = $(this).val();
            var ciudadSelect = $('#ciudad_id');
            ciudadSelect.empty(); // Limpia el select de ciudades

            // Filtra las ciudades donde el estado_id coincide con el id del estado seleccionado
            $.each(ciudades, function(index, ciudad) {
                if (ciudad.estado_id == estadoId) {
                    ciudadSelect.append('<option value="' + ciudad.id + '">' + ciudad.nombre + '</option>');
                }
            });
        });
       
        select2_autocompletado('.estado_id','Seleccione un estado');
        select2_autocompletado('.ciudad_id','Seleccione una ciudad');
        select2_autocompletado('.sector_comercial_id','Seleccione un sector');
    });
</script>
@stop