$('#add').on('click', function(){
    const producto_id = $('#producto_id_tmp').val();
    const producto = $('#search-product-input').val();
    const cantidad = $('#cantidad').val();
    const precio = $('#precio').val();
    const peso = $('#peso').val();
    const unidad_medida = $('#unidad_medida').val();

    // Verificar si el producto ya existe en la tabla
    const exists = $('#items-tbody tr').find(`input[name="pruducto_id[]"][value="${producto_id}"]`).length > 0;

    if (!exists) {
        addRow(producto_id, producto, cantidad, precio, peso, unidad_medida, 'P') ;
    }else{
        $('#producto_id_tmp').val('');
        Swal.fire(
            'Producto Existente',
            'El producto ya fue agregado',
            'warning'
          );
    }

    $('#search-product-modal').modal('hide');
    $('#search-product-list').empty();
    $('#search-product-input').focus(); 
});
$('#add_especial').on('click', function(){
    const producto_id = $('#codigo_producto').val();
    const producto = $('#nombre_producto').val();
    const cantidad = $('#cantidad_especial').val();
    const precio = $('#precio_especial').val();
    const peso = 1;
    const unidad_medida = 'PZAS';

    // Verificar si el producto ya existe en la tabla
    const exists = $('#items-tbody tr').find(`input[name="pruducto_id[]"][value="${producto_id}"]`).length > 0;

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
function selectProduct(producto_id, producto, disponible, peso, precio, unidad_medida, linea) {
   
    const exists = $('#items-tbody tr').find(`input[name="pruducto_id[]"][value="${producto_id}"]`).length > 0;
    if (!exists) {
        $('#producto_id_tmp').val(producto_id);         
        $('#search-product-input').val(producto);
        $('#search-product-list').empty();
        $('#disponible').val(disponible);         
        $('#peso').val(peso);  
        $('#precio').val(precio);
        $('#unidad_medida').val(unidad_medida);
        $('#codigo').val(producto_id);
        $('#linea').val(linea);
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
        Swal.fire(
            'Producto Existente',
            'El producto ya fue agregado',
            'warning'
          );
    }
      
}

// Función para agregar fila dinámica
// Función para agregar fila dinámica
function addRow(id, description, cantidad, precio, peso, unidad_medida, tipo) {
    let total = Math.round(cantidad * precio * 100) / 100;
    total = formatoNumero(total);
    const producto = id+'-'+description;
    $('#items-tbody').prepend(`
        <tr class="fila">
            <input type="hidden" value="${tipo}" name="tipo[]">
            <input type="hidden" value="${id}" name="producto_id[]">
            <input type="hidden" value='${description}' name="producto[]">
            <input type="hidden" value="${peso}" name="peso[]">
            <input type="hidden" value="${unidad_medida}" name="unidad_medida[]">
            <input type="hidden" value="${precio}" name="precio_bk[]" class="precio_bk">
             <input type="hidden" value="0" name="descuento_producto[]" class="descuento_producto">
            <td>${producto}</td>
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
    $('#search-product-list').empty();
    $('#search-product-input').focus();
});
$('#product-modal').on('shown.bs.modal', function() {
    $('#nombre_producto').val('');
    $('#codigo_producto').val('')
    $('#cantidad_especial').val('');
    $('#precio_especial').val('');
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
                        selectProduct(product.arti87, product.desc87, product.disp_uni_inv, product.peso87, product.precio_neto, product.unmd87, product.linea);
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
        let metodo_pago_ids = $('#metodo_pago').val();
        let forma_pago = $('#forma_pago').val();
        let moneda_extranjera = $('#moneda_extranjera').val();
        if (metodo_pago_ids === null || metodo_pago_ids.length === 0 || forma_pago === null || forma_pago === '') {
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
    }
});
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
    
    // Mostrar el total con impuesto en la tabla
    $('#total-table').text(totalConImpuesto.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    
    // Mostrar el impuesto en la tabla
    $('#iva-table').text(impuesto.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
}
$('#descuento').on('input', debounce(function(event) {
    updateTotal();
}, 500));
$('#con_iva').on('change', function() {
    updateTotal();
});

$('#codigo_servicio').change(function() {
    var selectedOption = $(this).find('option:selected');
    var unidadMedida = selectedOption.data('unidad-medida'); 
    var servicio = selectedOption.data('servicio'); 
    $('#servicio').val(servicio);
    $('#unidad').val(unidadMedida);
    $('#unidad_medida_servicio').val(unidadMedida); 
});