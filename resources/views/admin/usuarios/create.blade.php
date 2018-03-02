@extends('layouts.app')
@section('title') Administración de Usuarios @endsection
@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ url('/') }}">Inicio</a></li>
        <li><a href="{{route('usuarios')}}">Usuarios</a></li>
        <li class="active">Nuevo usuario</li>
    </ol>

    <!-- Datos -->
    <form id="frmDatos" method="post" action="{{ route('guardausuario') }}">
        {{ csrf_field() }}
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>Crear nuevo usuario</h3>
            </div>
            <div class="panel-body">
                @include('shared.partials.errorsform')

                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" class="form-control" name="name" id="name" placeholder="Nombre">
                </div>
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="****">
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="active" id="active" value="1" checked>
                        Cuenta activa
                    </label>
                </div>

                <div class="form-group">
                    <label for="perfil">Perfil</label>
                    <select name="perfil" id="perfil" class="form-control">
                        <option value="">---- Seleciona ----</option>
                        <option value="staff">Staff</option>
                        <option value="coordinador">Coordinador</option>
                        <option value="admin">Administrador</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="circunscripcion">Circunscripcion</label>
                    <select name="circunscripcion" id="circunscripcion" class="form-control">
                        <option value="">---- Seleciona ----</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select name="estado" id="estado" class="form-control">
                        <option value="">---- Seleciona ----</option>
                        <option value="Aguascalientes">Aguascalientes</option>
                        <option value="Baja California">Baja California</option>
                        <option value="Baja California Sur">Baja California Sur</option>
                        <option value="Campeche">Campeche</option>
                        <option value="Coahuila de Zaragoza">Coahuila de Zaragoza</option>
                        <option value="Colima">Colima</option>
                        <option value="Chiapas">Chiapas</option>
                        <option value="Chihuahua">Chihuahua</option>
                        <option value="Ciudad de México">Ciudad de México</option>
                        <option value="Durango">Durango</option>
                        <option value="Guanajuato">Guanajuato</option>
                        <option value="Guerrero">Guerrero</option>
                        <option value="Hidalgo">Hidalgo</option>
                        <option value="Jalisco">Jalisco</option>
                        <option value="Estado de México">Estado de México</option>
                        <option value="Michoacán de Ocampo">Michoacán de Ocampo</option>
                        <option value="Morelos">Morelos</option>
                        <option value="Nayarit">Nayarit</option>
                        <option value="Nuevo León">Nuevo León</option>
                        <option value="Oaxaca">Oaxaca</option>
                        <option value="Puebla">Puebla</option>
                        <option value="Querétaro">Querétaro</option>
                        <option value="Quintana Roo">Quintana Roo</option>
                        <option value="San Luis Potosí">San Luis Potosí</option>
                        <option value="Sinaloa">Sinaloa</option>
                        <option value="Sonora">Sonora</option>
                        <option value="Tabasco">Tabasco</option>
                        <option value="Tamaulipas">Tamaulipas</option>
                        <option value="Tlaxcala">Tlaxcala</option>
                        <option value="Veracruz de Ignacio de la Llave">Veracruz de Ignacio de la Llave</option>
                        <option value="Yucatán">Yucatán</option>
                        <option value="Zacatecas">Zacatecas</option>
                    </select>
                </div>
            </div>
            <div class="panel-footer text-right">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save fa-2x pull-left"></i> Guardar
                </button>
            </div>
        </div>
    </form>

@endsection