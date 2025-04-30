let calendar;
function cargarEventos(start, end, url_eventos, user_id) {
    $('#loading').css('display', 'flex');
    return $.ajax({
        url: url_eventos,
        method: 'GET',
        data: {
            start: start,
            end: end,
            user_id: user_id
        },
        dataType: 'json'
    })
    .fail(function(xhr, status, error) {
        $('#loading').css('display', 'none'); 
    })
    .always(function() {
        $('#loading').css('display', 'none'); 
    });
}



function calendario(user_id,url_eventos,url_actualizar_actividad,url_guardar_actividad,url_detalle,url_acciones,url_eliminar_actividad,token,url_visitas){
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        calendar = new FullCalendar.Calendar(calendarEl, {
            timeZone: 'America/Caracas', 
            themeSystem: 'bootstrap5',
            locale: 'es',
            initialView: 'timeGridWeek',
            slotMinTime: '08:00:00', 
            slotMaxTime: '18:00:00',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            buttonText: { 
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día',
                list: 'Lista'
            },
            firstDay: 1,
            events: function(fetchInfo, successCallback, failureCallback) {
                // Llamar a cargarEventos con las fechas de inicio y fin de la vista actual
                cargarEventos(fetchInfo.startStr, fetchInfo.endStr,url_eventos,user_id).then(function(data) {
                    successCallback(data); // Pasar los eventos al calendario
                }).catch(function() {
                    failureCallback(); // Manejar error en la llamada AJAX
                });
            },
            editable: true
        });
        calendar.render();
        calendar.on('dateClick', function(info) {
            $('#modal_guardar').modal('toggle');
            $('#modal_guardar input').val('');
            $('#fecha_inicio').val(info.dateStr);
            $('#event_id').val(0);
            guardarActividad(info,calendar,url_actualizar_actividad,url_guardar_actividad,url_detalle,url_acciones,url_eliminar_actividad,token);
        });
        calendar.on('eventDrop', function(info) {
        
            $.post(url_actualizar_actividad, {
                '_token': token,
                'id': info.event.id,
                'fecha_inicio':  info.event.start.toISOString(),
                'fecha_fin': info.event.end.toISOString()
            })
            .done(function(data) {
              
            })
            .fail(function(xhr, status, error) {
                console.log(error);
            });
           
        });
        calendar.on('eventResize', function(info) {
        
            $.post(url_actualizar_actividad, {
                '_token': token,
                'id': info.event.id,
                'fecha_inicio':  info.event.start.toISOString(),
                'fecha_fin': info.event.end.toISOString()
            })
            .done(function(data) {
          
            })
            .fail(function(xhr, status, error) {
                console.log(error);
            });
        
        });
        calendar.on('eventClick', function(info) {
        
            $.ajax({
                type: 'GET',
                url: url_detalle,
                data: { id: info.event.id },
                success: function(data) {
                    $('#modal_actualizar').modal('toggle');
                    $('#event_id_actualizar').val(info.event.id);
                    $('#numero_documento_actualizar').val(data.planificacion.cliente.numero_documento);
                    $('#nombre_actualizar').val(data.planificacion.cliente.nombre);
                    $('#ciudad_actualizar').val(data.ciudad);
                    $('#cliente_id_actualizar').val(data.planificacion.cliente_id);
                    $('#actividad_actualizar').val(data.planificacion.actividad);
                    $('#fecha_inicio_actualizar').val(data.planificacion.fecha_inicio);
                    const startDate = moment(data.planificacion.fecha_inicio);
                    const endDate = moment(data.planificacion.fecha_fin);
                    const durationMinutes = endDate.diff(startDate, 'minutes');
                    const durationHoursFormatted = formatDuration(durationMinutes/60);
                    cargar_acciones(url_acciones,accion_vendedor_id_actualizar,'#tipo_accion_actualizar')
                    cargar_acciones_ajax(data.tipo_accion_id,url_acciones,'#accion_vendedor_id_actualizar',data.planificacion.accion_vendedor_id);
                    seleccionar_acciones(data);                    
                    const $duracionSelect = $('#duracion_actualizar');
                    $duracionSelect.find('option').each(function() {
                        if ($(this).val() == durationHoursFormatted) {
                            $duracionSelect.val($(this).val());
                            return false;
                        }
                    });
                    boton_actualizar_evento(url_guardar_actividad,info,token);
                    boton_eliminar_evento(url_eliminar_actividad, info, token);

                    // Agregar botón de visita al cliente si es necesario
                    const cerrarButton = $('.cerrar');
                    const visitaButton = $('<a href="#" class="btn-secondary btn" title="Gestionar Cliente">Visitar Cliente</a>');
                    if (data.tipo_accion && data.tipo_accion === "Visita Presencial") {
                        let url_temp = url_visitas + '?cliente_id='+data.planificacion.cliente_id;
                        visitaButton.attr('href', url_temp);
                        cerrarButton.after(visitaButton);
                    } else {
                        visitaButton.remove();
                    }

                }
            });
           
           
        });
        
    });
    
}
function refrescarEventosDelCalendario(user_id, url_eventos) {
    if (calendar) {
        calendar.removeAllEvents(); // Usar la variable global para acceder al calendario
        const start = moment(calendar.view.activeStart).toISOString();
        const end = moment(calendar.view.activeEnd).toISOString()
        cargarEventos(start, end, url_eventos, user_id).then(function(data) {
            data.forEach(function(evento) {
                
                calendar.addEvent({
                    id: evento.id,
                    title: evento.title,
                    start: evento.start,
                    end: evento.end
                });
            });
        }).catch(function(error) {
            console.error("Error al cargar eventos:", error);
        });
    }
}

