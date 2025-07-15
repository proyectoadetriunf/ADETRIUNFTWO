<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Solicitudes</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Reporte de Solicitudes - Estado: {{ $estado }}</h2>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Año</th>
                <th>Costo Solicitado</th>
                <th>Costo Aprobado</th>
                <th>Estado</th>
                <th>Comentario</th>
            </tr>
        </thead>
        <tbody>
            @foreach($solicitudes as $s)
                <tr>
                    <td>{{ $s['nombre'] ?? '' }}</td>
                    <td>{{ $s['anio'] ?? '' }}</td>
                    <td>{{ $s['costo'] ?? '' }}</td>
                    <td>{{ $s['costo_aprobado'] ?? '' }}</td>
                    <td>{{ $s['estado'] ?? '' }}</td>
                    <td>{{ $s['comentario'] ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
