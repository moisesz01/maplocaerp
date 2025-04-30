@extends('adminlte::page')

@section('title', 'Crear Categoria')

@section('content_header')
    <div class="breadcrumbs">
        {{ Breadcrumbs::render('categoria.create') }}
    </div>
    <h1>Crear Categorías</h1>
@stop

@section('content')
    @include('right_sidebar')

    <form method="POST" action="{{ route('categorias.store') }}" enctype="multipart/form-data">
        @csrf

        <div id="categorias-container">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <label for="nombre_categoria">Nombre de la categoría:</label>
                    <input type="text" name="categoria" class="form-control" placeholder="Nombre de la categoría" required>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-12">
                    <label for="descripcion">Descripción:</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="3"
                        placeholder="Ingrese una descripción opcional"></textarea>
                </div>
            </div>
            <div class="col-sm-12">
                <label for="imagen">Imagen de la categoría:</label>
                <input  style="height: 80px"type="file" name="imagen" class="form-control" accept="image/*">
            </div>

            <div id="codigos-as400-container">
                <div class="row mb-2 codigo-row align-items-end">
                    <div class="col-sm-11">
                        <label for="codigo">Código AS400:</label>
                        <select name="codigos[]" class="form-control select-as400" required>
                            @foreach ($lineas as $linea)
                                <option value="{{ $linea }}">{{ $linea }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <button type="button" class="btn btn-success" id="agregar-codigo">+</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="row pt-4">
            <div class="col-sm-6">
                <button class="btn btn-success">Guardar</button>
                <a class="btn btn-primary" href="{{ route('categorias.index') }}">Volver</a>
            </div>
        </div>
    </form>
@stop

@section('css')
    <link rel="stylesheet"
        href="{{ Config::get('app.env') == 'local' ? asset('css/maploca_styles.css') : secure_asset('css/maploca_styles.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css"
        rel="stylesheet" />
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        let codigoIndex = 1;

        function initSelect2() {
            $('.select-as400').select2({
                theme: 'bootstrap',
                placeholder: 'Seleccione un código',
                width: '100%'
            });
        }

        initSelect2();

        document.getElementById('agregar-codigo').addEventListener('click', function() {
            const container = document.getElementById('codigos-as400-container');

            const newRow = document.createElement('div');
            newRow.classList.add('row', 'mb-2', 'codigo-row');

            newRow.innerHTML = `
        <div class="col-sm-11">
            <select name="codigos[]" class="form-control select-as400" required>
                <option value="">Seleccione un código</option>
                 @foreach ($lineas as $linea)
                                <option value="{{ $linea }}">{{ $linea }}</option>
                            @endforeach
            </select>
        </div>
        <div class="col-sm-1 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-remove">-</button>
        </div>
    `;

            container.appendChild(newRow);
            initSelect2();
            codigoIndex++;
        });

        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('btn-remove')) {
                e.target.closest('.codigo-row').remove();
            }
        });
    </script>
@stop
