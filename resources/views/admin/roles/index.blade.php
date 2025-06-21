@extends('layouts.app')

@section('content')
<div class="container">
    <h1>🔐 Gestión de Roles</h1>

    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <div class="alert alert-info mt-3">
        Aquí podrás ver y administrar los roles del sistema.
    </div>

    <div class="card mt-4">
        <div class="card-header bg-primary text-white">Asignar Rol a Usuario</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.roles.asignar') }}">
                @csrf
                <div class="row">
                    <div class="col-md-5">
                        <label for="user_id">Usuario</label>
                        <select name="user_id" class="form-control" required>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->_id }}">
                                    {{ $usuario->name }} ({{ $usuario->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <label for="rol_id">Rol</label>
                        <select name="rol_id" class="form-control" required>
                            <option value="admin">Admin</option>
                            <option value="moderador">Moderador</option>
                            <option value="usuario">Usuario</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success w-100">Asignar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de usuarios y su rol -->
    <div class="card mt-4">
        <div class="card-header bg-secondary text-white">Lista de Usuarios y Roles</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Rol</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                @switch($usuario->rol_id)
                                    @case('admin') Administrador @break
                                    @case('moderador') Moderador @break
                                    @case('usuario') Usuario @break
                                    @default <span class="text-muted">Sin rol</span>
                                @endswitch
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
