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
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <form class="form-inline" method="post" action="{{route('consultorTierraIndex')}}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="alianza">Alianza</label>
                                    <select name="alianza" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="PRI-PVEM-PANAL">PRI-PVEM-PANAL</option>
                                        <option value="PAN-PRD-MC">PAN-PRD-MC</option>
                                        <option value="MORENA-PT-PES">MORENA-PT-PES</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="categoria">Categoría</label>
                                    <select name="categoria" class="form-control">
                                        <option value="">Todos</option>
                                        @foreach( $categorias as $catego)
                                            <option value="{{$catego}}">{{$catego}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="subcategoria">Subcategoría</label>
                                    <select name="subcategoria" class="form-control">
                                        <option value="">Todos</option>
                                        @foreach( $subcategorias as $subcategoria=>$elementos)
                                            <optgroup label="{{strtoupper($subcategoria)}}">
                                                @foreach($elementos as $el)
                                                    <option value="{{$el}}">{{$el}}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-2x fa-search pull-left"></i> Buscar
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <table class="table table-responsive table-striped table-hover table-condensed table-fixed">
                        <thead>
                        <tr>
                            <th>Alianza</th>
                            <th>Fiscalizado a</th>
                            <th>Categoría</th>
                            <th>Subcategoría</th>
                            <th>Creado</th>
                            <th>Aprobado</th>
                            <th>Estado</th>
                            <th>Dirección</th>
                            <th>Acciones</th>
                        </tr>
                        </thead>
                        <tbody style="font-size: 1.2rem;">
                        @foreach($registros as $registro)
                            <tr class="clickable" id="{{$registro->id}}">
                                <td >{{ $registro->alianza }}</td>
                                <td >{{ $registro->partido }}</td>
                                <td >{{ $registro->categoria }}</td>
                                <td >{{ $registro->subcategoria }}</td>
                                <td >{{ Date::parse($registro->creado)->format('d-m-Y H:i:s') }}</td>
                                <td >{{ Date::parse($registro->fecha_revision)->format('d-m-Y H:i:s') }}</td>
                                <td >{{$registro->estado}}</td>
                                <td>{{$registro->direccion}}</td>
                                <td >
                                    <a href="{{route('consultorTierraDetalles', ['id'=>$registro->id])}}"
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