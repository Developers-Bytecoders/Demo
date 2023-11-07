<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lista de actividades</title>
    <style>
        @font-face {
            font-family: 'LatoRegular';
            src: url('/fonts/Lato-Regular.ttf') format('truetype');
            /* Puedes ajustar otros atributos de la fuente aquí, como weight y style. */
        }
        body {
            font-family: 'LatoRegular', sans-serif;
        }
        .table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .table tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
    </style>
</head>
<body>
    <div class="align-items-center pb-4">
        {{-- <i class="fas f-square me-1"></i> --}}
        <h1>Lista de actividades</h1>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Actividad</th>
                <th scope="col">Contenido</th>
                <th scope="col">Fecha de expiración</th>
                <th scope="col">Estatus</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tareas  as $tarea)
                <tr>
                    <th scope="row">{{ $tarea->id }}</th>
                    <td>{{ $tarea->name }}</td>
                    <td>{{ $tarea->content }}</td>
                    <td>
                        @php
                        // Establece la zona horaria de "Mexico City"
                        date_default_timezone_set('America/Mexico_City');
                        
                        $hoy = \Carbon\Carbon::now();
                        $fechaVencimiento = \Carbon\Carbon::parse($tarea->expires_at);
                    
                        $diferencia = $hoy->diff($fechaVencimiento);
                        
                        if ($diferencia->invert === 1) {
                            // La tarea ya expiró
                            $resultado = "Expirado hace ";
                            if ($diferencia->y > 0) {
                                $resultado .= $diferencia->y . " años, ";
                            }
                            if ($diferencia->m > 0) {
                                $resultado .= $diferencia->m . " meses, ";
                            }
                            if ($diferencia->d > 0) {
                                $resultado .= $diferencia->d . " días, ";
                            }
                            if ($diferencia->h > 0) {
                                $resultado .= $diferencia->h . " horas, ";
                            }
                            if ($diferencia->i > 0) {
                                $resultado .= $diferencia->i . " minutos, ";
                            }
                            $resultado = rtrim($resultado, ", ");
                            echo $resultado;
                        } else {
                            // La tarea aún no ha expirado
                            $resultado = "Faltan ";
                            if ($diferencia->y > 0) {
                                $resultado .= $diferencia->y . " años, ";
                            }
                            if ($diferencia->m > 0) {
                                $resultado .= $diferencia->m . " meses, ";
                            }
                            if ($diferencia->d > 0) {
                                $resultado .= $diferencia->d . " días, ";
                            }
                            if ($diferencia->h > 0) {
                                $resultado .= $diferencia->h . " horas, ";
                            }
                            if ($diferencia->i > 0) {
                                $resultado .= $diferencia->i . " minutos, ";
                            }
                            $resultado = rtrim($resultado, ", ");
                            echo $resultado;
                        }
                    @endphp
                    
                    </td>

                    <td>
                        @if ($tarea->status === 1)
                            <span class="badge text-bg-success">Pendiente</span>
                        @else
                            <span class="badge text-bg-danger">Terminado</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
