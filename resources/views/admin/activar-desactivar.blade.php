<!-- resources/views/admin/activar-desactivar.blade.php -->
@extends('adminlte::page')
@section('title', 'Activar/Desactivar Página')
@section('content_header')
    <h1>Activar/Desactivar Página</h1>
@endsection
@section('content')
    <div class="alert alert-{{ $activa ? 'success' : 'danger' }}">
        Estado actual: <strong>{{ $activa ? 'ACTIVA' : 'INACTIVA' }}</strong>
    </div>
    <form method="POST" action="{{ url('admin/configuracion/activar-desactivar') }}">
        @csrf
        <button type="submit" class="btn btn-{{ $activa ? 'danger' : 'success' }}">
            {{ $activa ? 'Desactivar' : 'Activar' }} página
        </button>
    </form>
@endsection