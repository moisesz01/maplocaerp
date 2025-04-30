@extends('adminlte::page')

@section('title', 'Calendario')

@section('content_header')
    <h1>Calendario</h1>
@stop

@section('content')

<form id="search-form" class="form-inline" role="form">
    @can('Ver planificaciones de todos los usuarios')
    <select id="usuario" name="usuario[]" class="form-control" multiple>
       
        @foreach($usuarios as $key => $usuario)
        <option value="{{$usuario->id}}"> {{$usuario->name}}</option>
        @endforeach
    </select>
    @endcan
    
    <input class="form-control margen_input" placeholder="Fecha" type="text" name="daterange" value=""
        disabled />    
    <div class="form-check ml-4">
        <label class="form-check-label ml-2 mr-2" for="radio4">
            <input type="radio" class="form-check-input na" id="radio4" name="opt_tiempo"
                value="n">Fecha Personalizada
        </label>
        <label class="form-check-label ml-4 mr-2" for="radio5">
            <input type="radio" class="form-check-input semana" id="radio5" name="opt_tiempo" value="s" checked>Semana Actual
        </label>
        <label class="form-check-label ml-4 mr-2" for="radio6">
            <input type="radio" class="form-check-input mes" id="radio6" name="opt_tiempo" value="m">Mes Actual
        </label>
        <label class="form-check-label ml-4" for="radio7">
            <input type="radio" class="form-check-input dia" id="radio7" name="opt_tiempo" value="d">Dia Actual
        </label>

    </div>
    <a title="Descargar Planificación" class="btn btn-secondary ml-2" id="export" href=""><i class="far fa-file-pdf"></i> Descargar Planificación</a>
    <a title="Descargar Planificación" class="btn btn-secondary ml-2" id="export_excel" href=""><i class="fas fa-file-excel"></i> Descargar Planificación</a>
</form>
    
    <div id="calendar-container" style="position: relative;">
        <div id="loading" class="loading">
            Cargando...
        </div>
        <div id="calendar" class="fc fc-media-screen fc-direction-ltr fc-theme-bootstrap5"></div>
    </div>
    @include('planificacion.modal_evento')
@stop

@section('css')
<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
<link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-autocomplete@1.2.8/jquery.autocomplete.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-theme/0.1.0-beta.10/select2-bootstrap.min.css" rel="stylesheet" />
<style>
.loading {
    display: none; /* Ocultar por defecto */
    position: absolute; /* Posicionamiento absoluto */
    top: 0; /* Alinear al top */
    left: 0; /* Alinear al left */
    right: 0; /* Alinear al right */
    bottom: 0; /* Alinear al bottom */
    background: rgba(255, 255, 255, 0.8); /* Fondo semi-transparente */
    z-index: 1000; /* Asegurarse que esté encima de otros elementos */
    justify-content: center; /* Centrar horizontalmente */
    align-items: center; /* Centrar verticalmente */
    font-size: 24px; /* Tamaño de fuente */
    color: #333; /* Color del texto */
}
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/bootstrap5@6.1.14/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/locale/es.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-autocomplete@1.2.8/jquery.autocomplete.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="{{ (Config::get('app.env')=='local')?asset('js/calendario.js'):secure_asset('js/calendario.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/i18n/es.js"></script>
<script>
    $(document).ready(function() {
        $('#usuario').select2({
            placeholder: 'Buscar Vendedor',
            allowClear: true,
            width: '100%',
            closeOnSelect: true
        });
    });
    
    eventos_usuario(
        "{{ route('planificacion.obtener_eventos') }}"
    )
    calendario(
        "",
        "{{ route('planificacion.obtener_eventos') }}",
        "{{route( 'planificacion.actualizar_actividad' )}}",
        "{{route('planificacion.guardar_actividad')}}",
        "{{route('planificacion.detalle')}}",
        "{{ route('planificacion.obtener_acciones') }}",
        "{{ route('planificacion.eliminar') }}",
        '{{ csrf_token() }}',
        "{{ route('clientes.visita') }}"
    );
    search_cliente("{{ route('numero_documento.search') }}");
    cargar_acciones("{{ route('planificacion.obtener_acciones') }}",'#accion_vendedor_id','#tipo_accion');

    $('input[name="daterange"]').daterangepicker({
                opens: 'left',
                autoUpdateInput: false,
                "locale": {
                    "format": "MM/DD/YYYY",
                    "separator": " - ",
                    "applyLabel": "Aplicar",
                    "cancelLabel": "Cancelar",
                    "fromLabel": "Desde",
                    "toLabel": "Hasta",
                    "customRangeLabel": "Custom",
                    "weekLabel": "W",
                    "daysOfWeek": [
                        "Dom",
                        "Lun",
                        "Mar",
                        "Mie",
                        "Jue",
                        "Vie",
                        "Sab"
                    ],
                    "monthNames": [
                        "Enero",
                        "Febrero",
                        "Marzo",
                        "Abril",
                        "Mayo",
                        "Junio",
                        "Julio",
                        "Agosto",
                        "Septiembre",
                        "Octubre",
                        "Noviembre",
                        "Deciembre"
                    ],
                    "firstDay": 1
                },
            }, function(start, end, label) {


            });
    $('input[type=radio][name=opt_tiempo]').change(function() {

        if (this.value == 'm') {
            $('input[name=daterange]').val('');
            $('input[name=daterange]').prop('disabled', true);
        } else if (this.value == 'd') {
            $('input[name=daterange]').val('');
            $('input[name=daterange]').prop('disabled', true);
        } else if (this.value == 's') {
            $('input[name=daterange]').val('');
            $('input[name=daterange]').prop('disabled', true);
        } else {
            $('input[name=daterange]').val('');
            $('input[name=daterange]').prop('disabled', false);
        }
    });
    $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });

    $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
    $('#export').on('click', function(e) {
        var url_excel = "{{route('planificacion.exportar')}}";
        const daterange = $('input[name=daterange]').val();
        const opcion_fecha = $('input[name="opt_tiempo"]:checked').val();
        const usuario_id = $('#usuario').val(); // Obtener el valor del input con id "usuario"
        
        // Construir la nueva URL incluyendo el usuario_id
        const url_new = url_excel + '?daterange=' + daterange + '&comparativo=' + '&opcion_fecha=' + opcion_fecha + '&user_id=' + usuario_id;

        $(this).prop("href", url_new);
    });
    $('#export_excel').on('click', function(e) {
        var url_excel = "{{route('planificacion.exportar_excel')}}";
        const daterange = $('input[name=daterange]').val();
        const opcion_fecha = $('input[name="opt_tiempo"]:checked').val();
        const usuario_id = $('#usuario').val(); // Obtener el valor del input con id "usuario"
        
        // Construir la nueva URL incluyendo el usuario_id
        const url_new = url_excel + '?daterange=' + daterange + '&comparativo=' + '&opcion_fecha=' + opcion_fecha + '&user_id=' + usuario_id;

        $(this).prop("href", url_new);
    });
</script>
@stop