<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model {
    protected $table = 'ingresos'; // Hace referencia al nombre de la tabla de la db
    public $timestamps = false; // Decimos que no vamos a trabajar con create ni update timestamps
    
    // Añade los campos que se pueden asignar masivamente
    protected $fillable = [
        'codigoEstudiante',
        'nombreEstudiante',
        'idPrograma',
        'fechaIngreso',
        'horaIngreso',
        'horaSalida',
        'idResponsable',
        'idSala',
    ];
}
