@extends('layouts.app')
@section('title') Administración de Usuarios @endsection
@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ url('/') }}">Inicio</a></li>
        <li><a href="{{route('usuarios')}}">Usuarios</a></li>
        <li class="active">Nuevo usuario</li>
    </ol>

    <!-- Datos -->
    <form id="frmDatos" method="post" action="{{ route('actualizausuario') }}">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{$usuario->id}}"/>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>Actualizar usuario</h3>
            </div>
            <div class="panel-body">
                @include('shared.partials.errorsform')

                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{$usuario->name}}">
                </div>
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" class="form-control" name="email" id="email" value="{{$usuario->email}}">
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="active" id="active" value="1" @if($usuario->active) checked @endif>
                        Cuenta activa
                    </label>
                </div>

                <div class="form-group">
                    <label for="perfil">Perfil</label>
                    <select name="perfil" id="perfil" class="form-control">
                        <option value="">---- Seleciona ----</option>
                        <option value="staff" @if($usuario->perfil == 'staff') selected @endif>Staff</option>
                        <option value="coordinador" @if($usuario->perfil == 'coordinador') selected @endif>Coordinador</option>
                        <option value="admin" @if($usuario->perfil == 'admin') selected @endif>Administrador</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="circunscripcion">Circunscripcion</label>
                    <select name="circunscripcion" id="circunscripcion" class="form-control">
                        <option value="">---- Seleciona ----</option>
                        <option value="1" @if($usuario->circunscripcion == 1) selected @endif>1</option>
                        <option value="2" @if($usuario->circunscripcion == 2) selected @endif>2</option>
                        <option value="3" @if($usuario->circunscripcion == 3) selected @endif>3</option>
                        <option value="4" @if($usuario->circunscripcion == 4) selected @endif>4</option>
                        <option value="5" @if($usuario->circunscripcion == 5) selected @endif>5</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="estado">Estado</label>
                    <select name="estado" id="estado" class="form-control">
                        <option value="">---- Seleciona ----</option>
                        <option value="Aguascalientes" @if($usuario->estado == "Aguascalientes") selected @endif>Aguascalientes</option>
                        <option value="Baja California" @if($usuario->estado == "Baja California") selected @endif>Baja California</option>
                        <option value="Baja California Sur" @if($usuario->estado == "Baja California Sur") selected @endif>Baja California Sur</option>
                        <option value="Campeche" @if($usuario->estado == "Campeche") selected @endif>Campeche</option>
                        <option value="Coahuila de Zaragoza" @if($usuario->estado == "Coahuila de Zaragoza") selected @endif>Coahuila de Zaragoza</option>
                        <option value="Colima" @if($usuario->estado == "Colima") selected @endif>Colima</option>
                        <option value="Chiapas" @if($usuario->estado == "Chiapas") selected @endif>Chiapas</option>
                        <option value="Chihuahua" @if($usuario->estado =="Chihuahua") selected @endif>Chihuahua</option>
                        <option value="Ciudad de México" @if($usuario->estado == "Ciudad de México") selected @endif>Ciudad de México</option>
                        <option value="Durango" @if($usuario->estado == "Durango") selected @endif>Durango</option>
                        <option value="Guanajuato" @if($usuario->estado == "Guanajuato") selected @endif>Guanajuato</option>
                        <option value="Guerrero" @if($usuario->estado == "Guerrero") selected @endif>Guerrero</option>
                        <option value="Hidalgo" @if($usuario->estado == "Hidalgo") selected @endif>Hidalgo</option>
                        <option value="Jalisco" @if($usuario->estado == "Jalisco") selected @endif>Jalisco</option>
                        <option value="Estado de México" @if($usuario->estado == "Estado de México") selected @endif>Estado de México</option>
                        <option value="Michoacán de Ocampo" @if($usuario->estado == "Michoacán de Ocampo") selected @endif>Michoacán de Ocampo</option>
                        <option value="Morelos" @if($usuario->estado == "Morelos") selected @endif>Morelos</option>
                        <option value="Nayarit" @if($usuario->estado == "Nayarit") selected @endif>Nayarit</option>
                        <option value="Nuevo León" @if($usuario->estado == "Nuevo León") selected @endif>Nuevo León</option>
                        <option value="Oaxaca" @if($usuario->estado == "Oaxaca") selected @endif>Oaxaca</option>
                        <option value="Puebla" @if($usuario->estado == "Puebla") selected @endif>Puebla</option>
                        <option value="Querétaro" @if($usuario->estado == "Querétaro") selected @endif>Querétaro</option>
                        <option value="Quintana Roo" @if($usuario->estado == "Quintana Roo") selected @endif>Quintana Roo</option>
                        <option value="San Luis Potosí" @if($usuario->estado == "San Luis Potosí") selected @endif>San Luis Potosí</option>
                        <option value="Sinaloa" @if($usuario->estado == "Sinaloa") selected @endif>Sinaloa</option>
                        <option value="Sonora" @if($usuario->estado == "Sonora") selected @endif>Sonora</option>
                        <option value="Tabasco" @if($usuario->estado == "Tabasco") selected @endif>Tabasco</option>
                        <option value="Tamaulipas" @if($usuario->estado == "Tamaulipas") selected @endif>Tamaulipas</option>
                        <option value="Tlaxcala" @if($usuario->estado == "Tlaxcala") selected @endif>Tlaxcala</option>
                        <option value="Veracruz de Ignacio de la Llave" @if($usuario->estado == "Veracruz de Ignacio de la Llave") selected @endif>Veracruz de Ignacio de la Llave</option>
                        <option value="Yucatán" @if($usuario->estado == "Yucatán") selected @endif>Yucatán</option>
                        <option value="Zacatecas" @if($usuario->estado == "Zacatecas") selected @endif>Zacatecas</option>
                    </select>
                </div>
            </div>
            <div class="panel-footer text-right">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-refresh fa-2x pull-left"></i> Actualizar
                </button>
            </div>
        </div>
    </form>

@endsection