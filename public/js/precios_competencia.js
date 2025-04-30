$('.search-product-input').on('input', debounce(function() {
    searchProducts(url);
}, 500));
function searchProducts(url) {
    $('#search-product-list').empty();
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            search: $('#search-product-input').val(),
            almacen_codigo: $('#almacen_codigo').val()
        },
        success: function(data) {
            $('#search-product-list').empty();
            if (data.data && data.data.length > 0) {
                $.each(data.data, function(index, product) {
                    $('#search-product-list').append(`
                        <li class="list-group-item">
                            <a href="#" onclick="selectProduct(
                                '${product.arti87}', 
                                '${product.desc87}',
                                ${product.disp_uni_inv},
                                ${product.peso87},
                                 ${product.precio_neto},
                                '${product.unmd87}',
                                '${product.linea}',
                            )">${product.desc87}</a>
                        </li>
                    `);
                });
            } else {
                $('#search-product-list').append(`
                    <li class="list-group-item">
                        <p>No se encontraron productos con la descripción suministrada.</p>
                    </li>
                `);
            }
        },
        error: function(xhr, status, error) {
            
            $('#search-product-list').empty();
            $('#search-product-list').append(`
                <li class="list-group-item">
                    <p>Error al buscar productos: ${error}</p>
                </li>
            `);
        }
    });
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
function selectProduct(producto_id, producto, disponible, peso, precio, unidad_medida, linea) {
    
    
    $('#producto_id_tmp').val(producto_id);         
    $('#search-product-input').val(producto);
    $('#search-product-list').empty();
    $('#disponible').val(disponible);         
    $('#peso').val(peso);  
    $('#precio').val(precio);
    $('#unidad_medida').val(unidad_medida);
    $('#codigo').val(producto_id);
    $('#linea').val(linea);
   
      
}