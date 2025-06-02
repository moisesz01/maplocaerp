<?php 
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;


Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('App de Maploca', route('home'));
});

Breadcrumbs::for('usuarios.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Lista de Usuarios', route('user.index'));
   
});

Breadcrumbs::for('usuarios.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('usuarios.index');
    $trail->push('Perfil del Usuario');
   
});
Breadcrumbs::for('usuarios.contrasena', function (BreadcrumbTrail $trail) {
    $trail->parent('usuarios.index');
    $trail->push('Cambio de contrasena');
   
});

Breadcrumbs::for('usuarios.create', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Crear Usuario');
   
});

Breadcrumbs::for('sector_comercial.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Sectores Comerciales', route('sector_comercial.index'));
   
});
Breadcrumbs::for('sector_comercial.create', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Crear Sector Comercial', route('sector_comercial.create'));
   
});
Breadcrumbs::for('sector_comercial.edit', function (BreadcrumbTrail $trail) {
  
    $trail->parent('sector_comercial.index', route('sector_comercial.index'));
    $trail->push('Editar Sector', route('sector_comercial.edit'));
   
});

Breadcrumbs::for('visita.detalle', function (BreadcrumbTrail $trail) {
  
    $trail->parent('visita.index', route('visita.index'));
    $trail->push('Detalle', route('visita.view'));
   
});


Breadcrumbs::for('clientes.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Clientes', route('clientes.index'));
   
});


Breadcrumbs::for('almacen.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Almacenes', route('almacen.index'));
   
});
Breadcrumbs::for('almacen.create', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Crear Almacen', route('almacen.create'));
   
});
Breadcrumbs::for('almacen.edit', function (BreadcrumbTrail $trail) {
  
    $trail->parent('almacen.index', route('almacen.index'));
    $trail->push('Editar Almacen', route('almacen.edit'));
   
});


Breadcrumbs::for('pedidos.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Lista de Cotizaciones', route('pedidos.index'));
   
});

Breadcrumbs::for('pedido.crear', function (BreadcrumbTrail $trail) {
    $trail->parent('pedidos.index');
    $trail->push('Crear');
   
});
Breadcrumbs::for('categoria.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Categorias', route('categorias.index'));
   
});
Breadcrumbs::for('categoria.create', function (BreadcrumbTrail $trail) {
    $trail->parent('categoria.index');
    $trail->push('Crear Categorias');
   
});
Breadcrumbs::for('categoria.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('categoria.index');
    $trail->push('Editar Categorias');
   
});
Breadcrumbs::for('subcategoria.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('SubCategorias', route('subcategorias.index'));
   
});
Breadcrumbs::for('subcategoria.create', function (BreadcrumbTrail $trail) {
    $trail->parent('subcategoria.index');
    $trail->push('Crear SubCategorias');
   
});

Breadcrumbs::for('factura.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Lista de Facturas', route('factura.index'));
   
});
Breadcrumbs::for('factura.crear', function (BreadcrumbTrail $trail) {
    $trail->parent('factura.index');
    $trail->push('Crear Facturas', route('factura.crear'));
   
});