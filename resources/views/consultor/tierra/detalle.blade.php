@extends('layouts.app')
@section('title') Detalles de reporte de tierra @endsection
@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ url('/') }}">Inicio</a></li>
        <li><a href="{{route('consultorTierraIndex')}}">Tierra</a></li>
        <li class="active">Detalles de reporte</li>
    </ol>
    <!-- Datos -->
    <form id="frmDatos" method="post" action="{{ route('coordinadorTierraGuardar') }}">
        <input type="hidden" name="id" value="{{$registro->id}}"/>
        {{ csrf_field() }}
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-6">

                <div class="panel panel-default">
                    <div class="panel-heading">Datos del reporte seleccionado</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Alianza</label>
                                    <p class="form-control-static">{{ $registro->alianza }}</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Fiscalizado a</label>
                                    <p class="form-control-static"> {{ $registro->partido }}</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Precio</label>
                                    <p class="form-control-static"> {{ $registro->precio }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Usuario de captura</label>
                                    <p class="form-control-static"> {{ $registro->usuario['nombre'] }}</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Usuario que revisó</label>
                                    <p class="form-control-static"> {{ $revisor->name }}</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Usuario que aprobó</label>
                                    <p class="form-control-static"> {{ $aprobador->name }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Fecha de creación</label>
                                    <p class="form-control-static">{{ Date::parse($registro->creado)->format('l d F Y H:i:s') }}</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Fecha de revisión</label>
                                    <p class="form-control-static"> {{ Date::parse($registro->fecha_revision)->format('l d F Y H:i:s') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Fecha de aprobación</label>
                                    <p class="form-control-static">{{ Date::parse($registro->updated_at)->format('l d F Y H:i:s') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Categoría</label>
                                    <p class="form-control-static"> {{ $registro->categoria }}</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Subcategoría</label>
                                    <p class="form-control-static"> {{ $registro->subcategoria }}</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label for="cantidad">Cantidad</label>:
                                    <p class="form-control-static">{{ $registro->cantidad }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Ubicación</label>
                                    <p class="form-control-static">
                                        {{ $registro->ubicacion['coordinates'][1] }}, {{ $registro->ubicacion['coordinates'][0] }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Circunscripción</label>
                                    <p class="form-control-static"> {{ $registro->circunscripcion }}</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Estado</label>
                                    <p class="form-control-static">{{ $registro->estado_id }} {{ $registro->estado }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Dirección</label>
                                    <p class="form-control-static">{{ $registro->direccion }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Referencias de ubicación</label>
                                    <p class="form-control-static">{{ $registro->referencias }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Publicidad Compartida</label>
                                    <p class="form-control-static">
                                        @if( $registro->compartida )
                                            SI
                                        @else
                                            NO
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-8">
                                <div class="form-group">
                                    <label class="control-label">&iquest;Quiénes aparecen?</label>
                                    <p class="form-control-static">
                                        @if( $registro->quienes_aparecen )
                                            @foreach($registro->quienes_aparecen as $key=>$val)
                                                @if($val)
                                                    {{ $key }}&nbsp;
                                                @endif
                                            @endforeach
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-sm-12">
                                <h5>Atributos</h5>
                        @foreach($registro->atributos as $campo)
                            @if( $loop->first)
                                <p>
                            @endif
                                    <strong>
                                        {{ str_replace("_", ' ',ucfirst($campo['nombre'])) }}
                                    </strong>
                                        {{$campo['valor']}}
                                    &nbsp;
                            @if( $loop->last )
                                </p>
                            @endif
                        @endforeach
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Comentarios</label>
                                    <p class="form-control-static">{{$registro->comentarios}}</p>
                                </div>
                            </div>
                        </div>
                        @if( isset($registro->fueRechazado) && $registro->fueRechazado)
                            <div class="row">
                                <div class="col-sm-12 bg-warning text-warning">
                                    <p class="text-center">Este registro había sido rechazado anteriormente.</p>
                                    <div class="form-group">
                                        <label class="control-label">Fecha de rechazo</label>
                                        <p class="form-control-static">{{ Date::parse($registro->rechazado_en)->format('l d F Y H:i:s') }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Motivo</label>
                                        <p class="form-control-static">{{ $registro->motivo }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- End panel body -->
                    </div>
                    <!-- End Panel -->
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-6">
                <!-- MAPA -->
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div id="map-canvas" style="width:100%;min-height:400px; height:100%;"></div>
                    </div>
                </div>
                <hr/>
                <!-- FOTOS -->
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">Selecciona la mejor imagen</div>
                            <div class="panel-body">
                                <div class="row">
                                    @if( count( $fotos) > 0)
                                        @foreach($fotos as $foto)
                                            <div class="col-sm-6 col-md-4">
                                                <img src="{{ $foto }}" class="img-thumbnail" style="width: 200px; height: 200px;"/>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-sm-6 col-md-4">
                                            <h5>No hay Fotos</h5>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
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
            zoom:18,
            center: {lat:{{ $registro->ubicacion['coordinates'][1] }}, lng:{{ $registro->ubicacion['coordinates'][0] }} },
        };
        var hfotos = {{ count($fotos) }};

        /**
         * Funcion para inicializar el mapa
         */
        function initMap()
        {
            map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
            var markerEvent = new google.maps.Marker({
                position: new google.maps.LatLng(mapOptions.center.lat, mapOptions.center.lng),
                map: map,
                title: '{{$registro->partido}} - {{$registro->subcategoria}}'
            });
            var infoWindow = new google.maps.InfoWindow({
                content: "<div><h3>{{$registro->subcategoria}} <small>{{$registro->partido}}</small></h3><address>{{$registro->direccion}}</address></div>",
            });
            markerEvent.addListener('click', function(){
                infoWindow.open(map, markerEvent);
            });
        }

        /**
         * Jquery code
         */
        $(document).ready(function () {
            $("#alertas").hide();
            $("#alerta-txt").empty();
            //Click en thumbnail
            $(".img-thumbnail").on('click', function(){
                $("#imagenGrande").attr('src', $(this).attr('src'));
                $("#modalImagenes").modal('show');
            });
        });
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiP1oJ0eKtfuh-fSBHUrKLqtrz0_MsX2U&callback=initMap"
            type="text/javascript"></script>
@endsection