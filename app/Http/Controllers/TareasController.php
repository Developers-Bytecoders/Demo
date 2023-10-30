<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarea;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;
use App\Traits\Helpers\CustomExceptionTrait;
use App\Traits\Helpers\ResponseTrait;
use App\Traits\Helpers\HandlerFilesTrait;
use Illuminate\Support\Facades\Session;

class TareasController extends Controller
{
    public function index()
    {
        try {
            // Retrieve all tasks
            $tasks = Tarea::paginate(15);

            return view('tareas.index', compact('tasks'));
        } catch (Exception $e) {
            return $this->excepcion('No se pudieron obtener las tareas: ' . $e->getMessage());
        }
    }

    public function edit($id) {
        $tarea = Tarea::find($id);

        if (!$tarea) {
            return abort(404); // Puedes personalizar el error 404 si lo deseas
        }

        return view('tareas.edit', compact('tarea'));
    }

    public function update(Request $request, $tareaId) {
        try {
            $tarea = Tarea::find($tareaId);

            if (!$tarea) {
                return abort(404); // Puedes personalizar el error 404 si lo deseas
            }

            $data = $request->validate([
                'name' => 'required|string|max:255',
                'content' => 'string|max:255',
                'expires_at' => 'date',
                'status' => 'boolean',
            ]);

            $tarea->update($data);
            Session::flash('successEdit', 'Tarea editada correctamente.');
            return redirect()->route('tareas.index', $tareaId)->with('success', 'Tarea actualizada correctamente');
        } catch (\Exception $e) {
            return redirect()->route('tareas.edit', $tareaId)->with('error', 'Error al actualizar la tarea');
        }
    }

    public function store(Request $request) {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'content' => 'string|max:255',
                'expires_at' => 'date',
                'status' => 'boolean',
            ]);

            $tarea = Tarea::create($data);

            // return response()->json(['message' => 'Tarea creada correctamente', 'tarea' => $tarea], 201);
            Session::flash('successCreate', 'Tarea creada correctamente.');
            return redirect()->route('tareas.index');
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al crear la tarea'], 500);
        }
    }

    
    public function destroy($tareaId) {
        try {
            $tarea = Tarea::find($tareaId);
            if (!$tarea) {
                return response()->json(['message' => 'Tarea no encontrada'], 404);
            }

            $tarea->delete();

            return response()->json(['message' => 'Tarea eliminada correctamente'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar la tarea'], 500);
        }
    }
}
