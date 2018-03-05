@extends('layouts.app')
@section('title') Reportes de Tierra @endsection
@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ url('/') }}">Inicio</a></li>
        <li class="active">Tierra</li>
    </ol>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">

                    <table class="table table-responsive table-striped table-hover table-condensed table-fixed">
                        <thead>
                        <tr>
                            <th >Alianza</th>
                            <th >Fiscalizado a</th>
                            <th >Categoría</th>
                            <th >Subcategoría</th>
                            <th >Cantidad</th>
                            <th >Revisado</th>
                            <th >Actualizado</th>
                            <th >Reportado</th>
                            <th >Estado</th>
                            <th>Dirección</th>
                            <th >Acciones</th>
                        </tr>
                        </thead>
                        <tbody style="font-size: 1.2rem;">
                            @foreach($registros as $registro)
                                    <tr class="clickable" id="{{$registro->id}}">
                                        <td >{{ $registro->alianza }}</td>
                                        <td >{{ $registro->partido }}</td>
                                        <td >{{ $registro->categoria }}</td>
                                        <td >{{ $registro->subcategoria }}</td>
                                        <td >{{$registro->cantidad}}</td>
                                        {{-- <td>{{ $registro->ubicacion["coordinates"][1] }},{{ $registro->ubicacion["coordinates"][0] }}</td>  --}}
                                        <td >{{ $registro->fecha_revision->format('d-m-Y H:i:s') }}</td>
                                        <td >{{ Date::parse($registro->updated_at)->format('d-m-Y H:i:s') }}</td>
                                        <td >{{ Date::parse($registro->created_at)->format('d-m-Y H:i:s') }}</td>
                                        <td >{{$registro->estado}}</td>
                                        <td>{{$registro->direccion}}</td>
                                        <td >
                                            <a href="{{route('coordinadorTierraDetalles', ['id'=>$registro->id])}}"
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