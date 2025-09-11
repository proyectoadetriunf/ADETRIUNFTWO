@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>🔔 Notificaciones</h1>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="card mt-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <span>Tus notificaciones</span>
            @if($notificaciones->count() > 0)
                <form method="POST" action="{{ route('notificaciones.eliminar-todas') }}" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar TODAS las notificaciones?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash-alt"></i> Eliminar Todas
                    </button>
                </form>
            @endif
        </div>
        <div class="card-body">
            @if($notificaciones->count() > 0)
                <div class="list-group">
                    @foreach($notificaciones as $notificacion)
                        <div class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <p class="mb-1">{{ $notificacion->mensaje }}</p>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($notificacion->created_at)->setTimezone('America/Tegucigalpa')->format('d/m/Y h:i A') }}
                                    ({{ \Carbon\Carbon::parse($notificacion->created_at)->diffForHumans() }})
                                </small>
                            </div>
                            <form method="POST" action="{{ route('notificaciones.eliminar', $notificacion->_id) }}" class="ms-2" onsubmit="return confirm('¿Estás seguro de eliminar esta notificación?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> No tienes notificaciones.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
