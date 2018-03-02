@extends('layouts.app')
@section('title') Administración de Usuarios @endsection
@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ url('/') }}">Inicio</a></li>
        <li><a href="{{route('usuarios')}}">Usuarios</a></li>
        <li class="active">Cambia contraseña</li>
    </ol>

    <!-- Datos -->
    <form id="frmDatos" method="post" action="{{ route('guardanuevopassword') }}">
        {{ csrf_field() }}
        <input type="hidden" name="id" value="{{$usuario->id}}"/>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3>Cambiar contraseña de acceso: {{$usuario->name}}</h3>
            </div>
            <div class="panel-body">
                @include('shared.partials.errorsform')
                <div class="form-group">
                    <label for="newpassword">Nueva contraseña</label>
                    <input type="password" class="form-control" name="newpassword" id="newpassword">
                </div>
                <div class="form-group">
                    <label for="newpassword_confirmation">Confirma contraseña</label>
                    <input type="password" class="form-control" name="newpassword_confirmation" id="newpassword_confirmation">
                </div>
            </div>
            <div class="panel-footer text-right">
                <button type="submit" class="btn btn-danger">
                    <i class="fa fa-refresh fa-2x pull-left"></i> Actualizar contraseña
                </button>
            </div>
        </div>
    </form>
@endsection