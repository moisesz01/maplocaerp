<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'almacen_id',
        'numero_celular',
        'VEND94'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function almacen()
    {
        return $this->belongsTo(Almacen::class,'almacen_id');
    }
    public function facturas()
    {
        return $this->hasMany(Factura::class);
    }
    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }
    public function facturadores()
    {
        return $this->hasMany(AlmacenFacturador::class);
    }
    public function planificaciones()
    {
        return $this->hasMany(Planificacion::class);
    }
}
