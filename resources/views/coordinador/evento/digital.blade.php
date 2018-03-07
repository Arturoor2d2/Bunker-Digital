@extends('layouts.app')
@section('title')Nuevo Evento @endsection
@section('content')
    <ol class="breadcrumb">
        <li><a href="{{ url('/') }}">Inicio</a></li>
        <li><a href="{{ route('coordinadorEventoIndex') }}">Eventos</a></li>

    </ol>

@endsection
@section('bottom_javascript')

@endsection