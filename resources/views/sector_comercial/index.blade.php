@extends('adminlte::page')

@section('title', 'Sectores Comerciales')

@section('content_header')
    <div class="breadcrumbs">
        {{ Breadcrumbs::render('sector_comercial.index') }}
    </div>
    <h1>Sectores Comerciales</h1>
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
        <div class="row">
            <div class="col-sm-12">
                <a href="{{ route('sector_comercial.create') }}" class="btn btn-success mb-2 float-right"><i class="fas fa-plus"></i> Agregar</a>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                Listado de Sectores
            </div>
            <div class="card-body">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th class="text-center ">Id</th>
                            <th class="text-center ">Sector</th>
                            <th class="text-center ">Editar</th>
                    </thead>
                    <tbody>
                        @foreach ($sectores as $sector)
                            <tr>
                                <td class="text-center ">{{ $sector->id }}</td>
                                <td class="text-center ">{{ $sector->nombre }}</td>
                                <td class="text-center ">
                                    <a class="btn btn-warning"
                                        href="{{ route('sector_comercial.edit', ['id_sector' => $sector]) }}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>


    </div>
    </div>

@stop
@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet"
        href="{{ Config::get('app.env') == 'local' ? asset('css/maploca_styles.css') : secure_asset('css/maploca_styles.css') }}">

@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.table').DataTable({
                "language": 
                    {
                        "sProcessing":     "Procesando...",
                        "sLengthMenu":     "Mostrar _MENU_ registros",
                        "sZeroRecords":    "No se encontraron resultados",
                        "sEmptyTable":     "Ningún dato disponible en esta tabla",
                        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
                        "sSearch":         "Buscar:",
                        "sInfoThousands":  ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst":    "Primero",
                            "sLast":     "Último",
                            "sNext":     "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        },
                        "buttons": {
                            "copy": "Copiar",
                            "colvis": "Visibilidad"
                        }
                    },
                "responsive": true,
                "autoWidth": false,
                "columnDefs": [{
                    "orderable": false,
                    "targets": 'no-sort'
                }]
            });
        });
    </script>
@stop
