@extends('layouts.app')
@section('title') Respaldos de Tierra @endsection
@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ url('/') }}">Inicio</a></li>
        <li class="active">Respaldos de Tierra</li>
    </ol>

    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Respaldos generados</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <form name="frmExportar" method="post" action="{{ route('consultorExportar') }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="tipo" value="tierra"/>
                                <button type="submit" name="btnExport" class="btn btn-primary">
                                    <i class="fa fa-2x fa-cloud-upload pull-left"></i> Exportar Ahora
                                </button>
                            </form>
                        </div>
                    </div>
                    <table class="table table-responsive table-striped table-hover table-condensed table-fixed">
                        <thead>
                        <tr>
                            <th>Identificador</th>
                            <th>Fecha de Respaldo</th>
                            <th>Archivo</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($respaldos) && count($respaldos)>0 )
                            @foreach($respaldos as $respaldo)
                                <tr>
                                    <td>{{$respaldo['id']}}</td>
                                    <td>{{$respaldo['fecha']->format('d-m-Y H:i:s')}}</td>
                                    <td><a href="{{$respaldo['ubicacion']}}" class="btn btn-sm btn-primary">Descargar</a></td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection