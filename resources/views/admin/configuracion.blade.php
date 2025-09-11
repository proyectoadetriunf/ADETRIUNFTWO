<!-- resources/views/admin/configuracion.blade.php -->
@extends('adminlte::page')
@section('title', 'Configuración Administrador')
@section('content_header')
    <h1>Configuración de Administrador</h1>
@endsection
@section('content')
    <div class="alert alert-info">Esta es la página de configuración para el Administrador.</div>
    <div class="mt-4">
        <ul>
            <li><a href="{{ url('admin/configuracion/activar-desactivar') }}">Activar/Desactivar página</a></li>
        </ul>
    </div>
@endsection