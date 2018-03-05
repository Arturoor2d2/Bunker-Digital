@extends('layouts.app')
@section('title')Detalle de evento @endsection
@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ url('/') }}">Inicio</a></li>
        <li><a href="{{ route('consultorEventoIndex') }}">Eventos</a></li>
        <li class="active">Detalle del evento: {{$evento->sede}}</li>
    </ol>

    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Información General</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-3">
                            <div class="form-group">
                                <label class="control-label">Alianza</label>
                                <p class="form-control-static">
                                    {{$evento->alianza}}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <div class="form-group">
                                <label class="control-label">Partido</label>
                                <p class="form-control-static">
                                    {{$evento->partido}}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <div class="form-group">
                                <label class="control-label">Fecha</label>
                                <p class="form-control-static">
                                    {{$evento->fecha->format('Y-m-d H:i:s')}}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <div class="form-group">
                                <label class="control-label">Estado</label>
                                <p class="form-control-static">
                                    {{ $evento->estado_id }} {{$evento->estado}}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-3">
                            <div class="form-group">
                                <label class="control-label">Aforo</label>
                                <p class="form-control-static">
                                    {{$evento->aforo}}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <div class="form-group">
                                <label class="control-label">Duración</label>
                                <p class="form-control-static">
                                    {{$evento->duracion}} hrs
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <div class="form-group">
                                <label class="control-label">Sede</label>
                                <p class="form-control-static">
                                    {{$evento->sede}}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <div class="form-group">
                                <label class="control-label">Valor de la Sede</label>
                                <p class="form-control-static">
                                    <span id="precioSede">{{$evento->precioSede}}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12">
                            <div class="form-group">
                                <label class="control-label">Dirección</label>
                                <p class="form-control-static">
                                    {{ $evento->direccion }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-3">
                            <div class="form-group">
                                <label class="control-label">Evento compartido</label>
                                <p class="form-control-static">
                                    @if( $evento->compartido ) SI @else NO @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-9">
                            <div class="form-group">
                                <label class="control-label">Candidatos a</label>
                                <p class="form-control-static">
                                    @if( $evento->quienes )
                                        @foreach($evento->quienes as $key=>$val)
                                            @if( $val )
                                                {{ ucfirst($key) }}
                                            @endif
                                        @endforeach
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-sm-12 col-md-3">
                            <div class="form-group">
                                <label class="control-label">Fecha revisión</label>
                                <p class="form-control-static">
                                    {{ $evento->fecha_revisado->format('Y-m-d H:i:s') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <div class="form-group">
                                <label class="control-label">Revisado por</label>
                                <p class="form-control-static">
                                    {{ $revisor->name }}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <div class="form-group">
                                <label class="control-label">Fecha aprobación</label>
                                <p class="form-control-static">
                                    {{ $evento->fecha_aprobado->format('Y-m-d H:i:s') }}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-3">
                            <div class="form-group">
                                <label class="control-label">Aprobado por</label>
                                <p class="form-control-static">
                                    {{ $aprobador->name }}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-9">
                            <div class="form-group">
                                <label class="control-label">Comentarios</label>
                                <p class="form-control-static">
                                    {{ $evento->comentarios }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <!-- Mapa -->
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div id="map-canvas" style="width:100%;min-height:400px; height:100%;"></div>
                </div>
            </div>
        </div>
        <!-- Panel registros -->
        <div class="col-sm-12 col-md-6">
            <div class="row">
                <div class="col-sm-12">
                    <div class="bg-success text-success">
                        <p class="text-center" style="font-size: 2.1rem;">
                            <strong>VALOR TOTAL DEL EVENTO:</strong> <span id="total">{{$evento->precio}}</span> MXN
                        </p>
                    </div>
                </div>
            </div>
            <!-- Elementos reportados en categorias -->
            <div class="row">
                <div class="col-sm-12">
                    <!-- TABS -->
                    <div class="tabContainer" style="height: 300px;">
                        <!-- Navegacion -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#estructura" aria-controls="estructura" role="tab" data-toggle="tab">Estructura</a></li>
                            <li role="presentation"><a href="#utilitario" aria-controls="utilitario" role="tab" data-toggle="tab">Utilitario</a></li>
                            <li role="presentation"><a href="#espectacular" aria-controls="espectacular" role="tab" data-toggle="tab">Espectacular</a></li>
                            <li role="presentation"><a href="#transporte" aria-controls="transporte" role="tab" data-toggle="tab">Transporte</a></li>
                            <li role="presentation"><a href="#produccion" aria-controls="produccion" role="tab" data-toggle="tab">Producción</a></li>
                            <li role="presentation"><a href="#animacion" aria-controls="animacion" role="tab" data-toggle="tab">Animación</a></li>
                            <li role="presentation"><a href="#adicionales" aria-controls="adicionales" role="tab" data-toggle="tab">Adicionales</a></li>
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
                                                        <th>Precio</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody style="font-size: 1.2rem">
                                                    @foreach($estructura as $row)
                                                        <tr>
                                                            <td>{{$row["subcategoria"]}}</td>
                                                            <td>{{$row["cantidad"]}}</td>
                                                            <td class="money">{{$row['precio']}}</td>
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
                                                        <th>Precio</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody style="font-size: 1.2rem">
                                                    @foreach($utilitario as $row)
                                                        <tr>
                                                            <td>{{$row["subcategoria"]}}</td>
                                                            <td>{{$row["cantidad"]}}</td>
                                                            <td class="money">{{$row['precio']}}</td>
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
                                                        <th>Precio</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody style="font-size: 1.2rem">
                                                    @foreach($espectacular as $row)
                                                        <tr>
                                                            <td>{{$row["subcategoria"]}}</td>
                                                            <td>{{$row["cantidad"]}}</td>
                                                            <td class="money">{{$row['precio']}}</td>
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
                                                        <th>Precio</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody style="font-size: 1.2rem">
                                                    @foreach($transporte as $row)
                                                        <tr>
                                                            <td>{{$row["subcategoria"]}}</td>
                                                            <td>{{$row["cantidad"]}}</td>
                                                            <td class="money">{{$row['precio']}}</td>
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
                                                        <th>Precio</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody style="font-size: 1.2rem">
                                                    @foreach($produccion as $row)
                                                        <tr>
                                                            <td>{{$row["subcategoria"]}}</td>
                                                            <td>{{$row["cantidad"]}}</td>
                                                            <td class="money">{{$row['precio']}}</td>
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
                                                        <th>Precio</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody style="font-size: 1.2rem">
                                                    @foreach($animacion as $row)
                                                        <tr>
                                                            <td>{{$row["subcategoria"]}}</td>
                                                            <td>{{$row["cantidad"]}}</td>
                                                            <td class="money">{{$row['precio']}}</td>
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
                            <!-- Adicionales -->
                            <div role="tabpanel" class="tab-pane" id="adicionales">
                                <div class="panel panel-default" style="height: 240px;">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12">
                                                <table class="table table-fixed-mini">
                                                    <thead>
                                                    <tr>
                                                        <th>Subcategoría</th>
                                                        <th>Cantidad</th>
                                                        <th>Precio</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody style="font-size: 1.2rem">
                                                    @foreach($adicional as $row)
                                                        <tr>
                                                            <td>{{$row["subcategoria"]}}</td>
                                                            <td>{{$row["cantidad"]}}</td>
                                                            <td class="money">{{$row['precio']}}</td>
                                                            <td>
                                                                <a href="#" id="{{$row["categoria"]}}-{{$row["numero"]}}"
                                                                   class="detallesAdicional btn btn-xs btn-info">
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
                        <!-- Fin tabs contenido -->
                    </div>
                    <!-- Fin Tabs -->
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
                    <!--  DATOS -->
                    <input type="hidden" id="evento_id" name="evento_id" value="{{$evento->id}}"/>
                    <div class="panel panel-default" id="datosPanel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Detalles del reporte seleccionado</h3>
                        </div>
                        <div class="panel-body">
                            <!-- Datos -->
                            <div id="detallesReporte">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label">Subcategoría</label>
                                            <p class="form-control-static" id="subcategoria"></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label">Cantidad</label>
                                            <p class="form-control-static" id="cantidad"></p>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label class="control-label">Valor Estimado</label>
                                            <p class="form-control-static" id="precio"></p>
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

                    </div>
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
                title: '<strong>{{$evento->partido}}</strong><br/>{{$evento->sede }}<br/> {{$evento->aforo}}  personas'
            });
            var infoWindow = new google.maps.InfoWindow({
                content: "<div><h3>{{$evento->sede}} <small>{{$evento->partido}}</small></h3><address>{{$evento->direccion}}</address></div>",
            });
            markerEvent.addListener('click', function(){
                infoWindow.open(map, markerEvent);
            });
        }


        $(document).ready(function() {
            //Ocultando paneles
            $("#datosPanel").hide();
            $("#errorPanel").hide();
            //Configurando modals
            $("#modalLoading").modal({
                keyboard:false,
                backdrop:'static',
                show:false
            });
            $("#modalImagenes").modal({
                show:false
            });
            $("#modalFail").modal({
                show:false
            });
            $("#modalSuccess").modal({
                show:false
            });

            //Accion para ampliar una foto
            $(document).on('click',"#detailFotos .img-thumbnail", function(){
                $("#imagenGrande").attr('src', $(this).attr('src'));
                $("#modalImagenes").modal('show');
            });

            //Evento en los registros reportados
            $("a.detallesEstructura, a.detallesEspectacular, a.detallesUtilitario, a.detallesTransporte, a.detallesProduccion, a.detallesAnimacion, a.detallesAdicional")
                .on('click', function(e){
                    e.preventDefault();
                    $("#datosPanel").hide();
                    $("#errorPanel").hide();
                    $("#detalleAtributos").empty();
                    $("#detailFotos").empty();
                    $("#modalLoading").modal('show');
                    $.get('{{ route('consultorEventoDetalleCategoria')  }}',
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
                        }else{
                            $("#datosPanel").hide();
                            $("#enviarDetalles").prop('disabled', true);
                            $("#errorMensaje").text(response.errmess);
                            $("#errorPanel").fadeIn();
                        }
                    }).fail(function(data){
                        $("#modalLoading").modal('hide');
                        $("#modalFail").modal('show');
                    });
                });

            /**
             * Funcion para cargar los detalles recibidos dentro de los paneles
             * @param data
             */
            function cargarDetalles(data)
            {
                $("#subcategoria").text(data.subcategoria);
                $("#cantidad").text(data.cantidad);
                $("#precio").text(accounting.formatMoney(data.precio));
                var atributos = data.atributos;
                $.each(atributos, function(index, element){

                    var tempHtml = '<div class="form-group">' +
                        '<label class="control-label">'+ucfirst(element.nombre)+'</label>';
                    if( typeof element.valor == 'boolean')
                    {
                        if(element.valor)
                            tempHtml+='<p class="form-control-static">SI</p>';
                        else
                            tempHtml+='<p class="form-control-static">NO</p>';
                    }else{
                        tempHtml+='<p class="form-control-static">'+element.valor+'</p>';
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


            $("#precioSede").text(accounting.formatMoney($("#precioSede").text()));
            $("#total").text(accounting.formatMoney($("#total").text()));
            $("td.money").each(function(index){
                $(this).text( accounting.formatMoney( $(this).text() ));
            });
        });
    </script>
    <!-- Google Maps Api -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiP1oJ0eKtfuh-fSBHUrKLqtrz0_MsX2U&callback=initMap"
            type="text/javascript"></script>
@endsection