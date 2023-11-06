<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tareas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
</head>

<body>
    <style>
        body {
            font-family: "Open Sans", sans-serif;
            line-height: 1.6;
        }

        .add-todo-input,
        .edit-todo-input {
            outline: none;
        }

        .add-todo-input:focus,
        .edit-todo-input:focus {
            border: none !important;
            box-shadow: none !important;
        }

        .view-opt-label,
        .date-label {
            font-size: 0.8rem;
        }

        .edit-todo-input {
            font-size: 1.7rem !important;
        }

        .todo-actions {
            visibility: hidden !important;
        }

        .todo-item:hover .todo-actions {
            visibility: visible !important;
        }

        .todo-item.editing .todo-actions .edit-icon {
            display: none !important;
        }

        label {
            font-weight: 600;
        }

        .inputLabel {
            margin-bottom: 1rem;
        }

        .toggle {
            cursor: pointer;
            display: inline-block;
        }

        .toggle-switch {
            display: inline-block;
            background: #ccc;
            border-radius: 16px;
            width: 58px;
            height: 32px;
            position: relative;
            vertical-align: middle;
            transition: background 0.25s;
        }

        .toggle-switch:before,
        .toggle-switch:after {
            content: "";
        }

        .toggle-switch:before {
            display: block;
            background: linear-gradient(to bottom, #fff 0%, #eee 100%);
            border-radius: 50%;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.25);
            width: 24px;
            height: 24px;
            position: absolute;
            top: 4px;
            left: 4px;
            transition: left 0.25s;
        }

        .toggle:hover .toggle-switch:before {
            background: linear-gradient(to bottom, #fff 0%, #fff 100%);
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.5);
        }

        .toggle-checkbox:checked+.toggle-switch {
            background: #56c080;
        }

        .toggle-checkbox:checked+.toggle-switch:before {
            left: 30px;
        }

        .toggle-checkbox {
            position: absolute;
            visibility: hidden;
        }

        .toggle-label {
            margin-left: 5px;
            position: relative;
            top: 2px;
        }
    </style>
    <section class="vh-100">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col">
                    <div class="card" id="list1" style="border-radius: .75rem; background-color: #eff1f2;">
                        <div class="card-body py-4 px-4 px-md-5">

                            <div class="align-items-center pb-4">
                                {{-- <i class="fas f-square me-1"></i> --}}
                                <h1>Lista de actividades</h1>
                            </div>

                            <div class="pb-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="">
                                            <form action="{{ route('tareas.store') }}" enctype="multipart/form-data"
                                                method="POST">
                                                @csrf
                                                <div class="inputLabel">
                                                    <label for="name">Actividad</label>
                                                    <input type="text" name="name"
                                                        class="form-control form-control-lg"
                                                        id="exampleFormControlInput1" placeholder="Comprar verduras"
                                                        required>
                                                </div>
                                                <div class="inputLabel">
                                                    <label for="content">Descripción</label>
                                                    <input type="text" name="content"
                                                        class="form-control form-control-lg ms-2"
                                                        id="exampleFormControlInput2"
                                                        placeholder="Las verduras son alimento" required>
                                                </div>
                                                <div class="inputLabel">
                                                    <label for="expires_at">Fecha de expiración</label>
                                                    <input type="datetime-local" name="expires_at"
                                                        class="form-control form-control-lg ms-2"
                                                        id="exampleFormControlInput3" required>
                                                </div>
                                                <div class="inputLabel">
                                                    <label for="status">Estatus</label>
                                                </div>
                                                <label class="toggle">
                                                    <input class="toggle-checkbox" type="checkbox" name="status"
                                                        onchange="toggleCheckboxValue(this)" value="1" checked>
                                                    <div class="toggle-switch"></div>
                                                    <span class="toggle-label"></span>
                                                </label>

                                                <div style="margin-top: 1rem;">
                                                    <button type="submit" class="btn btn-primary">Añadir</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div>
                                <a href="{{ url('/exportar-tareas') }}" class="btn btn-primary">Descargar las tareas en PDF</a>

                            </div>
                            <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Actividad</th>
                                        <th scope="col">Contenido</th>
                                        <th scope="col">Fecha de expiración</th>
                                        <th scope="col">Estatus</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tasks as $tarea)
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
                                            <td>
                                                <a href="{{ route('tareas.edit', $tarea->id) }}" class="text-info"
                                                    data-mdb-toggle="tooltip" title="Edit todo"><svg width="16"
                                                        height="16" viewBox="0 0 16 16" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M11.2728 2.98294L13.0171 4.72637M13.3531 10.8823V13.3529C13.3531 13.7898 13.1795 14.2087 12.8706 14.5176C12.5618 14.8265 12.1428 15 11.706 15H2.64708C2.21024 15 1.7913 14.8265 1.48242 14.5176C1.17353 14.2087 1 13.7898 1 13.3529V4.29401C1 3.85718 1.17353 3.43824 1.48242 3.12935C1.7913 2.82046 2.21024 2.64693 2.64708 2.64693H5.11769M12.3945 1.44704L7.67807 6.16344C7.43437 6.40679 7.26817 6.71684 7.20042 7.05451L6.76476 9.23524L8.94549 8.79876C9.28314 8.73123 9.59279 8.5657 9.83656 8.32193L14.553 3.60553C14.6947 3.4638 14.8071 3.29555 14.8838 3.11037C14.9605 2.92519 15 2.72672 15 2.52628C15 2.32585 14.9605 2.12738 14.8838 1.9422C14.8071 1.75702 14.6947 1.58877 14.553 1.44704C14.4112 1.30531 14.243 1.19288 14.0578 1.11618C13.8726 1.03948 13.6741 1 13.4737 1C13.2733 1 13.0748 1.03948 12.8896 1.11618C12.7045 1.19288 12.5362 1.30531 12.3945 1.44704Z"
                                                            stroke="#5DCA29" stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg></a>
                                                <a href="#!" onclick="eliminarTarea({{ $tarea->id }})"
                                                    class="text-danger" data-mdb-toggle="tooltip"
                                                    title="Delete todo"><svg width="18" height="18"
                                                        viewBox="0 0 18 18" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M15.375 4.5H2.625M14.1248 6.375L13.7798 11.55C13.647 13.5405 13.581 14.5358 12.9323 15.1425C12.2835 15.75 11.2853 15.75 9.29025 15.75H8.70975C6.71475 15.75 5.7165 15.75 5.06775 15.1425C4.419 14.5358 4.35225 13.5405 4.22025 11.55L3.87525 6.375M7.125 8.25L7.5 12M10.875 8.25L10.5 12"
                                                            stroke="#ED1010" stroke-width="1.5"
                                                            stroke-linecap="round" />
                                                        <path
                                                            d="M4.875 4.5H4.9575C5.25933 4.49229 5.55182 4.39367 5.79669 4.21703C6.04157 4.0404 6.22744 3.79398 6.33 3.51L6.3555 3.43275L6.42825 3.2145C6.4905 3.02775 6.522 2.93475 6.56325 2.85525C6.64441 2.69954 6.76088 2.56499 6.90336 2.46237C7.04583 2.35974 7.21035 2.2919 7.38375 2.26425C7.4715 2.25 7.56975 2.25 7.76625 2.25H10.2338C10.4303 2.25 10.5285 2.25 10.6162 2.26425C10.7896 2.2919 10.9542 2.35974 11.0966 2.46237C11.2391 2.56499 11.3556 2.69954 11.4367 2.85525C11.478 2.93475 11.5095 3.02775 11.5717 3.2145L11.6445 3.43275C11.7395 3.7487 11.9361 4.02451 12.2038 4.21745C12.4714 4.41039 12.7952 4.5097 13.125 4.5"
                                                            stroke="#ED1010" stroke-width="1.5" />
                                                    </svg></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                            <div style="display: flex;justify-content: center;">
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination">
                                        @if ($tasks->onFirstPage())
                                            <li class="page-item disabled"><span class="page-link">Previous</span>
                                            </li>
                                        @else
                                            <li class="page-item"><a class="page-link"
                                                    href="{{ $tasks->previousPageUrl() }}">Previous</a></li>
                                        @endif

                                        @foreach ($tasks->getUrlRange(1, $tasks->lastPage()) as $page => $url)
                                            @if ($page == $tasks->currentPage())
                                                <li class="page-item active"><span
                                                        class="page-link">{{ $page }}</span></li>
                                            @else
                                                <li class="page-item"><a class="page-link"
                                                        href="{{ $url }}">{{ $page }}</a></li>
                                            @endif
                                        @endforeach

                                        @if ($tasks->hasMorePages())
                                            <li class="page-item"><a class="page-link"
                                                    href="{{ $tasks->nextPageUrl() }}">Next</a></li>
                                        @else
                                            <li class="page-item disabled"><span class="page-link">Next</span></li>
                                        @endif
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>


    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        @if (Illuminate\Support\Facades\Session::has('successCreate'))
            crearAlertas("Tarea añadida", "{{ Session::get('successCreate') }}",
                "success");
            {{ Session::forget('successCreate') }}
        @endif

        @if (Illuminate\Support\Facades\Session::has('successEdit'))
            crearAlertas("Tarea editada", "{{ Session::get('successEdit') }}",
                "success");
            {{ Session::forget('successEdit') }}
        @endif

        @if (Illuminate\Support\Facades\Session::has('errorCreate'))
            crearAlertas("Error al crear la tarea", "{{ Session::get('errorCreate') }}",
                "error");
            {{ Session::forget('errorCreate') }}
        @endif

        

        function eliminarTarea(id) {
            Swal.fire({
                title: '¿Seguro que desea eliminar esta nota?',
                text: 'Esta acción no se puede revertir',
                icon: 'warning',
                toast: true,
                position: 'top-end',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                allowOutsideClick: false,
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('tareas.delete', '') }}";
                    axios.delete(`${url}/${id}`, {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    }).then((response) => {
                        Swal.fire({
                            title: 'Tarea eliminada',
                            text: "La tarea se ha eliminado correctamente",
                            icon: 'success',
                            toast: true,
                            position: 'top-end',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ok',
                            allowOutsideClick: false,
                        }).then((result) => {
                            location.reload();
                        });
                    }).catch((error) => {
                        crearAlertaSimple('{{ __('stock/stock/index.stockDeletedFail') }}',
                            '{{ __('stock/stock/index.accept') }}', 'error');
                    });
                }
            });
        }

        function crearAlertaSimple(titulo, mensaje, icono) {
            Swal.fire({
                title: titulo,
                text: mensaje,
                icon: icono,
                confirmButtonText: 'Aceptar',
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
        }

        function crearAlertas(titulo, mensaje, icono) {
            const opciones = {
                title: titulo,
                text: mensaje,
                icon: icono,
                toast: true,
                position: 'top-end',
            };

            Swal.fire(opciones);

            // Se modifican los estilos del icono cuando es 'warning'
            if (icono == 'warning') {
                determinarIcono(icono);
            }
        }

        function toggleCheckboxValue(checkbox) {
            if (checkbox.checked) {
                checkbox.value = 1;
            } else {
                checkbox.value = 0;
            }
        }
    </script>
</body>

</html>
