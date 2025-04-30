@extends('adminlte::page')

@section('title', 'Bienvenido a Maploca')

@section('content_header')
{{ Breadcrumbs::render('home') }}
@stop

@section('content')
<div class="container">
    <section id="welcome-section">
        <h1>Maploca</h1>
      
    </section>
    <section id="content-section">
        
        
    </section>
</div>

@stop
@section('footer')
<div class="footer">
    
</div>

@stop
@include('right_sidebar')
@section('css')
<link rel="stylesheet" href="{{ (Config::get('app.env')=='local')?asset('css/maploca_styles.css'):secure_asset('css/maploca_styles.css') }}">
<style>
<style>
    .main-header{
        background-color: #fe9117 !important;
    }


    .brand-text{
  color: transparent !important;
} 
        .container {
            width: 100%;
            margin: auto;
            overflow: hidden;
        }
        #welcome-section {


            padding: 30px;
            text-align: center;
        }
        #welcome-section h1 {
            font-size: 30px;
            margin-bottom: 10px;
        }
        #content-section {
            text-align: justify;
        }
        #content-section h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }
        #content-section p {
            line-height: 1.6em;
        }
        .card {
    text-align: center;
}

.card-body {
    display: inline-block;
    vertical-align: middle;
}

.card-title {
    margin-top: 10px;
}

.card-body a {
    display: block;
    margin-top: 10px;
}
.footer{
    text-align: right;
}
.footer a {
    margin-right: 10px;
}
    </style>
</style>
@stop
