<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class Resevento extends Model
{
    /**
     * @var string Conexion a la base de datos objetivo
     */
    protected $connection = 'mongores';
    /**
     * @var string Conexion a la coleccion que contiene los datos
     */
    protected $table = 'eventos';
    /**
     * @var array Atributos que son asignados en masa obligados
     */
    protected $fillable = ["alianza","partido", "categoria", "ubicacion","aforo","duracion"];

    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
        'fecha',
        'fecha_revisado',
        'fecha_aprovado',
        'fecha_rechazo_staff',
        'fecha_rechazo',
        'fecha_enviado_revision',
        'fecha_aprobado'
    ];
}
