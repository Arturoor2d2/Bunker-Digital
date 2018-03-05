@section('top_javascript')
    <script src="{{ asset('plugins/moment/moment.min.js') }}" type="text/javascript"></script>
@endsection
@extends('layouts.app')
@section('title') Eventos registrados @endsection
@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ url('/') }}">Inicio</a></li>
        <li class="active">Eventos</li>
    </ol>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12 text-center">
                            <form class="form-inline" method="post" action="{{route('consultorEventoIndexFiltro')}}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="alianza">Alianza</label>
                                    <select name="alianza" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="PRI-PVEM-PANAL">PRI-PVEM-PANAL</option>
                                        <option value="PAN-PRD-MC">PAN-PRD-MC</option>
                                        <option value="MORENA-PT-PES">MORENA-PT-PES</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="estado">Estado</label>
                                    <select name="estado" class="form-control">
                                        <option value="">Todos</option>
                                        <option value="01">Aguascalientes</option>
                                        <option value="02">Baja California</option>
                                        <option value="03">Baja California Sur</option>
                                        <option value="04">Campeche</option>
                                        <option value="05">Coahuila de Zaragoza</option>
                                        <option value="06">Colima</option>
                                        <option value="07">Chiapas</option>
                                        <option value="08">Chihuahua</option>
                                        <option value="09">Ciudad de México</option>
                                        <option value="10">Durango</option>
                                        <option value="11">Guanajuato</option>
                                        <option value="12">Guerrero</option>
                                        <option value="13">Hidalgo</option>
                                        <option value="14">Jalisco</option>
                                        <option value="15">Estado de México</option>
                                        <option value="16">Michoacán de Ocampo</option>
                                        <option value="17">Morelos</option>
                                        <option value="18">Nayarit</option>
                                        <option value="19">Nuevo León</option>
                                        <option value="20">Oaxaca</option>
                                        <option value="21">Puebla</option>
                                        <option value="22">Querétaro</option>
                                        <option value="23">Quintana Roo</option>
                                        <option value="24">San Luis Potosí</option>
                                        <option value="25">Sinaloa</option>
                                        <option value="26">Sonora</option>
                                        <option value="27">Tabasco</option>
                                        <option value="28">Tamaulipas</option>
                                        <option value="29">Tlaxcala</option>
                                        <option value="30">Veracruz de Ignacio de la Llave</option>
                                        <option value="31">Yucatán</option>
                                        <option value="32">Zacatecas</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-2x fa-search pull-left"></i> Buscar
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <table class="table table-responsive table-striped table-hover table-condensed table-fixed">
                        <thead>
                        <tr>
                            <th>Alianza</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Sede</th>
                            <th>Aforo</th>
                            <th>Precio</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody style="font-size: 1.2rem;">
                        @foreach($eventos as $registro)
                            <tr class="clickable" id="{{$registro->id}}">
                                <td >{{ $registro->alianza }}</td>
                                <td >{{ $registro->fecha }}</td>
                                <td >{{ $registro->estado }}</td>
                                <td >{{ $registro->sede }}</td>
                                <td >{{ $registro->aforo }}</td>
                                <td class="money">{{ $registro->precio }}</td>
                                <td >
                                    <a href="{{route('consultorEventoDetalles', ['id'=>$registro->id])}}"
                                       class="btn btn-primary btn-sm">
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

@endsection
@section('bottom_javascript')
    <!-- Librerias javascript -->
    <script
            src="https://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
            crossorigin="anonymous"></script>
    <!-- Codigo javascript -->
    <script language="javascript" type="text/javascript">
        $(document).ready(function() {
            $(".money").each(function(index, element){
               $(element).text(accounting.formatMoney($(element).text()));
            });
        });
    </script>
@endsection