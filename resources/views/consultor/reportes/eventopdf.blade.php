<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Integra') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="{{ asset('plugins/fontawesome/css/font-awesome.min.css') }}" rel="stylesheet">

    <!-- TOP Javascript -->
    <script src="https://use.fontawesome.com/a0199b7c84.js"></script>
    <script src="{{ asset('plugins/accounting/accounting.min.js') }}" type="text/javascript"></script>
    <style>
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col text-center">
            <h1>Reporte de evento de la alianza: {{ $evento['alianza'] }}</h1>
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col">
            <p>
                <strong>Fecha:</strong>&nbsp;<span class="font-weight-light">{{ $evento['fecha'] }}</span>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <p>
                <strong>Aforo:</strong>&nbsp;<span class="font-weight-light">{{ $evento['aforo'] }} personas</span>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <p>
                <strong>Duración:</strong>&nbsp;<span class="font-weight-light">{{ $evento['duracion'] }} hrs.</span>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <p>
                <strong>Evento Compartido</strong>&nbsp;<span class="font-weight-light">@if($evento['compartido']) Si @else No @endif</span>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <p>
                <strong>Candidato(s) a:</strong>&nbsp;
                <span class="font-weight-light">
                    @if($evento['quienes']['presidente']) Presidente, @endif
                    @if($evento['quienes']['senador']) Senador, @endif
                    @if($evento['quienes']['diputadoFed']) Diputado Federal, @endif
                    @if($evento['quienes']['gobernador']) Gobernador, @endif
                    @if($evento['quienes']['alcalde']) Alcalde @endif
                </span>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <p>
                <strong>Sede</strong>&nbsp;<span class="font-weight-light">{{ $evento['sede'] }}</span>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <p>
                <strong>Ubicación</strong>&nbsp;<span class="font-weight-light">Lat. {{ $evento['ubicacion']['coordinates'][1] }} Long. {{ $evento['ubicacion']['coordinates'][0] }}</span>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <p>
                <strong>Circunscripción</strong>&nbsp;<span class="font-weight-light">{{ $evento['circunscripcion'] }}</span>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <p>
                <strong>Estado</strong>&nbsp;<span class="font-weight-light">{{ $evento['estado'] }}</span>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <p>
                <strong>Dirección</strong>&nbsp;<span class="font-weight-light">{{ $evento['direccion'] }}</span>
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col text-center">
            <h2>Valor Estimado: <span class="money">{{ number_format((float)$evento['precio'], 2)}}</span> MXN</h2>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <p>
                <strong>Gasto de la Sede:</strong>&nbsp;<span class="font-weight-light money">{{ number_format((float)$evento['precioSede'], 2) }}</span> MXN
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <p>
                <strong>Gasto en elementos de estructura:</strong>&nbsp;<span class="font-weight-light money">{{ number_format((float)$estructura, 2) }}</span> MXN
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <p>
                <strong>Gasto en elementos de espectacular:</strong>&nbsp;<span class="font-weight-light money">{{ number_format((float)$espectacular,2) }}</span> MXN
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <p>
                <strong>Gasto en elementos de utilitario:</strong>&nbsp;<span class="font-weight-light money">{{ number_format((float)$utilitario,2) }}</span> MXN
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <p>
                <strong>Gasto en elementos de transporte:</strong>&nbsp;<span class="font-weight-light money">{{ number_format((float)$transporte, 2) }}</span> MXN
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <p>
                <strong>Gasto en elementos de producción:</strong>&nbsp;<span class="font-weight-light money">{{ number_format((float)$produccion,2) }}</span> MXN
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <p>
                <strong>Gasto en elementos de animación:</strong>&nbsp;<span class="font-weight-light money">{{ number_format((float)$animacion,2) }}</span> MXN
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <p>
                <strong>Gasto en elementos adicionales:</strong>&nbsp;<span class="font-weight-light money">{{ number_format((float)$adicionales,2) }}</span> MXN
            </p>
        </div>
    </div>
    <div class="page-break"></div>

