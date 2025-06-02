@extends('adminlte::page')

@section('title', 'Registrar Cliente')

@section('content_header')
<div class="breadcrumbs"> 
   
    </div>
    <h1>Registrar Cliente</h1>
@stop

@section('content')
@include('right_sidebar')
@php
    date_default_timezone_set('America/Caracas');
@endphp
<div class="card">
    <div class="card-header">
        Formulario de Registro de Clientes
    </div>
    <div class="card-body">
       
            <form method="POST" action="{{route('clientes.guardar')}}" enctype="multipart/form-data">
                @csrf
               
                <div class="row">
                    <div class="col-sm-4">
                        <label for="tipo_documento">Tipo de Documento:</label>
                        <select name="tipo_documento" id="tipo_documento" class="form-control">
                            <option value="V">V</option>
                            <option value="J">J</option>
                            <option value="E">E</option>
                            <option value="P">P</option>
                            <option value="G">G</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label for="numero_documento">Número de Documento:</label>
                        <input type="text" name="numero_documento" id="numero_documento" class="form-control">
                    </div>
                    <div class="col-sm-4">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="nombre">
                    </div>    
                    <div class="col-sm-4">
                        <label for="copiar_datos">Copiar datos</label>
                        <input type="checkbox" name="copiar_datos" id="copiar_datos">
                    </div> 
                    
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label for="denominacion_comercial">Denominación Comercial</label>
                        <input type="text" class="form-control" name="denominacion_comercial" id="denominacion_comercial">
                    </div>
                    <div class="col-sm-4">
                        <label for="persona_contacto">Persona Contacto</label>
                        <input type="text" class="form-control" name="persona_contacto" id="persona_contacto">
                    </div>
                    <div class="col-sm-4">
                        <label for="cargo_profesion">Cargo o Profesión</label>
                        <input type="text" class="form-control" name="cargo_profesion" id="cargo_profesion">
                    </div>
                </div>
               
                <div class="row">
                    <div class="col-sm-6">
                        <label for="correo">Correo:</label>
                        <input type="email" class="form-control" value="" name="correo">
                    </div>
                    <div class="col-sm-6">
                        <label for="telefono">Teléfono: (Formato ejm: 0424-1234567)</label>
                        <input type="text" name="telefono" class="form-control" pattern="[0-9]{4}-[0-9]{7}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <label for="pais_id">Estado:</label>
                        <select class="form-control estado_id" id="estado_id" name="estado_id" required>
                            <option value="">Seleccione un estado:</option>
                            @foreach($estados as $estado)
                                <option value="{{ $estado->id }}">{{ $estado->nombre }}</option>
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
                        <textarea class="form-control" id="direccion" name="direccion" rows="2" required></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="sector_comercial_id">Sector Comercial:</label>
                        <select class="form-control sector_comercial_id" id="sector_comercial_id" name="sector_comercial_id" required>
                            <option value="">Seleccione un sector</option>
                            @foreach($sectores as $sector)
                                <option value="{{ $sector->id }}">{{ $sector->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="direccion">Observaciones: </label>
                        <textarea class="form-control" id="observaciones" name="observaciones" rows="2"></textarea>
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
        $('#numero_documento').on('blur', function() {
            var tipoDocumento = $('#tipo_documento').val();
            var numeroDocumento = $(this).val();
            if (tipoDocumento && numeroDocumento) {
                $.ajax({
                    type: 'GET',
                    url: '{{ route("clientes.verificar_documento") }}',
                    data: {
                        tipo_documento: tipoDocumento,
                        numero_documento: numeroDocumento
                    },
                    success: function(response) {
                        if (response.existe) {
                            // Mostrar mensaje de error
                            $('#numero_documento').addClass('is-invalid');
                            $('#numero_documento').next('.invalid-feedback').text('El número de documento ya existe');
                            // Deshabilitar botón de submit
                            $('#submit-button').prop('disabled', true);
                            // Mostrar sweet alert
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'El cliente ya se encuentra registrado',
                            });
                        } else {
                            // Quitar mensaje de error
                            $('#numero_documento').removeClass('is-invalid');
                            $('#numero_documento').next('.invalid-feedback').text('');
                            // Habilitar botón de submit
                            $('#submit-button').prop('disabled', false);
                        }
                    }
                });
            } else {
                // Habilitar botón de submit si no se ha ingresado número de documento
                $('#submit-button').prop('disabled', false);
            }
        });
        var ciudades = {!! json_encode($ciudades) !!};
    



        select2_autocompletado('.estado_id','Seleccione un estado')
        select2_autocompletado('.ciudad_id','Seleccione una ciudad')
        select2_autocompletado('.sector_comercial_id','Seleccione un sector')

        // Agregamos el evento change al select estado_id
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
        
    });
</script>
@stop