@extends('adminlte::page')


@section('content')
<div class="container">
    <h1 class="mb-4">🔐 Gestión de Roles</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    

    {{-- 🔁 Formulario para asignar rol --}}
    <div class="card mt-4 shadow">
        <div class="card-header bg-primary text-white font-weight-bold">🎯 Asignar Rol a Usuario</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.roles.asignar') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label for="user_id">👤 Usuario</label>
                        <select name="user_id" class="form-control" required>
                            @foreach($usuarios as $usuario)
                                <option value="{{ (string) $usuario->_id }}">{{ $usuario->name }} ({{ $usuario->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-5">
                        <label for="rol_id">🎓 Rol</label>
                        <select name="rol_id" class="form-control" required>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->rol_id }}">{{ $rol->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success btn-block">Asignar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- 📋 Tabla de usuarios --}}
    <div class="card mt-5 shadow">
        <div class="card-header bg-secondary text-white font-weight-bold">👥 Lista de Usuarios y Roles</div>
        <div class="card-body">
            {{-- 🔍 Filtro por rol --}}
            <form method="GET" class="mb-3">
                <div class="form-row">
                    <div class="col-md-4">
                        <select name="filtro_rol" class="form-control" onchange="this.form.submit()">
                            <option value="">🔽 Filtrar por rol</option>
                            @foreach($roles as $rol)
                                <option value="{{ $rol->rol_id }}" {{ request('filtro_rol') == $rol->rol_id ? 'selected' : '' }}>
                                    {{ $rol->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>

            {{-- Tabla --}}
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol asignado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios->filter(fn($u) => request('filtro_rol') ? $u->rol_id == request('filtro_rol') : true) as $usuario)
                        <tr>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                @php
                                    $rol = $roles->firstWhere('rol_id', $usuario->rol_id);
                                @endphp
                                @if($rol)
                                    <span class="badge badge-info">{{ $rol->nombre }}</span>
                                @else
                                    <span class="badge badge-dark">Sin rol ❓</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection





