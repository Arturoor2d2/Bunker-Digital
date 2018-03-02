<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model as Moloquent;

use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class User extends Moloquent implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, Notifiable;
    use SoftDeletes;
    /**
     * @var string Conexion a la base de datos objetivo
     */
    protected $connection = 'mongousr';
    /**
     * @var string Conexion a la coleccion que contiene los datos
     */
    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','circunscripcion', 'perfil', 'estado', 'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'api-token','password', 'remember_token',
    ];
    /**
     * Indicando los cast automaticos para campos principales
     * @var array Campos
     */
    protected $casts = [
        "web"=>"boolean",
        "active"=>"boolean",
        "circunscripcion"=>"integer",
        "estado_id"=>"string",
        "estado"=>"string",
        "name"=>"string",
        "email"=>"string"
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
        'deleted_at'
    ];
}