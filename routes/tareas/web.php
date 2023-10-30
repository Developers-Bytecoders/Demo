<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TareasController;

Route::get('/', [TareasController::class, 'index'])->name('tareas.index');

Route::get('tareas/create', [TareasController::class, 'create'])->name('tareas.create');

Route::post('tareas/create', [TareasController::class, 'store'])->name('tareas.store');

Route::get('tareas/edit/{id}', [TareasController::class, 'edit'])->name('tareas.edit');

Route::put('tareas/update/{tareaId}', [TareasController::class, 'update'])->name('tareas.update');

Route::delete('tareas/delete/{tareaId}', [TareasController::class, 'destroy'])->name('tareas.delete');
