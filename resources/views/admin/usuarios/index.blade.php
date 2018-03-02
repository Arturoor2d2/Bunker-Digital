@extends('layouts.app')
@section('title') Administración de Usuarios @endsection
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-offset-2 col-md-8">
            <div class="text-right">
                <a class="btn btn-success" href="{{route('crearusuario')}}">
                    <i class="fa fa-2x fa-plus pull-left"></i> Crear nuevo
                </a>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="row" style="margin-top:5px;">

        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3>Usuarios Registrados</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo electrónico</th>
                            <th>Circunscripción</th>
                            <th>Estado</th>
                            <th>Perfil</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($usuarios as $usuario)
                                <tr>
                                    <td>{{$usuario->name}}</td>
                                    <td>{{$usuario->email}}</td>
                                    <td>{{$usuario->circunscripcion}}</td>
                                    <td>{{$usuario->estado}}</td>
                                    <td>{{$usuario->perfil}}</td>
                                    <td>
                                        <a href="{{ route('verusuario', ['id'=>$usuario->id]) }}" class="btn btn-sm btn-primary">
                                            <i class="fa fa-pencil-square-o fa-2x pull-left"></i> Actualizar datos
                                        </a>
                                        <a href="{{ route('cambiapassword', ['id'=>$usuario->id]) }}" class="btn btn-sm btn-warning">
                                            <i class="fa fa-user-secret fa-2x pull-left"></i> Cambiar contraseña
                                        </a>
                                        <a href="{{ route('borrausuario', ['id'=>$usuario->id]) }}" class="btn btn-sm btn-danger">
                                            <i class="fa fa-trash fa-2x pull-left"></i> Eliminar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

@endsection