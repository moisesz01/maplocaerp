<?php

namespace App\Console\Commands;

use App\Models\Planificacion;
use Illuminate\Console\Command;
use App\Mail\NotificacionPlanificacion;
use Illuminate\Support\Facades\Mail;

class NotificarPlanificaciones extends Command
{
    protected $signature = 'notificar:planificaciones';
    protected $description = 'notificacionesde planificaciones proximas';

    public function handle()
    {
        $upcomingPlanifications = Planificacion::where('fecha_inicio', '>', now())
            ->where('fecha_inicio', '<', now()->addMinutes(20))
            ->get();

        // Notificar por correo electrónico
        foreach ($upcomingPlanifications as $planificacion) {
            //$email = new NotificacionPlanificacion($planificacion,'Notificación de planificación próxima', $planificacion->vendedor->email);
            $email = $planificacion->vendedor->email;
            Mail::send('emails.planificaciones',['planificacion' => $planificacion],function($message) use($email){
                $message->to($email)->from($email)->subject('Notificación de planificación próxima');
            });
        }
        
    }
}
