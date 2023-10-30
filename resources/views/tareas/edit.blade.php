<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Editar tarea</title>
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
                                <h1>Editar tarea: {{ $tarea->name }}</h1>
                            </div>

                            <div class="pb-2">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="">
                                            <form action="{{ route('tareas.update', $tarea->id) }}"
                                                enctype="multipart/form-data" method="POST">
                                                @method('PUT')
                                                @csrf
                                                <div class="inputLabel">
                                                    <label for="name">Actividad</label>
                                                    <input type="text" name="name"
                                                        class="form-control form-control-lg"
                                                        id="exampleFormControlInput1" placeholder="{{ $tarea->name }}"
                                                        value="{{ $tarea->name }}" required>
                                                </div>
                                                <div class="inputLabel">
                                                    <label for="content">Descripción</label>
                                                    <input type="text" name="content"
                                                        class="form-control form-control-lg ms-2"
                                                        id="exampleFormControlInput2"
                                                        placeholder="{{ $tarea->content }}"
                                                        value="{{ $tarea->content }}" required>
                                                </div>
                                                <div class="inputLabel">
                                                    <label for="expires_at">Fecha de expiración</label>
                                                    <input type="datetime-local" name="expires_at"
                                                        class="form-control form-control-lg ms-2"
                                                        id="exampleFormControlInput3" value="{{ $tarea->expires_at }}"
                                                        required>
                                                </div>
                                                <div class="inputLabel">
                                                    <label for="status">Estatus</label>
                                                </div>
                                                <select name="status" id="status"
                                                    class="form-select form-select-solid select2-hidden-accessible"
                                                    data-control="select2" data-hide-search="true"
                                                    >
                                                    <option value="1"
                                                        {{ $tarea->status == 1 ? 'selected' : '' }}>
                                                        Pendiente</option>
                                                    <option value="0"
                                                        {{ $tarea->status == 0 ? 'selected' : '' }}>
                                                        Terminado</option>
                                                </select>

                                                <div style="margin-top: 1rem;">
                                                    <button type="submit" class="btn btn-primary">Editar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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

        @if (Illuminate\Support\Facades\Session::has('errorEdit'))
            crearAlertas("Error al editar la tarea", "{{ Session::get('errorEdit') }}",
                "error");
            {{ Session::forget('errorEdit') }}
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
            Swal.fire(
                titulo,
                mensaje,
                icono
            )
            //se modifcan los estilos del icono cuando es warning
            if (icono == 'warning') determinarIcono(icono)
        }

        // Obtén el elemento del checkbox por su ID
        const statusCheckbox = document.getElementById("statusCheckbox");

        // Agrega un controlador de eventos para detectar cambios en el checkbox
        statusCheckbox.addEventListener("change", function() {
            // Verifica si el checkbox está marcado (checked)
            if (statusCheckbox.checked) {
                // Si está marcado, establece el valor del checkbox en 1
                statusCheckbox.value = 1;
            } else {
                // Si no está marcado, establece el valor del checkbox en 0
                statusCheckbox.value = 0;
            }
        });
    </script>
</body>

</html>
