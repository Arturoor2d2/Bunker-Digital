<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Model;


class Pubfija extends Model
{
    /**
     * @var string Conexion a la base de datos objetivo
     */
    protected $connection = 'mongores';
    /**
     * @var string Conexion a la coleccion que contiene los datos
     */
    protected $table = 'pubfija';
    /**
     * @var array Atributos que son asignados en masa obligados
     */
    protected $fillable = ["categoria", "subcategoria", "ubicacion","status"];

    /**
     * @var bool Fechas de timestamp automaticas
     */
    public $timestamps = true;

    /**
     * Definiendo campos que seran fechas tipo instancia Carbon
     * @var array Campos
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'fecha_envio',
        'fecha_revision',
        'fecha_rechazo',
        'rechazado_en',
        'aprobado'
    ];

}
