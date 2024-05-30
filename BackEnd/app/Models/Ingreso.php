<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingreso extends Model
{
    protected $table = 'ingresos';
    public $timestamps = false;

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

