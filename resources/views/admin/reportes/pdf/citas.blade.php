<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Reporte de Citas</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #eee;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>

<body>

    <h2>Reporte de Citas</h2>

    <p>Fecha inicio: <strong>{{ $inicio }}</strong></p>
    <p>Fecha fin: <strong>{{ $fin }}</strong></p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Cliente</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($citas as $cita)
            <tr>
                <td>{{ $cita->id }}</td>
                <td>{{ $cita->fecha }}</td>
                <td>{{ $cita->hora }}</td>
                <td>{{ $cita->usuario->nombre }}</td>
                <td>${{ number_format($cita->total, 0) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>