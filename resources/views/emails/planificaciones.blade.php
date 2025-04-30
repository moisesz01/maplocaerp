
<h1>Notificación de planificación próxima</h1>

<p>Estimado/a {{ $planificacion->vendedor->name }},</p>

<p>Tiene una planificación próxima que se realizará en {{ date('d-m-Y g:ia', strtotime($planificacion->fecha_inicio)) }}.</p>

<p>Detalles de la planificación:</p>

<ul>
    <li>Cliente: {{ $planificacion->cliente->nombre }}</li>
    <li>Acción: {{ $planificacion->accion->nombre }}</li>
    <li>Tipo de acción: {{ $planificacion->accion->tipo_accion->nombre }}</li>
</ul>
