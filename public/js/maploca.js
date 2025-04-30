function select2_autocompletado(clase, placeholder){
    $(clase).select2({
        placeholder: placeholder,
         theme: 'bootstrap'    
     }).on('select2:open', function() {
             // Agregar la clase al contenedor de Select2
         $(this).data('select2').$container.addClass('select2-bootstrap-prepend');
         $(this).addClass('form-control');   
     })
}
// js del modulo de clientes

$('#get-location').click(function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            $('#latitud').val(position.coords.latitude);
            $('#longitud').val(position.coords.longitude);
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
    if ($('#fecha_checkin').length > 0) {
        var fechaActual = new Date();
        var opciones = { timeZone: 'America/Caracas', year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit' };  
        $('#fecha_checkin').val(moment(fechaActual).format('YYYY-MM-DDTHH:mm'));
    }
});

