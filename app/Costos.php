<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Model;

class Costos extends Model
{
    /**
     * @var string Conexion a la base de datos objetivo
     */
    protected $connection = 'mongoint';
    /**
     * @var string Conexion a la coleccion que contiene los datos
     */
    protected $table = 'costos';
    /**
     * @var array Atributos que son asignados en masa obligados
     */
    protected $fillable = [
        "categoria",
        "subcategoria",
        "precio"];
    /**
     * Indicando los cast automaticos para campos principales
     * @var array Campos
     */
    protected $casts = [
        "categoria"=>"string",
        "subcategoria"=>"string",
        "precio"=>"double"
    ];
    /**
     * Se requiere el uso de timestamps automáticos
     * @var bool
     */
    public $timestamps = false;
}
