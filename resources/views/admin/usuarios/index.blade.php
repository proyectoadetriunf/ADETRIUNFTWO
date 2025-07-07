
@extends('adminlte::page')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm border-0">

        {{-- Encabezado --}}
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-users-cog me-2"></i> Gestión de Usuarios</h4>
            <a href="{{ route('usuarios.create') }}" class="btn btn-success fw-bold">
                <i class="fas fa-user-plus me-1"></i> Agregar Usuario
            </a>
        </div>

        <div class="card-body">

            {{-- Alertas flash --}}
            @foreach (['success'=>'success','error'=>'danger'] as $key=>$theme)
                @if (session($key))
                    <x-adminlte-alert :theme="$theme" dismissable>
                        {{ session($key) }}
                    </x-adminlte-alert>
                @endif
            @endforeach

            {{-- Tabla --}}
            <div class="table-responsive">
                <table id="tabla-usuarios" class="table table-striped align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>👤 Nombre</th>
                            <th>📧 Correo</th>
                            <th>📌 Estado</th>
                            <th>🕑 Últ. conexión</th>   {{-- nueva --}}
                            <th class="text-center">⚙️ Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $usuario)
                            <tr>
                                <td>{{ $usuario->name }}</td>
                                <td>{{ $usuario->email }}</td>

                                {{-- Estado (Activo/Inactivo) --}}
                                <td>
                                    @if ($usuario->is_active)
                                        <span class="badge bg-success">Activo</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </td>

                                {{-- Últ. conexión --}}
                                <td>
                                    @if ($usuario->last_login_at)
                                        <span title="{{ $usuario->last_login_at->format('d/m/Y H:i') }}">
                                            {{ $usuario->last_login_at->diffForHumans() }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                {{-- Acciones --}}
                                <td class="text-center">
                                    <a href="{{ route('usuarios.edit', (string) $usuario->_id) }}"
                                       class="btn btn-sm btn-outline-warning me-1"
                                       title="Editar usuario">✏️</a>

                                    <form action="{{ route('usuarios.destroy', (string) $usuario->_id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger"
                                                title="Eliminar usuario">🗑</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No hay usuarios registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <p class="text-muted mt-3">
                Aquí podrás ver y administrar todos los usuarios registrados en el sistema.
            </p>
        </div>
    </div>
</div>
@endsection
