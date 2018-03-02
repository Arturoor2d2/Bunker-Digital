<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Model;

class Respaldos extends Model
{
    /**
     * @var string Conexion a la base de datos objetivo
     */
    protected $connection = 'mongores';
    /**
     * @var string Conexion a la coleccion que contiene los datos
     */
    protected $table = 'respaldos';
    /**
     * @var array Atributos que son asignados en masa obligados
     */
    protected $fillable = ["tipo", "fecha", "ubicacion"];

    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
        'fecha'
    ];
}
