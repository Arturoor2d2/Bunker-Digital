@extends('layouts.app')
@section('title') Reportes de Tierra @endsection
@section('content')
    <div class="row">
        <div class="col-sm-12 text-left text-muted" style="font-size: 0.8em;">
            Actualizando datos en <span id="tiempo"></span> seg.
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <!-- Mapa -->
            <div class="row">
                <div class="col-md-12">
                    <div id="map-canvas" style="width:100%;min-height:400px; height:100%;"></div>
                </div>
            </div>
            <!-- Detalles -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong>Detalles</strong>
                        </div>
                        <div class="panel-body">

                            <div id="errorContainer" class="hidden">
                                @include('shared.partials.404', ['mensaje'=>""])
                            </div>

                            <div id="detailContainer" class="hidden">
                                <div class="row">
                                    <!-- Datos -->
                                    <div class="col-sm-12 col-md-6">
                                        <p class="text-danger">
                                            <strong>Fecha Rechazo</strong>: <span id="detailFechaRechazo"></span> <br/>
                                            <strong>Motivo</strong>: <span id="detailMotivo"></span>
                                        </p>
                                        <hr/>
                                        <p>
                                            <strong>Alianza</strong>: <span id="detailAlianza"></span><br/>
                                            <strong>Fecha</strong>: <span id="detailFecha"></span> <br/>
                                            <strong>Categoría</strong>: <span id="detailCategoria"></span> <br/>
                                            <strong>Subcategoría</strong>: <span id="detailSubcategoria"></span> <br/>
                                            <strong>Estado</strong>: <span id="detailEstado"></span> <br/>
                                            <strong>Dirección</strong>: <span id="detailDireccion"></span> <br/>
                                            <strong>Usuario</strong>: <span id="detailUsuario"></span> <br/>
                                        </p>
                                        <hr/>
                                        <p id="detailCaracteristicas"></p>
                                    </div>
                                    <!-- Fotos -->
                                    <div class="col-sm-12 col-md-6">
                                        <div id="detailFotos">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Acciones -->
                        <div class="panel-footer">
                            <div id="actionButtons" class="text-center hidden">
                                <a src="{{route('staffTierraCompletar')}}" id="review" class="btn btn-warning">
                                    <i class="fa fa-eye fa-lg pull-left"></i>Revisar Información
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tabla con registros -->
        <div class="col-md-6">
            <div class="row">
                <div class="col-sm-10 text-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Filtro: RECHAZADOS <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="{{route('staffTierraIndex')}}">ACTIVOS</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ route('staffTierraIndex', ['rechazado'=>true]) }}">RECHAZADOS</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="col-sm-12" style="background-color: white;">
                    <table class="table table-responsive table-striped table-hover table-condensed table-fixed">
                        <thead>
                        <tr>
                            <th>Alianza</th>
                            <th>Categoría</th>
                            <th>Subcategoría</th>
                            <th>Cantidad</th>
                            <th>Estado</th>
                            <th>Fecha Rechazo</th>
                            <th>Dirección</th>
                        </tr>
                        </thead>
                        <tbody style="font-size: 1.2rem">
                        @foreach( $registros as $registro )
                                <tr class="clickable" id="{{$registro->id}}">
                                    <td>{{ $registro->alianza }}</td>
                                    <td>{{ $registro->categoria }}</td>
                                    <td>{{ $registro->subcategoria }}</td>
                                    {{-- <td>{{ $registro->ubicacion["coordinates"][1] }},{{ $registro->ubicacion["coordinates"][0] }}</td>  --}}
                                    <td>{{$registro->cantidad}}</td>
                                    <td>{{$registro->estado}}</td>
                                    <td>{{ Date::parse($registro->rechazado_en)->format('l d F Y H:i:s') }}</td>
                                    <td>{{$registro->direccion}}</td>
                                </tr>
                        @endforeach
                        </tbody>
                    </table>
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

@endsection

