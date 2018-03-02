@extends('layouts.app')
@section('title') Reportes de Tierra :: Completar Información @endsection
@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ url('/') }}">Inicio</a></li>
        <li><a href="{{route('staffTierraIndex')}}">Tierra</a></li>
        <li class="active">Completar información</li>
    </ol>
    <!-- Datos -->
    <form id="frmDatos" method="post" action="{{ route('staffTierraGuardar') }}">
        <input type="hidden" name="id" value="{{$registro->id}}"/>
        <div class="row">
         <div class="col-sm-12 col-md-6 col-lg-6">

            <div class="panel panel-default">
                <div class="panel-heading">Datos del reporte seleccionado</div>
                <div class="panel-body">
                        {{ csrf_field() }}
                        @if(isset($registro->fueRechazado) && $registro->fueRechazado)
                        <div class="row">
                            <div class="col-sm-12 bg-danger text-danger">
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
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Alianza</label>
                                    <p class="form-control-static">{{ $registro->alianza }}</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Fecha</label>
                                    <p class="form-control-static">{{ Date::parse($registro->created_at)->format('l d F Y H:i:s') }}</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Usuario</label>
                                    <p class="form-control-static"> {{ $registro->usuario['nombre'] }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Categoría</label>
                                    <p class="form-control-static" id="txtCategoria"> {{ $registro->categoria }}</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Subcategoría</label>
                                    <p class="form-control-static" id="txtSubcategoria"> {{ $registro->subcategoria }}</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <button type="button" id="changeCatSub" class="btn btn-lg btn-default">
                                    <i class="fa fa-refresh pull-left"></i> RECLASIFICAR
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Ubicación</label>
                                    <p class="form-control-static">
                                        {{ $registro->ubicacion['coordinates'][1] }}, {{ $registro->ubicacion['coordinates'][0] }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="cantidad">Cantidad</label>:
                                    <input type="number" name="cantidad"
                                           class="form-control"
                                           id="cantidad" value="{{ $registro->cantidad }}" />
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label for="cantidad">Partido para cargar gasto</label>:
                                    <select name="partido[]" id="partido" class="form-control" multiple>
                                        <option value="{{$registro->alianza}}">TODOS</option>
                                        @foreach($partidos as $partido)
                                            @if( strpos($registro->partido, $partido) !== false )
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
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Circunscripción</label>
                                    <p class="form-control-static"> {{ $registro->circunscripcion }}</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Estado</label>
                                    <p class="form-control-static">{{ $registro->estado_id }} {{ $registro->estado }}</p>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Dirección</label>
                                    <p class="form-control-static">{{ $registro->direccion }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group">
                                    <label for="referencias">Referencias de ubicación</label>
                                    <input type="text" name="referencias" id="referencias"
                                           class="form-control"
                                           placeholder="Entre calles, negocios cercanos, etc."
                                           value="@if(isset($registro->referencias) ){{$registro->referencias}}@endif" />
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
                                @if( $registro->quienes_aparecen )
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
                                        <input type="checkbox" name="quienes_aparecen[]" id="quienes_aparecen" value="presidente">presidente
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="quienes_aparecen[]" id="quienes_aparecen" value="senador">senador
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="quienes_aparecen[]" id="quienes_aparecen" value="gobernador">gobernador
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="quienes_aparecen[]" id="quienes_aparecen" value="diputadoFed">diputadoFed
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="quienes_aparecen[]" id="quienes_aparecen" value="alcalde">alcalde
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
                                    <label for="comentarios">Comentarios</label>
                                    <textarea class="form-control" name="comentarios" id="comentarios" rows="3">@if(isset($registro->comentarios) ){{$registro->comentarios}}@endif</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 text-center">

                                <div id="alertas" class="alert alert-danger" role="alert">
                                    <p>
                                        <strong>Error</strong><br/>
                                        <span id="alerta-txt"></span>
                                    </p>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-6 text-center">
                                        <button type="button" class="btn btn-danger btn-lg" id="rechazar">
                                            <i class="fa fa-ban fa-2x pull-left"></i>RECHAZAR
                                        </button>
                                    </div>
                                    <div class="col-sm-12 col-md-6 text-center">
                                        <button type="button" class="btn btn-success btn-lg" id="enviar">
                                            <i class="fa fa-floppy-o fa-2x pull-left"></i>Guardar información
                                        </button>
                                    </div>
                                </div>
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
                                                    @if(isset($registro->mejor_foto) && $registro->mejor_foto == $loop->iteration)
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
                      action="{{ route('staffTierraRechazar') }}">
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
                                <input type="hidden" name="registro_id" value="{{$registro->id}}"/>
                                <strong>Categoría</strong> {{$registro->categoria}}
                                <strong>Subcategoría</strong> {{$registro->subcategoria}}
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
                title: '{{$registro->alianza}} - {{$registro->subcategoria}}'
            });
            var infoWindow = new google.maps.InfoWindow({
                content: "<div><h3>{{$registro->subcategoria}} <small>{{$registro->alianza}}</small></h3><address>{{$registro->direccion}}</address></div>",
            });
            markerEvent.addListener('click', function(){
                infoWindow.open(map, markerEvent);
            });
        }
        /**
         * Jquery code
         **/
        $(document).ready(function () {
            $("#errorReclasifica").empty().hide();
            /**
             * Configuraciones
             */
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("#alertas").hide();
            //click en thumbnail
            $(".img-thumbnail").on('click', function(){
                $("#imagenGrande").attr('src', $(this).attr('src'));
                $("#modalImagenes").modal('show');
            });
            //Eventos de reclasificacion
            $("#modalReclasifica").modal({
                keyboard:false,
                backdrop:'static',
                show:false
            });
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
                    $.post('{{route('staffSubcategoriasTierra')}}', {categoria:cat}, function(response){
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

                    $.post('{{ route('staffTierraReclasifica') }}', {categoria:cat, subcategoria:scat, elemento:'{{$registro->id}}'}, function(response){
                        $("#txtCategoria").text(response.categoria);
                        $("#txtSubcategoria").text(response.subcategoria);
                        $("#modalReclasifica").modal('hide');
                        setTimeout(location.reload(true), 2500);
                    }).fail(function( jqXHR, textStatus, errorThrown){
                        $("#errorReclasifica").text(jqXHR.responseJSON.mensaje).show();
                    });
                }else{
                    $("#errorReclasifica").text('Debes seleccionar la categoría y subcategoría.').show();
                }
            });

            /** Enviar **/
            $("#enviar").on('click', function() {

                $(this).prop('disabled', true);
                $("#rechazar").prop('disabled', true);
                //Validaciones
                if(hfotos ==  0)
                {
                    $("#alerta-txt").text('No hay fotos en el expediente, no se puede enviar la información, se debe RECHAZAR.');
                }else{

                   var foto = $("input[name='foto']:checked").val();
                   if( hfotos > 0 && foto != undefined && foto > 0)
                   {
                       var cant = $("#cantidad").val();
                       if( cant != undefined && cant != "" && !isNaN(cant) && cant > 0){
                           var ref = $("#referencias").val();
                           if( ref != "" && ref != null && ref != undefined && $.trim(ref) != "" && ref.length > 0 )
                           {
                               $("#alertas").hide();
                               $("#alerta-txt").empty();
                               $("#rechazar").prop('disabled', true);

                               var comen = $("#comentarios").val();
                               var confMes = "";
                               if(comen == undefined || comen == "" || $.trim(comen) == "" || comen.length == 0)
                                   confMes = "NO INGRESASTE COMENTARIOS. ";
                               if( window.confirm(confMes+" Deseas proceder con el envío del registro?") ){
                                   $("#frmDatos").submit();
                               }else{
                                   $("#comentarios").focus();
                                   $("#enviar").prop('disabled', false);
                                   $("#rechazar").prop('disabled', false);
                               }
                           }else{
                               $("#enviar").prop('disabled', false);
                               $("#rechazar").prop('disabled', false);
                               $("#alerta-txt").text('Debes ingresar las referencias de ubicación.');
                               $("#alertas").fadeIn();
                               $("#referencias").focus();
                           }
                       }else{
                           $("#enviar").prop('disabled', false);
                           $("#rechazar").prop('disabled', false);
                           $("#alerta-txt").text('Debes ingresar un número en la cantidad.');
                           $("#alertas").fadeIn();
                           $("#cantidad").focus();
                       }
                   }else{
                       $("#enviar").prop('disabled', false);
                       $("#rechazar").prop('disabled', false);
                       $("#alerta-txt").text('Debe seleccionar la mejor imagen.');
                       $("#alertas").fadeIn();
                   }
                }
            });

            /** Rechazar **/
            $("#rechazar").on('click', function(){
                $("#modalRechazado").modal('show');
            });
        });
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiP1oJ0eKtfuh-fSBHUrKLqtrz0_MsX2U&callback=initMap"
            type="text/javascript"></script>
@endsection