<table border="1">
    <thead>
        <tr>
            <th colspan="5">Reporte de Solicitudes - Estado: {{ $estado }}</th>
        </tr>
        <tr>
            <th>Nombre</th>
            <th>Año</th>
            <th>Costo</th>
            <th>Descripción</th>
            <th>Comentario</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($solicitudes as $s)
            <tr>
                <td>{{ $s['nombre'] ?? '' }}</td>
                <td>{{ $s['anio'] ?? '' }}</td>
                <td>L. {{ number_format($s['costo_aprobado'] ?? ($s['costo'] ?? 0), 2) }}</td>
                <td>{{ $s['descripcion'] ?? '' }}</td>
                <td>{{ $s['comentario'] ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

