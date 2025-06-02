<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlEmision extends Model
{
    use HasFactory;
    protected $table = 'control_emision';
    protected $fillable = [
        'id', 
        'COMP96', 
        'SUCU96', 
        'CDDO96', 
        'NUDO96'
    ];
}
