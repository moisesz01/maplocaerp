@extends('adminlte::page')

@section('title', 'Cotizaciones')

@section('content_header')
<div class="breadcrumbs">
    {{ Breadcrumbs::render('factura.index') }}
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
<input type="hidden" name="tipo_documento_id" id="tipo_documento_id" value="{{$tipo_documento_id}}"> 
<div class="card" style="margin-left: -18px;margin-right: -18px;">
    <div class="card-header" style="margin-left: -5px;margin-right: -5px;">
        <div class="d-flex justify-content-between">
            Listado de Cotizaciones
            <a href="{{ route('factura.crear', ['tipo' => 'Cotización']) }}" class="btn btn-success"> <i class="fas fa-plus"></i> Nueva Cotización</a>
        </div>
    </div>
    <div class="card-body" style="margin-left: -5px;margin-right: -5px;">

        <form id="search-form" class="form-inline" role="form">
            <input type="text" id="cliente" placeholder="Buscar Cliente" class="form-control mr-1">
            <button id="buscar" class="btn btn-primary ml-1 mt-1">Buscar</button>
            <button id="clean" class="btn btn-danger mt-1" style="margin-left: 10px;"><i class="fas fa-eraser"></i></button>
        </form>
        <!-- Tabla con el orden de columnas predeterminado (móvil) -->
        <table id="documentos" class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Cliente</th>    
                    <th>Vendedor</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div class="modal fade" id="smallModal" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="titulo-modal-del"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="smallBody">
                    <div>
                        <!-- the result to be displayed apply here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
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
            var headers = ["Fecha", "Cliente", "Vendedor", "Acciones"];
            thead.innerHTML = headers.map(header => `<th>${header}</th>`).join("");
        }
    }

    // Llamar a la función para ajustar los encabezados
    adjustTableHeaders();

    var columns = isMobileDevice() ? [
        { data: 'fecha_creacion', render: function(data) { return moment(data, 'YYYY-MM-DD').format('DD-MM-YYYY'); } },
        { data: 'cliente' },
        { data: 'action', name: 'action', orderable: false, searchable: false },
        { data: 'vendedor' }
    ] : [
        { data: 'fecha_creacion', render: function(data) { return moment(data, 'YYYY-MM-DD').format('DD-MM-YYYY'); } },
        { data: 'cliente' },
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
            url: "{{ route('get.facturas') }}",
            data: function (d) {
                d.cliente = $('#cliente').val();
                d.status_id = $('select[name=status_id] option').filter(':selected').val();
                d.tipo_documento_id = $('#tipo_documento_id').val();
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
        $(document).on('click', '#smallButton', function(event) {
            event.preventDefault();
            let href = $(this).attr('data-attr');
            let cod = $(this).attr('data-cotizacion');

            $.ajax({
                url: href,
                beforeSend: function() {
                    $('#loader').show();
                },
                // return the result
                success: function(result) {
                    $('#smallModal').modal("show");
                    $('#titulo-modal-del').text("Cotización: " + cod);
                    $('#smallBody').html(result).show();
                },
                complete: function() {
                    $('#loader').hide();
                },
                error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                },
                timeout: 8000
            })
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