<!-- Estructura -->
    <div class="row">
        <div class="col text-center">
            <h1>Evento de {{ $evento['alianza'] }}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col text-center">
            <h3>Detalle de la Categoría: Estructura</h3>
        </div>
    </div>
    <hr/>
    @foreach($Restructura as $item)
        <div class="row">
            <div class="col">
                <p>
                    <strong># Elemento:</strong>&nbsp;<span>{{$item['numero']+1}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Categoría:</strong>&nbsp;<span>{{$item['categoria']}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Subcategoría:</strong>&nbsp;<span>{{$item['subcategoria']}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Cantidad:</strong>&nbsp;<span>{{$item['cantidad']}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Precio:</strong>&nbsp;<span class="money">{{number_format((float)$item['precio'],2)}}</span> MXN
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col text-center">
                <h4>Atributos</h4>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    @foreach($item['atributos'] as $item2)
                        <strong>{{ucfirst($item2['nombre'])}}:</strong>&nbsp;<span>{{$item2['valor']}}</span><br/>
                    @endforeach
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4 class="text-center">Evidencia Fotográfica</h4>
            </div>
        </div>
        <hr/>
        <div class="text-center">
            @foreach($item['evidencia'] as $evidencia)
                <img width="30%"
                     height="30%"
                     src="{{$evidencia}}"/>
            @endforeach
        </div>
        <div class="page-break"></div>
    @endforeach

<!-- Espectacular -->
    <div class="row">
        <div class="col text-center">
            <h1>Evento de {{ $evento['alianza'] }}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col text-center">
            <h3>Detalle de la Categoría: Espectacular</h3>
        </div>
    </div>
    <hr/>
    @foreach($Respectacular as $item)
        <div class="row">
            <div class="col">
                <p>
                    <strong># Elemento:</strong>&nbsp;<span>{{$item['numero']+1}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Categoría:</strong>&nbsp;<span>{{$item['categoria']}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Subcategoría:</strong>&nbsp;<span>{{$item['subcategoria']}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Cantidad:</strong>&nbsp;<span>{{$item['cantidad']}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Precio:</strong>&nbsp;<span class="money">{{number_format((float)$item['precio'], 2)}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col text-center">
                <h4>Atributos</h4>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    @foreach($item['atributos'] as $item2)
                        <strong>{{ucfirst($item2['nombre'])}}:</strong>&nbsp;<span>{{$item2['valor']}}</span><br/>
                    @endforeach
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4 class="text-center">Evidencia Fotográfica</h4>
            </div>
        </div>
        <hr/>
        <div class="text-center">
            @foreach($item['evidencia'] as $evidencia)
                <img width="30%"
                     height="30%"
                    src="{{$evidencia}}"/>
            @endforeach
        </div>
        <div class="page-break"></div>
    @endforeach

<!-- Utilitario -->

    <div class="row">
        <div class="col text-center">
            <h1>Evento de {{ $evento['alianza'] }}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col text-center">
            <h3>Detalle de la Categoría: Utilitario</h3>
        </div>
    </div>
    <hr/>
    @foreach($Rutilitario as $item)
        <div class="row">
            <div class="col">
                <p>
                    <strong># Elemento:</strong>&nbsp;<span>{{$item['numero']+1}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Categoría:</strong>&nbsp;<span>{{$item['categoria']}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Subcategoría:</strong>&nbsp;<span>{{$item['subcategoria']}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Cantidad:</strong>&nbsp;<span>{{$item['cantidad']}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Precio:</strong>&nbsp;<span class="money">{{number_format((float)$item['precio'], 2)}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col text-center">
                <h4>Atributos</h4>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    @foreach($item['atributos'] as $item2)
                        <strong>{{ucfirst($item2['nombre'])}}:</strong>&nbsp;<span>{{$item2['valor']}}</span><br/>
                    @endforeach
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4 class="text-center">Evidencia Fotográfica</h4>
            </div>
        </div>
        <hr/>
        <div class="text-center">
            @foreach($item['evidencia'] as $evidencia)
                <img width="30%"
                     height="30%"
                     src="{{$evidencia}}"/>
            @endforeach
        </div>
        <div class="page-break"></div>
    @endforeach

<!-- Transporte -->
    <div class="row">
        <div class="col text-center">
            <h1>Evento de {{ $evento['alianza'] }}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col text-center">
            <h3>Detalle de la Categoría: Transporte</h3>
        </div>
    </div>
    <hr/>
    @foreach($Rtransporte as $item)
        <div class="row">
            <div class="col">
                <p>
                    <strong># Elemento:</strong>&nbsp;<span>{{$item['numero']+1}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Categoría:</strong>&nbsp;<span>{{$item['categoria']}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Subcategoría:</strong>&nbsp;<span>{{$item['subcategoria']}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Cantidad:</strong>&nbsp;<span>{{$item['cantidad']}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Precio:</strong>&nbsp;<span class="money">{{number_format((float)$item['precio'], 2)}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col text-center">
                <h4>Atributos</h4>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    @foreach($item['atributos'] as $item2)
                        <strong>{{ucfirst($item2['nombre'])}}:</strong>&nbsp;<span>{{$item2['valor']}}</span><br/>
                    @endforeach
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4 class="text-center">Evidencia Fotográfica</h4>
            </div>
        </div>
        <hr/>
        <div class="text-center">
            @foreach($item['evidencia'] as $evidencia)
                <img width="30%"
                     height="30%"
                     src="{{$evidencia}}"/>
            @endforeach
        </div>
        <div class="page-break"></div>
    @endforeach

