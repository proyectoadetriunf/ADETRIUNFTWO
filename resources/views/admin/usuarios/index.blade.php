@extends('adminlte::page')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-users-cog"></i> Gestión de Usuarios</h4>
            <a href="{{ route('usuarios.create') }}" class="btn btn-success fw-bold">
            Agregar Usuario
            </a>


        </div>

        <div class="card-body">

            @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif




            <p class="text-muted">Aquí podrás ver y administrar todos los usuarios registrados en el sistema.</p>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>👤 Nombre</th>
                            <th>📧 Correo</th>
                            <th class="text-center">⚙️ Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->name }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td class="text-center">
                                <a href="{{ route('usuarios.edit', (string) $usuario->_id) }}" class="btn btn-sm btn-outline-warning me-1">
                                    ✏️ Editar
                                </a>

                                <form action="{{ route('usuarios.destroy', (string) $usuario->_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">
                                        🗑 Eliminar
                                    </button>
                                </form>
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
</div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush

