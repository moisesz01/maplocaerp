let formas_vueltos = [];

function set_formas_vueltos($formas_vueltos){
    formas_vueltos = $formas_vueltos;   
}


$('#add').on('click', function(){
    const producto_id = $('#producto_id_tmp').val().trim(); // Agregamos trim() para eliminar espacios
    const producto = $('#search-product-input').val();
    const cantidad = $('#cantidad').val();
    const precio = $('#precio').val();
    const peso = $('#peso').val();
    const unidad_medida = $('#unidad_medida').val();
    const estandar = $('#estandar').val();
    const unidad_venta = $('#unidad_venta').val();
    const largo = $('#largo').val();
    const factor = $('#factor').val();
    // Verificar si el producto ya existe en la tabla
    const exists = $('#items-tbody tr').find(`input[name="producto_id[]"][value="${producto_id}"]`).length > 0;
 
    if (!exists) {
        const validacion = validar_disponibilidad();
        if(!validacion){
            return;
        }
        $('#search-product-modal').modal('hide');
        $('#search-product-list').empty();
        $('#search-product-input').focus();
        addRow(producto_id, producto, cantidad, precio, peso, unidad_medida, 'P', estandar, unidad_venta, largo, factor);
    } else {
     
        Swal.fire(
            'Producto Existente',
            'El producto ya fue agregado',
            'warning'
        );
    }

     
});
$('#add_especial').on('click', function(){
    const producto_id = $('#codigo_producto').val();
    const producto = $('#nombre_producto').val();
    const cantidad = $('#cantidad_especial').val();
    const precio = $('#precio_especial').val();
    const peso = 1;
    const unidad_medida = 'PZAS';

    // Verificar si el producto ya existe en la tabla
    const exists = $('#items-tbody tr').find(`input[name="producto_id[]"][value="${producto_id}"]`).length > 0;

    if (!exists) {
        addRow(producto_id, producto, cantidad, precio, peso, unidad_medida,'P') ;
    }else{
        $('#producto_id_tmp').val('');
        Swal.fire(
            'Producto Existente',
            'El producto ya fue agregado',
            'warning'
          );
    }

    $('#product-modal').modal('hide');
});
$('#add_servicio').on('click', function(){
    
    const servicio_id = $('#codigo_servicio').val();
    const servicio = $('#servicio').val();
    const cantidad = $('#cantidad_servicio').val();
    const precio = $('#precio_servicio').val();
    const unidad_medida = $('#unidad_medida_servicio').val();
    addRow(servicio_id, servicio, cantidad, precio, 0, unidad_medida,'S');
    $('#servicio-modal').modal('hide');
    
});

