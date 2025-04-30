@extends('adminlte::page')

@section('title', 'Editar SubCategorías')

@section('content_header')
    <div class="breadcrumbs">
        {{ Breadcrumbs::render('subcategoria.create') }}
    </div>
    <h1>Editar SubCategorías</h1>
@stop

@section('content')
    @include('right_sidebar')

    <form method="POST" action="{{ route('subcategorias.update', $subcategoria->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input hidden name="id" value="{{ $subcategoria->id }}">

        <div id="categorias-container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <label for="nombre_sub_categoria">Nombre de la Subcategoría:</label>
                    <input type="text" name="nombre_sub_categoria" class="form-control"
                        placeholder="Nombre de la Subcategoría"
                        value="{{ old('subcategoria', $subcategoria->subcategoria) }}" required>
                </div>
                <div class="col-sm-6">
                    <label for="categoria">Categoría:</label>
                    <select name="categoria_id" id="categoria" class="form-control select2" required>
                        <option value="">Seleccione una categoría</option>
                        @foreach ($categorias as $cat)
                            <option value="{{ $cat->id }}" @if (old('categoria_id', $subcategoria->categoria_id ?? '') == $cat->id) selected @endif>
                                {{ ucfirst($cat->categoria) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-12">
                <label for="imagen">Imagen de la SubCategoría:</label>
                <input type="file" name="imagen" class="form-control" accept="image/*" style="height: 80px;">

                @if (!empty($subcategoria->imagen_temporal))
                    <div class="mb-2 mt-2">
                        <img src="{{ $subcategoria->imagen_temporal }}" alt="Imagen actual de subcategoría"
                            style="max-height: 150px;" class="img-thumbnail">
                    </div>
                @elseif (!empty($subcategoria->imagen))
                    <div class="alert alert-warning mt-2">
                        <i class="fas fa-exclamation-triangle"></i>
                        La imagen existe pero no se puede mostrar ({{ $subcategoria->imagen }})
                    </div>
                @endif
            </div>
            <div class="row mb-2 align-items-end">

                @if (empty($codigosAS400))
                    <div class="col-sm-11">
                        <label for="categoria_as400">Categoría AS400:</label>
                        <select name="codigos[]" class="form-control select2" required>
                            <option value="">Seleccione una categoría AS400</option>
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <button type="button" class="btn btn-success" id="agregar-codigo">+</button>
                    </div>
                @else
                    <div class="col-sm-11">
                        <label for="categoria_as400">Categoría AS400:</label>
                        <select name="codigos[]" class="form-control select2 select-as400" required>
                            <option value="">Seleccione un código</option>
                            @foreach ($codigosAS400 as $linea)
                                <option value="{{ $linea }}" @if ($linea == $codigosAS400[0]) selected @endif>
                                    {{ $linea }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <button type="button" class="btn btn-success" id="agregar-codigo">+</button>
                    </div>
                @endif
            </div>

            @foreach ($codigosAS400 as $index => $codigo)
                @if ($index > 0)
                    <div class="row mb-2 align-items-end codigo-row">
                        <div class="col-sm-11">
                            <select name="codigos[]" class="form-control select2 select-as400" required>
                                <option value="">Seleccione un código</option>
                                @foreach ($codigosAS400 as $linea)
                                    <option value="{{ $linea }}" @if ($linea == $codigo) selected @endif>
                                        {{ $linea }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <button type="button" class="btn btn-danger eliminar-codigo">−</button>
                        </div>
                    </div>
                @endif
            @endforeach



        </div>

        <div id="codigos-as400-container">

        </div>

        <div class="row pt-4">
            <div class="col-sm-6">
                <button type="submit" class="btn btn-success">Guardar</button>
                <a class="btn btn-primary" href="{{ route('subcategorias.index') }}">Volver</a>
            </div>
        </div>
    </form>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/maploca_styles.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css"
        rel="stylesheet" />
    <style>
        .select2 {
            width: 100% !important;
        }
    </style>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('eliminar-codigo')) {
                e.target.closest('.codigo-row').remove();
            }
        });
        $(document).ready(function() {
            let codigoIndex = 1;
            let codigosAS400 = [];


            function initSelect2() {
                $('.select2').select2({
                    theme: 'bootstrap',
                    placeholder: 'Seleccione un código',
                    width: '100%'
                });
            }

            initSelect2();

            let categoriaIdInicial = $('#categoria').val();

            if (categoriaIdInicial) {
                $.ajax({
                    url: '{{ route('subcategorias.filter') }}',
                    method: 'GET',
                    data: {
                        id: categoriaIdInicial
                    },
                    success: function(response) {
                        console.log('Códigos cargados al inicio:', response);

                        codigosAS400 = response;

                        if (Array.isArray(codigosAS400)) {
                            $('select[name="codigos[]"]').each(function() {
                                const currentValue = $(this)
                                    .val();
                                $(this).empty().append(
                                    '<option value="">Seleccione una categoría AS400</option>'
                                );

                                codigosAS400.forEach(function(codigo) {
                                    const selected = (codigo == currentValue) ?
                                        'selected' : '';
                                    $(this).append('<option value="' + codigo + '" ' +
                                        selected + '>' + codigo + '</option>');
                                }.bind(
                                    this));

                                $(this).val(currentValue);
                            });

                            initSelect2();
                        }
                    }
                });
            }





            document.getElementById('agregar-codigo').addEventListener('click', function() {

                const container = document.getElementById('codigos-as400-container');

                const newRow = document.createElement('div');
                newRow.classList.add('row', 'mb-2', 'codigo-row');

                let optionsHtml = '<option value="">Seleccione un código</option>';
                codigosAS400.forEach(function(codigo) {
                    optionsHtml += '<option value="' + codigo + '">' + codigo + '</option>';
                });
                newRow.innerHTML = `

    <div class="col-sm-11">
        <select name="codigos[]" class="form-control select2" required>
            ${optionsHtml}
        </select>
    </div>
    <div class="col-sm-1 d-flex align-items-end">
        <button type="button" class="btn btn-danger btn-remove">−</button>
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
        });
    </script>

@stop
