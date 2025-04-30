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
            <form method="POST" action="{{route('precio_competencia.store')}}" enctype="multipart/form-data">
                @csrf
            
                
                <div id="rows-container">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="nombre_articulo">Articulo:</label>
                            <input type="hidden" name="codigo_articulo[]" class="codigo_articulo">
                            <input type="text" id="search-product-input" class="form-control search-product-input" placeholder="Buscar producto..." autocomplete="off" name="nombre_articulo[]" required>
                            <ul id="search-product-list" class="list-group">
                                {{-- Resultados de la búsqueda --}}
                            </ul>
                        </div>
                        <div class="col-sm-6">
                            <label for="competidor">Competidor:</label>
                            <input type="text" name="competidor[]" class="form-control" required>
                        </div>
                        <div class="col-sm-6">
                            <label for="tipo_precio">Tipo de Precio:</label>
                            <select name="tipo_precio[]" id="tipo_precio" class="form-control" required>
                                <option value="detal">Al Detal</option>
                                <option value="mayor">Al Mayor</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label for="precio">precio:</label>
                            <input type="number" step="any" name="precio[]" class="form-control" required>
                        </div>
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-danger remove-row"><i class="fas fa-trash-alt"></i> Eliminar </button>
                        </div>
                    </div>
                </div>

                <button type="submit" id="submit-button" class="btn btn-success mt-2">Guardar</button>
                <button type="button" id="add-row" class="btn btn-primary mt-2"><i class="fas fa-plus-circle"></i> Agregar fila</button>
            </form>
        
        </div>
    </div>
   
@stop

@section('css')
    <link rel="stylesheet" href="{{ (Config::get('app.env')=='local')?asset('css/maploca_styles.css'):secure_asset('css/maploca_styles.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
@stop

@section('js')
@section('js')
<script>
    $(document).ready(function() {
        $('#add-row').on('click', function() {
            var row = $('#rows-container .row').first().clone();
            row.find('input, select').val('');
            $('#rows-container').append(row);
        });

        $(document).on('click', '.remove-row', function() {
            if ($('#rows-container .row').length > 1) {
                $(this).closest('.row').remove();
            } 
        });

        const url = '{{route("pedido.productos")}}';

        $(document).on('input', '.search-product-input', debounce(function() {
            var $input = $(this);
            var $list = $input.siblings('.list-group');
            searchProducts($input, $list, url);
        }, 500));
    });



    function debounce(func, wait) {
    let timeout;
    return function(...args) {
        const later = () => {
            timeout = null;
            func.apply(this, args); // <--- Use apply() to preserve this context
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function searchProducts($input, $list, url) {
    $list.empty();
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            search: $input.val(),
            almacen_codigo: 'no aplica'
        },
        success: function(data) {
            $list.empty();
            if (data.data && data.data.length > 0) {
                $.each(data.data, function(index, product) {
                    $list.append(`
                        <li class="list-group-item">
                            <a href="#" class="select-product" data-producto-id="${product.arti87}" data-producto="${product.desc87}">${product.desc87}</a>
                        </li>
                    `);
                });
            } else {
                $list.append(`
                    <li class="list-group-item">
                        <p>No se encontraron productos con la descripción suministrada. <strong>Presiona aquí para guardar producto no existente</  strong></p>
                    </li>
                `);
            }
        },
        error: function(xhr, status, error) {
            $list.empty();
            $list.append(`
                <li class="list-group-item">
                    <p>Error al buscar productos: ${error}</p>
                </li>
            `);
        }
    });
}

$(document).on('click', '.select-product', function() {
    let input = $(this).closest('.row').find('.search-product-input');
    let producto_id = $(this).data('producto-id');
    let producto = $(this).data('producto');
    input.val(producto);
    input.closest('.row').find('.codigo_articulo').val(producto_id);
    let list = $(this).closest('.row').find('.list-group');
    list.empty();
});
$(document).on('click', '.list-group-item p', function() {
    let list = $(this).closest('.list-group');
    list.empty();
});
</script>

@stop