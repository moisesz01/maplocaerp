@extends('adminlte::page')

@section('title', 'Editar Categoria')

@section('content_header')
    <div class="breadcrumbs">
        {{ Breadcrumbs::render('categoria.edit') }}
    </div>
    <h1>Editar Categorías</h1>
@stop

@section('content')
    @include('right_sidebar')

    <form method="POST" action="{{ route('categorias.update', $categoria->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input hidden name="id" value="{{ $categoria->id }}">
        <div id="categorias-container">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <label for="nombre_categoria">Nombre de la categoría:</label>
                    <input type="text" name="categoria" class="form-control"
                        value="{{ old('categoria', $categoria->categoria) }}" required>

                </div>
            </div>

            <div class="row mb-2">
                <div class="col-sm-12">
                    <label for="descripcion">Descripción:</label>
                    <textarea name="descripcion" id="descripcion" class="form-control" rows="3"
                        placeholder="Ingrese una descripción opcional" required>{{ old('descripcion', $categoria->descripcion) }}</textarea>
                </div>
            </div>
            <div class="col-sm-12">
                <input type="file" name="imagen" class="form-control" accept="image/*" style="height: 80px;">
                @if (!empty($categoria->imagen_temporal))
                    <div class="mb-2">
                        <img src="{{ $categoria->imagen_temporal }}" alt="Imagen de categoría" style="max-height: 150px;"
                            class="img-thumbnail">
                    </div>
                @elseif (!empty($categoria->imagen))
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> La imagen existe en el sistema pero no se pudo generar
                        una vista previa temporal
                    </div>
                @endif
            </div>

            <div id="codigos-as400-container">
                <label for="codigo">Código AS400:</label>

                @if (count($codigosAS400) > 0)
                    @foreach ($codigosAS400 as $index => $codigo)
                        <div class="row mb-2 codigo-row align-items-end">
                            <div class="col-sm-11">
                                <select name="codigos[]" class="form-control select-as400" required>
                                    <option value="">Seleccione un código</option>
                                    @foreach ($lineas as $linea)
                                        <option value="{{ $linea }}"
                                            @if ($linea == $codigo) selected @endif>
                                            {{ $linea }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-1">
                                @if ($index === 0)
                                    <button type="button" class="btn btn-success" id="agregar-codigo">+</button>
                                @else
                                    <button type="button" class="btn btn-danger eliminar-codigo">−</button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <!-- Si no existen categorías AS400, mostramos el select por defecto -->
                    <div class="row mb-2 codigo-row align-items-end">
                        <div class="col-sm-11">
                            <select name="codigos[]" class="form-control select-as400" required>
                                <option value="">Seleccione un código</option>
                                @foreach ($lineas as $linea)
                                    <option value="{{ $linea }}">{{ $linea }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <button type="button" class="btn btn-success" id="agregar-codigo">+</button>
                        </div>
                    </div>
                @endif
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
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('eliminar-codigo')) {
                e.target.closest('.codigo-row').remove();
            }
        });
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
