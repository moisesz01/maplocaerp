@extends('adminlte::page')

@section('title', 'Cotizaciones')

@section('content_header')
<div class="breadcrumbs">
    {{ Breadcrumbs::render('pedidos.index') }}
</div>
    <h1>Cotizaciones</h1>
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
        <div class="d-flex justify-content-between">
            Listado de Cotizaciones
            <a href="{{ route('pedido.crear',['visita_id' =>0]) }}" class="btn btn-success"> <i class="fas fa-plus"></i> Nueva Cotización</a>
        </div>
    </div>
    <div class="card-body" style="margin-left: -5px;margin-right: -5px;">

        <form id="search-form" class="form-inline" role="form">
            <input type="text" id="cliente" placeholder="Buscar Cliente" class="form-control mr-1">
            <select name="status_id" id="status_id" class="form-control">
                <option value="">Seleccione Status...</option>
                @foreach ($estados as $item)
                    <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                @endforeach
            </select>
            <button id="buscar" class="btn btn-primary ml-1 mt-1">Buscar</button>
            <button id="clean" class="btn btn-danger mt-1" style="margin-left: 10px;"><i class="fas fa-eraser"></i></button>
            <a title="Descargar Excel" class="btn btn-secondary mt-1 ml-1" id="export" href="{{ route('exportar.inventario',['almacen_codigo'=>$almacen_codigo]) }}"><i class="fas fa-file-excel"></i></a>
        </form>
        <!-- Tabla con el orden de columnas predeterminado (móvil) -->
        <table id="documentos" class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>Status</th>
                    <th>&nbsp;</th>
                    <th>Vendedor</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@include('users.modals')
@stop

@section('css')
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ (Config::get('app.env')=='local')?asset('css/maploca_styles.css'):secure_asset('css/maploca_styles.css') }}">
@stop

@section('js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="{{ (Config::get('app.env')=='local')?asset('js/pedido.js'):secure_asset('js/pedido.js') }}"></script>
<script>
    function isMobileDevice() {
        return (typeof window.orientation !== "undefined") || (navigator.userAgent.indexOf('IEMobile') !== -1);
    }

    // Ajustar el orden de las columnas en el <thead>
    function adjustTableHeaders() {
        var thead = document.querySelector("#documentos thead tr");
        if (!isMobileDevice()) {
            // Orden para ordenadores: Fecha, Cliente, Status, Vendedor, Acciones
            var headers = ["Fecha", "Cliente", "Status", "Vendedor", "Acciones"];
            thead.innerHTML = headers.map(header => `<th>${header}</th>`).join("");
        }
    }

    // Llamar a la función para ajustar los encabezados
    adjustTableHeaders();

    var columns = isMobileDevice() ? [
        { data: 'fecha_creacion', render: function(data) { return moment(data, 'YYYY-MM-DD').format('DD-MM-YYYY'); } },
        { data: 'cliente' },
        { data: 'status' },
        { data: 'action', name: 'action', orderable: false, searchable: false },
        { data: 'vendedor' }
    ] : [
        { data: 'fecha_creacion', render: function(data) { return moment(data, 'YYYY-MM-DD').format('DD-MM-YYYY'); } },
        { data: 'cliente' },
        { data: 'status' },
        { data: 'vendedor' },
        { data: 'action', name: 'action', orderable: false, searchable: false }
    ];

    var oTable = $("#documentos").DataTable({
        processing: true,
        serverSide: true,
        filter: false,
        "pageLength": 10,
        "order": [[0, "desc"]],
        ajax: {
            url: "{{ route('get.documentos') }}",
            data: function (d) {
                d.cliente = $('#cliente').val();
                d.status_id = $('select[name=status_id] option').filter(':selected').val();
            }
        },
        "columns": columns,
        responsive: true,
        autoWidth: false,
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": "Nada Encontrado - disculpa",
            "info": "Mostrando la página _PAGE_ de _PAGES_ ",
            "infoEmpty": "No hay registros",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search": "Buscar:",
            "paginate": {
                "next": "Siguiente",
                "previous": "Anterior"
            }
        },
        "bLengthChange" : false, 
        "bInfo":false, 
    });

    $('#buscar').on('click', function(e) {
        oTable.draw();
        e.preventDefault();
    });

    $('#clean').on('click', function(e) {
        $('select option:contains("Status")').prop('selected', true);
        $('#cliente').val('');
        oTable.draw();
        e.preventDefault();
    });

    function alert_confirmacion(event, url, titulo, texto_boton, texto_confirmacion) {
        event.preventDefault();
        Swal.fire({
            title: titulo,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: texto_boton
        }).then((result) => {
            if (result.value) {
                window.location.href = url;
            }
        });
    }
</script>
@stop