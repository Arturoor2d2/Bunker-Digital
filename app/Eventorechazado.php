<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Model;

/**
 * Class Eventorechazado
 * @package App
 *
 * Clase para manejar los eventos rechazados
 */
class Eventorechazado extends Model
{
    /**
     * @var string Conexion a la base de datos objetivo
     */
    protected $connection = 'mongoint';
    /**
     * @var string Conexion a la coleccion que contiene los datos
     */
    protected $table = 'eventorechazado';
    /**
     * @var array Atributos que son asignados en masa obligados
     */
    protected $fillable = [
        "alianza",
        "partido",
        "sede",
        "aforo",
        "compartido",
        "quienes",
        "duracion",
        "ubicacion",
        "direccion",
        "estado",
        "estado_id",
        "circunscripcion",
        "usuario",
        "status"];
    /**
     * Indicando los cast automaticos para campos principales
     * @var array Campos
     */
    protected $casts = [
        "compartido"=>"boolean",
        "precio"=>"double",
        "status"=>"integer",
        "circunscripcion"=>"integer",
        "aforo"=>"integer",
        "fueRechazado"=>"boolean"
    ];
    /**
     * Se requiere el uso de timestamps automáticos
     * @var bool
     */
    public $timestamps = true;
    /**
     * Definiendo campos que seran fechas tipo instancia Carbon
     * @var array Campos
     */
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
