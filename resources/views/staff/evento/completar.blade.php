@extends('layouts.app')

@section('title')Detalles del evento @endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <ol class="breadcrumb">
                <li><a href="{{url('/')}}">Inicio</a></li>
                <li><a href="{{route('staffEventoIndex')}}">Eventos</a></li>
                <li class="active">Detalle del evento en: {{ $evento->sede }}</li>
            </ol>
        </div>
    </div>
    <input type="hidden" id="evento_id" value="{{$evento->id}}"/>
        <div class="row">
            <!-- Panel con datos y mapa -->
            <div class="col-sm-12 col-md-6">
                <!-- Datos Generales -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                @if(isset($evento->fueRechazado) && $evento->fueRechazado)
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="bg-danger text-danger">
                                            <div class="form-group">
                                                <label class="control-label">Fecha de rechazo</label>
                                                <p class="form-control-static">{{
                                                    Date::parse($evento->fecha_rechazo)->format('l d F Y H:i:s') }}</p>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label">Motivo</label>
                                                <p class="form-control-static">{{ $evento->motivo }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <form id="frmGeneral">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="evento_id" value="{{$evento->id}}"/>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Alianza</label>
                                                <p class="form-control-static">
                                                    {{$evento->alianza}}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Fecha</label>
                                                <p class="form-control-static">
                                                    {{Date::parse($evento->fecha)->format('d-m-Y H:i:s')}}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Usuario que reporta</label>
                                                <p class="form-control-static">
                                                    {{$evento->usuario['nombre']}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Circunscripción</label>
                                                <p class="form-control-static">
                                                    {{$evento->circunscripcion}}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Estado</label>
                                                <p class="form-control-static">
                                                    {{$evento->estado_id}} {{$evento->estado}}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Sede</label>
                                                <p class="form-control-static">
                                                    {{$evento->sede}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Ubicación</label>
                                                <p class="form-control-static">
                                                    {{ $evento->ubicacion['coordinates'][1] }}, {{ $evento->ubicacion['coordinates'][0] }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-9">
                                            <div class="form-group">
                                                <label class="control-label">Dirección</label>
                                                <p class="form-control-static">
                                                    {{$evento->direccion}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="descripcion_evento">Nombre Evento</label>
                                                <textarea class="form-control" name="descripcion_evento" id="descripcion_evento" rows="1"></textarea>
                                                <label for="compartido">Evento compartido</label>
                                                <select name="compartido" id="compartida" class="form-control">
                                                    @if( $evento->compartido )
                                                        <option value="true" selected>SI</option>
                                                        <option value="false">NO</option>
                                                    @else
                                                        <option value="true">SI</option>
                                                        <option value="false" selected>NO</option>
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-8">
                                            <p>
                                                <strong>&iquest;De que candidatos?</strong>
                                            </p>
                                            @if( $evento->quienes )
                                                @foreach($evento->quienes as $key=>$val)
                                                    @if($val)
                                                        <label class="checkbox-inline">
                                                            <input type="checkbox" name="quienes[]" id="quienes" value="{{$key}}" checked> {{$key}}
                                                        </label>
                                                    @else
                                                        <label class="checkbox-inline">
                                                            <input type="checkbox" name="quienes[]" id="quienes" value="{{$key}}"> {{$key}}
                                                        </label>
                                                    @endif
                                                @endforeach
                                            @else
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="quienes[]" id="quienes" value="presidente">presidente
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="quienes[]" id="quienes" value="senador">senador
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="quienes[]" id="quienes" value="gobernador">gobernador
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="quienes[]" id="quienes" value="diputadoFed">diputadoFed
                                                </label>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="quienes[]" id="quienes" value="alcalde">alcalde
                                                </label>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="aforo">Aforo (personas)</label>:
                                                <input type="number" name="aforo"
                                                       class="form-control"
                                                       id="aforo" value="{{ $evento->aforo }}" />
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="aforo">Duración(hrs)</label>:
                                                <input type="text" name="duracion"
                                                       class="form-control"
                                                       id="duracion" value="{{ $evento->duracion }}" />
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label for="partido">Partido para cargar gasto:</label>
                                                <select name="partido[]" id="partido" class="form-control" multiple>
                                                    <option value="{{$evento->alianza}}">TODOS</option>
                                                    @foreach($partidos as $partido)
                                                        @if( strpos($evento->partido, $partido) !== false )
                                                            <option value="{{$partido}}" selected>{{$partido}}</option>
                                                        @else
                                                            <option value="{{$partido}}">{{$partido}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 text-center">
                                            <button type="button"
                                                    style="margin-top: 20px;"
                                                    class="btn btn-success" id="guardaGenerales">
                                                <i class="fa fa-save fa-2x pull-left"></i>Guardar cambios
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Mapa -->
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div id="map-canvas" style="width:100%;min-height:400px; height:100%;"></div>
                    </div>
                </div>
                <hr/>
                <!-- Boton para cerrar cambios -->
                <div class="row" style="margin-top: 15px; margin-bottom: 15px;">
                    <div class="col-sm-12 col-md-12 text-center">
                        <form
                        name="frmRevisar"
                        id="frmRevisar"
                        method="post"
                        action="{{ route('staffEventoRevisar') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="evento_id" value="{{$evento->id}}"/>
                            <div class="form-group">
                                <label for="comentarios">Comentarios</label>
                                <textarea class="form-control" name="comentarios" id="comentarios" rows="3"></textarea>
                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-sm-6 col-md-5">
                                    <button
                                            type="button"
                                            id="rechazar"
                                            class="btn btn-danger btn-lg">
                                        <i class="fa fa-trash fa-2x pull-left"></i>Rechazar
                                    </button>
                                </div>
                                <div class="col-sm-6 col-md-offset-1 col-md-5">
                                    <button
                                            type="button"
                                            id="finalizar"
                                            class="btn btn-primary btn-lg">
                                        <i class="fa fa-send-o fa-2x pull-left"></i>Enviar a revisión
                                    </button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
            <!-- Panel con levantamiento de información -->
            <div class="col-sm-12 col-md-6">
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="tabContainer" style="height: 300px;">
                            <!-- Navegacion -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#estructura" aria-controls="estructura" role="tab" data-toggle="tab">Estructura</a></li>
                                <li role="presentation"><a href="#utilitario" aria-controls="utilitario" role="tab" data-toggle="tab">Utilitario</a></li>
                                <li role="presentation"><a href="#espectacular" aria-controls="espectacular" role="tab" data-toggle="tab">Espectacular</a></li>
                                <li role="presentation"><a href="#transporte" aria-controls="transporte" role="tab" data-toggle="tab">Transporte</a></li>
                                <li role="presentation"><a href="#produccion" aria-controls="produccion" role="tab" data-toggle="tab">Producción</a></li>
                                <li role="presentation"><a href="#animacion" aria-controls="animacion" role="tab" data-toggle="tab">Animación</a></li>
                            </ul>

                            <!-- Tabs de contenido -->
                            <div class="tab-content">
                                <!-- Estructura -->
                                <div role="tabpanel" class="tab-pane active" id="estructura">
                                    <div class="panel panel-default" style="height: 240px;">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12">
                                                    <table class="table table-fixed-mini">
                                                        <thead>
                                                        <tr>
                                                            <th>Subcategoría</th>
                                                            <th>Cantidad</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody style="font-size: 1.2rem">
                                                        @foreach($estructura as $row)
                                                            <tr>
                                                                <td>{{$row["subcategoria"]}}</td>
                                                                <td>{{$row["cantidad"]}}</td>
                                                                <td>
                                                                    <a href="#" id="{{$row["categoria"]}}-{{$row["numero"]}}"
                                                                       class="detallesEstructura btn btn-xs btn-info">
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
                                </div>
                                <!-- Utilitario -->
                                <div role="tabpanel" class="tab-pane" id="utilitario">
                                    <div class="panel panel-default" style="height: 240px;">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12">
                                                    <table class="table table-fixed-mini">
                                                        <thead>
                                                        <tr>
                                                            <th>Subcategoría</th>
                                                            <th>Cantidad</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody style="font-size: 1.2rem">
                                                        @foreach($utilitario as $row)
                                                            <tr>
                                                                <td>{{$row["subcategoria"]}}</td>
                                                                <td>{{$row["cantidad"]}}</td>
                                                                <td>
                                                                    <a href="#" id="{{$row["categoria"]}}-{{$row["numero"]}}"
                                                                       class="detallesUtilitario btn btn-xs btn-info">
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
                                </div>
                                <!-- Espectacular -->
                                <div role="tabpanel" class="tab-pane" id="espectacular">
                                    <div class="panel panel-default" style="height: 240px;">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12">
                                                    <table class="table table-fixed-mini">
                                                        <thead>
                                                        <tr>
                                                            <th>Subcategoría</th>
                                                            <th>Cantidad</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody style="font-size: 1.2rem">
                                                        @foreach($espectacular as $row)
                                                            <tr>
                                                                <td>{{$row["subcategoria"]}}</td>
                                                                <td>{{$row["cantidad"]}}</td>
                                                                <td>
                                                                    <a href="#" id="{{$row["categoria"]}}-{{$row["numero"]}}"
                                                                       class="detallesEspectacular btn btn-xs btn-info">
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
                                </div>
                                <!-- Transporte -->
                                <div role="tabpanel" class="tab-pane" id="transporte">
                                    <div class="panel panel-default" style="height: 240px;">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12">
                                                    <table class="table table-fixed-mini">
                                                        <thead>
                                                        <tr>
                                                            <th>Subcategoría</th>
                                                            <th>Cantidad</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody style="font-size: 1.2rem">
                                                        @foreach($transporte as $row)
                                                            <tr>
                                                                <td>{{$row["subcategoria"]}}</td>
                                                                <td>{{$row["cantidad"]}}</td>
                                                                <td>
                                                                    <a href="#" id="{{$row["categoria"]}}-{{$row["numero"]}}"
                                                                       class="detallesTransporte btn btn-xs btn-info">
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
                                </div>
                                <!-- Produccion -->
                                <div role="tabpanel" class="tab-pane" id="produccion">
                                    <div class="panel panel-default" style="height: 240px;">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12">
                                                    <table class="table table-fixed-mini">
                                                        <thead>
                                                        <tr>
                                                            <th>Subcategoría</th>
                                                            <th>Cantidad</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody style="font-size: 1.2rem">
                                                        @foreach($produccion as $row)
                                                            <tr>
                                                                <td>{{$row["subcategoria"]}}</td>
                                                                <td>{{$row["cantidad"]}}</td>
                                                                <td>
                                                                    <a href="#" id="{{$row["categoria"]}}-{{$row["numero"]}}"
                                                                       class="detallesProduccion btn btn-xs btn-info">
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
                                </div>
                                <!-- Animacion -->
                                <div role="tabpanel" class="tab-pane" id="animacion">
                                    <div class="panel panel-default" style="height: 240px;">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-12">
                                                    <table class="table table-fixed-mini">
                                                        <thead>
                                                        <tr>
                                                            <th>Subcategoría</th>
                                                            <th>Cantidad</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody style="font-size: 1.2rem">
                                                        @foreach($animacion as $row)
                                                            <tr>
                                                                <td>{{$row["subcategoria"]}}</td>
                                                                <td>{{$row["cantidad"]}}</td>
                                                                <td>
                                                                    <a href="#" id="{{$row["categoria"]}}-{{$row["numero"]}}"
                                                                       class="detallesAnimacion btn btn-xs btn-info">
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel con detalles de registro -->
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <!-- Error -->
                        <div class="row" id="errorPanel">
                            <div class="col-sm-12 col-md-12">
                                <div class="panel panel-danger">
                                    <div class="panel-body">
                                        <div class="text-center text-danger">
                                            <i class="fa fa-5x fa-ban"></i>
                                        </div>
                                        <h1 class="text-center text-danger">
                                            404
                                            <br/>
                                            <small>No encontrado</small>
                                        </h1>
                                        <p class="text-center text-muted" id="errorMensaje"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- FORM CON DATOS -->
                        <form id="frmDetalles">
                            {{ csrf_field() }}
                            <input type="hidden" name="evento_id" value="{{$evento->id}}"/>
                            <input type="hidden" name="referencia" id="referencia" value=""/>
                        <div class="panel panel-default" id="datosPanel">
                            <div class="panel-heading">
                                Detalles del reporte seleccionado
                            </div>
                            <div class="panel-body">

                                <!-- Datos -->
                                <div id="detallesReporte">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Categoría</label>
                                                <p class="form-control-static" id="categoria"></p>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Subcategoría</label>
                                                <p class="form-control-static" id="subcategoria"></p>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <button type="button" id="changeCatSub" class="btn btn-sm btn-default">
                                                <i class="fa fa-refresh pull-left"></i> RECLASIFICAR
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="cantidad">Cantidad</label>
                                                <input type="number" id="cantidad" name="cantidad" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="comentario_staff">Comentario Staff</label>
                                                <input type="text" id="comentario_staff" name="comentario_staff" placeholder="Escribe aqui tu comentario" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div id="detalleAtributos"></div>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div id="detailFotos">
                                                <div class="row"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <div id="detallesEnviar" class="text-right">
                                    <button id="enviarDetalles"
                                            class="btn btn-success">
                                        <i class="fa fa-save fa-2x pull-left"></i>Guardar datos de reporte</button>
                                </div>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <!-- Modal para imagen -->
    <div class="modal fade" id="modalImagenes" tabindex="-1" role="dialog" aria-labelledby="tituloModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="tituloModal">Imagen recibida</h4>
                </div>
                <div class="modal-body">
                    <img src="" style="width: 100%;" id="imagenGrande"/>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL Loading -->
    <div class="modal fade" id="modalLoading" tabindex="-1" role="dialog" aria-labelledby="tituloLoading">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="tituloLoading">Cargando ...</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-10 col-md-offset-1 text-center">
                            <i class="fa fa-refresh fa-spin fa-4x fa-fw"></i>
                            <h3>
                                Espera mientras se carga la información
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL Fail -->
    <div class="modal fade" id="modalFail" tabindex="-1" role="dialog" aria-labelledby="tituloFail">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="tituloFail">ERROR</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-10 col-md-offset-1 text-center text-danger">
                            <i class="fa fa-ban fa-4x fa-fw"></i>
                            <h3>
                                Ocurrió un error inesperado.<br/>
                                <small id="errmess"></small>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL Success -->
    <div class="modal fade" id="modalSuccess" tabindex="-1" role="dialog" aria-labelledby="tituloOk">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="tituloOk">Éxito</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-10 col-md-offset-1 text-center text-success">
                            <i class="fa fa-check fa-4x fa-fw"></i>
                            <h3>
                                Datos salvados.
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para rechazar -->
    <div class="modal fade" id="modalRechazado" tabindex="-1" role="dialog" aria-labelledby="tituloModalRechazar">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" name="frmRechazado" id="frmRechazado"
                      action="{{ route('staffEventoRechaza') }}">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="tituloModalRechazar">Rechazar evento</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="{{$evento->id}}"/>
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-sm-12 col-md-10 col-md-offset-1">
                                <div class="form-group">
                                    <label for="motivo">Motivo del rechazo</label>
                                    <textarea class="form-control" name="motivo" id="motivo" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Rechazar registro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- Modal para reclasificar elemento -->
    <div class="modal fade" id="modalReclasifica" tabindex="-1" role="dialog" aria-labelledby="tituloModalRe">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="tituloModalRe">Reclasifica registro</h4>
                </div>
                <div class="modal-body">
                    <fieldset>
                        <legend>Datos actuales</legend>
                        <p class="text-muted">
                            <input type="hidden" id="reclasificaelemento" name="reclasificaelemento" value="" />
                            <strong>Categoría</strong> <span id="txtCategoActual"></span>
                            <strong>Subcategoría</strong> <span id="txtSubategoActual"></span>
                        </p>
                    </fieldset>
                    <div class="clearfix"></div>
                    <div class="text-danger bg-danger text-center" style="padding-top: 2px; padding-bottom: 2px;" id="errorReclasifica"></div>
                    <div class="clearfix"></div>
                    <fieldset>
                        <legend>Nuevos valores</legend>
                        <div class="row">
                            <div class="col-sm-12 col-md-10 col-md-offset-1">
                                <div class="form-group">
                                    <label for="categorias">Categorias</label>
                                    <select name="categorias" id="categorias" class="form-control">
                                        <option value="">----- SELECCIONA -----</option>
                                        @foreach($categorias as $catego)
                                            <option value="{{$catego}}">{!! strtoupper($catego) !!}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-10 col-md-offset-1">
                                <div class="form-group">
                                    <label for="subcategorias">Subategorias</label>
                                    <select name="subcategorias" id="subcategorias" class="form-control">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btnReclasifica" class="btn btn-primary">Guardar</button>
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
        var map = null;
        var mapOptions = {
            zoom:16,
            center: {lat:{{ $evento->ubicacion['coordinates'][1] }}, lng:{{ $evento->ubicacion['coordinates'][0] }} },
        };
        /**
         * Capitalizar cadena
         */
        function ucfirst (str) {
            str += ''
            var f = str.charAt(0)
                .toUpperCase()
            return f + str.substr(1)
        }
        /**
         * Funcion para inicializar el mapa
         */
        function initMap()
        {
            map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
            var markerEvent = new google.maps.Marker({
                position: new google.maps.LatLng(mapOptions.center.lat, mapOptions.center.lng),
                map: map,
                title: '<strong>{{$evento->alianza}}</strong><br/>{{$evento->sede }}<br/> {{$evento->aforo}}  personas'
            });
            var infoWindow = new google.maps.InfoWindow({
                content: "<div><h3>{{$evento->sede}} <small>{{$evento->alianza}}</small></h3><address>{{$evento->direccion}}</address></div>",
            });
            markerEvent.addListener('click', function(){
                infoWindow.open(map, markerEvent);
            });
        }

        $(document).ready(function(){
            $("#errorReclasifica").empty().hide();
            /**
             * Configuraciones
             */
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            //Ocultando paneles
            $("#datosPanel").hide();
            $("#errorPanel").hide();
            //Configurando modals
            $("#modalLoading").modal({
                keyboard:false,
                backdrop:'static',
                show:false
            });
            $("#modalFail").modal({
                show:false
            });
            $("#modalSuccess").modal({
                show:false
            });
            $("#modalRechazado").modal({
                show:false
            });
            $("#modalReclasifica").modal({
                keyboard:false,
                backdrop:'static',
                show:false
            });
            //Accion para ampliar una foto
            $(document).on('click',"#detailFotos .img-thumbnail", function(){
                $("#imagenGrande").attr('src', $(this).attr('src'));
                $("#modalImagenes").modal('show');
            });

           //Datos generales
            $("#guardaGenerales").on('click', function(e){
                e.preventDefault();
                $("#modalLoading").modal('show');
                $.post('{{route('staffEventoGuardaGenerales')}}', $("#frmGeneral").serialize())
                    .done(function(response){
                        $("#modalLoading").modal('hide');
                        $("#errmess").empty();
                        $("#modalFail").modal('hide');
                        $("#modalSuccess").modal('show');
                    })
                    .fail(function(data){
                        $("#modalLoading").modal('hide');
                        var res = $.parseJSON(data.responseText);
                        $("#errmess").text(res.errmess);
                        $("#modalFail").modal('show');
                    });
            });

            //Para enviar detalles
            $("#enviarDetalles").on('click', function(e){
                e.preventDefault();
                $("#modalLoading").modal('show');
                var Cantidad = $("#cantidad").val();
                var comentario_staff = $("#comentario_staff").val();
                $.post('{{route('staffEventoGuardaDetalles')}}', $("#frmDetalles").serialize())
                    .done(function(response){
                        $("#modalLoading").modal('hide');
                        $("#modalSuccess").modal('show');
                        var actualizar = $("#referencia").val().split('-');
                        $("#"+actualizar[0]+" table tbody").children().eq(actualizar[1]).children().eq(1).text(Cantidad).text(comentario_staff);
                    })
                    .fail(function(data){
                        $("#modalLoading").modal('hide');
                        $("#modalFail").modal('show');
                    });
            });

            //Para finalizar reporte
            $("#finalizar").on('click', function(e){
                e.preventDefault();
                if( window.confirm('Deseas enviar a revisión el evento?') )
                    $("#frmRevisar").submit();
            });

            //Para rechazar evento
            $("#rechazar").on('click', function(e){
               e.preventDefault();
               $("#modalRechazado").modal('show');
            });
            /**
             * Funcion para cargar los detalles recibidos dentro de los paneles
             * @param data
             */
            function cargarDetalles(data)
            {
                $("#categoria").text(data.categoria.toUpperCase());
                $("#subcategoria").text(data.subcategoria.toUpperCase());
                $("#cantidad").val(data.cantidad);
                $("#comentario_staff").val(data.comentario_staff);
                var atributos = data.atributos;
                $.each(atributos, function(index, element){
                    var tempNombre = element.nombre.split(' ').join('');
                    var tempHtml = '<div class="form-group">' +
                        '<label for="'+tempNombre+'">'+ucfirst(element.nombre)+'</label>';

                    if( typeof element.valor == 'boolean')
                    {

                        tempHtml+='<select name="'+tempNombre+'" id="'+tempNombre+'" class="form-control">';
                        if(element.valor)
                            tempHtml += '<option value="true" selected>SI</option><option value="false">NO</option>';
                        else
                            tempHtml += '<option value="true">SI</option><option value="false" selected>NO</option>';
                        tempHtml+='</select>';
                    }else{
                        if(typeof element.valor == 'number')
                        {
                            tempHtml+='<input type="number" id="'+tempNombre+'" name="'+tempNombre+'" value="'+element.valor+'" class="form-control"/>';
                        }
                        else if(element.nombre == 'Comentario capturista'){
                            tempHtml+='<input type="text" id="'+tempNombre+'" name="'+tempNombre+'" value="'+element.valor+'" class="form-control" readonly="readonly"/>';
                        }
                        else{
                            tempHtml+='<input type="text" id="'+tempNombre+'" name="'+tempNombre+'" value="'+element.valor+'" class="form-control"/>';
                        }
                    }
                    tempHtml+='</div>';
                    $("#detalleAtributos").append(tempHtml);
                });

                var fotografias = data.evidencia;
                $.each(fotografias, function(index, element){
                    var tempHtml = '<div class="col-sm-6 col-md-4">'+
                        '<img src="'+element+'" class="img-thumbnail" style="width: 200px; height: 200px;"/>'+
                        '</div>';
                    $("#detailFotos").append(tempHtml);
                });
            }

            //Evento en los registros reportados
            $("a.detallesEstructura, a.detallesEspectacular, a.detallesUtilitario, a.detallesTransporte, a.detallesProduccion, a.detallesAnimacion")
                .on('click', function(e){
                    e.preventDefault();
                    $("#datosPanel").hide();
                    $("#errorPanel").hide();
                    $("#detalleAtributos").empty();
                    $("#detailFotos").empty();
                    $("#modalLoading").modal('show');
                    $.get('{{ route('staffEventoDetalleCategoria')  }}',
                        {
                            id:$(this).attr('id'),
                            ref:$("#evento_id").val()
                        }
                    ).done(function(response)
                    {
                        $("#detalleAtributos").empty();
                        $("#detailFotos").empty();
                        $("#enviarDetalles").prop('disabled', true);
                        $("#modalLoading").modal('hide');
                        if(!response.error)
                        {
                            $("#referencia").val(response.data.categoria+"-"+response.data.numero);
                            cargarDetalles(response.data);
                            $("#enviarDetalles").prop('disabled', false);
                            $("#datosPanel").fadeIn();
                            $("#errorPanel").hide();

                            $("#txtCategoActual").text(response.data.categoria.toUpperCase());
                            $("#txtSubategoActual").text(response.data.subcategoria.toUpperCase());


                        }else{
                            $("#datosPanel").hide();
                            $("#enviarDetalles").prop('disabled', true);
                            $("#errorMensaje").text(response.errmess);
                            $("#errorPanel").fadeIn();

                            $("#txtCategoActual").empty();
                            $("#txtSubategoActual").empty();
                        }
                    }).fail(function(data){
                        $("#modalLoading").modal('hide');
                        $("#modalFail").modal('show');
                    });
            });



            //Eventos de reclasificacion
            $("#changeCatSub").on('click', function(e){
                e.preventDefault();
                $("#errorReclasifica").empty().hide();
                $("#categorias").val($("#categorias option:first").val());
                $("#subcategorias").empty();
                $("#modalReclasifica").modal('show');
            });
            $("#categorias").on('change', function(){
                $("#errorReclasifica").empty().hide();
                var cat = $(this).val();
                $("#subcategorias").empty().append('\'<option value="">CARGANDO DATOS ....</option>\'');
                if(cat!="" && cat!=undefined && cat.length>0)
                {
                    $.post('{{route('staffSubcategoriasEvento')}}', {categoria:cat}, function(response){
                        $("#subcategorias").empty();
                        if(response.subcategoria != undefined && response.subcategoria!=null)
                        {
                            $("#subcategorias").append('<option value="">----- SELECCIONA -----</option>');
                            $.each(response.subcategoria, function(index, element){
                                $("#subcategorias").append('<option value="'+element+'">'+element.toUpperCase()+'</option>');
                            });
                        }
                    }).fail(function(response){
                        $("#subcategorias").empty();
                        $("#errorReclasifica").text('No se cargaron las subcategorias.').show();
                    });
                }
            });
            $("#btnReclasifica").on('click', function(e){
                e.preventDefault();
                var cat = $("#categorias").val();
                var scat = $("#subcategorias").val();

                if((
                        cat != null &&
                        cat != '' &&
                        cat != undefined &&
                        cat.length > 0
                    ) && (
                        scat != null &&
                        scat != '' &&
                        scat != undefined &&
                        scat.length > 0
                    )){
                    $.post('{{ route('staffEventoReclasifica') }}',
                        {
                            categoria:cat,
                            subcategoria:scat,
                            elemento:$("#evento_id").val(),
                            referencia:$("#referencia").val()
                        }, function(response){
                        $("#modalReclasifica").modal('hide');

                        setTimeout(location.reload(true), 2500);
                    }).fail(function( jqXHR, textStatus, errorThrown){
                        $("#errorReclasifica").text(jqXHR.responseJSON.mensaje).show();
                    });
                }else{
                    $("#errorReclasifica").text('Debes seleccionar la categoría y subcategoría.').show();
                }
            });


        });
    </script>
<!-- Google Maps Api -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiP1oJ0eKtfuh-fSBHUrKLqtrz0_MsX2U&callback=initMap"
        type="text/javascript"></script>
@endsection