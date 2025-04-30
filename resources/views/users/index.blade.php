@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
<div class="breadcrumbs">
    {{ Breadcrumbs::render('usuarios.index') }}
      
    </div>
    <h1>Listado de Usuarios</h1>
@stop

@section('content')
@include('right_sidebar')
@if (session('info'))
<div class="alert alert-dismissable alert-success">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <strong>
        {{session('info')}}
    </strong>
</div>

@endif
<div class="row">
    <div class="col-sm-12">
        <a href="{{ route('user.create') }}" class="btn btn-success mb-2 float-right"> <i class="fas fa-plus"></i> Nuevo Usuario</a>
    </div>
</div>
<div class="card">
    <div class="card-header">
        Listado de Usuarios
    </div>
    <div class="card-body">

        <form id="search-form" class="form-inline" role="form">

            <select id="usuario" name="usuario" class="form-control">
                <option value="" selected disabled>Buscar usuario</option>
                @foreach($usuarios as $key => $usuario)
                <option value="{{$usuario->id}}"> {{$usuario->name}}</option>
                @endforeach
            </select>
            <select id="estado" name="estado" class="form-control" style="margin-left: 10px;">
                <option value="" selected disabled>Estado</option>
                <option value="0"> Inactivo</option>
                <option value="1"> Activo</option>
            </select>
            <button id="buscar" class="btn btn-primary" style="margin-left: 10px;">Buscar</button>
            <button id="clean"  class="btn btn-danger" style="margin-left: 10px;"><i class="fas fa-eraser"></i></button>


        </form>
        <br>


        <table id="usuarios" class="table table-striped table-sm">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Almacen</th>
                <th>Status</th>
                <th>&nbsp;</th>
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

<script>
    $(document).on('click', '#smallButton', function(event) {
    event.preventDefault();
    let href = $(this).attr('data-attr');
    let cod = $(this).attr('data-producto');

    $.ajax({
        url: href,
        beforeSend: function() {
            $('#loader').show();
        },
        // return the result
        success: function(result) {
            $('#smallModal').modal("show");
            $('#titulo-modal-del').text("Usuario: "+cod);
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

var oTable = $("#usuarios").DataTable({
        processing: true,
        serverSide: true,
        filter:false,
        "pageLength": 25,

        "order": [[ 0, "asc" ]],
        ajax: {
            url: "{{ route('get.usuarios') }}",
            data: function (d) {
                d.usuario = $('select[name=usuario] option').filter(':selected').val();
                d.estado = $('select[name=estado] option').filter(':selected').val();
                console.log(d.usuario);
            }
            
        },
        "columns":[
            {data: 'nombre'},
            {data: 'correo'},
            {data: 'almacen'},
            {data: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],

        responsive: true,
        autoWidth: false,
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros por página",
            "zeroRecords": "Nada Encontrado - disculpa",
            "info": "Mostrando la página _PAGE_ de _PAGES_ ",
            "infoEmpty": "No hay registros",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "search":"Buscar:",
            "paginate":{
                "next":"Siguiente",
                "previous":"Anterior"
            }
        }
    });
    $('#buscar').on('click', function(e) {
        oTable.draw();
        e.preventDefault();
    });
    $('#clean').on('click', function(e) {
        $('select option:contains("usuario")').prop('selected',true);
        $('select option:contains("Sucursal")').prop('selected',true);
        oTable.draw();
        e.preventDefault();
    });
</script>
@stop