@extends('adminlte::page')

@section('title', 'Forbidden')

@section('content_header')
    <h1>Forbidden</h1>
@stop

@section('content')
@include('right_sidebar')
    <div class="error-page">
        <h2 class="headline text-danger">403</h2>
        <div class="error-content">
            <h3><i class="fas fa-exclamation-triangle text-danger"></i> Acceso Denegado</h3>
            <p>No tiene permiso para acceder a este módulo.</p>
            <p>Por favor, póngase en contacto con su administrador para obtener más información.</p>
            <button class="btn btn-primary" onclick="goBack()">Volver</button>
        </div>
    </div>
@stop

@section('css')
    <style>
       .error-page {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f5f5f5;
            background-size: cover;
        }
       .headline {
            font-size: 10rem;
            margin: 0;
        }
       .error-content {
            text-align: center;
            padding: 1rem;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 0.5rem;
        }
       .btn {
            margin-top: 1rem;
        }
    </style>
@stop

@section('js')
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
@stop