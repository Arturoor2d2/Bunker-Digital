<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Model;


class Pubrechazada extends Model
{
    /**
     * @var string Conexion a la base de datos objetivo
     */
    protected $connection = 'mongoint';
    /**
     * @var string Conexion a la coleccion que contiene los datos
     */
    protected $table = 'pubrechazada';
    /**
     * @var array Atributos que son asignados en masa obligados
     */
    protected $fillable = ["categoria", "subcategoria", "ubicacion","status"];

    public $timestamps = true;

    protected $dates = array(
        'created_at',
        'updated_at',
        'fecha_envio',
        'fecha_revision',
        'fecha_rechazo',
        'rechazado_en',
        'aprobado'
    );


}