@section('bottom_javascript')
    <!-- Librerias javascript -->
    <script
            src="https://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
            crossorigin="anonymous"></script>
    <!-- Codigo javascript -->
    <script language="javascript" type="text/javascript">
        var InitMarkers = [
                @foreach($registros as $registro)
            { lattitude:{{ $registro->ubicacion["coordinates"][1]  }}, longitude:{{ $registro->ubicacion["coordinates"][0] }}, title:"{{$registro->alianza}}=>{{$registro->subcategoria }}" },
            @endforeach
        ];

        var Markers = [];
        var map = null;
        var activeRow = undefined;
        var mapOptions = {
            zoom:5,
            center: {lat: 19.790611, lng: -99.327393 },
        };
        /**
         * Funcion para mostrar marcadores sobre el mapa
         * @param lattitude Coordenada
         * @param longitude Coordenada
         * @param title Contenido
         * @param content Contenido
         */
        function setMarker(lattitude, longitude, title, content)
        {
            var markerEvent = new google.maps.Marker({
                position: new google.maps.LatLng(lattitude, longitude),
                map: map,
                title: title
            });
            var infoWindow = new google.maps.InfoWindow({
                content: content,
            });
            markerEvent.addListener('click', function(){
                infoWindow.open(map, markerEvent);
                map.setCenter(markerEvent.getPosition());
                map.setZoom(18);
            });
            return markerEvent;
        }
        /**
         * Funcion para centrar el mapa
         * @param lattitude Coordenada
         * @param longitude Coordenada
         * @param zoom Nivel de zoom
         */
        function centerMap(lattitude, longitude, zoom)
        {
            map.setCenter(new google.maps.LatLng(lattitude, longitude));
            map.setZoom(zoom);
        }
        /**
         * Funcion para inicializar el canvas del mapa
         */
        function initMap()
        {
            map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
            if( InitMarkers.length > 0)
            {
                for(var i = 0; i < InitMarkers.length; i++)
                {
                    Markers.push( setMarker(InitMarkers[i].lattitude, InitMarkers[i].longitude, InitMarkers[i].title, InitMarkers[i].title) );
                }
            }
        }
        $(window).ready(function(){
            var timeRefresh = 30;
            var interval = setInterval( function(){
                timeRefresh--;
                $("#tiempo").text(timeRefresh);
                if(timeRefresh === 0){
                    clearInterval(interval);
                    location.reload(true);
                }

            }, 1000);
        });

        /**
         * Inicializando
         **/
        $(document).ready(function(){
            /**
             * Configuraciones
             */
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            //Accion click sobre las filas de la tabla
            $("tr.clickable").on('click', function(){
                centerMap( mapOptions.center.lat, mapOptions.center.lng, mapOptions.zoom );
                var element = $(this).attr('id');
                $.get('{{route('staffTierraDetalleRechaza')}}', {id:element}, function(response){
                    if(!response.error)
                    {
                        activeRow = element;
                        loadDocumentData(response.data);
                    }else{
                        showErrorDetail(true);
                    }
                });
            });
            //Accion para ampliar una foto
            $(document).on('click',"#detailFotos .img-thumbnail", function(){
                $("#imagenGrande").attr('src', $(this).attr('src'));
                $("#modalImagenes").modal('show');
            });
            //Accion para Revisar registro
            $("#review").on('click', function(e){
                e.preventDefault();
                var SRC = $(this).attr('src');
                if(activeRow != undefined) {
                    SRC += '/' + activeRow;
                    window.location = SRC;
                }
            });

            /**
             * Funcion para mostrar el panel de detalles
             * @param show
             */
            function showErrorDetail(show)
            {
                //Muestra panel de error
                if(show)
                {
                    //Oculta detalles
                    if( $("#detailContainer").hasClass('show') )
                        $("$detailContainer").removeClass('show');
                    if(!$("#detailContainer").hasClass('hidden'))
                        $("#detailContainer").addClass('hidden');
                    if( $("#actionButtons").hasClass('show'))
                        $("#actionButtons").removeClass('show');
                    if( !$("#actionButtons").hasClass('hidden'))
                        $("#actionButtons").addClass('hidden');
                    //Muestra error
                    if( $("#errorContainer").hasClass('hidden') )
                        $("#errorContainer").removeClass('hidden');
                    if( !$("#errorContainer").hasClass('show'))
                        $("#errorContainer").addClass('show');
                }else{
                    //Oculta error
                    if( $("#errorContainer").hasClass('show') )
                        $("errorContainer").removeClass('show');
                    if(!$("#errorContainer").hasClass('hidden'))
                        $("#errorContainer").addClass('hidden');
                    //Muestra detalles
                    if( $("#detailContainer").hasClass('hidden') )
                        $("#detailContainer").removeClass('hidden');
                    if( !$("#detailContainer").hasClass('show'))
                        $("#detailContainer").addClass('show');
                    if( $("#actionButtons").hasClass('hidden'))
                        $("#actionButtons").removeClass('hidden');
                    if( !$("#actionButtons").hasClass('show'))
                        $("#actionButtons").addClass('show');
                }
            }

            /**
             * Funcion para cardar los datos del regitro recibido
             * @param data
             */
            function loadDocumentData(data)
            {
                //Cargar atributos fijos
                $("#detailAlianza").text(data.alianza);
                $("#detailCategoria").text(data.categoria);
                $("#detailSubcategoria").text(data.subcategoria);
                $("#detailEstado").text(data.estado);
                $("#detailDireccion").text(data.direccion);
                $("#detailUsuario").text(data.usuario.nombre);
                $("#detailFecha").text(data.created_at);
                $("#detailMotivo").text(data.motivo);
                var dr = new Date(data.rechazado_en).toLocaleDateString();
                $("#detailFechaRechazo").text(dr);
                //Cargar atributos variables
                $("#detailCaracteristicas").empty();
                var datosVariables = data.atributos;
                if(datosVariables != undefined && $.isArray(datosVariables)) {
                    $.each(datosVariables, function (index, element) {
                        var tempHtml = '<strong>' + element.nombre.toUpperCase() + '</strong>:' + element.valor + "<br/>";
                        $("#detailCaracteristicas").append(tempHtml);
                    });
                }
                //Cargar fotografias
                $("#detailFotos").empty();
                var fotografias = data.fotos;
                if(fotografias != undefined && $.isArray(fotografias)){
                    $.each(fotografias, function(index, element){
                        var tempHtml = '<img src="https://repofisca-nvirginia.s3.amazonaws.com/'+element+'" class="img-thumbnail" style="width: 140px; height: 140px;"/>';
                        $("#detailFotos").append(tempHtml);
                    });
                }
                //Centra el mapa
                centerMap(data.ubicacion['coordinates'][1],data.ubicacion['coordinates'][0],18);
                //Muestra detalles
                showErrorDetail(false);
            }
        });
    </script>
    <!-- Google Maps Api -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiP1oJ0eKtfuh-fSBHUrKLqtrz0_MsX2U&callback=initMap"
            type="text/javascript"></script>
@endsection