function eventos_usuario(url_eventos){
    $('#usuario').on('change', function() {
        refrescarEventosDelCalendario($(this).val(), url_eventos);
    });
}


function search_cliente(url_search){
    $('#numero_documento').keyup(function(){ 
        var query = $(this).val();
        if(query != '')
        {
         var _token = $('input[name="_token"]').val();
         $.ajax({
          url:url_search,
          data:{term:query,},
          success:function(data){
                $('#documentoList').fadeIn();  
                $('#documentoList').html(data);
          }
         });
        }
    }); 
}
$(document).on('click', 'li', function(){  
    const nombre = $(this).find('a').data('nombre');
    const ciudad = $(this).find('a').data('ciudad');
    const cliente_id = $(this).find('a').data('id');
    const documento = $(this).find('a').data('documento');
    $('#nombre').val(nombre); 
    $('#ciudad').val(ciudad);
    $('#cliente_id').val(cliente_id);
    $('#numero_documento').val(documento);  
    $('#documentoList').fadeOut();  
}); 

function cargar_acciones(url,input_name,tipo_accion){
    $(tipo_accion).on('change', function() {
        var tipoAccionId = $(this).val();
        cargar_acciones_ajax(tipoAccionId,url,input_name);
    });
}
function cargar_acciones_ajax(tipoAccionId, url, input_name, accion_vendedor_id = 0) {
    $.ajax({
        type: 'GET',
        url: url,
        data: { tipo_accion_id: tipoAccionId },
        success: function(data) {
            $(input_name).empty();
            $.each(data, function(index, item) {
                let selected = item.id == accion_vendedor_id ? 'selected' : '';
                $(input_name).append(`<option value="${item.id}" ${selected}>${item.nombre}</option>`);
            });
        }
    });
}
function formatDuration(durationHours) {
    if (durationHours % 1 === 0) {
        return durationHours + '';
    } else {
        return durationHours.toFixed(1).replace(',', '.');
    }
}
function seleccionar_acciones(data){
    const tipo_accion = $('#tipo_accion_actualizar');
    tipo_accion.find('option').each(function() {
        if ($(this).val() == data.tipo_accion_id) {
            tipo_accion.val($(this).val());
            return false;
        }
    });
    /*
    const accion_vendedor_id = $('#accion_vendedor_id_actualizar');
    accion_vendedor_id.find('option').each(function() {
        console.log("hola:",$(this).val()," mundo: ",data.planificacion.accion_vendedor_id);
        if ($(this).val() == data.planificacion.accion_vendedor_id) {
            tipo_accion.val($(this).val());
            return false;
        }
    }); */
}
function boton_actualizar_evento(url_guardar_actividad,info,token){
    $('#actualizar').off('click');
    $('#actualizar').click(function(){
        let event_id = $('#event_id_actualizar').val();
        let actividad = $('#actividad_actualizar').val();
        let fecha_inicio = moment($('#fecha_inicio_actualizar').val()).format('YYYY-MM-DD HH:mm') ;
        let duracion =  parseFloat($('#duracion_actualizar').val());
        let cliente_id = $('#cliente_id_actualizar').val();
        let accion_vendedor_id = $('#accion_vendedor_id_actualizar').val();
       
        $.post(url_guardar_actividad, {
            '_token': token,
            'actividad': actividad,
            'fecha_inicio': fecha_inicio,
            'duracion':duracion,
            'cliente_id':cliente_id,
            'accion_vendedor_id':accion_vendedor_id,
            'event_id':event_id
        })
        .done(function(data) {
            info.event.setProp('title', data.cliente+'-'+data.tipo_accion);
            info.event.setStart(data.planificacion.fecha_inicio);
            info.event.setEnd(data.planificacion.fecha_fin);
            $('#modal_actualizar').modal('hide');
            $('#event_id').val(0);
        })
        .fail(function(xhr, status, error) {
            $('#actividadError').html("Debe ingresar una actividad");
        });

    });
}
function boton_eliminar_evento(url_eliminar_actividad, info, token) {
    $('#eliminar').off('click');
    $('#eliminar').click(function(){
        if (confirm("¿Está seguro de eliminar esta actividad?")) {
            let event_id = $('#event_id_actualizar').val();
            $.ajax({
                type: 'DELETE',
                url: url_eliminar_actividad,
                data: {
                    '_token': token,
                    'event_id': event_id
                }
            })
            .done(function(data) {
                info.event.remove();
                $('#modal_actualizar').modal('hide');
                Swal.fire(
                    'Eliminado',
                    'La actividad ha sido eliminada',
                    'success'
                  );
            })
            .fail(function(xhr, status, error) {
                console.log(error);
            });
        }
    });
}

