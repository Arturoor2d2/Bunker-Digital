@extends('layouts.app2')

@section('content')
    <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand" href="{{ url('/') }}">
            Integra
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <!--  <li class="nav-item active">
                    <a class="nav-link" href="#"> Eventos </a>
                </li>
                -->
            </ul>
        </div>
    </nav>
<div class="container-fluid">
    <h1 class="text-center text-muted">Seguimiento al Gasto de Eventos</h1>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <p>
                        <img src="{{ asset('img/pri.jpg') }}"
                             width="50%" height="50%">
                    </p>
                    <h2 class="card-title" style="color:#139042;">PRI-PVEM-PANAL</h2>
                    <h4 class="card-text">
                        <strong>Cantidad:&nbsp;</strong>{{ $fichas['pri']['numero'] }}
                        <br/>
                        <strong>Gasto Total estimado:&nbsp;</strong><span class="money">{{ $fichas['pri']['valor'] }}</span>
                    </h4>
                    <hr/>
                    <h5 class="text-center">Distribución del Gasto</h5>
                    <p class="card-text text-left text-secondary font-weight-light">
                        <strong>Sedes:</strong>&nbsp;<span class="money">{{ $fichas['pri']['sedes'] }}</span>
                        <br/>
                        <strong>Categoría de estructura:</strong>&nbsp;<span class="money">{{ $fichas['pri']['estructura'] }}</span>
                        <br/>
                        <strong>Categoría de espectacular:</strong>&nbsp;<span class="money">{{ $fichas['pri']['espectacular'] }}</span>
                        <br/>
                        <strong>Categoría de utilitario:</strong>&nbsp;<span class="money">{{ $fichas['pri']['utilitario'] }}</span>
                        <br/>
                        <strong>Categoría de transporte:</strong>&nbsp;<span class="money">{{ $fichas['pri']['transporte'] }}</span>
                        <br/>
                        <strong>Categoría de producción:</strong>&nbsp;<span class="money">{{ $fichas['pri']['produccion'] }}</span>
                        <br/>
                        <strong>Categoría de animación:</strong>&nbsp;<span class="money">{{ $fichas['pri']['animacion'] }}</span>
                        <br/>
                        <strong>Categoría de adicionales:</strong>&nbsp;<span class="money">{{ $fichas['pri']['adicionales'] }}</span>
                    </p>
                    <div id="donutchartPri" style="width:100%; height: 300px; box-sizing:content-box;"></div>
                    <hr/>
                    <h5>Registros</h5>
                    <table class="table table-striped table-hover table-sm">
                        <thead class="thead-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Sede</th>
                            <th>Valor</th>
                            <th>PDF</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($fichas['pri']['eventos'] as $reg)
                            <tr>
                                <td>{{ $reg['fecha']->format('d / m / Y - H:i:s') }}</td>
                                <td>{{ $reg['sede'] }}</td>
                                <td class="money">{{ $reg['precio'] }}</td>
                                <td>
                                    <a href="{{route('consultorReportesEventoPdf', ['id'=>$reg['id']])}}">Ver</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <p>
                        <img src="{{ asset('img/pan.jpg') }}"
                             width="65%" height="65%">
                    </p>
                    <h2 class="card-title" style="color:#0A358B;">PAN-PRD-MC </h2>
                    <h4 class="card-text">
                        <strong>Cantidad:&nbsp;</strong>{{ $fichas['pan']['numero'] }}
                        <br/>
                        <strong>Gasto Total estimado:&nbsp;</strong><span class="money">{{ $fichas['pan']['valor'] }}</span>
                    </h4>
                    <hr/>
                    <h5 class="text-center">Distribución del Gasto</h5>
                    <p class="card-text text-left text-secondary font-weight-light">
                        <strong>Gasto en sedes:</strong>&nbsp;<span class="money">{{ $fichas['pan']['sedes'] }}</span>
                        <br/>
                        <strong>Gasto en la categoría de estructura:</strong>&nbsp;<span class="money">{{ $fichas['pan']['estructura'] }}</span>
                        <br/>
                        <strong>Gasto en la categoría de espectacular:</strong>&nbsp;<span class="money">{{ $fichas['pan']['espectacular'] }}</span>
                        <br/>
                        <strong>Gasto en la categoría de utilitario:</strong>&nbsp;<span class="money">{{ $fichas['pan']['utilitario'] }}</span>
                        <br/>
                        <strong>Gasto en la categoría de transporte:</strong>&nbsp;<span class="money">{{ $fichas['pan']['transporte'] }}</span>
                        <br/>
                        <strong>Gasto en la categoría de producción:</strong>&nbsp;<span class="money">{{ $fichas['pan']['produccion'] }}</span>
                        <br/>
                        <strong>Gasto en la categoría de animación:</strong>&nbsp;<span class="money">{{ $fichas['pan']['animacion'] }}</span>
                        <br/>
                        <strong>Gasto en la categoría de adicionales:</strong>&nbsp;<span class="money">{{ $fichas['pan']['adicionales'] }}</span>
                    </p>
                    <div id="donutchartPan" style="width:100%; height: 300px; box-sizing:content-box;"></div>
                    <hr/>
                    <h5>Registros</h5>
                    <table class="table table-striped table-hover table-sm">
                        <thead class="thead-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Sede</th>
                            <th>Valor</th>
                            <th>PDF</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($fichas['pan']['eventos'] as $reg)
                            <tr>
                                <td>{{ $reg['fecha']->format('d / m / Y - H:i:s') }}</td>
                                <td>{{ $reg['sede'] }}</td>
                                <td class="money">{{ $reg['precio'] }}</td>
                                <td>
                                    <a href="{{route('consultorReportesEventoPdf', ['id'=>$reg['id']])}}">Ver</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body text-center">
                    <p>
                        <img src="{{ asset('img/morena.jpg') }}"
                             width="50%" height="50%">
                    </p>
                    <h2 class="card-title" style="color:#B32825;">MORENA-PT-PES </h2>
                    <h4 class="card-text">
                        <strong>Cantidad:&nbsp;</strong>{{ $fichas['morena']['numero'] }}
                        <br/>
                        <strong>Gasto Total estimado:&nbsp;</strong><span class="money">{{ $fichas['morena']['valor'] }}</span>
                    </h4>
                    <hr/>
                    <h5 class="text-center">Distribución del Gasto</h5>
                    <p class="card-text text-left text-secondary font-weight-light">
                        <strong>Gasto en sedes:</strong>&nbsp;<span class="money">{{ $fichas['morena']['sedes'] }}</span>
                        <br/>
                        <strong>Gasto en la categoría de estructura:</strong>&nbsp;<span class="money">{{ $fichas['morena']['estructura'] }}</span>
                        <br/>
                        <strong>Gasto en la categoría de espectacular:</strong>&nbsp;<span class="money">{{ $fichas['morena']['espectacular'] }}</span>
                        <br/>
                        <strong>Gasto en la categoría de utilitario:</strong>&nbsp;<span class="money">{{ $fichas['morena']['utilitario'] }}</span>
                        <br/>
                        <strong>Gasto en la categoría de transporte:</strong>&nbsp;<span class="money">{{ $fichas['morena']['transporte'] }}</span>
                        <br/>
                        <strong>Gasto en la categoría de producción:</strong>&nbsp;<span class="money">{{ $fichas['morena']['produccion'] }}</span>
                        <br/>
                        <strong>Gasto en la categoría de animación:</strong>&nbsp;<span class="money">{{ $fichas['morena']['animacion'] }}</span>
                        <br/>
                        <strong>Gasto en la categoría de adicionales:</strong>&nbsp;<span class="money">{{ $fichas['morena']['adicionales'] }}</span>
                    </p>
                    <div id="donutchartMorena" style="width:100%; height: 300px; box-sizing:content-box;"></div>
                    <hr/>
                    <h5>Registros</h5>
                    <table class="table table-striped table-hover table-sm">
                        <thead class="thead-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Sede</th>
                            <th>Valor</th>
                            <th>PDF</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($fichas['morena']['eventos'] as $reg)
                            <tr>
                                <td>{{ $reg['fecha']->format('d / m / Y - H:i:s') }}</td>
                                <td>{{ $reg['sede'] }}</td>
                                <td class="money">{{ $reg['precio'] }}</td>
                                <td>
                                    <a href="{{route('consultorReportesEventoPdf', ['id'=>$reg['id']])}}">Ver</a>
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
@endsection

