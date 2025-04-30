@extends('adminlte::page')

@section('title', 'Bienvenido a Nuestro Mundo del Vino')

@section('content_header')
@stop

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center">
            <h1 style="margin-top: 60px; ">Configuraciones</h1>
        </div>

        <div class="row justify-content-center align-items-center" style="height: 30vh ">

            <div class="col-md-3">
                <div class="card" style="background-color: #e7ecf5;">
                    <h5 class="card-title text-center">Tipo de Cliente</h5>
                    <div class="card-body text-center">
                        <a href="{{ route('tipo_cliente.index') }}" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-users fa-5x"></i>
                        </a>
                        
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="background-color: #e7ecf5;">
                    <h5 class="card-title text-center">Tipo de Actividad</h5>
                    <div class="card-body text-center">
                        <a href="{{ route('tipo_actividad.index') }}" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-ticket-alt fa-5x"></i>
                        </a>
                        
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card" style="background-color: #e7ecf5;">
                    <h5 class="card-title text-center">Conceptos</h5>
                    <div class="card-body text-center">
                        <a href="{{ route('conceptos.index') }}" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-sticky-note fa-5x"></i>
                        </a>
                        
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card" style="background-color: #e7ecf5;">
                    <h5 class="card-title text-center">Actividades</h5>
                    <div class="card-body text-center">
                        <a href="{{ route('actividades.index') }}" style="text-decoration: none; color: inherit;">
                            <i class="fas fa-sliders-h fa-5x"></i>
                        </a>
                        
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha384-s5vD7lwB0nHDuVweBBjKvFZyCVik4kW+OzWn1lI9Qq2ULjoRrAExE/xq7zWCGzF/" crossorigin="anonymous">
    <style>
        .hola {
            margin-bottom: 40px
        }
    </style>
@stop

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
@stop