function selectProduct(producto_id, producto, disponible, peso, precio, unidad_medida, linea, estandar, peso_conversion, ancho_conversion, alto_espesor_conversion) {
   
    peso = parseFloat(peso).toFixed(2);
    precio = parseFloat(precio).toFixed(2);
    if (estandar == 'N') {
        Swal.fire(
            'Producto no Estandar',
            'El producto seleccionado no es estándar, debe ingresar largo y unidad de medida',
            'info'
        ); 
        peso = peso_conversion;
        $('#largo').prop('disabled', false); // Habilita el input con id "largo"
        $('#unidad_venta').prop('disabled', false);
    } else {
        $('#largo').prop('disabled', true); // Deshabilita el input con id "largo" si no se cumple la condición
        $('#unidad_venta').prop('disabled', true);
    }
    const exists = $('#items-tbody tr').find(`input[name="producto_id[]"][value="${producto_id}"]`).length > 0;
    if (!exists) {
        $('#producto_id_tmp').val(producto_id);         
        $('#search-product-input').val(producto);
        $('#search-product-list').empty();
        $('#disponible').val(disponible);         
        $('#peso').val(peso);  
        $('#precio').val(precio);
        $('#unidad_medida').val(unidad_medida);
        $('#unidad_inventario').val(unidad_medida);
        $('#codigo').val(producto_id);
        $('#linea').val(linea);
        $('#estandar').val(estandar);
        $('#ancho_conversion').val(ancho_conversion);
        $('#alto_espesor_conversion').val(alto_espesor_conversion);
    }else{
        $('#producto_id_tmp').val('');
        $('#search-product-input').val('');
        $('#search-product-list').empty();
        $('#disponible').val('');         
        $('#peso').val('');  
        $('#precio').val('');
        $('#unidad_medida').val('');
        $('#codigo').val('');
        $('#linea').val('');
        $('#estandar').val('');
        $('#ancho_conversion').val('');
        $('#alto_espesor_conversion').val('');
        Swal.fire(
            'Producto Existente',
            'El producto ya fue agregado',
            'warning'
          );
    }
      
}
function validar_disponibilidad(){
    const disponible = parseFloat($("#disponible").val()) ;
    const cantidad = parseFloat($('#cantidad').val());
    const estandar = $('#estandar').val();
    const largo = $('#largo').val();
    const factor = $('#factor').val();
    const unidad_venta = $('#unidad_venta').val();
    const unidad_inventario = $('#unidad_inventario').val();
    if (estandar == 'N') {
        if (largo<=0) {
            Swal.fire(
                'Producto Sin Largo',
                'El largo debe ser mayor a cero',
                'warning'
            );
            return false;
        }
        if (!unidad_venta || unidad_venta === '' || $('#unidad_venta option:selected').is(':disabled')) {
            Swal.fire(
                'Unidad de Venta no seleccionada',
                'Debe seleccionar una unidad de venta válida',
                'warning'
            );
            return false;
        }
        if(unidad_inventario!=unidad_venta){
            if ((factor*cantidad)>disponible) {
                Swal.fire(
                    'Producto Sin Disponibilidad',
                    'La cantidad solicitada supera la cantidad disponible',
                    'warning'
                );
                return false;   
            }
        }else{
            if(cantidad>disponible){
        
                Swal.fire(
                    'Producto Sin Disponibilidad',
                    'La cantidad solicitada supera la cantidad disponible',
                    'warning'
                );
                return false;
            }
        }
        
    }else{
        if(cantidad>disponible){
        
            Swal.fire(
                'Producto Sin Disponibilidad',
                'La cantidad solicitada supera la cantidad disponible',
                'warning'
            );
            return false;
        }
    }
    if(cantidad<=0){
        Swal.fire(
            'Producto Sin cantidad',
            'La cantidad solicitada debe ser mayor a cero',
            'warning'
        );
        return false;
    }
    
    return true;
}
// Función para agregar fila dinámica
function addRow(id, description, cantidad, precio, peso, unidad_medida, tipo, estandar, unidad_venta,largo, factor) {
    
    let producto = id+'-'+description;
    if (estandar=='S') {
        unidad_venta = unidad_medida;
    }else{
        console.log(unidad_medida+"---"+unidad_venta);
        if (unidad_medida.trim() !== unidad_venta.trim()) {
            precio = factor * precio    
            precio = parseFloat(precio).toFixed(2);
            producto = producto+' '+largo;
            description = description+' '+largo;
        } 
    }
    let total = Math.round(cantidad * precio * 100) / 100;
    total = formatoNumero(total);

    $('#items-tbody').prepend(`
        <tr class="fila">
            <input type="hidden" value="${estandar}" name="estandar[]">
            <input type="hidden" value="${tipo}" name="tipo[]">
            <input type="hidden" value="${id}" name="producto_id[]">
            <input type="hidden" value='${description}' name="producto[]">
            <input type="hidden" value="${peso}" name="peso[]" class="peso">
            <input type="hidden" value="${unidad_medida}" name="unidad_medida[]">
            <input type="hidden" value="${unidad_venta}" name="unidad_venta[]">
            <input type="hidden" value="${largo}" name="largo[]">
            <input type="hidden" value="${precio}" name="precio_bk[]" class="precio_bk">
             <input type="hidden" value="0" name="descuento_producto[]" class="descuento_producto">
            <td>${producto}</td>
            <td>${unidad_venta}</td>
            <td><input type="number" value="${cantidad}" class="form-control cantidad" name="cantidad[]" required></td>
            <td><input type="number" step="any" class="form-control precio" value="${precio}" name="precio[]" required></td>
            <td class="total_fila">${total}</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)"><i class="fas fa-trash-alt"></i></button>
                <button type="button" style="margin-top: 10px;" class="btn btn-primary btn-sm" onclick="mostraPorcentaje(this)"><i class="fas fa-percentage"></i></button>
                <button type="button" style="margin-top: 10px;padding-left: 12px;" class="btn btn-success btn-sm" onclick="verPrecio(this)"><i class="fas fa-dollar-sign"></i></button>
            </td>
        </tr>
    `);
    cambio_precio_cantidad();
   
    updateTotal();
    updatePeso();
}
function cambio_precio_cantidad(){
 // Agregamos el evento de cambio a los campos cantidad y precio
 $('#items-tbody .cantidad, #items-tbody .precio').on('input', debounce(function(event) {
    var $input = $(event.target);
    var cantidad = $input.hasClass('cantidad') ? $input.val() : $input.closest('tr').find('.cantidad').val();
    var precio = $input.hasClass('precio') ? $input.val() : $input.closest('tr').find('.precio').val();
    var total = Math.round(cantidad * precio * 100) / 100;
    total = formatoNumero(total);
    $input.closest('tr').find('.total_fila').text(total);
    updateTotal();
    updatePeso();

}, 500));
}
function verPrecio(element) {
    let precio = $(element).closest('tr').find('.precio_bk').val();
    Swal.fire(
        'Precio base del producto',
        precio,
        'info'
      ); 
} 
function removeRow(element) {
    $(element).closest('tr').remove();
    updateTotal();
    updatePeso();
} 
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        const later = () => {
            timeout = null;
            func(...args); 
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

$('#search-product-modal').on('shown.bs.modal', function() {
    $('#search-product-input').val('');
    $('#producto_id_tmp').val('')
    $('#cantidad').val('');
    $('#precio').val('');
    $('#peso').val('');
    $('#disponible').val('');
    $('#codigo').val('');
    $('#linea').val('');
    $('#estandar').val('');
    $('#search-product-list').empty();
    $('#search-product-input').focus();
});
$('#product-modal').on('shown.bs.modal', function() {
    $('#nombre_producto').val('');
    $('#codigo_producto').val('')
    $('#cantidad_especial').val('');
    $('#precio_especial').val('');
    $('#largo').prop('disabled', true); 
    $('#unidad_venta').prop('disabled', true);
});

function searchProducts(url) {
    $('#search-product-list').empty();
    var searchWithStock = $('#searchWithStock').is(':checked'); // true si está marcado, false si no
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            search: $('#search-product-input').val(),
            almacen_codigo: $('#almacen_codigo').val(),
            con_stock: searchWithStock ? 's' : 'n'
        },
        success: function(data) {
            $('#search-product-list').empty();
            if (data.data && data.data.length > 0) {
                $.each(data.data, function(index, product) {
                   
                    const listItem = $('<li class="list-group-item">');
                    const anchor = $('<a href="#">');
                    anchor.text(product.desc87);
                    anchor.on('click', function() {
                        selectProduct(
                            product.arti87,
                            product.desc87, 
                            product.disp_uni_inv, 
                            product.peso87, 
                            product.precio_neto, 
                            product.unmd87, 
                            product.linea, 
                            product.estandar,
                            product.peso_conversion,
                            product.ancho_conversion,
                            product.alto_espesor_conversion
                        );
                    });
                    listItem.append(anchor);
                    $('#search-product-list').append(listItem);
                });
            } else {
                const listItem = $('<li class="list-group-item">');
                const paragraph = $('<p>No se encontraron productos con la descripción suministrada.</p>');
                listItem.append(paragraph);
                $('#search-product-list').append(listItem);
            }
        },
        error: function(xhr, status, error) {
            $('#search-product-list').empty();
            const listItem = $('<li class="list-group-item">');
            const paragraph = $('<p>Error al buscar productos: ' + error + '</p>');
            listItem.append(paragraph);
            $('#search-product-list').append(listItem);
        }
    });
}
function buscar_producto(url){
    $('#search-product-input').on('input', debounce(function() {
        searchProducts(url);
    }, 500));
}
function buscar_cliente(url){
    $('#search-client-input').on('input', debounce(function() {
        searchClients(url);
    }, 500));
}
function searchClients(url) {
    $('#search-client-list').empty();
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            search: $('#search-client-input').val(),
        },
        success: function(data) {
            $('#search-client-list').empty();
            if (data && data.length > 0) {
                $.each(data, function(index, product) {
                    $('#search-client-list').append(`
                        <li class="list-group-item">
                            <a href="#" onclick="selectCliente(
                                ${data[index].id}, 
                                '${data[index].nombre}',
                                '${data[index].tipo_documento}',
                                '${data[index].numero_documento}',
                                '${data[index].direccion}',
                            )">${data[index].nombre}</a>
                        </li>
                    `);
                });
            } else {
                
                $('#search-client-list').append(`
                    <li class="list-group-item">
                        <p>No se encontró el cliente.</p>
                    </li>
                `);
            }
        }
    });
}
function selectCliente(cliente_id,nombre,tipo_documento,numero_documento,direccion) {
    $('#search-client-input').val(nombre);
    $('#rif').val(tipo_documento+'-'+numero_documento);
    $('#direccion').val(direccion);
    $('#cliente_id').val(cliente_id);
    $('#search-client-list').empty();
}
function formatoNumero(numero) {
    return numero.toFixed(2).replace(/\./g, ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}
function enviar_formulario(){
    $('#submit-button').on('click', function(event) {
        event.preventDefault();

        // Validación de cliente
        let cliente_id = $('#cliente_id').val();
        if (cliente_id.length === 0) {
            Swal.fire(
                'Cliente No Seleccionado',
                'Se debe seleccionar un cliente',
                'warning'
            );
            return;
        }

        // Validación de métodos de pago
        
        let forma_pago = $('#forma_pago').val();
        let moneda_extranjera = $('#moneda_extranjera').val();
        if ( forma_pago === null || forma_pago === '') {
            Swal.fire(
                'Forma o método de pago no seleccionado',
                'Se debe seleccionar una forma y método de pago',
                'warning'
            );
            return;
        }
  

        if (moneda_extranjera === null || moneda_extranjera.length === 0) {
            Swal.fire({
                title: 'Moneda extranjera requerida',
                html: `Debe selecciona el tipo de moneda extranjera`,
                icon: 'error',
                confirmButtonText: 'Entendido'
            });
            return;
        }

        // Validación de productos
        let itemsTbody = $('#items-tbody');
        let rows = itemsTbody.find('tr');

        if (rows.length === 0) {
            Swal.fire(
                'Producto sin Seleccionar',
                'Debe agregar al menos un producto',
                'warning'
            );
            return;
        }

        // Validación de cantidades, precios y montos
        let cantidadValid = true;
        let precioValid = true;
        let montoPagoValid = true;

        itemsTbody.find('input[name="cantidad[]"]').each(function() {
            if ($(this).val() <= 0) cantidadValid = false;
        });

        itemsTbody.find('input[name="precio[]"]').each(function() {
            if ($(this).val() <= 0) precioValid = false;
        });

        $('input[name="monto_pago[]"]').each(function() {
            if ($(this).val() <= 0) montoPagoValid = false;
        });

        if (!cantidadValid) {
            Swal.fire('Advertencia!', 'La cantidad debe ser mayor a cero', 'warning');
            return;
        } 
        if (!precioValid) {
            Swal.fire('Advertencia!', 'El precio debe ser mayor a cero', 'warning');
            return;
        } 
        if (!montoPagoValid) {
            Swal.fire('Advertencia!', 'El monto de pago debe ser mayor a cero', 'warning');
            return;
        }

        // Si todo es válido, enviar el formulario
        $(this).closest('form').submit();
    });
}

// Función para actualizar los precios de los productos en la tabla
/*function updatePrices() {
    const descuento = parseFloat($('#descuento').val());
    const filas = $('.fila');

    filas.each(function() {
        const precioOriginal = parseFloat($(this).find('.precio').val());
        let precio = precioOriginal;

        if (descuento > 0) {
            precio = precioOriginal - (precioOriginal * (descuento / 100));
        }

        $(this).find('.precio').val(precio);
        const cantidad = parseInt($(this).find('.cantidad').val());
        const total = precio * cantidad;
        $(this).find('.total_fila').text(formatoNumero(total));
    });
}*/
  
  // Agregar evento input al input descuento para llamar a la función updatePrices
  //$('#descuento').on('input', debounce(updatePrices, 500));


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
            $('#titulo-modal-del').text("Cotización: "+cod);
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
let activeRow = null;
function mostraPorcentaje(element){
    const fila = $(element).closest('tr');
    activeRow = fila;
    const input_porcentaje_descuento = fila.find('.descuento_producto');
    const precioOriginal = parseFloat(fila.find('.precio').val());
    const descuento_producto = input_porcentaje_descuento.val();
    if(descuento_producto!=0){ console.log("entro");
        const porcentajeDescuento = parseFloat(descuento_producto);
        const descuento = precioOriginal * (porcentajeDescuento / 100);
        const precioConDescuento =  precioOriginal - descuento;
        $('.descuento-input').val(descuento_producto);
        $('.descuento-preview').text(`Precio original: ${formatoNumero(precioOriginal)}\nPrecio con descuento: ${formatoNumero(precioConDescuento)}`);
    }else{
        $('.descuento-input').val('');
        $('.descuento-preview').text('');
    } 

    // Mostrar modal para ingresar porcentaje de descuento
    $('#descuento-modal').modal('show');

    // Agregar evento input al input de porcentaje de descuento
    $('#descuento-modal').find('.descuento-input').on('input', function() {
        const porcentajeDescuento = parseFloat($(this).val());
        const descuento = precioOriginal * (porcentajeDescuento / 100);
        const precioConDescuento =  precioOriginal - descuento;

        // Mostrar precio con descuento antes de aceptar
        $('#descuento-modal').find('.descuento-preview').text(`Precio original: ${formatoNumero(precioOriginal)}\nPrecio con descuento: ${formatoNumero(precioConDescuento)}`);
    });

    
}
$('#aplicar').on('click', function() {
    if (activeRow) {
        const input_porcentaje_descuento = activeRow.find('.descuento_producto');
        const porcentajeDescuento = parseFloat($('.descuento-input').val());
        input_porcentaje_descuento.val(porcentajeDescuento);
        activeRow = null; // Resetear la fila activa
        $('#descuento-modal').modal('hide');
        updateTotal();
        updatePeso();
    }
});
$('#largo').on('input', function() {
    CalcularFactor();
});
$('#unidad_venta').on('input', function() {
    CalcularFactor();
});
function updatePeso(){
    let peso_total = 0;
    $('#items-tbody tr').each(function() {
        var peso = $(this).find('.peso').val();
        var cantidad = $(this).find('.cantidad').val();
        var totalRow = parseFloat(peso) * cantidad;
        peso_total += totalRow;
    });
    peso_total = parseFloat(peso_total).toFixed(2);
    $('#peso_total').val(peso_total);
}
function CalcularFactor(){
   
    let ancho = $('#ancho_conversion').val();
    let alto = $('#alto_espesor_conversion').val();
    let largo = $('#largo').val();
    let peso = $('#peso').val();
    let factor = 0;
    if (alto > 0 && ancho > 0 && largo > 0) {
        factor = (alto * ancho * largo * peso) ; 
        $('#factor').val(factor.toFixed(2));
    } else {
        $('#factor').val('');
    }
}
function updateTotal() { 
    var total = 0;
    var descuentoGeneral = parseFloat($('#descuento').val());
    var igtf = 0;
    $('#items-tbody tr').each(function() {
        var precio = $(this).find('.precio').val();
        var cantidad = $(this).find('.cantidad').val();
        var descuentoProducto = $(this).find('.descuento_producto').val();
        var precioConDescuentoProducto = precio;
        if (descuentoProducto > 0) {
            precioConDescuentoProducto = precio - (precio * (descuentoProducto / 100));
        }
        var totalRow = parseFloat(precioConDescuentoProducto) * cantidad;
        total += totalRow;
    });
    if (descuentoGeneral > 0) {
        var descuentoTotal = total * (descuentoGeneral / 100);
        total -= descuentoTotal;
    }

    $('#subtotal-table').text(total.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    var formaPago = $('#metodo_pago').val();
    
    if (Array.isArray(formaPago) && formaPago.includes("1") && $('#con_iva').is(':checked')) {
        var montoPago = parseFloat($('#monto_pago_1').val());
        igtf = montoPago * 0.03; // 3% del monto
        $('#igtf-table').text(igtf.toFixed(2)); // Actualizar el valor en el th
    } else {
        $('#igtf-table').text('0.00'); // Si no es "Efectivo Divisas", mostrar 0.00
    }
    var impuesto = 0;
    if ($('#con_iva').is(':checked')) {
        impuesto = total * 0.16;
    }
    
    // Actualizar el total con el impuesto
    var totalConImpuesto = total + impuesto + igtf;
    
    var importe_ml = totalConImpuesto * parseFloat($('#tasa').val());
    $('#importe_me').val(totalConImpuesto.toFixed(2));
    $('#importe_ml').val(importe_ml.toFixed(2));
    
    // Mostrar el total con impuesto en la tabla
    $('#total-table').text(totalConImpuesto.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    
    // Mostrar el impuesto en la tabla
    $('#iva-table').text(impuesto.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
}
$('#descuento').on('input', debounce(function(event) {
    updateTotal();
    updatePeso();
}, 500));
$('#con_iva').on('change', function() {
    updateTotal();
    updatePeso();
});

$('#codigo_servicio').change(function() {
    var selectedOption = $(this).find('option:selected');
    var unidadMedida = selectedOption.data('unidad-medida'); 
    var servicio = selectedOption.data('servicio'); 
    $('#servicio').val(servicio);
    $('#unidad').val(unidadMedida);
    $('#unidad_medida_servicio').val(unidadMedida); 
});


$('#tipo_movimiento').change(function() {
    updateTotal();
    var selectedOption = $(this).find('option:selected');
    if (selectedOption.text().toLowerCase().includes('pago móvil')) {
        // Buscar y seleccionar la opción que contenga "BS" en el texto
        $('#moneda option').each(function() {
            if ($(this).text().toUpperCase().includes('BS')) {
                $(this).prop('selected', true);
                $('#moneda').trigger('change');
                return false;
            }
        });
    }
});

// Manejar cambios en moneda para calcular tasas
$('#moneda').change(function() {
    if ($(this).val()) {
        calcularMontos();
    }
});

// Calcular montos cuando cambia monto o tasa
$('#monto, #tasa').on('input', calcularMontos);

function calcularMontos() {
    var monto = parseFloat($('#monto').val()) || 1;
    var tasa = parseFloat($('#tasa').val()) || 1;
    var monedaTexto = $('#moneda option:selected').text().toUpperCase();
    
    if (!$('#moneda').val()) return;
    
    if (monedaTexto.includes('BS')) {
        // Si la moneda es BS (Bolívares)
        $('#monto_me').val((monto / tasa).toFixed(2)); // Convertir a moneda extranjera
        $('#monto_ml').val(monto.toFixed(2)); // Mantener en bolívares
    } else {
        // Para cualquier otra moneda (USD, EUR, etc.)
        $('#monto_me').val(monto.toFixed(2)); // Mantener en moneda extranjera
        $('#monto_ml').val((monto * tasa).toFixed(2)); // Convertir a bolívares
    }
    updateTotal();
}

// Validación al agregar a la tabla
$('#agregar-pago').on('click', function(e) {
    e.preventDefault();
    
    // Validar campos obligatorios
    if (!$('#tipo_movimiento').val()) {
        alert('Debe seleccionar un Tipo de Movimiento');
        $('#tipo_movimiento').focus();
        return false;
    }
    
    if (!$('#moneda').val()) {
        alert('Debe seleccionar una Moneda');
        $('#moneda').focus();
        return false;
    }
    
    var monto = $('#monto').val();
    if (!monto || parseFloat(monto) <= 0) {
        alert('Debe ingresar un Monto válido');
        $('#monto').focus();
        return false;
    }
    
    agregarATabla('I');
});

function agregarVuelto(tipo_movimiento,tipoMovimientoText,monedaText,monto_me,monto_ml,tasa) {
    var tbody = $('#movimientos-table');
    
    // Eliminar mensaje "Sin pagos realizados" si existe
    if (tbody.find('td').text() === 'Sin pagos realizados.') {
        tbody.empty();
    }

    var tipo_descripcion = 'Vuelto';
    // Crear nueva fila
    var newRow = $('<tr>').append(
        $('<td>').text(tipoMovimientoText),
        $('<td>').text(monto_me),
        $('<td>').text(monto_ml),
        $('<td>').text(tasa),
        $('<td>').text(monedaText),
        $('<td>').text(tipo_descripcion),
        $('<td>').append(
            $('<button>').addClass('btn btn-danger btn-sm eliminar-fila').text('Eliminar'),
            // Inputs hidden para enviar los datos
            $('<input>').attr({
                type: 'hidden',
                name: 'tipo_in_out[]',
                value: "E"
            }),
            $('<input>').attr({
                type: 'hidden',
                name: 'tipos_movimiento[]',
                value: tipo_movimiento
            }),
            $('<input>').attr({
                type: 'hidden',
                name: 'importe_me[]',
                value: monto_me
            }),
            $('<input>').attr({
                type: 'hidden',
                name: 'importe_ml[]',
                value: monto_ml
            }),
            $('<input>').attr({
                type: 'hidden',
                name: 'tasas[]',
                value: tasa
            }),
            $('<input>').attr({
                type: 'hidden',
                name: 'monedas[]',
                value: 3
            }),
            $('<input>').attr({
                type: 'hidden',
                name: 'punto_venta[]',
                value: ""
            }),
            $('<input>').attr({
                type: 'hidden',
                name: 'tipo_tarjeta[]',
                value: ""
            }),
            $('<input>').attr({
                type: 'hidden',
                name: 'numeros_transaccion[]',
                value: ""
            }),
            $('<input>').attr({
                type: 'hidden',
                name: 'monto[]',
                value: monto_ml
            }),
            $('<input>').attr({
                type: 'hidden',
                name: 'moneda_texto[]',
                value: monedaText
            }),
            $('<input>').attr({
                type: 'hidden',
                name: 'factores[]',
                value: ""
            })
        
        )
    );
    
    tbody.append(newRow);
    
    // Limpiar campos del formulario
    $('#monto, #monto_me, #monto_ml, #transaccion').val('');
    $('#tipo_tarjeta').val('').prop('selectedIndex', 0);
    $('#punto_venta').val('').prop('selectedIndex', 0);
   

}

// Función para agregar datos a la tabla
function agregarATabla(tipo) {
    var tbody = $('#movimientos-table');
    
    // Eliminar mensaje "Sin pagos realizados" si existe
    if (tbody.find('td').text() === 'Sin pagos realizados.') {
        tbody.empty();
    }
    
    // Obtener datos del formulario
    var tipoMovimientoText = $('#tipo_movimiento option:selected').text();
    var monedaText = $('#moneda option:selected').text();
    var puntoVentaText = $('#punto_venta option:selected').text();
    var tipoTarjetaText = $('#tipo_tarjeta option:selected').text();
    var tipo_descripcion = (tipo=='I') ? 'Ingreso' : 'Vuelto';
    // Crear nueva fila
    var newRow = $('<tr>').append(
        $('<td>').text(tipoMovimientoText),
        $('<td>').text($('#monto_me').val()),
        $('<td>').text($('#monto_ml').val()),
        $('<td>').text($('#tasa').val()),
        $('<td>').text(monedaText),
        $('<td>').text(tipo_descripcion),
        $('<td>').append(
            $('<button>').addClass('btn btn-danger btn-sm eliminar-fila').text('Eliminar'),
            // Inputs hidden para enviar los datos
            $('<input>').attr({
                type: 'hidden',
                name: 'tipo_in_out[]',
                value: tipo
            }),
            $('<input>').attr({
                type: 'hidden',
                name: 'tipos_movimiento[]',
                value: $('#tipo_movimiento').val()
            }),
            $('<input>').attr({
                type: 'hidden',
                name: 'importe_me[]',
                value: $('#monto_me').val()
            }),
            $('<input>').attr({
                type: 'hidden',
                name: 'importe_ml[]',
                value: $('#monto_ml').val()
            }),
            $('<input>').attr({
                type: 'hidden',
                name: 'tasas[]',
                value: $('#tasa').val()
            }),
            $('<input>').attr({
                type: 'hidden',
                name: 'monedas[]',
                value: $('#moneda').val()
            }),
            $('<input>').attr({
                type: 'hidden',
                name: 'punto_venta[]',
                value: $('#punto_venta').val()
            }),
            $('<input>').attr({
                type: 'hidden',
                name: 'tipo_tarjeta[]',
                value: $('#tipo_tarjeta').val()
            }),
            $('<input>').attr({
                type: 'hidden',
                name: 'numeros_transaccion[]',
                value: $('#transaccion').val()
            }),
            $('<input>').attr({
                type: 'hidden',
                name: 'monto[]',
                value: $('#monto').val()
            }),
            $('<input>').attr({
                type: 'hidden',
                name: 'moneda_texto[]',
                value: monedaText
            }),
            $('<input>').attr({
                type: 'hidden',
                name: 'factores[]',
                value: $('#factor').val()
            })
        
        )
    );
    
    tbody.append(newRow);
    
    // Limpiar campos del formulario
    $('#monto, #monto_me, #monto_ml, #transaccion').val('');
    $('#tipo_tarjeta').val('').prop('selectedIndex', 0);
    $('#punto_venta').val('').prop('selectedIndex', 0);
    calcularTotales();
    updateTotal();
}

// Eliminar filas de la tabla (usando delegación de eventos)
$(document).on('click', '.eliminar-fila', function() {
    $(this).closest('tr').remove();
  
    // Si no quedan filas, mostrar mensaje
    if ($('#movimientos-table tr').length === 0) {
        $('#movimientos-table').html('<tr><td colspan="6">Sin pagos realizados.</td></tr>');
    }
    calcularTotales();
});
function calcularTotales() {
    var totalME = 0;
    var totalML = 0;
    
    // Recorrer todas las filas de la tabla (excepto la de "Sin pagos realizados")
    $('#movimientos-table tr').each(function() {
        // Excluir la fila de mensaje vacío
        if (!$(this).find('td').first().text().includes('Sin pagos realizados')) {
            var importeME = parseFloat($(this).find('td:nth-child(2)').text()) || 0;
            var importeML = parseFloat($(this).find('td:nth-child(3)').text()) || 0;
            
            totalME += importeME;
            totalML += importeML;
        }
    });
    
    // Actualizar los inputs de totales
    $('#importe_pagado_me').val(totalME.toFixed(2));
    $('#importe_pagado_ml').val(totalML.toFixed(2));

    // Calcular los valores de los input restante_me y restante_ml
    var importeME = parseFloat($('#importe_me').val()) || 0;
    var importeML = parseFloat($('#importe_ml').val()) || 0;
    var restanteME = importeME - totalME;
    var restanteML = importeML - totalML;
    
    // Actualizar los input restante_me y restante_ml
    $('#restante_me').val(restanteME.toFixed(2));
    $('#restante_ml').val(restanteML.toFixed(2));

    // Mostrar SweetAlert si hay vuelto
    if (restanteME < 0) {
        vueltos(restanteML,importeML,totalML,restanteME);
    }
}
function vueltos(restanteML, importeML, totalML, restanteME) {
    const diferencia = Math.abs(restanteML.toFixed(2));
    const diferenciaME = Math.abs(restanteME.toFixed(2));
    let opcionesSelect = '';
    formas_vueltos.forEach(function(forma) {
        opcionesSelect += `<option value="${forma.id}">${forma.DESC5I}</option>`;
    });
    
    Swal.fire({
        title: 'Vuelto generado',
        html: `
            <p>Total a pagar: ${importeML.toFixed(2)}</p>
            <p>Total cancelado: ${totalML.toFixed(2)}</p>
            <p>Diferencia (vuelto): ${diferencia.toFixed(2)}</p>
            <div class="form-group">
                <label for="vuelto-option">¿Cómo desea manejar el vuelto?</label>
                <select id="vuelto-option" class="form-control">
                    ${opcionesSelect}
                </select>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const select = document.getElementById('vuelto-option');
            const selectedOption = select.options[select.selectedIndex];
            
            return {
                option: select.value, // ID
                optionName: selectedOption.text, // Nombre del vuelto
                diferencia: diferencia
            };
        }
    }).then((result) => {
        console.log(result);
        if (!result.dismiss) {
            agregarVuelto(result.value.option,result.value.optionName,"BS",diferenciaME,diferencia,$('#tasa').val());
            
        }
    });
}

$('#moneda_extranjera').on('change', function() {
    var selectedOption = $(this).find('option:selected');
    var tasa = selectedOption.attr('data-tasa');
    var texto = selectedOption.text();
    $('#tasa').val(tasa);
    $('#tasa_temp').val(tasa);
    $('#tasa_pago').val(tasa);
    if (texto === 'TEC') {
        $('#tasa_temp').prop('readonly', false);
    } else {
        $('#tasa_temp').prop('readonly', true);
    }  
    updateTotal();            
});
$('#tasa_temp').on('input', function() {
    updateTotal();
    $('#tasa').val($(this).val());
    $('#tasa_pago').val($(this).val());
});