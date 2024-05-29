<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HorarioSala extends Model
{
    protected $table = 'horarios_salas'; // Hace referencia al nombre de la tabla de la db
    public $timestamps = false; // Decimos que no vamos a trabajar con create ni update timestamps
    
    // Añade los campos que se pueden asignar masivamente
    protected $fillable = [
        'dia',
        'materia',
        'horaInicio',
        'horaFin',
        'idPrograma',
        'idSala',
    ];
}