function guardarActividad(info,calendar,url_actualizar_actividad,url_guardar_actividad,url_detalle,url_acciones,url_eliminar_actividad,token) {
    $('#guardar').off('click');
    $('#guardar').click(function(){
               
    let event_id = $('#event_id').val();
    let actividad = $('#actividad').val();
    let fecha_inicio = moment(info.dateStr).format('YYYY-MM-DD HH:mm') ;
    let duracion =  parseFloat($('#duracion').val());
    let cliente_id = $('#cliente_id').val();
    let accion_vendedor_id = $('#accion_vendedor_id').val();

    
    if (cliente_id.length === 0) {
        Swal.fire(
            'Cliente No Seleccionado',
            'Se debe seleccionar un cliente',
            'warning'
            );
        return;
    }
   
    $.post(url_guardar_actividad, {
        '_token': token,
        'actividad': actividad,
        'fecha_inicio': fecha_inicio,
        'duracion':duracion,
        'cliente_id':cliente_id,
        'accion_vendedor_id':accion_vendedor_id,
        'event_id':event_id
    })
    .done(function(data) {
        
        calendar.addEvent( {
            'id':data.planificacion.id,
            'title': data.cliente+'-'+data.tipo_accion,
            'start':data.planificacion.fecha_inicio,
            'end':data.planificacion.fecha_fin
        } );
        $('#modal_guardar').modal('hide');
    })
    .fail(function(xhr, status, error) {
        $('#actividadError').html("Debe ingresar una actividad");
    });

});
}