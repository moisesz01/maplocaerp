@extends('adminlte::page')

@section('title', 'Sincronización de Parámetros')

@section('content_header')
<div class="breadcrumbs">
    {{ Breadcrumbs::render('sincronizacion.index') }}
</div>
    <h1>Sincronización de Parámetros</h1>
@stop

@section('content')
@include('right_sidebar')
@if (session('info'))
<div class="alert alert-dismissable alert-success">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <strong>
        {{ session('info') }}
    </strong>
</div>
@endif
@if (session('danger'))
<div class="alert alert-dismissable alert-danger">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <strong>
        {{ session('danger') }}
    </strong>
</div>
@endif

<div class="card" style="margin-left: -18px;margin-right: -18px;">
    <div class="card-header" style="margin-left: -5px;margin-right: -5px;">
        Parámetros de Sincronización
    </div>
    <div class="card-body" style="margin-left: -5px;margin-right: -5px;">
          <div class="main-container">
        <div class="cars-container">
            <button class="car-button sports-car" id="btnTasas" onclick="makeAjaxRequest('tasas')">
                <div class="car-icon"><i class="fas fa-dollar-sign"></i></div>
                <div class="car-title">Sincronizar</div>
                <div class="car-description">Tasa de Cambio</div>
                <div class="spinner-container" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </button>
            
            <button class="car-button electric-car" onclick="makeAjaxRequest('inventario')">
                <div class="car-icon"><i class="fas fa-boxes"></i></div>
                <div class="car-title">Sincronizar</div>
                <div class="car-description">Inventario</div>
                <div class="spinner-container" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </button>
            
            <button class="car-button luxury-car" onclick="makeAjaxRequest('emision')">
                <div class="car-icon"><i class="fas fa-file-invoice"></i></div>
                <div class="car-title">Sincronizar </div>
                <div class="car-description">Control de Emisiones</div>
                <div class="spinner-container" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </button>
        </div>
    </div>
    </div>
</div>

@stop

@section('css')
<link rel="stylesheet" href="{{ (Config::get('app.env')=='local')?asset('css/maploca_styles.css'):secure_asset('css/maploca_styles.css') }}">
    <style>
        .cars-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            max-width: 1000px;
        }
        
        .car-button {
            position: relative;
            width: 280px;
            height: 160px;
            border-radius: 15px;
            background: linear-gradient(135deg, #2c3e50, #4ca1af);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            border: none;
            outline: none;
            padding: 20px;
            text-align: center;
        }
        
        .car-button:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.3);
        }
        
        .car-button:active {
            transform: translateY(0);
        }
        
        .car-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }
        
        .car-button:hover::before {
            left: 100%;
        }
        
        .car-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }
        
        .car-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .car-description {
            font-size: 14px;
            opacity: 0.8;
        }
        
        /* Estilos específicos para cada botón */
        .sports-car {
            background: linear-gradient(135deg, #e74c3c, #f39c12);
        }
        
        .electric-car {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
        }
        
        .luxury-car {
            background: linear-gradient(135deg, #9b59b6, #3498db);
        }
        
        /* Spinner */
        .spinner-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 10;
        }
        
        .fa-spinner {
            font-size: 30px;
            color: white;
        }
        
        /* Resultados de AJAX */
        .result-container {
            margin-top: 30px;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            display: none;
        }
        
        .result-title {
            color: #2c3e50;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .result-content {
            color: #34495e;
        }
    </style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function makeAjaxRequest(tipo) {
    // Obtener el botón que se clickeó
    const button = event.currentTarget;
    const spinner = button.querySelector('.spinner-container');
    
    // Mostrar spinner
    spinner.style.display = 'flex';
    
    // Deshabilitar el botón durante la petición
    button.disabled = true;
    
    let apiUrl = '';
    let requestData = {};
    let requestMethod = 'GET';
    let textSuccess = '';
    let textError = '';

    if (tipo === 'tasas') {
        apiUrl = '{{ route("sincronizacion.tasas") }}';
        textSuccess = 'Las tasas de cambio se han sincronizado correctamente.';
        textError = 'Ocurrió un error al sincronizar las tasas de cambio.';
    } else if (tipo === 'inventario') {
        apiUrl = '{{ route("sincronizacion.inventario") }}';
        textSuccess = 'El inventario se ha sincronizado correctamente.';
        textError = 'Ocurrió un error al sincronizar el inventario.';
    } else if (tipo === 'emision') {
        apiUrl = '{{ route("sincronizacion.control_emision") }}';
        textSuccess = 'El control de emisiones se ha sincronizado correctamente.';
        textError = 'Ocurrió un error al sincronizar el control de emisiones.';
    } 

    
            
    $.ajax({
        url: apiUrl,
        type: 'get',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            console.log('Datos recibidos:', data);
            Swal.fire({
                icon: 'success',
                title: '¡Sincronización exitosa!',
                text: textSuccess,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar'
            });
        },
        error: function(xhr, status, error) {
            console.error('Error en la petición AJAX:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error en la sincronización',
                text: textError + error,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Aceptar'
            });
        },
        complete: function() {
            // Ocultar spinner y habilitar botón
            spinner.style.display = 'none';
            button.disabled = false;
        }
    });
            
}

// Asegurar que el código se ejecute después de que jQuery esté cargado
$(document).ready(function() {
    // Puedes agregar otros manejadores de eventos aquí si es necesario
});
</script>
@stop