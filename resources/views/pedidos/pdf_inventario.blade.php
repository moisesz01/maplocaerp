@include('css_pdf')
@php
    date_default_timezone_set("America/Caracas");
@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <style>
            * {
            margin: 0;
            padding: 0;
            border: 0;
            font-size: 100%;
            font: inherit;
            vertical-align: baseline;
            }
            @page {
                margin: 100px 25px;
            }

            header {
                position: fixed;
                top: 0px;
                left: 0px;
                right: 0px;
                height: 35px;
                font-size: 15px !important;
                background-color: #A3A7AA;
                color: white;
                line-height: 30px;
                margin-left:20px;
                margin-right:20px;
            }

            footer {
                position: fixed;
                bottom: 20px;
                left: 0px;
                right: 0px;
                font-size: 12px !important;
                text-align: center;
            }
            .cabecera_tabla{
                background-color: #A3A7AA;
                color: #ffffff;
            }
            .columna {
                text-align: center;
                vertical-align: middle;
                justify-content: center;
                align-items: center;
            }
            .contenido{
                font-size: 12px !important;
            }
            .letra_pequena{
                font-size: 10px !important;
            }
            .margenes-laterales{
                margin-left:50px;
                margin-right:50px;
            }
            .margenes_up_down{
                margin-top: 40px;
                margin-bottom: 40px;
            }
            .columna-derecha {
                text-align: right;
            }
        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
            <div style="padding-bottom: 10px;">
                <span class="float-left"></span> 
                <span class="float-right"><strong style="text-decoration: underline">Inventario del dia:  {{date(d-m-Y)}}</strong></span>
            </div>
           
            
        </header>

        <footer>
            <div class="margenes-laterales" style="font-size: 10px">
                <p style="margin-left:0px;">Página web: www.maploca.com; Instagram: @maploca.ve; Facebook: maploca.ve </p>
            </div>
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            <div class="contenedor margenes-laterales margenes_up_down" style="height: 50px;">
                <div class="row">
                    <div class="col-sm-3 float-left">
                      
                            <div style="margin-left:5px;">
                                <img class="img logo_cotizacion" style="object-fit: cover; width: 120px; display: block;" src="{{URL::asset('/imgs/logo_maploca_slogan.png')}}" />
                            </div>
                            
                        
                    </div>
                    <div class="col-sm-9 float-right mt-3" style="">
                        <p class="letra_pequena">
                            DIRECCION FISCAL: AV.PPAL.LOS CORTIJOS DE LOURDES.EDIF.MAPLOCA II.
                        </p>
                        <p class="letra_pequena"> EDO. MIRANDA TLF 239-09-11</p>
                    </div>
                    
                </div>
                <br>
                
            
            </div>
            <div class="contenido contenedor margenes-laterales" style="height: 50px;">
                <div class="row" style="font-size: 12px !important; margin-bottom:0px;">
                    <div class="col-sm-6" style="float: left; padding-right:80px;">
                        <div><strong> Sucursal: </strong></div>
                        <div><strong>Contacto Sucursal:</strong></div>
                    </div>
                </div>
            </div>
            
            <br>
            <div class="row" style="mb-4">
                <div class="contenido contenedor margenes-laterales" style="font-size: 12px !important;">
                    <p style="text-align:center"> Inventario:</p>
                    
                </div>
            </div>
            
            <div class="contenido contenedor margenes-laterales">
                <div class="row">
                    <div class="table-responsive-sm" style="font-size: 10px;">
                        <table class="table table-sm table-bordered" style="font-size: 10px;">
                            <thead>
                                <tr class="cabecera_tabla">
                                    <th class="text-center">ITEM</th>
                                    <th class="text-center">CÓDIGO</th>
                                    <th class="text-center">ARTICULO</th>
                                    <th class="text-center">U/M</th>
                                    <th class="text-center">KILOS</th>
                                    <th class="text-center">PRECIO UNITARIO</th>
                                    <th class="text-center">LINEA</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                              
                            </tbody>
                            
                        </table>
                    </div>
                </div>
            </div>
            
        </main>
    </body>
</html>