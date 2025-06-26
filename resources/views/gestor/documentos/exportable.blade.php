<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Encuestas Exportadas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        thead {
            background-color: #f2f2f2;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px 10px;
            text-align: left;
        }

        th {
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>
    <h2>📑 Registro de Encuestas</h2>
    <table>
        <thead>
            <tr>
                <th>🧍 Beneficiario</th>
                <th>📅 Fecha</th>
                <th>❓ Pregunta</th>
                <th>✅ Respuesta</th>
            </tr>
        </thead>
        <tbody>
            @forelse($encuestas as $item)
                <tr>
                    <td>{{ $item['beneficiario'] }}</td>
                    <td>{{ $item['fecha'] }}</td>
                    <td>{{ $item['pregunta'] }}</td>
                    <td>{{ $item['respuesta'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No se encontraron encuestas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