<!-- Produccion -->
    <div class="row">
        <div class="col text-center">
            <h1>Evento de {{ $evento['alianza'] }}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col text-center">
            <h3>Detalle de la Categoría: Producción</h3>
        </div>
    </div>
    <hr/>
    @foreach($Rproduccion as $item)
        <div class="row">
            <div class="col">
                <p>
                    <strong># Elemento:</strong>&nbsp;<span>{{$item['numero']+1}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Categoría:</strong>&nbsp;<span>{{$item['categoria']}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Subcategoría:</strong>&nbsp;<span>{{$item['subcategoria']}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Cantidad:</strong>&nbsp;<span>{{$item['cantidad']}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Precio:</strong>&nbsp;<span class="money">{{number_format((float)$item['precio'], 2)}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col text-center">
                <h4>Atributos</h4>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    @foreach($item['atributos'] as $item2)
                        <strong>{{ucfirst($item2['nombre'])}}:</strong>&nbsp;<span>{{$item2['valor']}}</span><br/>
                    @endforeach
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4 class="text-center">Evidencia Fotográfica</h4>
            </div>
        </div>
        <hr/>
        <div class="text-center">
            @foreach($item['evidencia'] as $evidencia)
                <img width="30%"
                     height="30%"
                     src="{{$evidencia}}"/>
            @endforeach
        </div>
        <div class="page-break"></div>
    @endforeach

<!-- Animacion -->
    <div class="row">
        <div class="col text-center">
            <h1>Evento de {{ $evento['alianza'] }}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col text-center">
            <h3>Detalle de la Categoría: Animación</h3>
        </div>
    </div>
    <hr/>
    @foreach($Ranimacion as $item)
        <div class="row">
            <div class="col">
                <p>
                    <strong># Elemento:</strong>&nbsp;<span>{{$item['numero']+1}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Categoría:</strong>&nbsp;<span>{{$item['categoria']}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Subcategoría:</strong>&nbsp;<span>{{$item['subcategoria']}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Cantidad:</strong>&nbsp;<span>{{$item['cantidad']}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Precio:</strong>&nbsp;<span class="money">{{number_format((float)$item['precio'], 2)}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col text-center">
                <h4>Atributos</h4>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    @foreach($item['atributos'] as $item2)
                        <strong>{{ucfirst($item2['nombre'])}}:</strong>&nbsp;<span>{{$item2['valor']}}</span><br/>
                    @endforeach
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4 class="text-center">Evidencia Fotográfica</h4>
            </div>
        </div>
        <hr/>
        <div class="text-center">
            @foreach($item['evidencia'] as $evidencia)
                <img width="30%"
                     height="30%"
                     src="{{$evidencia}}"/>
            @endforeach
        </div>
        <div class="page-break"></div>
    @endforeach

<!-- Adicionales -->
    <div class="row">
        <div class="col text-center">
            <h1>Evento de {{ $evento['alianza'] }}</h1>
        </div>
    </div>
    <div class="row">
        <div class="col text-center">
            <h3>Detalle de la Categoría: Adicionales</h3>
        </div>
    </div>
    <hr/>
    @foreach($Radicionales as $item)
        <div class="row">
            <div class="col">
                <p>
                    <strong># Elemento:</strong>&nbsp;<span>{{$item['numero']+1}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Categoría:</strong>&nbsp;<span>{{$item['categoria']}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Subcategoría:</strong>&nbsp;<span>{{$item['subcategoria']}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Cantidad:</strong>&nbsp;<span>{{$item['cantidad']}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    <strong>Precio:</strong>&nbsp;<span class="money">{{number_format((float)$item['precio'], 2)}}</span>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col text-center">
                <h4>Atributos</h4>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p>
                    @foreach($item['atributos'] as $item2)
                        <strong>{{ucfirst($item2['nombre'])}}:</strong>&nbsp;<span>{{$item2['valor']}}</span><br/>
                    @endforeach
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h4 class="text-center">Evidencia Fotográfica</h4>
            </div>
        </div>
        <hr/>
        <div class="text-center">
            @foreach($item['evidencia'] as $evidencia)
                <img width="30%"
                     height="30%"
                     src="{{$evidencia}}"/>
            @endforeach
        </div>
        <div class="page-break"></div>
    @endforeach

</div>
<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script type="text/javascript" language="javascript">
    $(document).ready(function(){
        $(".money").each(function(){
            $(this).text( accounting.formatMoney($(this).text()) );
        });
    });
</script>
</body>
</html>