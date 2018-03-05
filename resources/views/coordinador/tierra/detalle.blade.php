@extends('layouts.app')
@section('title') Detalles de reporte de tierra @endsection
@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ url('/') }}">Inicio</a></li>
        <li><a href="{{route('coordinadorTierraIndex')}}">Tierra</a></li>
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
                                    <label class="control-label">Fecha de creación</label>
                                    <p class="form-control-static">{{ Date::parse($registro->created_at)->format('l d F Y H:i:s') }}</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Fecha de actualización</label>
                                    <p class="form-control-static">{{ Date::parse($registro->updated_at)->format('l d F Y H:i:s') }}</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Usuario de captura</label>
                                    <p class="form-control-static"> {{ $registro->usuario['nombre'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Fiscalizado a</label>
                                    <p class="form-control-static"> {{ $registro->partido }}</p>
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
                                    <label class="control-label">Usuario revisor</label>
                                    <p class="form-control-static"> {{ $revisor->name }}</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Estado asignado</label>
                                    <p class="form-control-static"> {{ $revisor->estado_id }} {{ $revisor->estado }}</p>
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
                                    <input type="number" name="cantidad"
                                           class="form-control"
                                           id="cantidad" value="{{ $registro->cantidad }}" />
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
                                    <label for="compartida">Publicidad Compartida</label>
                                    <select name="compartida" id="compartida" class="form-control">
                                        @if( $registro->compartida )
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
                                    <strong>&iquest;Quiénes aparecen?</strong>
                                </p>
                                @if( $registro->quienes_aparecen)
                                    @foreach($registro->quienes_aparecen as $key=>$val)
                                        @if($val)
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="quienes_aparecen[]" id="quienes_aparecen" value="{{$key}}" checked> {{$key}}
                                            </label>
                                        @else
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="quienes_aparecen[]" id="quienes_aparecen" value="{{$key}}"> {{$key}}
                                            </label>
                                        @endif
                                    @endforeach
                                @else
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="quienes_aparecen[]" id="quienes_aparecen" value="presidente"> presidente
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="quienes_aparecen[]" id="quienes_aparecen" value="senador"> senador
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="quienes_aparecen[]" id="quienes_aparecen" value="gobernador"> gobernador
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="quienes_aparecen[]" id="quienes_aparecen" value="diputadoFed"> diputadoFed
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="quienes_aparecen[]" id="quienes_aparecen" value="alcalde"> alcalde
                                    </label>
                                @endif
                            </div>
                        </div>
                        <hr/>
                        @foreach($registro->atributos as $campo)
                            @if( $loop->first)
                                <div class="row">
                                    @endif
                                    @include('shared.partials.textInput4', [
                                        'nombre'=>$campo['nombre'],
                                        'valor'=>$campo['valor'],
                                        'etiqueta'=>ucfirst($campo['nombre'])
                                    ])
                                    @if( $loop->last )
                                </div>
                            @endif
                        @endforeach
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
                        <hr/>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label class="control-label" for="precio">Precio</label>:
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="text" name="precio"
                                               class="form-control" id="precio"
                                               value="@if(isset($registro->precio) && $registro->precio > 0){{$registro->precio}}@else{{"0.0"}}@endif"
                                               aria-describedby="precio">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <div id="alertas" class="alert alert-danger" role="alert">
                                    <p>
                                        <strong>Error</strong><br/>
                                        <span id="alerta-txt"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6 text-center">
                                <button type="button" class="btn btn-danger btn-lg" id="rechazar">
                                    <i class="fa fa-ban fa-2x pull-left"></i>Rechazar
                                </button>
                            </div>
                            <div class="col-sm-12 col-md-6 text-center">
                                <button type="button" class="btn btn-success btn-lg" id="enviar">
                                    <i class="fa fa-floppy-o fa-2x pull-left"></i>Aprobar información
                                </button>
                            </div>
                        </div>
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
                                                <div class="radio">
                                                    <label>
                                                        @if( $registro->mejor_foto == $loop->iteration )
                                                            <input type="radio" name="foto" id="foto{{$loop->iteration}}" value="{{$loop->iteration}}" checked/>
                                                        @else
                                                            <input type="radio" name="foto" id="foto{{$loop->iteration}}" value="{{$loop->iteration}}" />
                                                        @endif
                                                        Foto {{$loop->iteration}}
                                                    </label>
                                                </div>
                                                <img src="{{ $foto }}" class="img-thumbnail" style="width: 200px; height: 200px;"/>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-sm-6 col-md-4">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="foto" id="foto0" value="0" checked />
                                                    No hay Foto
                                                </label>
                                            </div>
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
    <!-- Modal para rechazado -->
    <div class="modal fade" id="modalRechazado" tabindex="-1" role="dialog" aria-labelledby="tituloModal2">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="post" name="frmRechazado" id="frmRechazado"
                      action="{{route('coordinadorTierraRechaza')}}">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="tituloModal2">Rechazar registro</h4>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" value="{{$registro->id}}"/>
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
            //click para enviar datos
            $("#enviar").on('click', function(){
                $(this).prop('disabled', true);
                $("#rechazar").prop('disabled', true);
                $("#alertas").hide();
                $("#alerta-txt").empty();

                var precio = $("#precio").val();
                var foto = $("input[name='foto']:checked").val();
                var cantidad = $("#cantidad").val();
                //Validaciones
                if(hfotos ==  0)
                {
                    $("#alerta-txt").text('No hay fotos en el expediente, no se puede aprobar la información, se debe RECHAZAR.');
                    $("#alertas").fadeIn();
                    $("#rechazar").prop('disabled', false);
                }else{
                    if( hfotos > 0 && foto != undefined && foto > 0)
                    {
                        if(cantidad != undefined && !isNaN(cantidad) && !isNaN(parseInt(cantidad)) )
                        {
                            if( precio != undefined && precio != '' && !isNaN(parseFloat(precio)) && precio > 0 )
                            {
                                $("#frmDatos").submit();
                            }else{
                                $("#alerta-txt").text("Debes ingresar el precio de la publicidad.");
                                $("#alertas").fadeIn();
                                $("#precio").focus();
                                $("#enviar").prop('disabled', false);
                                $("#rechazar").prop('disabled', false);
                            }
                        }else{
                            $("#alerta-txt").text("Debes ingresar una cantidad.");
                            $("#alertas").fadeIn();
                            $("#cantidad").focus();
                            $("#enviar").prop('disabled', false);
                            $("#rechazar").prop('disabled', false);
                        }
                    }else{
                        $("#enviar").prop('disabled', false);
                        $("#rechazar").prop('disabled', false);
                        $("#alerta-txt").text('Debe seleccionar la mejor imagen.');
                        $("#alertas").fadeIn();
                    }
                }
            });
            //Click para rechazar registro
            $("#rechazar").on('click', function(){
                $("#modalRechazado").modal('show');
            });

        });
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiP1oJ0eKtfuh-fSBHUrKLqtrz0_MsX2U&callback=initMap"
            type="text/javascript"></script>
@endsection