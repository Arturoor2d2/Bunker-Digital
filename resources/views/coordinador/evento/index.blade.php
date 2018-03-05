@extends('layouts.app')
@section('title') Eventos Reportados @endsection
@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ url('/') }}">Inicio</a></li>
        <li class="active">Eventos</li>
    </ol>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table table-responsive table-striped table-hover table-condensed table-fixed">
                        <thead>
                            <tr>
                                <th class="text-center">Alianza</th>
                                <th class="text-center">Partido</th>
                                <th class="text-center">Fecha</th>
                                <th class="text-center">Aforo</th>
                                <th class="text-center">Sede</th>
                                <th class="text-center">Duración</th>
                                <th class="text-center">Fecha de Revisión</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($eventos as $evento)
                            <tr>
                                <td class="text-center">{{$evento->alianza}}</td>
                                <td class="text-center">{{$evento->partido}}</td>
                                <td class="text-center">{{$evento->fecha->format('Y-m-d H:i:s')}}</td>
                                <td class="text-center">{{$evento->aforo}}</td>
                                <td class="text-center">{{$evento->sede}}</td>
                                <td class="text-center">{{$evento->duracion}}</td>
                                <td class="text-center">{{Date::parse($evento->fecha_enviado_revision)->format('Y-m-d H:i:s')}}</td>
                                <td class="text-center">
                                    <a href="{{route('coordinadorEventoDetalles', ['id'=>$evento->id])}}"
                                       class="btn btn-primary btn-sm">
                                        Ver detalles
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
@section('bottom_javascript')
    <!-- Librerias javascript -->
    <script
            src="https://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
            crossorigin="anonymous"></script>
    <!-- Codigo javascript -->
    <script language="javascript" type="text/javascript">
        $(document).ready(function() {
        });
    </script>
@endsection