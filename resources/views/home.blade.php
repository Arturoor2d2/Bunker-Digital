@extends('layouts.app')
@section('title') Plataforma de control fiscal @endsection
@section('content')

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Información General</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <h4>Nombre</h4>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            {{ Auth::user()->name }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <h4>E-mail</h4>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            {{ Auth::user()->email }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <h4>Perfil</h4>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            {{ Auth::user()->perfil }}
                        </div>
                    </div>
                        <hr/>
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <h4>Circunscripción</h4>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            {{ Auth::user()->circunscripcion }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-6">
                            <h4>Estado</h4>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            {{ Auth::user()->estado_id }} {{ Auth::user()->estado }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @auth
        <!-- Panel de Acciones -->
        @if( Auth::User()->perfil == "staff")
            @include('staff.menu')
        @elseif( Auth::User()->perfil == "coordinador")
            @include('coordinador.menu')
        @elseif( Auth::User()->perfil == "consultor")
            @include('consultor.menu')
        @elseif( Auth::User()->perfil == "admin")
            @include('admin.menu')
        @else
            @include('shared.partials.404', ['mensaje'=>'No se puede encontrar la página deseada'])
        @endif
    <!-- Fin de panel de acciones -->
    @endauth

@endsection

@section('bottom_javascript')
    <script
            src="https://code.jquery.com/jquery-3.2.1.min.js"
            integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
            crossorigin="anonymous"></script>

@endsection