@section('bottom_javascript')
    <script type="text/javascript"
            language="javascript">
        $(document).ready(function(){
            $(".money").each(function(){
                $(this).text( accounting.formatMoney($(this).text()) );
            });
        });
    </script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load("current", {packages:["corechart"]});

        google.charts.setOnLoadCallback(drawChartPri);
        google.charts.setOnLoadCallback(drawChartPan);
        google.charts.setOnLoadCallback(drawChartMorena);
        function drawChartPri() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Gasto');
            data.addColumn('number', 'Miles de pesos');
            data.addRows([
                ['Estructura', {{$fichas['pri']['estructura']}}],
                ['Espectacular',{{$fichas['pri']['espectacular']}}],
                ['Utilitario',{{$fichas['pri']['utilitario']}}],
                ['Transporte',{{$fichas['pri']['transporte']}}],
                ['Produccion',{{$fichas['pri']['produccion']}}],
                ['Animación',{{$fichas['pri']['animacion']}}],
                ['Adicionales',{{$fichas['pri']['adicionales']}}]
            ]);

            var options = {
                title: 'Distribución del gasto en categorias',
                is3D: true,
                colors: ['#139143', '#D0312D', '#19A4AC', '#5BAD4E', '#FAE33F', '#FD9827', '#A9A9A9']
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchartPri'));
            chart.draw(data, options);
        }
        function drawChartPan(){
            var data = google.visualization.arrayToDataTable([
                ['Gasto', 'Miles de Pesos'],
                ['Estructura', {{$fichas['pan']['estructura']}}],
                ['Espectacular',{{$fichas['pan']['espectacular']}}],
                ['Utilitario',{{$fichas['pan']['utilitario']}}],
                ['Transporte',{{$fichas['pan']['transporte']}}],
                ['Produccion',{{$fichas['pan']['produccion']}}],
                ['Animación',{{$fichas['pan']['animacion']}}],
                ['Adicionales',{{$fichas['pan']['adicionales']}}],
            ]);

            var options = {
                title: 'Distribución del gasto en categorias',
                is3D: true,
                colors: ['#F75A21', '#0A358B', '#F8D02F', '#64BDEF', '#ECECEC', '#0B4C8B', '#F9EB37']
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchartPan'));
            chart.draw(data, options);
        }
        function drawChartMorena(){
            var data = google.visualization.arrayToDataTable([
                ['Gasto', 'Miles de Pesos'],
                ['Estructura', {{$fichas['morena']['estructura']}}],
                ['Espectacular',{{$fichas['morena']['espectacular']}}],
                ['Utilitario',{{$fichas['morena']['utilitario']}}],
                ['Transporte',{{$fichas['morena']['transporte']}}],
                ['Produccion',{{$fichas['morena']['produccion']}}],
                ['Animación',{{$fichas['morena']['animacion']}}],
                ['Adicionales',{{$fichas['morena']['adicionales']}}],
            ]);

            var options = {
                title: 'Distribución del gasto en categorias',
                is3D: true,
                colors: ['#B32825', '#D21D28', '#612F76', '#EDE332', '#1575B2', '#5F6C75', '#4688F1']
            };

            var chart = new google.visualization.PieChart(document.getElementById('donutchartMorena'));
            chart.draw(data, options);
        }
    </script>
@endsection