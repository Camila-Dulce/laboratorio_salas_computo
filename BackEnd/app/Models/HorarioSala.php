<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioSala extends Model {
    protected $table = 'horario_salas';
    public $timestamps = false;
    
    protected $fillable = [
        'idSala',
        'dia',
        'horaInicio',
        'horaFin',
    ];
}
