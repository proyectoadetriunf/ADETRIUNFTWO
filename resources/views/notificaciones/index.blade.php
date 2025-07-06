@extends('adminlte::page')

@section('content')
<div class="container">
    <h1>🔔 Notificaciones</h1>
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">Tus notificaciones</div>
        <div class="card-body">
            @if($notificaciones->count() > 0)
                <ul class="list-group">
                    @foreach($notificaciones as $notificacion)
                        <li class="list-group-item">
                            {{ $notificacion->mensaje }}
                            <span class="text-muted float-right">{{ \Carbon\Carbon::parse($notificacion->created_at)->diffForHumans() }}</span>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="alert alert-info">No tienes notificaciones.</div>
            @endif
        </div>
    </div>
</div>
@endsection
