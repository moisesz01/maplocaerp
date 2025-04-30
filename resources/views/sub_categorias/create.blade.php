@extends('adminlte::page')

@section('title', 'Crear SubCategorías')

@section('content_header')
    <div class="breadcrumbs">
        {{ Breadcrumbs::render('subcategoria.create') }}
    </div>
    <h1>Crear SubCategorías</h1>
@stop

@section('content')
    @include('right_sidebar')

    <form method="POST" action="{{ route('subcategorias.store') }}" enctype="multipart/form-data">
        @csrf
        @method('POST')
        <div id="categorias-container">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <label for="nombre_sub_categoria">Nombre de la Subcategoría:</label>
                    <input type="text" name="nombre_sub_categoria" class="form-control"
                        placeholder="Nombre de la Subcategoría" required>
                </div>
                <div class="col-sm-6">
                    <label for="categoria">Categoría:</label>
                    <select name="categoria_id" id="categoria" class="form-control select2" required>
                        <option value="">Seleccione una categoría</option>
                        @foreach ($categoria as $cat)
                            <option value="{{ $cat->id }}">{{ ucfirst($cat->categoria) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-12">
                    <label for="imagen">Imagen de la SubCategorías:</label>
                    <input  style="height: 80px"type="file" name="imagen" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="row mb-2">

                <div class="col-sm-11">
                    <label for="categoria_as400">Categoría AS400:</label>
                    <select name="categoria_as400[]" class="form-control select2" required>
                        <option value="">Seleccione una categoría AS400</option>
                    </select>
                </div>
                <div class="col-sm-1 d-flex align-items-end">
                    <button type="button" class="btn btn-success" id="agregar-codigo">+</button>
                </div>
            </div>
        </div>

        <div id="codigos-as400-container"></div>

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
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
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

            $('#categoria').on('change', function() {
                let categoriaId = $('#categoria').val();

                $.ajax({
                    url: '{{ route('subcategorias.filter') }}',
                    method: 'GET',
                    data: {
                        id: categoriaId
                    },
                    success: function(response) {
                        console.log('Respuesta AJAX:',
                            response);

                        codigosAS400 = response;

                        if (Array.isArray(codigosAS400)) {
                            let select = $('select[name="categoria_as400[]"]').first();
                            select.empty().append(
                                '<option value="">Seleccione una categoría AS400</option>');

                            codigosAS400.forEach(function(codigo) {
                                select.append('<option value="' + codigo + '">' +
                                    codigo + '</option>');
                            });

                            initSelect2();
                        } else {
                            console.error('La respuesta no es un array:', codigosAS400);
                        }
                    }
                });
            });




            document.getElementById('agregar-codigo').addEventListener('click', function() {
                const container = document.getElementById('codigos-as400-container');

                const newRow = document.createElement('div');
                newRow.classList.add('row', 'mb-2', 'codigo-row');

                // Construye el select con las opciones actuales
                let optionsHtml = '<option value="">Seleccione un código</option>';
                codigosAS400.forEach(function(codigo) {
                    optionsHtml += '<option value="' + codigo + '">' + codigo + '</option>';
                });

                newRow.innerHTML = `
                    <div class="col-sm-11">
                        <select name="categoria_as400[]" class="form-control select2" required>
                            ${optionsHtml}
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
        });
    </script>

@stop
