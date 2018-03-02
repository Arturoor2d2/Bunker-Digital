@extends('layouts.app')
@section('title')Reportes de Eventos @endsection
@section('content')
    <div class="row">
        <div class="col-sm-12 text-right text-muted" style="font-size: 0.8em;">
            Actualizando datos en <span id="tiempo"></span> seg.
        </div>
    </div>
    <div class="row">
        <!-- Tabla de Datos -->
        <div class="col-sm-12 col-md-6">
            <!-- Filtros -->
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Filtro: ACTIVOS<span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="{{route('staffEventoIndex')}}">ACTIVOS</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ route('staffEventoIndex', ['rechazado'=>true]) }}">RECHAZADOS</a></li>
                </ul>
            </div>
            <!-- Tabla -->
            <div style="background-color: white;">
                <table class="table table-responsive table-striped table-hover table-condensed table-fixed">
                    <thead>
                        <tr>
                            <th>Alianza</th>
                            <th>Sede</th>
                            <th>Aforo</th>
                            <th>Fecha</th>
                            <th>Duración</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 1.2rem">
                    @foreach($eventos as $evento)
                        @if($evento->status == 1)
                            <tr class="clickable warning" id="{{$evento->id}}">
                        @else
                            <tr class="clickable" id="{{$evento->id}}">
                        @endif
                                <td>{{$evento->alianza}}</td>
                                <td>{{$evento->sede}}</td>
                                <td>{{$evento->aforo}}</td>
                                <td>{{Date::parse($evento->fecha)->format('d-m-Y H:i:s')}}</td>
                                <td>{{$evento->duracion}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Mapa y detalles -->
        <div class="col-sm-12 col-md-6">
            <!-- Mapa -->
            <div class="row">
                <div class="col-sm-12">
                    <div id="map-canvas" style="width:100%;min-height:400px; height:100%;"></div>
                </div>
            </div>
            <!-- Detalles -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Detalles</div>
                        <div class="panel-body">
                            <div id="errorContainer" class="hidden">
                                @include('shared.partials.404', ['mensaje'=>""])
                            </div>
                            <div id="detailContainer" class="hidden">
                                <div class="row">
                                    <!-- Datos -->
                                    <div class="col-sm-12">
                                        <div id="rechazo"></div>
                                        <p>
                                            <strong>Alianza</strong>: <span id="detailAlianza"></span> <br/>
                                            <strong>Fecha</strong>: <span id="detailFecha"></span> <br/>
                                            <strong>Sede</strong>: <span id="detailSede"></span> <br/>
                                            <strong>Aforo</strong>: <span id="detailAforo"></span> personas<br/>
                                            <strong>Estado</strong>: <span id="detailEstado"></span> <br/>
                                            <strong>Dirección</strong>: <span id="detailDireccion"></span> <br/>
                                            <strong>Usuario</strong>: <span id="detailUsuario"></span> <br/>
                                            <strong>Evento Compartido</strong>: <span id="detailCompartido"></span> <br/>
                                            <strong>Candidatos a</strong>: <span id="detailQuienes"></span> <br/>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Acciones -->
                        <div class="panel-footer">
                            <div id="actionButtons" class="text-center hidden">
                                <a src="{{route('staffEventoCompletar')}}" id="review" class="btn btn-warning">
                                    <i class="fa fa-pencil-square-o fa-lg pull-left"></i>Completar Información
                                </a>
                            </div>
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
        var InitMarkers = [
                @foreach($eventos as $registro)
            { lattitude:{{ $registro->ubicacion["coordinates"][1]  }}, longitude:{{ $registro->ubicacion["coordinates"][0] }}, title:'<strong>{{$registro->partido}}</strong><br/>{{$registro->sede }}<br/> {{$registro->aforo}}  personas <br/><a href="#" onclick="verDetalles(\'{{$registro->id}}\')">Ver Detalles</a>' },
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
        /**
         * Configuraciones
         */
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        //Funcion para cargar detalles
        function verDetalles(element)
        {
            $.get('{{route('staffEventoDetalles')}}', {id:element}, function(response){
                if(!response.error)
                {
                    activeRow = element;
                    loadDocumentData(response.data);
                }else{
                    showErrorDetail(true);
                }
            });
        }
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
            $("#rechazo").empty().hide();
            //Cargar si fue rechazado por coordinador
            if(data.motivo !== undefined)
            {
                var dr = new Date(data.fecha_rechazo).toLocaleDateString();
                $("#rechazo").html('<p class="text-danger"><strong>Este registro ha sido rechazado previamente.</strong><br/><strong>Fecha rechazado</strong>: '+dr+'<br/><strong>Motivo</strong>: '+data.motivo+'</p><hr/>').show();
            }
            //Cargar atributos
            $("#detailAlianza").text(data.alianza);
            $("#detailSede").text(data.sede);
            $("#detailAforo").text(data.aforo);
            $("#detailEstado").text(data.estado);
            $("#detailDireccion").text(data.direccion);
            $("#detailUsuario").text(data.usuario.nombre);
            $("#detailFecha").text(new Date(data.fecha).toLocaleDateString());
            if(data.compartido)
                $("#detailCompartido").text("SI");
            else
                $("#detailCompartido").text("NO");
            var quienes = "";
            if(data.quienes.presidente)
                quienes += " Presidente,";
            if(data.quienes.senador)
                quienes += " Senador,";
            if(data.quienes.diputadoFed)
                quienes += " Diputado Federal,";
            if(data.quienes.gobernador)
                quienes += " Gobernador,";
            if(data.quienes.alcalde)
                quienes += " Alcalde,";

            $("#detailQuienes").text(quienes.slice(0, quienes.length-1));
            //Centra el mapa
            centerMap(data.ubicacion['coordinates'][1],data.ubicacion['coordinates'][0],18);
            //Muestra detalles
            showErrorDetail(false);
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
         * Codigo JQUERY
         **/
        $(document).ready(function(){

            //Click en evento
            $("tr.clickable").on('click', function(){
                verDetalles($(this).attr('id'));
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
        });
    </script>
    <!-- Google Maps Api -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBiP1oJ0eKtfuh-fSBHUrKLqtrz0_MsX2U&callback=initMap"
            type="text/javascript"></script>
@endsection