@section('right_sidebar')

<nav class="pt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

        
        @can('Módulo Configuraciones')
        <li class="nav-item has-treeview">
            <a class="nav-link" href="">
                <i class="fas fa-user-tag"></i>
                <p>Clientes<i class="fas fa-angle-left right"></i></p>
            </a>

            <ul class="nav nav-treeview" style="display: none;">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('sector_comercial.index') }}">
                        <i class="far fa-fw fa-circle text-green"></i>
                        <p> Sectores Comerciales </p>
                    </a>
                </li> 
            </ul>
        </li>
        <li class="nav-item has-treeview">
            <a class="nav-link" href="">
                <i class="fas fa-th-large"></i>
                <p>Categorias<i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview" style="display: none;">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('categorias.index') }}">
                        <i class="far fa-fw fa-circle text-yellow"></i>
                        <p> Categorias </p>
                    </a>
                </li> 
            </ul>
            <ul class="nav nav-treeview" style="display: none;">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('subcategorias.index') }}">
                        <i class="far fa-fw fa-circle text-yellow"></i>
                        <p> SubCategorias </p>
                    </a>
                </li> 
            </ul>
        </li>
        <li class="nav-item has-treeview">
            <a class="nav-link" href="{{ route('almacen.index') }}">
                <i class="fas fa-tools"></i>
                <p>Almacenes<i class="fas fa-angle-left right"></i></p>
            </a>
        </li>
        <li class="nav-item has-treeview">
            <a class="nav-link" href="{{ route('sincronizacion.index') }}">
                <i class="fas fa-tools"></i>
                <p>Sincronización<i class="fas fa-angle-left right"></i></p>
            </a>
        </li>
        @endcan
        @can('Módulo Usuarios')
            <li class="nav-item has-treeview">
                <a class="nav-link" href="{{ route('user.index') }}">
                    <i class="fas fa-tools"></i>
                    <p>Usuarios<i class="fas fa-angle-left right"></i></p>
                </a>
            </li>
        @endcan
       
    </ul>
</nav>
@stop
